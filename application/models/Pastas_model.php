<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pastas_model extends CI_Model {
        function __construct() {
                parent::__construct();
        }
        public function get_pastas($id='', $instituicao='', $so_exercicio=false, $so_ativas=true, $so_nome=false, $so_num=false, $nome='', $masp='', $admissao='',$tipo='') {
                $semad_grupo=array(1371, 2101, 2241, 2091);
                $semad_string="1371, 2101, 2241, 2091";
                if(strlen($id) > 0){
                        $this -> db -> where('p.pr_pasta', $id);
                }
                if(strlen($instituicao) > 0 && !$so_exercicio){                        
                        if(in_array($instituicao,$semad_grupo)){
                                $this -> db -> where("(i.pr_instituicao in ({$semad_string}) or i2.pr_instituicao in ({$semad_string}) or p.pr_pasta in (select es_pasta from rl_instituicoes_pastas where es_instituicao in ({$semad_string})))");
                        }
                        else{
                                $this -> db -> where("(i.pr_instituicao={$instituicao} or i2.pr_instituicao={$instituicao} or p.pr_pasta in (select es_pasta from rl_instituicoes_pastas where es_instituicao={$instituicao}))");
                        }
                }
                else if(strlen($instituicao) > 0 && $so_exercicio){
                        if(in_array($instituicao,$semad_grupo)){
                                $this -> db -> where("i2.pr_instituicao in ({$semad_string})");
                        }
                        else{
                                $this -> db -> where("i2.pr_instituicao={$instituicao}");
                        }
                }
                if($so_ativas){
                        $this -> db -> where('p.bl_ativo', '1');
                }
                if(strlen($nome) > 0){
                        $this -> db -> where("p.vc_nome like '%{$nome}%'");
                }
                if(strlen($masp) > 0){
                        $this -> db -> where("p.in_masp={$masp}");
                }
                if(strlen($admissao) > 0){
                        $this -> db -> where("p.in_admissao={$admissao}");
                }
				if($id == ''){
						if(strlen($tipo) > 0){
								$this -> db -> where("p.en_tipo in ('estagiario','externo','serventuario','empregado_publico')");
						}
						else{
								$this -> db -> where("p.en_tipo='servidor' or p.en_tipo is null");
						}
				}
                if($so_nome){
                        $this -> db -> select ('p.vc_nome, p.in_masp, p.ch_cpf');
                }
                else{
                        $this -> db -> select ('p.*, i.vc_sigla as lotacao, i2.vc_sigla as exercicio');
                }                
                
                $this -> db -> from ('tb_pastas p');
                $this -> db -> join('tb_instituicoes2 i', 'p.es_instituicao_lotacao=i.pr_instituicao');
                $this -> db -> join('tb_instituicoes2 i2', 'p.es_instituicao_exercicio=i2.pr_instituicao');

                $this -> db -> order_by ('p.vc_nome', 'ASC');
                $query = $this -> db -> get();
                //echo $this -> db -> last_query();
                if($so_num){
                        return $query -> num_rows();
                }
                if ($query -> num_rows() > 0) {
                        return $query -> result();
                }
        }
        public function get_pastas_publicas($id='', $inst='') {
                if(strlen($id) > 0){
                        $this -> db -> where('pp.pr_pasta_publica', $id);
                }
                if(strlen($inst) > 0){
                        $this -> db -> where('i.pr_instituicao', $inst);
                }
                $this -> db -> select ('pp.*, p.vc_nome as nome, p.in_masp, p.in_admissao, pp.dt_cadastro as cadastro, u.vc_nome as usuario, i.vc_sigla');                
                $this -> db -> from ('tb_pastas_publicas pp');
                $this -> db -> join('tb_usuarios u', 'pp.es_usuario=u.pr_usuario');
                $this -> db -> join('tb_instituicoes2 i', 'u.es_instituicao=i.pr_instituicao');
                $this -> db -> join('tb_pastas p', 'pp.es_pasta=p.pr_pasta');
                
                $query = $this -> db -> get();
                if ($query -> num_rows() > 0) {
                        return $query -> result();
                }
        }
        public function update_pasta($campo, $valor, $primaria){
                if(strlen($primaria)==0){
                        return FALSE;
                }
                if(strlen($campo)==0){
                        return FALSE;
                }
                $this -> db -> set ($campo, $valor);
                $this -> db -> set ('es_alterador', $this -> session -> uid);
                $this -> db -> set ('dt_alteracao', date('Y-m-d H:i:s'));
                $this -> db -> where('pr_pasta', $primaria);
                $this -> db -> update ('tb_pastas');
                return $this -> db -> affected_rows();
        }
        public function create_pasta($dados){
				if(!(strlen($dados['tipo_pasta']) >0)){
						$dados['tipo_pasta'] = 'servidor';
				}
			
                $data=array(
                        'in_masp' => $dados['masp'],
                        'in_admissao' => $dados['admissao'],
                        'vc_nome' => $dados['nome'],
                        'ch_cpf' => $dados['cpf'],
                        'es_instituicao_lotacao' => $dados['lotacao'],
                        'es_instituicao_exercicio' => $dados['exercicio'],
                        'es_cadastrador' => $this -> session -> uid,
                        'dt_cadastro' => date('Y-m-d H:i:s'),
						'en_tipo' => $dados['tipo_pasta']
                );
                $this -> db -> insert ('tb_pastas', $data);
                return $this -> db -> insert_id();
        }
        public function add_viewer($pasta, $instituicao){
                $data=array(
                        'es_pasta' => $pasta,
                        'es_instituicao' => $instituicao,
                        'es_cadastrador' => $this -> session -> uid,
                        'dt_cadastro' => date('Y-m-d H:i:s')
                );
                $this -> db -> replace ('rl_instituicoes_pastas', $data);
                return $this -> db -> insert_id();
        }
        public function delete_view($inst, $pasta){
                $this -> db -> where('es_pasta', $pasta);
                $this -> db -> where('es_instituicao', $inst);
                $this -> db -> delete ('rl_instituicoes_pastas');
                return $this -> db -> affected_rows();
        }
        public function create_link($id, $codigo){
                $data=array(
                        'pr_pasta_publica' => $codigo,
                        'es_pasta' => $id,
                        'es_usuario' => $this -> session -> uid,
                        'dt_cadastro' => date('Y-m-d H:i:s')
                );
                $this -> db -> insert ('tb_pastas_publicas', $data);
                return $this -> db -> insert_id();
        }
        public function get_alteracoes($id='') {
                if(strlen($id) > 0){
                        $this -> db -> where('a.pr_alteracao', $id);
                }
                $this -> db -> select ('a.*, u.vc_nome, i.vc_sigla');                
                $this -> db -> from ('tb_alteracoes a');
                $this -> db -> join('tb_usuarios u', 'a.es_usuario=u.pr_usuario');
                $this -> db -> join('tb_instituicoes2 i', 'u.es_instituicao=i.pr_instituicao');
                $this -> db -> order_by ('a.dt_cadastro', 'DESC');
                
                $query = $this -> db -> get();
                if ($query -> num_rows() > 0) {
                        return $query -> result();
                }
        }
        public function create_remocao($documento, $pasta) {
                $data=array(
                        'tx_justificativa' => '',
                        'es_documento' => $documento,
                        'es_pasta' => $pasta,
                        'es_usuario' => $this -> session -> uid,
                        'dt_remocao' => date('Y-m-d H:i:s')
                );
                $this -> db -> insert ('tb_remocoes', $data);
                return $this -> db -> insert_id();
        }
        public function get_remocoes($id='', $instituicao='') {
                if(strlen($id) > 0){
                        $this -> db -> where('a.pr_alteracao', $id);
                }
                if(strlen($instituicao) > 0){
                        $this -> db -> where("(p.es_instituicao_lotacao=$instituicao or p.es_instituicao_exercicio=$instituicao)");
                }
                $this -> db -> select ('r.*, p.vc_nome as nome, p.in_masp, p.in_admissao, d.in_documento, u.vc_nome as usuario, i.vc_sigla');                
                $this -> db -> from ('tb_remocoes r');
                $this -> db -> join('tb_usuarios u', 'r.es_usuario=u.pr_usuario');
                $this -> db -> join('tb_instituicoes2 i', 'u.es_instituicao=i.pr_instituicao');
                $this -> db -> join('tb_pastas p', 'r.es_pasta=p.pr_pasta');
                $this -> db -> join('tb_documentos d', 'r.es_documento=d.pr_documento');
                $this -> db -> order_by ('r.dt_remocao', 'DESC');
                
                $query = $this -> db -> get();
                if ($query -> num_rows() > 0) {
                        return $query -> result();
                }
        }
}
