<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {
        function __construct() {
                parent::__construct();
        }
        public function get_usuarios($id='', $login=''){
                if(strlen($id) > 0){
                        $this -> db -> where('pr_usuario', $id);
                }
                else if(strlen($login) > 0){
                        $this -> db -> where('vc_login', $login);
                }
                $this -> db -> join ('tb_perfis p', 'p.pr_perfil=u.es_perfil');
                $this -> db -> join('tb_instituicoes2 i', 'u.es_instituicao=i.pr_instituicao', 'left');
                $this -> db -> select('*');
                $this -> db -> order_by ('vc_nome', 'ASC');
                $query = $this -> db -> get('tb_usuarios u');
                if($query -> num_rows() >= 1){
                        return $query -> result();
                }
                else{
                        return NULL;
                }
        }
        public function create_usuario($dados){
                $this -> load -> library('encryption');
                //var_dump($dados);
                /*
                $options = [
                    'salt' => '',
                ];
                $password = password_hash($dados['senha'], PASSWORD_BCRYPT, $options);*/
                $password = $this -> encryption -> encrypt($dados['senha']);
                $data=array(
                        'vc_nome' => $dados['NomeCompleto'],
                        'vc_login' => $dados['cpf'],
                        'ch_cpf' => $dados['cpf'],
                        'vc_email' => $dados['Email'],
                        'vc_senha' => $password,
                        'es_perfil' => $dados['perfil'],
                        'es_instituicao' => $dados['instituicao'],
                        'vc_telefone' => $dados['Telefone'],
                        'vc_senha_temporaria' => NULL,
                        'in_unidade_sei' => $dados['unidade'],
                        'dt_cadastro' => date('Y-m-d H:i:s'),
                        'bl_trocasenha' => '1'
                );
                $this -> db -> insert ('tb_usuarios', $data);
                return $this -> db -> insert_id();
        }
        public function update_usuario($campo, $valor, $primaria){
                if(strlen($primaria)==0){
                        return FALSE;
                }
                if(strlen($campo)==0){
                        return FALSE;
                }
                $this -> db -> set ($campo, $valor);
                $this -> db -> where('pr_usuario', $primaria);
                $this -> db -> update ('tb_usuarios');
                return $this -> db -> affected_rows();
        }
        public function get_perfis($id=''){
                if(strlen($id) > 0){
                        $this -> db -> where('pr_usuario', $id);
                }
                $this -> db -> select('*');
                $this -> db -> where('pr_perfil > 0');
                $this -> db -> order_by ('pr_perfil', 'ASC');
                $query = $this -> db -> get('tb_perfis');
                if($query -> num_rows() >= 1){
                        return $query -> result();
                }
                else{
                        return NULL;
                }
        }
	public function login($login, $senha){
                $this -> load -> library('encryption');

                $this -> db -> select ('*');
                $this -> db -> from ('tb_usuarios u');
                $this -> db -> join ('tb_instituicoes2 i', 'i.pr_instituicao=u.es_instituicao');
                $where = array('vc_login' => $login);
                $this -> db -> where($where);
                $query = $this -> db -> get();
                if($query -> num_rows() == 1){
                        $row = $query -> row();
                        if($row -> in_erros > 3){
                                $this -> session -> set_userdata('erro', 'Essa conta está bloqueada por muitas tentativas de acesso sem sucesso!');
                                return NULL;
                        }
                        else if($row -> bl_removido == '1'){
                                $this -> session -> set_userdata('erro', 'Essa conta está desativada!');
                                return NULL;
                        }
                        else if($this -> encryption -> decrypt($row -> vc_senha) == $senha){
                                $this -> update_usuario('in_erros', 0, $row -> pr_usuario);
                                $this -> session -> set_userdata('erro', '');
                                return $row;
                        }
                        else if($this -> encryption -> decrypt($row -> vc_senha_temporaria) == $senha){
                                $this -> update_usuario('in_erros', 0, $row -> pr_usuario);
                                $this -> update_usuario('vc_senha', $row -> vc_senha_temporaria, $row -> pr_usuario);
                                $this -> update_usuario('vc_senha_temporaria', NULL, $row -> pr_usuario);
                                $this -> update_usuario('bl_trocasenha', 1, $row -> pr_usuario);
                                $row -> bl_trocasenha = '1';
                                $this -> session -> set_userdata('erro', '');
                                return $row;
                        }
                        else{
                                $this -> update_usuario('in_erros', ($row -> in_erros + 1), $row -> pr_usuario);
                                $this -> session -> set_userdata('erro', 'Usuário ou senha incorretos!');
                                return NULL;
                        }
                }
                else{
                        $this -> session -> set_userdata('erro', 'Usuário ou senha incorretos!');
                        return NULL;
                }
	}
        public function alterar_senha($senha){
                $this -> load -> library('encryption');

                $password = $this -> encryption -> encrypt($senha);
                $this -> db -> set ('vc_senha', $password);
                $this -> db -> where('pr_usuario', $this -> session -> uid);
                $this -> db -> update ('tb_usuarios');
                return $this -> db -> affected_rows();
        }
        public function get_log($id = ''){
                if(strlen($id) > 0){
                        $this -> db -> where('pr_log', $id);
                }
		$this -> db -> order_by('pr_log', 'DESC');
                $this -> db -> limit(1000);
                $this -> db -> order_by('pr_log', 'DESC');
                $query = $this -> db -> get('tb_log');
                if($query -> num_rows() > 0){
                        return $query -> result();
                }
                else{
                        return NULL;
                }
        }
        public function log($tipo, $local, $texto, $tabela='', $chave='', $email = true){
                $this -> load -> library('email');

                $config['protocol'] = 'smpt';
                $config['smtp_host'] = '';
                $config['smtp_port'] = 25;
                $config['smtp_user'] = '';
                $config['smtp_pass'] = '';
                $config['charset'] = 'UTF-8';
                $config['wordwrap'] = TRUE;
                $config['mailtype'] = 'html';

                $this -> email -> initialize($config);

                /*
                 * Tipos:
                 * erro: erro de sistema
                 * seguranca: violações de segurança
                 * sucesso: mensagens de sucesso
                 * advertencia: advertências leves
                 */
                //echo "tipo: $tipo, local: $local, texto: $texto, tabela: $tabela, chave: $chave<br/>";

                if(strlen($chave) == 0 || $chave == 'null'){
                        $chave = null;
                }
                $texto .= '<br/>';
                $local = addslashes($local);
                $texto = addslashes($texto);

                if($this -> session -> uid == 0){
                        $this -> session -> uid = null;
                }
                if(strlen($chave) > 0){
                        $data = array(
                                'en_tipo' => $tipo,
                                'vc_local' => $local,
                                'es_usuario' => $this -> session -> uid,
                                'vc_tabela' => $tabela,
                                'in_chave' => $chave,
                                'tx_texto' => $texto,
                                'vc_ip' => $_SERVER['REMOTE_ADDR'],
                                'vc_sessao' => session_id(),
                                'dt_log' => date('Y-m-d H:i:s')
                        );
                }
                else{
                        $data = array(
                                'en_tipo' => $tipo,
                                'vc_local' => $local,
                                'es_usuario' => $this -> session -> uid,
                                'vc_tabela' => $tabela,

                                'tx_texto' => $texto,
                                'vc_ip' => $_SERVER['REMOTE_ADDR'],
                                'vc_sessao' => session_id(),
                                'dt_log' => date('Y-m-d H:i:s')
                        );
                }
                $this -> db -> insert ('tb_log', $data);

                if(!isset($config['administrador']) || strlen($config['administrador'])==0){
                        $config['administrador'] = 'administrador@planejamento.mg.gov.br';
                }

                if($email && ($tipo == 'erro' || $tipo == 'seguranca')){
                        $this -> email -> from('naoresponda@planejamento.mg.gov.br', $this -> config -> item('nome'));
                        $this -> email -> to($config['administrador']);
                        if($tipo == 'erro'){
                                $this -> email -> subject('['.$this -> config -> item('nome').'] Erro');
                                $this -> email -> message($texto);
                        }
                        else if($tipo == 'seguranca'){
                                $this -> email -> subject('['.$this -> config -> item('nome').'] Violação');
                                $this -> email -> message($texto);
                        }
                        $this -> email -> send();
                }
                /*
                //log redundante em arquivo texto
                if(!file_exists('./application/logs')){
                        mkdir('./application/logs', 0766);
                }
                chmod('./application/logs', 0766);

                $arquivo=fopen('./application/logs/'.date('Y_m').'.log', 'a');
                chmod('./application/logs/'.date('Y_m').'.log', 0766);
                $escrito = date('d/m/Y H:i:s').'> Tipo '.$tipo.' ('.$local.'): '.$texto."\n";
                fwrite($arquivo, $escrito);

                chmod('./application/logs/'.date('Y_m').'.log', 0644);
                chmod('./application/logs', 0644);
                fclose($arquivo);*/
        }
}
