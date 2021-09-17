<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Interna extends CI_Controller {
        private $pagina=array();
        function __construct() {
                parent::__construct();
                $this -> load -> model('Usuarios_model');
                if(!$this -> session -> logado){
                        redirect('Publico');
                }
		else{
                        //altera��o para impedir login simult�neo em v�rias m�quinas, matando as sess�es mais antigas
                        $this -> db -> where('id', session_id());
                        $this -> db -> where('timestamp < (SELECT max(timestamp) FROM `tb_sessoes` where es_usuario = '.$this -> session -> uid.')', null, false);
                        $this -> db -> select('*');
                        $query = $this -> db -> get('tb_sessoes');
                        if($query -> num_rows() > 0){
                               redirect('Interna/logout');
                        }
                }
        }
	public function index()	{
                $pagina['menu1'] = 'Interna';
                $pagina['menu2'] = 'index';
                $pagina['url'] = 'Interna/index';
                $pagina['nome_pagina'] = 'Página inicial';
                $pagina['icone'] = 'fa fa-home';

                $dados = array();
                $dados += $pagina;
                //$dados['adicionais'] = array('chart.js' => true, 'amcharts' => true);
                $dados['adicionais'] = array('google_chart' => true);

                //dashboard 1
                for($i = 6; $i >= 0; $i--){
                        $result = db_result("SELECT count(*) from rl_documentos_pastas where dt_cadastro>='".date('Y-m-d', mktime(0, 0, 0, date('m')-$i, 1, date('Y')))."' and dt_cadastro<='".date('Y-m-d', mktime(0, 0, 0, date('m')-$i+1, 0, date('Y')))."' and es_usuario in (select pr_usuario from tb_usuarios where es_instituicao={$this -> session -> instituicao})");
                        $dados['dash1'][$i] = $result;
                }

                //dashboard 2
                $i = 0;
                $query = $this -> db -> query("SELECT vc_documento, count(*) as cont from tb_documentos where pr_documento in (select es_documento from rl_documentos_pastas where es_pasta in (select pr_pasta from tb_pastas where es_instituicao_lotacao={$this -> session -> instituicao} or es_instituicao_exercicio={$this -> session -> instituicao} or pr_pasta in (select es_pasta from rl_instituicoes_pastas where es_instituicao={$this -> session -> instituicao}))) group by vc_documento order by count(*) desc limit 0,5");
                foreach ($query -> result() as $row){
                        $dados['dash2'][$i] = $row;
                        $i++;
                }
                //dashboard 3
                $total_pastas = db_result("SELECT count(*) from tb_pastas where (es_instituicao_lotacao={$this -> session -> instituicao} or es_instituicao_exercicio={$this -> session -> instituicao} or pr_pasta in (select es_pasta from rl_instituicoes_pastas where es_instituicao={$this -> session -> instituicao}))");
                $digitalizadas = db_result("SELECT count(*) from tb_pastas where (es_instituicao_lotacao={$this -> session -> instituicao} or es_instituicao_exercicio={$this -> session -> instituicao} or pr_pasta in (select es_pasta from rl_instituicoes_pastas where es_instituicao={$this -> session -> instituicao})) and pr_pasta in (select es_pasta from rl_documentos_pastas where es_documento in (select pr_documento from tb_documentos where es_processo in (select pr_processo from tb_processos where es_tipo_processo=100000783)))");

                $dados['dash3']['total'] = $total_pastas;
                $dados['dash3']['digitalizadas'] = $digitalizadas;

                //dashboard 4
                $i = 0;
                $query = $this -> db -> query("SELECT t.vc_tipo_processo, count(*) as cont from tb_processos p join tb_tipos_processo t on p.es_tipo_processo=t.pr_tipo_processo where pr_processo in (select es_processo from tb_documentos where pr_documento in (select es_documento from rl_documentos_pastas where es_pasta in (select pr_pasta from tb_pastas where es_instituicao_lotacao={$this -> session -> instituicao} or es_instituicao_exercicio={$this -> session -> instituicao} or pr_pasta in (select es_pasta from rl_instituicoes_pastas where es_instituicao={$this -> session -> instituicao})))) group by es_tipo_processo order by count(*) desc limit 0,5");
                foreach ($query -> result() as $row){
                        $dados['dash4'][$i] = $row;
                        $i++;
                }

                $this -> load -> view('inicial', $dados);
	}
        public function logout(){ //faz o logout da sess�o
                $this -> Usuarios_model -> log('sucesso', 'Interna', 'Usu�rio '.$this -> session -> uid.' deslogado com sucesso.', 'tb_usuarios', $this -> session -> uid);

                $this -> session -> set_userdata('uid', null);
                $this -> session -> set_userdata('perfil', null);
                $this -> session -> set_userdata('nome', null);
                $this -> session -> set_userdata('instituicao', null);
                $this -> session -> set_userdata('logado', false);
                $this -> session -> set_userdata('erro', '');

                $this -> db -> set ('es_usuario', NULL);
                $this -> db -> where('id', session_id());
                $this -> db -> update ('tb_sessoes');

                session_unset();
                session_destroy();
                redirect('Publico');
        }
        public function alterar_senha(){ //fun��o de preenchimento da combo da view de cadastro
                $this -> load -> model('Usuarios_model');
                $this -> load -> library('encryption');
                if($this -> input -> post ('senhaAtual') && $this -> input -> post ('senhaNova') && $this -> input -> post ('senhaConfirmacao')){
                        if(strlen($this -> input -> post ('senhaNova')) < 8){
                                echo 'ERRO: Insira uma nova senha com no m�nimo 8 caracteres.';
                        }
                        else if(strlen($this -> input -> post ('senhaNova')) > 20){
                                echo 'ERRO: Insira uma nova senha com no m�ximo 20 caracteres.';
                        }
                        else if($this -> input -> post ('senhaNova') != $this -> input -> post ('senhaConfirmacao')){
                                echo 'ERRO: A confirma��o n�o corresponde � nova senha inserida!';
                        }
                        else if($this -> input -> post ('senhaAtual') == $this -> input -> post ('senhaNova')){
                                echo 'ERRO: A senha atual n�o deve ser a mesma que a nova senha!';
                        }
                        else{
                                $this -> db -> select ('vc_senha');
                                $this -> db -> from ('tb_usuarios');
                                $this -> db -> where('pr_usuario', $this -> session -> uid);
                                $query = $this -> db -> get();
                                $row = $query -> row();
                                if($this -> encryption -> decrypt($row -> vc_senha) != $this -> input -> post ('senhaAtual')){
                                        echo 'ERRO: Sua senha atual est� incorreta!';
                                }
                                else{
                                        if($this -> Usuarios_model -> alterar_senha ($this -> input -> post ('senhaNova'))){
                                                $this -> Usuarios_model -> update_usuario('bl_trocasenha', '0', $this -> session -> uid);
                                                $this -> session -> set_userdata('trocasenha', false);
                                                echo 'Sucesso na altera��o da sua senha!';
                                                $this -> Usuarios_model -> log('sucesso', 'Interna/alterar_senha', 'Senha alterada com sucesso para o usu�rio '.$this -> session -> uid, 'tb_usuarios', $this -> session -> uid);
                                        }
                                        else{
                                                echo 'ERRO: indefinido';
                                                $this -> Usuarios_model -> log('erro', 'Interna/alterar_senha', 'Erro indefinido na altera��o de senha para o usu�rio '.$this -> session -> uid, 'tb_usuarios', $this -> session -> uid);
                                        }
                                }
                        }
                }
                else{
                        echo 'ERRO: Favor preencher todos os campos';
                }
        }
        public function download(){
                $this -> load -> model('Anexos_model');
                $this -> load -> model('Usuarios_model');

                $anexo = $this -> uri -> segment(3);
                $dados['anexo'] = $this -> Anexos_model -> get_anexo ($anexo);
                $arq = './anexos/'.$dados['anexo'][0] -> pr_anexo;
		$fp = fopen($arq, 'rb');
		$tamanho=filesize($arq);

		$content = fread($fp, $tamanho);

		fclose($fp);

		if(strlen($content)>0){
			header("Content-length: {$tamanho}");
			header('Content-type: '.$dados['anexo'][0] -> vc_mime);
			header('Content-Disposition: attachment; filename='.$dados['anexo'][0] -> vc_arquivo);

			//$content = addslashes($content);
			echo $content;
		}
		else{
			log_site(1, 'Download', 'Erro no download do arquivo '.$dados['anexo'][0] -> pr_anexo, '', '');
                        $this -> Usuarios_model -> log('erro', 'Interna/download', 'Erro no download do arquivo '.$dados['anexo'][0] -> pr_anexo, 'tb_anexos', $dados['anexo'][0] -> pr_anexo);
			echo "<script type=\"text/javascript\">alert('Erro no download do arquivo. O arquivo est� corrompido.');</script>";
			echo "<noscript>Erro no download do arquivo. O arquivo est� corrompido.<br /><a href=\"/home\">Voltar</a></noscript>";
		}
        }
        public function avatar(){
                $this -> load -> model('Usuarios_model');

                $erro = false;
                $codigo = $this -> uri -> segment(3);
                if(strlen($codigo)>0){
                        $arq = "pics/{$codigo}";
                        $fp = fopen($arq, 'rb');
                        $tamanho=filesize($arq);

                        $content = fread($fp, $tamanho);

                        fclose($fp);

                        if($tamanho > 0){
                                if(strlen($content) > 0){
                                        header("Content-length: {$tamanho}");
                                        header("Content-type: image/jpeg");
                                        header("Content-Disposition: inline; filename=\"{$codigo}.jpg\"");

                                        //$content = addslashes($content);
                                        echo $content;
                                }
                                else{
                                        $this -> Usuarios_model -> log('erro', 'Interna/avatar', "Erro na exibi��o do avatar {$codigo}", 'tb_usuarios', $this -> session -> uid);
                                        $erro = true;

                                }
                        }
                        else{
                                $erro = true;
                        }
                }
                else{
                        $erro = true;
                }
                if($erro){
                        $arq2='images/nopic.jpg';
                        $fp2 = fopen($arq2, 'rb');
                        $tamanho2=filesize($arq2);
                        $content = fread($fp2, $tamanho2);
                        header("Content-length: {$tamanho2}");
                        header("Content-type: image/jpeg");
                        header("Content-Disposition: inline; filename=\"nopic.jpg\"");
                        fclose($fp2);
                        echo $content;
                }
        }
	public function auditoria(){
                $this -> load -> helper('date');

                $pagina['menu1'] = 'Interna';
                $pagina['menu2'] = 'auditoria';
                $pagina['url'] = 'Interna/auditoria';
                $pagina['nome_pagina'] = 'Auditoria';
                $pagina['icone'] = 'fa fa-gear';

                $dados = $pagina;
                $dados['adicionais'] = array('datatables' => true);

                if($this -> session -> perfil != 3){ //administrador
                        $this -> Usuarios_model -> log('seguranca', 'Relatorios/index', "Tentativa de acesso � auditoria pelo usu�rio ".$this -> session -> uid.' que n�o tem o perfil adequado.', 'tb_usuarios', $this -> session -> uid);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Voc� n�o tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Interna/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $dados['log'] = $this -> Usuarios_model -> get_log('');
                }
                $this -> load -> view('auditoria', $dados);
        }
}
