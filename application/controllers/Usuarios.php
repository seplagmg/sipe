<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {
        function __construct() {
                parent::__construct();
                $this -> load -> model('Usuarios_model');
                if(!$this -> session -> logado){
                        redirect('Publico');
                }
        }
	public function index()	{
                $this -> load -> helper('date');

                $pagina['menu1'] = 'Usuarios';
                $pagina['menu2'] = 'index';
                $pagina['url'] = 'Usuarios/index';
                $pagina['nome_pagina'] = 'Usuários';
                $pagina['icone'] = 'fa fa-users';

                $dados = $pagina;
                $dados['adicionais'] = array('datatables' => true);
                $dados['sucesso'] = '';

                if($this -> session -> perfil != 3){ //administrador
                        $this -> Usuarios_model -> log('seguranca', 'Usuarios/index', "Tentativa de acesso à gestão de usuários pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_usuarios', $this -> session -> uid);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Interna/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $dados['usuarios'] = $this -> Usuarios_model -> get_usuarios();
                }
                $this -> load -> view('usuarios', $dados);
        }
	public function create(){
                $this -> load -> model('Instituicoes_model');
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

                $this -> load -> helper('string');

                $pagina['menu1'] = 'Usuarios';
                $pagina['menu2'] = 'create';
                $pagina['url'] = 'Usuarios/create';
                $pagina['nome_pagina'] = 'Novo usuário';
                $pagina['icone'] = 'fa fa-users';

                $dados = $pagina;
                $dados['adicionais'] = array('inputmasks' => true);
                //$dados += $this -> input -> post(null,true);

                if($this -> session -> perfil != 3){ //administrador
                        $this -> Usuarios_model -> log('seguranca', 'Usuarios/create', "Tentativa de acesso à gestão de usuários pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_usuarios', $this -> session -> uid);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Interna/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $this -> form_validation -> set_rules('NomeCompleto', "'Nome completo'", 'required|min_length[10]|minus_maius', array('minus_maius' => 'Não utilize somente maiúsculas ou minúsculas no campo \'Nome completo\'.'));
                        $this -> form_validation -> set_rules('cpf', "'CPF'", 'required|verificaCPF', array('required' => 'O campo \'CPF\' é obrigatório.', 'verificaCPF' => 'O número inserido no camp \'CPF\' é inválido.'));
                        $this -> form_validation -> set_rules('Email', "'E-mail'", 'required|valid_email');
                        $this -> form_validation -> set_rules('instituicao', "'Instituição'", 'required|maior_que_zero', array('maior_que_zero' => "O campo 'Instituição' é obrigatório."));
                        $this -> form_validation -> set_rules('perfil', "'Perfil'", 'required');
                        $this -> form_validation -> set_rules('unidade', "'Unidade no SEI'", 'required|min_length[9]|maior_que_zero', array('maior_que_zero' => "O campo 'Unidade do SEI' é obrigatório."));

                        if ($this -> form_validation -> run() == FALSE){
                                $dados['sucesso'] = '';
                                $dados['erro'] = validation_errors();
                        }
                        else{
                                //var_dump($this -> input -> post());
                                $senha = random_string ('alnum', 8);
                                $dados_form = $this -> input -> post(null,true);
                                $dados_form['senha'] = $senha;
                                $dados_form['Telefone'] = null;
                                $dados_form['cpf'] = str_replace('.', '', $dados_form['cpf']);
                                $dados_form['cpf'] = str_replace('-', '', $dados_form['cpf']);

                                $query2 = $this -> db -> query("SELECT * from tb_usuarios where ch_cpf='{$dados_form['cpf']}' or vc_login='{$dados_form['cpf']}'");
                                $num = $query2 -> num_rows();
                                if($num > 0){
                                        $dados['sucesso'] = '';
                                        $dados['erro'] =  'Já existe um usuário criado com esse CPF.';
                                }
                                else{
                                        $usuario = $this -> Usuarios_model -> create_usuario($dados_form);
                                        if($usuario > 0){
                                                $this -> email -> set_mailtype('html');
                                                $this -> email -> from($this -> config -> item('email'), $this -> config -> item('nome'));
                                                $this -> email -> to($dados_form['Email']);
                                                $this -> email -> subject('['.$this -> config -> item('nome').'] Confirmação de cadastro');
                                                $msg = "<html><body>Olá {$dados_form['NomeCompleto']},<br/><br/>Seu cadastro foi realizado no ".$this -> config -> item('nome').". Seus dados para acesso são:<br/><br/>Usuário: {$dados_form['Email']}<br/>Senha inicial: $senha<br/><br/>Acesse o sistema por meio do link: <a href=\"".base_url()."\">".base_url()."</a></body></html>";
                                                $this -> email -> message($msg);
                                                if(!$this -> email -> send()){
                                                        $this -> Usuarios_model -> log('erro', 'Usuarios/create', "Erro de envio de e-mail com senha de cadastro para o e-mail {$dados_form['Email']} do usuário {$usuario}.", 'tb_usuarios', $usuario);
                                                }

                                                $dados['sucesso'] = 'Cadastro realizado com sucesso. O usuário vai receber sua senha inicial de acesso por e-mail.<br/><br/><a href="'.base_url('Usuarios/index').'" class="btn btn-light">Voltar</a>';
                                                $dados['erro'] =  NULL;
                                                $this -> Usuarios_model -> log('sucesso', 'Usuarios/create', "Usuário {$usuario} criado com sucesso.", 'tb_usuarios', $usuario);
                                        }
                                        else{
                                                $erro = $this -> db -> error();
                                                $dados['sucesso'] = '';
                                                $dados['erro'] =  'Erro no cadastro de usuário. Os responsáveis já foram avisados.<br/><br/><a href="'.base_url('Usuarios/index').'" class="btn btn-light">Voltar</a>';
                                                $this -> Usuarios_model -> log('erro', 'Usuarios/create', 'Erro de criação de usuário. Erro: '.$erro['message']);
                                        }
                                }
                        }
                        $dados['perfis'] = $this -> Usuarios_model -> get_perfis ('');
                        $dados['instituicoes'] = $this -> Instituicoes_model -> get_instituicoes ('');
                }
                $this -> load -> view('usuarios', $dados);
        }
	public function edit(){
                $this -> load -> model('Instituicoes_model');
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

                $this -> load -> helper('string');

                $pagina['menu1'] = 'Usuarios';
                $pagina['menu2'] = 'edit';
                $pagina['url'] = 'Usuarios/edit';
                $pagina['nome_pagina'] = 'Editar usuário';
                $pagina['icone'] = 'fa fa-users';

                $dados = $pagina;
                if($this -> session -> perfil != 3){ //administrador
                        $this -> Usuarios_model -> log('seguranca', 'Usuarios/create', "Tentativa de acesso à gestão de usuários pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_usuarios', $this -> session -> uid);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Interna/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $usuario = $this -> uri -> segment(3);
                        $dados_form = $this -> input -> post(null,true);
                        if(isset($dados_form['codigo']) && $dados_form['codigo'] > 0){
                                $usuario = $dados_form['codigo'];
                        }
                        $dados_usuario = $this -> Usuarios_model -> get_usuarios ($usuario);
                        $dados['codigo'] = $usuario;
                        $dados += (array) $dados_usuario[0];
                        $dados['ch_cpf'] = exibir_cpf($dados['ch_cpf']);
                        //var_dump($usuario);
                        //var_dump($dados_usuario);
                        //var_dump($dados_form);

                        if($usuario == $this -> session -> uid){
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Você não pode atualizar seus próprios dados por essa funcionalidade. Essa tentativa foi registrada para fins de auditoria.';
                                $this -> Usuarios_model -> log('seguranca', 'Usuarios/edit', "Usuário {$usuario} tentou atualizar seus próprios dados.", 'tb_usuarios', $usuario);
                        }
                        else{
                                $this -> form_validation -> set_rules('NomeCompleto', "'Nome completo'", 'required|min_length[10]|minus_maius', array('minus_maius' => 'Não utilize somente maiúsculas ou minúsculas no campo \'Nome completo\'.'));
                                $this -> form_validation -> set_rules('Email', "'E-mail'", 'required|valid_email');
                                $this -> form_validation -> set_rules('instituicao', "'Instituição'", 'required|maior_que_zero', array('maior_que_zero' => "O campo 'Instituição' é obrigatório."));
                                $this -> form_validation -> set_rules('perfil', "'Perfil'", 'required');
                                $this -> form_validation -> set_rules('unidade', "'Unidade no SEI'", 'required|min_length[9]|maior_que_zero', array('maior_que_zero' => "O campo 'Unidade do SEI' é obrigatório."));

                                if ($this -> form_validation -> run() == FALSE){
                                        $dados['sucesso'] = '';
                                        $dados['erro'] = validation_errors();
                                }
                                else{
                                        //var_dump($this -> input -> post());

                                        $this -> Usuarios_model -> update_usuario('vc_nome',$dados_form['NomeCompleto'], $usuario);
                                        $this -> Usuarios_model -> update_usuario('vc_email', $dados_form['Email'], $usuario);
                                        //$this -> Usuarios_model -> update_usuario('vc_login', $dados_form['Email'], $usuario);
                                        $this -> Usuarios_model -> update_usuario('es_instituicao', $dados_form['instituicao'], $usuario);
                                        $this -> Usuarios_model -> update_usuario('es_perfil', $dados_form['perfil'], $usuario);
                                        $this -> Usuarios_model -> update_usuario('dt_alteracao', date('Y-m-d H:i:s'), $usuario);
                                        $this -> Usuarios_model -> update_usuario('in_unidade_sei', $dados_form["unidade"] , $usuario);
                                        $this -> Usuarios_model -> log('sucesso', 'Usuarios/edit', "Usuário {$usuario} editado com sucesso pelo usuário ".$this -> session -> uid, 'tb_usuarios', $usuario);

                                        $dados['sucesso'] = 'Usuário editado com sucesso.<br/><br/><a href="'.base_url('Usuarios/index').'" class="btn btn-light">Voltar</a>';
                                        $dados['erro'] = '';
                                }
                        }
                        $dados['perfis'] = $this -> Usuarios_model -> get_perfis ('');
                        $dados['instituicoes'] = $this -> Instituicoes_model -> get_instituicoes ('');
                }
                $this -> load -> view('usuarios', $dados);
        }
	public function novaSenha(){
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

                $this -> load -> helper('string');
                $this -> load -> library('encryption');

                $pagina['menu1'] = 'Usuarios';
                $pagina['menu2'] = 'novaSenha';
                $pagina['url'] = 'Usuarios/novaSenha';
                $pagina['nome_pagina'] = 'Nova senha';
                $pagina['icone'] = 'fa fa-users';

                $dados = $pagina;
                if($this -> session -> perfil != 3){ //administrador
                        $this -> Usuarios_model -> log('seguranca', 'Usuarios/create', "Tentativa de acesso à gestão de usuários pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_usuarios', $this -> session -> uid);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Interna/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $usuario = $this -> uri -> segment(3);
                        $dados['usuario'] = $this -> Usuarios_model -> get_usuarios($usuario);
                        if($dados['usuario'][0] -> pr_usuario > 0){
                                $senha = random_string ('alnum', 8);
                                $password = $this -> encryption -> encrypt($senha);
                                $this -> Usuarios_model -> update_usuario('vc_senha_temporaria', $password, $usuario);
                                $this -> Usuarios_model -> update_usuario('in_erros', 0, $usuario);
                                $this -> Usuarios_model -> update_usuario('bl_trocasenha', '1', $usuario);

                                $this -> email -> set_mailtype('html');
                                $this -> email -> from($this -> config -> item('email'), $this -> config -> item('nome'));
                                $this -> email -> to($dados['usuario'][0] -> vc_email);
                                $this -> email -> subject('['.$this -> config -> item('nome').'] Nova senha');
                                $msg='<html><body>Olá '.$dados['usuario'][0] -> vc_nome.',<br/><br/>Foi solicitada uma nova senha do '.$this -> config -> item('nome').'. Seus dados para acesso são:<br/><br/>Usuário: '.$dados['usuario'][0] -> vc_login."<br/>Senha inicial: $senha<br/><br/>Se não foi você que solicitou essa recuperação de senha, não se preocupe pois sua senha antiga ainda funciona.<br/><br/>Acesse o sistema por meio do link: <a href=\"".base_url()."\">".base_url()."</a></body></html>";
                                $this -> email -> message($msg);
                                if(!$this -> email -> send()){
                                        $dados['sucesso'] = '';
                                        $dados['erro'] =  'Erro no envio do e-mail com a nova senha. Os responsáveis já foram avisados.';
                                        $this -> Usuarios_model -> log('erro', 'Usuarios/novaSenha', 'Erro de envio de e-mail com senha de cadastro para o e-mail '.$dados['usuario'][0] -> vc_email.' do usuário '.$dados['usuario'][0] -> pr_usuario, 'tb_usuarios', $usuario);
                                }
                                else{
                                        $dados['sucesso'] = 'Nova senha enviada com sucesso.<br/><br/><a href="'.base_url('Usuarios/index').'" class="btn btn-light">Voltar</a>';
                                        $dados['erro'] =  NULL;
                                        $this -> Usuarios_model -> log('sucesso', 'Usuarios/novaSenha', "Nova senha para Usuário {$usuario} enviada com sucesso.", 'tb_usuarios', $usuario);
                                }
                        }
                        else{
                                $erro = $this -> db -> error();
                                $dados['sucesso'] = '';
                                $dados['erro'] =  'Erro na recuperação dos dados do usuário. Os responsáveis já foram avisados.<br/><br/><a href="'.base_url('Usuarios/index').'" class="btn btn-light">Voltar</a>';
                                $this -> Usuarios_model -> log('erro', 'Usuarios/novaSenha', "Erro na recuperação dos dados do usuário {$usuario}. Erro: ".$erro['message']);
                        }
                }
                $this -> load -> view('usuarios', $dados);
        }
	public function delete(){
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

                $this -> load -> helper('string');

                $pagina['menu1'] = 'Usuarios';
                $pagina['menu2'] = 'delete';
                $pagina['url'] = 'Usuarios/delete';
                $pagina['nome_pagina'] = 'Desativar usuário';
                $pagina['icone'] = 'fa fa-users';

                $dados = $pagina;
                if($this -> session -> perfil != 3){ //administrador
                        $this -> Usuarios_model -> log('seguranca', 'Usuarios/create', "Tentativa de acesso à gestão de usuários pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_usuarios', $this -> session -> uid);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Interna/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $usuario = $this -> uri -> segment(3);
                        $dados['usuario'] = $this -> Usuarios_model -> get_usuarios($usuario);
                        $dados += (array) $dados['usuario'];
                        //var_dump($usuario);
                        //var_dump($dados_usuario);

                        if($usuario == $this -> session -> uid){
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Você não pode desativar seu próprio acesso por essa funcionalidade. Essa tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Usuarios/index').'" class="btn btn-light">Voltar</a>';
                                $this -> Usuarios_model -> log('seguranca', 'Usuarios/delete', "Usuário {$usuario} tentou se desativar.", 'tb_usuarios', $usuario);
                        }
                        else{
                                $this -> Usuarios_model -> update_usuario('bl_removido', '1', $usuario);
                                $this -> Usuarios_model -> update_usuario('vc_senha', null, $usuario);
                                $this -> Usuarios_model -> update_usuario('vc_senha_temporaria', null, $usuario);
                                $dados['sucesso'] = 'O usuário \''.$dados['usuario'][0] -> vc_nome.'\' foi desativado com sucesso.<br/><br/><a href="'.base_url('Usuarios/index').'" class="btn btn-light">Voltar</a>';
                                $dados['erro'] = '';
                                $this -> Usuarios_model -> log('sucesso', 'Usuarios/delete', "Usuário {$usuario} desativado pelo usuário ".$this -> session -> uid, 'tb_usuarios', $usuario);
                        }
                }
                $this -> load -> view('usuarios', $dados);
        }
	public function reactivate(){
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

                $this -> load -> helper('string');
                $this -> load -> library('encryption');

                $pagina['menu1'] = 'Usuarios';
                $pagina['menu2'] = 'reactivate';
                $pagina['url'] = 'Usuarios/reactivate';
                $pagina['nome_pagina'] = 'Reativar conta';
                $pagina['icone'] = 'fa fa-users';

                $dados = $pagina;
                if($this -> session -> perfil != 3){ //administrador
                        $this -> Usuarios_model -> log('seguranca', 'Usuarios/create', "Tentativa de acesso à gestão de usuários pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_usuarios', $this -> session -> uid);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Interna/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $usuario = $this -> uri -> segment(3);
                        $dados['usuario'] = $this -> Usuarios_model -> get_usuarios($usuario);
                        if($dados['usuario'][0] -> pr_usuario > 0){
                                $senha = random_string ('alnum', 8);
                                $password = $this -> encryption -> encrypt($senha);
                                $this -> Usuarios_model -> update_usuario('bl_removido', '0', $usuario);
                                $this -> Usuarios_model -> update_usuario('vc_senha_temporaria', $password, $usuario);
                                $this -> Usuarios_model -> update_usuario('dt_alteracao', date('Y-m-d H:i:s'), $usuario);

                                $this -> email -> set_mailtype('html');
                                $this -> email -> from($this -> config -> item('email'), $this -> config -> item('nome'));
                                $this -> email -> to($dados['usuario'][0] -> vc_email);
                                $this -> email -> subject('['.$this -> config -> item('nome').'] Nova senha');
                                $msg='<html><body>Olá '.$dados['usuario'][0] -> vc_nome.',<br/><br/>Foi solicitada uma nova senha do '.$this -> config -> item('nome').'. Seus dados para acesso são:<br/><br/>Usuário: '.$dados['usuario'][0] -> vc_login."<br/>Senha inicial: $senha<br/><br/>Se não foi você que solicitou essa recuperação de senha, não se preocupe pois sua senha antiga ainda funciona.<br/><br/>Acesse o sistema por meio do link: <a href=\"".base_url()."\">".base_url()."</a></body></html>";
                                $this -> email -> message($msg);
                                if(!$this -> email -> send()){
                                        $this -> Usuarios_model -> log('erro', 'Usuarios/reactivate', 'Erro de envio de e-mail com senha de cadastro para o e-mail '.$dados['usuario'][0] -> vc_email.' do usuário '.$dados['usuario'][0] -> pr_usuario, 'tb_usuarios', $usuario);
                                }
                                else{
                                        $this -> Usuarios_model -> log('sucesso', 'Usuarios/reactivate', "Nova senha para Usuário {$usuario} enviada com sucesso.", 'tb_usuarios', $usuario);
                                }
                                $dados['sucesso'] = 'Usuário reativado com sucesso.<br/><br/><a href="'.base_url('Usuarios/index').'" class="btn btn-light">Voltar</a>';
                                $dados['erro'] =  NULL;
                        }
                        else{
                                $erro = $this -> db -> error();
                                $dados['sucesso'] = '';
                                $dados['erro'] =  'Erro na recuperação dos dados do usuário. Os responsáveis já foram avisados.';
                                $this -> Usuarios_model -> log('erro', 'Usuarios/reactivate', "Erro na recuperação dos dados do usuário {$usuario}. Erro: ".$erro['message']);
                        }
                }
                $this -> load -> view('usuarios', $dados);
        }
}