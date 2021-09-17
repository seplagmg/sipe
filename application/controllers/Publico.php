<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//páginas públicas
class Publico extends CI_Controller {
        function __construct() {
                parent::__construct();
                if($this -> session -> logado){
                        redirect('Interna');
                }
                $this -> load -> helper('form');
                $this -> load -> library('form_validation');
                $this -> load -> model('Usuarios_model');
        }
	public function index(){ //login
                $pagina['menu1'] = 'Publico';
                $pagina['menu2'] = 'index';
                $pagina['url'] = 'Publico/index';
                $pagina['nome_pagina'] = 'Entre no sistema';
                //echo ENVIRONMENT ;

                $this -> form_validation -> set_rules('cpf', "'CPF'", 'trim|required');
                $this -> form_validation -> set_rules('senha', "'Senha'", 'trim|required|min_length[8]');

                if ($this -> form_validation -> run() == FALSE){ //validações de preenchimento
                        $dados['erro']= validation_errors();
                }
                else{
                        $dados['erro']= NULL;
                        $dados_form = $this -> input -> post(null,true);
                        $dados_form['cpf'] = addslashes($dados_form['cpf']);
                        $dados_form['cpf'] = str_replace('.', '', $dados_form['cpf']);
                        $dados_form['cpf'] = str_replace('-', '', $dados_form['cpf']);
                        $dados_form['cpf'] = str_replace('/', '', $dados_form['cpf']);
                        $row = $this -> Usuarios_model -> login($dados_form['cpf'], $dados_form['senha']); //fazer login
                        if(is_object($row) && $row -> pr_usuario > 0 && strlen($this -> session -> erro) == 0){ //sem erro
                                $this -> session -> set_userdata('uid', $row -> pr_usuario);
                                $this -> session -> set_userdata('perfil', $row -> es_perfil);
                                $this -> session -> set_userdata('instituicao', $row -> es_instituicao);
                                $this -> session -> set_userdata('sigla', $row -> vc_sigla);
                                $this -> session -> set_userdata('nome', $row -> vc_nome);
                                $this -> session -> set_userdata('trocasenha', $row -> bl_trocasenha);
                                $this -> session -> set_userdata('logado', true);
                                $this -> session -> set_userdata('erro', '');

                                $this -> Usuarios_model -> log('sucesso', 'Publico', 'Usuário '.$row -> pr_usuario.' logado com sucesso.', 'tb_usuarios', $row -> pr_usuario);

                                $this -> Usuarios_model -> update_usuario('dt_ultimoacesso', date('Y-m-d H:i:s'), $row -> pr_usuario);
                                $this -> db -> set ('es_usuario', $row -> pr_usuario);
                                $this -> db -> where('id', session_id());
                                $this -> db -> update ('tb_sessoes');

                                redirect('Interna');
                        }
                        else{ //exibe erro na página inicial
                                $dados['erro']= $this -> session -> erro;
                                $this -> Usuarios_model -> log('advertencia', 'Publico', 'Login sem sucesso para usuário '.$dados_form['cpf']);
                                $this -> session -> set_userdata('erro', '');
                        }
                }
                $dados['sucesso'] = '';
                $dados += $pagina;

                $this -> load -> view('home', $dados);
	}
	public function recuperar(){ //recuperar senha
                $pagina['menu1'] = 'Publico';
                $pagina['menu2'] = 'recuperar';
                $pagina['url'] = 'Publico/recuperar';
                $pagina['nome_pagina'] = 'Recuperar senha';

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

                $this -> load -> library('encryption');
                $this -> load -> helper('string');

                $this -> form_validation -> set_rules('cpf', "'CPF'", 'trim|required');

                if ($this -> form_validation -> run() == FALSE){
                        $dados['sucesso'] = '';
                        $dados['erro']= validation_errors();
                }
                else{
                        $dados_form = $this -> input -> post(null,true);
                        $dados_form['cpf'] = str_replace('.', '', $dados_form['cpf']);
                        $dados_form['cpf'] = str_replace('-', '', $dados_form['cpf']);
                        $dados_form['cpf'] = str_replace('/', '', $dados_form['cpf']);
                        $row = $this -> Usuarios_model -> get_usuarios('', $dados_form['cpf']);
                        if(isset($row[0] -> vc_email) && strlen($row[0] -> vc_email) > 0){
                                $senha = random_string ('alnum', 8);
                                $password = $this -> encryption -> encrypt($senha);
                                $this -> Usuarios_model -> update_usuario('vc_senha_temporaria', $password, $row[0] -> pr_usuario);

                                $this -> email -> set_mailtype('html');
                                $this -> email -> from($this -> config -> item('email'), $this -> config -> item('nome'));
                                $this -> email -> to($row[0] -> vc_email);
                                $this -> email -> subject('['.$this -> config -> item('nome').'] Recuperação de senha');
                                $msg = '<html><body>Olá '.$row[0] -> vc_nome.',<br/><br/>Foi solicitada a recuperação de senha do '.$this -> config -> item('nome').'. Seus dados para acesso são:<br/><br/>Usuário: '.$row[0] -> vc_login."<br/>Senha inicial: $senha<br/><br/>Se não foi você que solicitou essa recuperação de senha, não se preocupe pois sua senha antiga ainda funciona.<br/><br/>Acesse o sistema por meio do link: <a href=\"".base_url()."\">".base_url()."</a></body></html>";
                                $this -> email -> message($msg);
                                if(!$this -> email -> send()){
                                        //log
                                        $dados['sucesso'] = '';
                                        $dados['erro'] = 'Erro no envio da mensagem. Os responsáveis já foram avisados.';
                                        $this -> Usuarios_model -> log('erro', 'Publico/recuperar', 'Erro no envio de e-mail de recuperação de senha para '.$row[0] -> vc_email);
                                }
                                else{
                                        $dados['sucesso'] = 'Senha recuperada com sucesso. Favor verificar seu e-mail.';
                                        $dados['erro'] = '';
                                        $this -> Usuarios_model -> log('sucesso', 'Publico/recuperar', 'Sucesso no envio de e-mail de recuperação de senha para '.$row[0] -> vc_email);
                                }
                        }
                        else{
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Não foi encontrado cadastro com esse usuário!';
                        }

                }
                $dados += $pagina;

                $this -> load -> view('home', $dados);
	}
	public function contato(){ //fale conosco
                $pagina['menu1'] = 'Publico';
                $pagina['menu2'] = 'contato';
                $pagina['url'] = 'Publico/contato';
                $pagina['nome_pagina'] = 'Fale conosco';

                $this -> load -> library('email');

                $config['protocol'] = 'smpt';
                $config['smtp_host'] = '';
                $config['smtp_port'] = 25;
                $config['smtp_user'] = '';
                $config['smtp_pass'] = '';
                $config['charset'] = 'UTF-8';
                $config['wordwrap'] = TRUE;
                $config['mailtype'] = 'html';

                $this->email->initialize($config);

                $this -> form_validation -> set_rules('nome', "'Nome completo'", 'required|min_length[10]|max_length[100]');
                $this -> form_validation -> set_rules('email', "'E-mail'", 'required|valid_email');
                $this -> form_validation -> set_rules('assunto', "'Assunto'", 'required|max_length[100]');
                $this -> form_validation -> set_rules('msg', "'Mensagem'", 'required|min_length[10]|max_length[4000]');

                if ($this -> form_validation -> run() == FALSE){
                        $dados['sucesso'] = '';
                        $dados['erro']= validation_errors();
                }
                else{
                        $dados_form = $this -> input -> post(null,true);
                        $this -> email -> set_mailtype('html');
                        $this -> email -> from($dados_form['email'], $dados_form['nome']);
                        $this -> email -> to($this -> config -> item('email'));
                        $this -> email -> subject('['.$this -> config -> item('nome').'] Fale conosco: '.$dados_form['assunto']);
                        $this -> email -> message($dados_form['msg']);
                        if($this -> email -> send()){
                                $dados['sucesso'] = 'Mensagem enviada com sucesso.';
                                $dados['erro'] = '';
                        }
                        else{
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Erro no envio da mensagem.';
                        }
                }
                $dados += $pagina;

                $this -> load -> view('home', $dados);
	}
        public function download_publico($arquivo) {
                $arquivos_permitidos = array('Manual_SIPE_V3.pdf');

                if(in_array($arquivo, $arquivos_permitidos)){
                        $arq = "./download_pagina/".$arquivo;
                        $fp = @fopen($arq, 'rb');
                        $tamanho = @filesize($arq);
                        //echo "arq: $arq, tam: $tamanho<br>";
                        $content = @fread($fp, $tamanho);
                        fclose($fp);

                        if (strlen($content) > 0) {
                                header("Content-length: {$tamanho}");
                                header("Content-type: ".$this -> tipoArquivo($arquivo));
                                header("Content-Disposition: attachment; filename=\"$arquivo\"");

                                echo $content;
                        }
                }
                exit;
        }
        private function tipoArquivo($nomeArquivo) {
                $arr = explode(".", $nomeArquivo);
                $ext = strtolower($arr [count($arr) - 1]);
                switch ($ext) {
                        case "doc" :
                            return "application/vnd.ms-word";
                        case "xls" :
                            return "application/vnd.ms-excel";
                        case "mdb" :
                            return "application/vnd.ms-access";
                        case "pdf" :
                            return "application/pdf";
                        case "zip" :
                            return "application/zip";
                        case "htm" :
                        case "html" :
                            return "text/html";
                        case "xml" :
                            return "text/xml";
                        case "txt" :
                            return "text/plain";
                        case "gif" :
                            return "image/gif";
                        case "jpg" :
                        case "jpeg" :
                            return "image/jpeg";
                        case "png" :
                            return "image/png";
                        case "bmp" :
                            return "image/x-bitmap";
                        case "mpeg" :
                            return "video/mpeg";
                        default :
                            return "application/octet-stream";
                }
        }
}
