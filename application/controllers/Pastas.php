<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pastas extends CI_Controller {
        function __construct() {
                parent::__construct();
                if(!$this -> session -> logado){
                        redirect('Publico');
                }
                $this -> load -> model('Pastas_model');
        }

	public function index($tipo = 'normal')	{
                $this -> load -> helper('date');

                $pagina['menu1'] = 'Pastas';
                $pagina['menu2'] = 'index';
                $pagina['url'] = 'Pastas/index';
                $pagina['nome_pagina'] = 'Pastas funcionais';
                $pagina['icone'] = 'fa fa-folder-open';

                $dados = $pagina;
                $dados['adicionais'] = array('datatables' => true);
                //$dados += $this -> input -> post(null,true);

                $so_ativas = true;
                if($this -> input -> post('desativadas') == 'on'){
                        $so_ativas = false;
                        $dados['desativadas'] = 'on';
                }
                else{
                        $so_ativas = true;
                        $dados['desativadas'] = '';
                }
                if($this -> session -> perfil == 2 || $this -> session -> perfil == 3 || $this -> session -> perfil == 5){
                        $so_exercicio = false;
                        if($this -> input -> post('outroexercicio') == 'on' || strlen($this -> input -> post('enviado')) == 0){
                                $so_exercicio = false;
                                $dados['outroexercicio'] = 'on';
                        }
                        else{
                                $so_exercicio = true;
                                $dados['outroexercicio'] = '';
                        }
                }
                else{
                        $so_exercicio = false;
                }
                $nome[0] = '';
                if(strlen($this -> input -> post('nome')) > 0){
                        $nome = explode('-', $this -> input -> post('nome'));
                        //var_dump($nome);
                }
                $dados['tipo'] = $tipo;
                if($tipo == 'normal'){
                        if($this -> session -> perfil == 3 && strlen($this -> input -> post('nome')) > 0){ //administrador pesquisando nome (sem restrição de instituição, ativas e de exercício)
                                $dados['pastas'] = $this -> Pastas_model -> get_pastas('', '', false, false, false, false, trim($nome[0]));
                        }
                        else{
                                $dados['num_pastas'] = $this -> Pastas_model -> get_pastas('', $this -> session -> instituicao, $so_exercicio, $so_ativas, false, true, trim($nome[0]));
                                if($dados['num_pastas'] < 4000){ //mostra normal
                                        $dados['pastas'] = $this -> Pastas_model -> get_pastas('', $this -> session -> instituicao, $so_exercicio, $so_ativas, false, false, trim($nome[0]));
                                }
                                else{ //transforma em 'search'
                                        $pagina['menu2'] = 'search';
                                        $pagina['url'] = 'Pastas/index';
                                        $pagina['nome_pagina'] = 'Pesquisar em todas as pastas';
                                        $dados = $pagina;
                                        $dados['adicionais'] = array('jquery-ui' => true, 'typeahead' => true);
                                        $dados['sucesso'] = '';
                                        $dados['erro'] = '';
                                        $dados['pastas'] = $this -> Pastas_model -> get_pastas('', $this -> session -> instituicao, false, false, true, false, ''); //para o autocomplete
                                }
                        }
                }
                else{
                        if($this -> session -> perfil == 3 && strlen($this -> input -> post('nome')) > 0){ //administrador pesquisando nome (sem restrição de instituição, ativas e de exercício)
                                $dados['pastas'] = $this -> Pastas_model -> get_pastas('', '', false, false, false, false, trim($nome[0]),'','0');
                        }
                        else{
                                $dados['num_pastas'] = $this -> Pastas_model -> get_pastas('', $this -> session -> instituicao, $so_exercicio, $so_ativas, false, true, trim($nome[0]),'','','1');
                                if($dados['num_pastas'] < 4000){ //mostra normal
                                        $dados['pastas'] = $this -> Pastas_model -> get_pastas('', $this -> session -> instituicao, $so_exercicio, $so_ativas, false, false, trim($nome[0]),'','','1');
                                }
                                else{ //transforma em 'search'
                                        $pagina['menu2'] = 'search';
                                        $pagina['url'] = 'Pastas/index';
                                        $pagina['nome_pagina'] = 'Pesquisar em todas as pastas';
                                        $dados = $pagina;
                                        $dados['adicionais'] = array('jquery-ui' => true, 'typeahead' => true);
                                        $dados['sucesso'] = '';
                                        $dados['erro'] = '';
                                        $dados['pastas'] = $this -> Pastas_model -> get_pastas('', $this -> session -> instituicao, false, false, true, false, '','','','1'); //para o autocomplete
                                }
                        }
                }
                $this -> load -> view('pastas', $dados);
	}
	public function create($tipo = 'normal'){
                $this -> load -> model('Instituicoes_model');
                $this -> load -> model('Usuarios_model');
                $this -> load -> library('MY_Form_Validation');

                $pagina['menu1'] = 'Pastas';
                $pagina['menu2'] = 'create';
                $pagina['url'] = 'Pastas/create';
                $pagina['nome_pagina'] = 'Nova pasta funcional';
                $pagina['icone'] = 'fa fa-folder-open';
                $dados = $pagina;

                $dados_form = $this -> input -> post(null, true);
                if(isset($dados_form['tipo']) && strlen($dados_form['tipo']) > 0){
                                $tipo = $dados_form['tipo'];
                }
                $dados['tipo'] = $tipo;

                if($this -> session -> perfil != 3 && $this -> session -> perfil != 5){
                        $this -> Usuarios_model -> log('seguranca', 'Pastas/create', "Tentativa de criar pasta pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_usuarios', $this -> session -> uid);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $dados['adicionais'] = array('inputmasks' => true);
                        //$dados += $this -> input -> post(null,true);

                        $this -> form_validation -> set_rules('nome', "'Nome do servidor'", 'required|min_length[10]');
                        if($tipo == 'normal'){
                                $this -> form_validation -> set_rules('masp', "'MASP'", 'required|digitoMatrícula', array('digitoMatrícula' => 'O número inserido no campo \'MASP\' é inválido.'));
                        }
                        if($tipo == 'normal'){
                                $this -> form_validation -> set_rules('admissao', "'Admissão'", 'required|maior_que_zero', array('maior_que_zero' => 'O campo \'Admissão\' é obrigatório.'));
                        }
                        $this -> form_validation -> set_rules('cpf', "'CPF'", 'required|verificaCPF', array('required' => 'O campo \'CPF\' é obrigatório.', 'verificaCPF' => 'O número inserido no camp \'CPF\' é inválido.'));
                        if($this -> session -> perfil == 3 && $tipo == 'normal'){ //administrador
                                $this -> form_validation -> set_rules('lotacao', "'Instituição de lotação'", 'required|maior_que_zero', array('maior_que_zero' => 'O campo \'Instituição de lotação\' é obrigatório.'));
                        }
                        $this -> form_validation -> set_rules('exercicio', "'Instituição de exercício'", 'required|maior_que_zero', array('maior_que_zero' => 'O campo \'Instituição de exercício\' é obrigatório.'));

                        if ($this -> form_validation -> run() == FALSE){
                                $dados['sucesso'] = '';
                                $dados['erro'] = validation_errors();
                        }
                        else{
                                $dados_form['cpf'] = str_replace('.', '', $dados_form['cpf']);
                                $dados_form['cpf'] = str_replace('-', '', $dados_form['cpf']);
                                $dados_form['masp'] = str_replace('.', '', $dados_form['masp']);
                                $dados_form['masp'] = str_replace('-', '', $dados_form['masp']);
                                $dados_form['masp'] = str_replace('/', '', $dados_form['masp']);
                                if($this -> session -> perfil != 3 || $tipo != 'normal'){
                                        $dados_form['lotacao'] = $this -> session -> instituicao;
                                }
                                if($tipo != 'normal'){
                                        if(strlen($dados_form['masp']) == 0){
                                                $dados_form['masp'] = '0';
                                        }
                                        $dados_form['admissao'] = '0';
                                }
                                if($tipo == 'normal'){
                                        $dados['tipo_pasta'] = 'servidor';
                                }

                                $query2 = $this -> db -> query("SELECT * from tb_pastas where in_masp={$dados_form['masp']} and in_admissao={$dados_form['admissao']} and ch_cpf='{$dados_form['cpf']}'");
                                $num = $query2 -> num_rows();
                                if($num>0){
                                        $dados['sucesso'] = '';
                                        $dados['erro'] =  'Já existe uma pasta criada com esse MASP e essa admissão.';
                                }
                                else{
                                        $pasta = $this -> Pastas_model -> create_pasta($dados_form);
                                        if($pasta > 0){
                                                if($tipo == 'normal'){
                                                        $dados['sucesso'] = 'Pasta cadastrada com sucesso.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                                                }
                                                else{
                                                        $dados['sucesso'] = 'Pasta cadastrada com sucesso.<br/><br/><a href="'.base_url('Pastas/index/externo').'" class="btn btn-light">Voltar</a>';
                                                }
                                                $dados['erro'] =  NULL;
                                                $this -> Usuarios_model -> log('sucesso', 'Pastas/create', "Pasta {$pasta} criada com sucesso.", 'tb_pastas', $pasta);
                                        }
                                        else{
                                                $erro = $this -> db -> error();
                                                $dados['sucesso'] = '';
                                                if($tipo == 'normal'){
                                                        $dados['erro'] =  'Erro no cadastro da pasta. Os responsáveis já foram avisados.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                                                }
                                                else{
                                                        $dados['erro'] =  'Erro no cadastro da pasta. Os responsáveis já foram avisados.<br/><br/><a href="'.base_url('Pastas/index/externo').'" class="btn btn-light">Voltar</a>';
                                                }
                                                $this -> Usuarios_model -> log('erro', 'Pastas/create', 'Erro de criação da pasta'.$dados_form['masp'].'-'.$dados_form['admissao'].'. Erro: '.$erro['message']);
                                        }
                                }
                        }
                        $semad_grupo = array(1371, 2101, 2241, 2091);
                        $semad_string = '1371, 2101, 2241, 2091';
                        if(in_array($this -> session -> instituicao,$semad_grupo) && $this -> session -> perfil != 3){
                                $dados['instituicoes2'] = $this -> Instituicoes_model -> get_instituicoes ($semad_string);
                        }
                        $dados['instituicoes'] = $this -> Instituicoes_model -> get_instituicoes ('');

                }
                $this -> load -> view('pastas', $dados);
        }
        public function edit(){
                $this -> load -> model('Instituicoes_model');
                $this -> load -> model('Usuarios_model');

                $pagina['menu1'] = 'Pastas';
                $pagina['menu2'] = 'edit';
                $pagina['url'] = 'Pastas/edit';
                $pagina['nome_pagina'] = 'Editar pasta';
                $pagina['icone'] = 'fa fa-folder-open';
                $dados = $pagina;

                $dados_form = $this -> input -> post(null,true);
                $pasta = $this -> uri -> segment(3);
                $tipo = $this -> uri -> segment(4);
                if(isset($dados_form['codigo']) && $dados_form['codigo'] > 0){
                        $pasta = $dados_form['codigo'];
                        if(isset($dados_form['tipo'])){
                                $tipo = $dados_form['tipo'];
                        }
                }

                if(strlen($tipo) == 0){
                        $tipo = 'normal';
                }

                if($this -> session -> perfil != 3 && $this -> session -> perfil != 5){
                        $this -> Usuarios_model -> log('seguranca', 'Pastas/edit', "Tentativa de desativar pasta {$pasta} pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_pastas', $pasta);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $dados['adicionais'] = array('inputmasks' => true);
                        $dados_pasta = $this -> Pastas_model -> get_pastas ($pasta);
                        $dados['codigo'] = $pasta;
                        $dados['tipo'] = $tipo;
                        $dados += (array) $dados_pasta[0];

                        if($this -> session -> perfil != 3 && $dados_pasta[0] -> es_instituicao_exercicio != $this -> session -> instituicao){
                                $this -> Usuarios_model -> log('seguranca', 'Pastas/edit', "Tentativa de editar pasta {$pasta} pelo usuário ".$this -> session -> uid.' de outra instituição de exercício.', 'tb_pastas', $pasta);
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                                $dados['menu2'] = '';
                        }
                        else{
                                $this -> form_validation -> set_rules('nome', "'Nome do servidor'", 'required|min_length[10]');
                                //$this -> form_validation -> set_rules('masp', "'MASP'", 'required|digitoMatrícula', array('digitoMatrícula' => 'O número inserido no campo \'MASP\' é inválido.'));
                                //$this -> form_validation -> set_rules('admissao', "'Admissão'", 'required|maior_que_zero', array('maior_que_zero' => 'O número inserido no campo \'Admissão\' é inválido.'));
                                //$this -> form_validation -> set_rules('cpf', "'CPF'", 'required|verificaCPF', array('required' => 'O campo \'CPF\' é obrigatório.', 'verificaCPF' => 'O número inserido no camp \'CPF\' é inválido.'));
                                if($this -> session -> perfil == 3 && $tipo == 'normal'){ //administrador
                                        $this -> form_validation -> set_rules('lotacao', "'Instituição de lotação'", 'required|maior_que_zero', array('maior_que_zero' => 'O campo \'Instituição de lotação\' é obrigatório.'));
                                }
                                $this -> form_validation -> set_rules('exercicio', "'Instituição de exercício'", 'required|maior_que_zero', array('maior_que_zero' => 'O campo \'Instituição de exercício\' é obrigatório.'));

                                if ($this -> form_validation -> run() == FALSE){
                                        $dados['sucesso'] = '';
                                        $dados['erro'] = validation_errors();
                                }
                                else{
                                        //$dados_form['cpf'] = str_replace('.', '', $dados_form['cpf']);
                                        //$dados_form['cpf'] = str_replace('-', '', $dados_form['cpf']);
                                        //$dados_form['masp'] = str_replace('.', '', $dados_form['masp']);
                                        //$dados_form['masp'] = str_replace('-', '', $dados_form['masp']);
                                        //$dados_form['masp'] = str_replace('/', '', $dados_form['masp']);
                                        $this -> Pastas_model -> update_pasta('vc_nome', $dados_form['nome'], $pasta);
                                        //$this -> Pastas_model -> update_pasta('ch_cpf', $dados_form['cpf'], $pasta);
                                        $semad_grupo = array(1371, 2101, 2241, 2091);
                                        if(($this -> session -> perfil == 3 && $tipo == 'normal') || in_array($this -> session -> instituicao, $semad_grupo)){ //administrador
                                                $this -> Pastas_model -> update_pasta('es_instituicao_lotacao', $dados_form['lotacao'], $pasta);
                                        }
                                        $this -> Pastas_model -> update_pasta('es_instituicao_exercicio', $dados_form['exercicio'], $pasta);
                                        if($tipo == 'normal'){
                                                $dados_form['tipo_pasta'] = 'servidor';
                                                //echo "teste";
                                        }

                                        $this -> Pastas_model -> update_pasta('en_tipo', $dados_form['tipo_pasta'], $pasta);
                                        $this -> Pastas_model -> add_viewer($pasta, $dados_pasta[0] -> es_instituicao_exercicio);
                                        $this -> Usuarios_model -> log('sucesso', 'Pastas/edit', "Pasta {$pasta} editada com sucesso pelo usuário ".$this -> session -> uid, 'tb_pastas', $pasta);

                                        if($tipo == 'normal'){
                                                $dados['sucesso'] = 'Pasta editada com sucesso.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                                        }
                                        else{
                                                $dados['sucesso'] = 'Pasta editada com sucesso.<br/><br/><a href="'.base_url('Pastas/index/externo').'" class="btn btn-light">Voltar</a>';
                                        }
                                        $dados['erro'] = '';
                                }

                        }
                        $semad_grupo = array(1371, 2101, 2241, 2091);
                        $semad_string = '1371, 2101, 2241, 2091';
                        if(in_array($this -> session -> instituicao, $semad_grupo) && $this -> session -> perfil != 3){
                                $dados['instituicoes2'] = $this -> Instituicoes_model -> get_instituicoes ($semad_string);
                        }

                        $dados['instituicoes'] = $this -> Instituicoes_model -> get_instituicoes ('');

                }
                $this -> load -> view('pastas', $dados);
        }


        public function transform(){
                $this -> load -> model('Instituicoes_model');
                $this -> load -> model('Usuarios_model');

                $pagina['menu1'] = 'Pastas';
                $pagina['menu2'] = 'transform';
                $pagina['url'] = 'Pastas/transform';
                $pagina['nome_pagina'] = 'Transformar em servidor efetivo';
                $pagina['icone'] = 'fa fa-folder-open';
                $dados = $pagina;

                $dados_form = $this -> input -> post(null, true);
                $pasta = $this -> uri -> segment(3);
                $tipo = 'normal';
                if(isset($dados_form['codigo'])){
                        $pasta = $dados_form['codigo'];
                }

                if($this -> session -> perfil != 3 && $this -> session -> perfil != 5){
                        $this -> Usuarios_model -> log('seguranca', 'Pastas/edit', "Tentativa de desativar pasta {$pasta} pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_pastas', $pasta);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $dados['adicionais'] = array('inputmasks' => true);
                        $dados_pasta = $this -> Pastas_model -> get_pastas ($pasta);
                        //var_dump($dados_vaga);
                        $dados['codigo'] = $pasta;

                        $dados['tipo'] = $tipo;
                        $dados += (array) $dados_pasta[0];
                        //$dados += $this -> input -> post(null,true);
                        //var_dump($this -> input -> post());

                        if($this -> session -> perfil != 3 && $dados_pasta[0] -> es_instituicao_exercicio != $this -> session -> instituicao){
                                $this -> Usuarios_model -> log('seguranca', 'Pastas/transformar', "Tentativa de transformar pasta {$pasta} pelo usuário ".$this -> session -> uid.' de outra instituição de exercício.', 'tb_pastas', $pasta);
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                                $dados['menu2'] = '';
                        }
                        else if($dados_pasta[0] -> en_tipo != 'serventuario'){
                                $this -> Usuarios_model -> log('seguranca', 'Pastas/transformar', "Tentativa de transformar pasta {$pasta} pelo usuário ".$this -> session -> uid.' de um usuário externo diferente de serventuário.', 'tb_pastas', $pasta);
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                                $dados['menu2'] = '';
                        }
                        else{
                                //$this -> form_validation -> set_rules('nome', "'Nome do servidor'", 'required|min_length[10]');
                                $this -> form_validation -> set_rules('masp', "'MASP'", 'required|digitoMatrícula', array('digitoMatrícula' => 'O número inserido no campo \'MASP\' é inválido.'));
                                $this -> form_validation -> set_rules('admissao', "'Admissão'", 'required|maior_que_zero', array('maior_que_zero' => 'O número inserido no campo \'Admissão\' é inválido.'));
                                //$this -> form_validation -> set_rules('cpf', "'CPF'", 'required|verificaCPF', array('required' => 'O campo \'CPF\' é obrigatório.', 'verificaCPF' => 'O número inserido no camp \'CPF\' é inválido.'));


                                if ($this -> form_validation -> run() == FALSE){
                                        $dados['sucesso'] = '';
                                        $dados['erro'] = validation_errors();
                                }
                                else{
                                        //$dados_form['cpf'] = str_replace('.', '', $dados_form['cpf']);
                                        //$dados_form['cpf'] = str_replace('-', '', $dados_form['cpf']);
                                        $dados_form['masp'] = str_replace('.', '', $dados_form['masp']);
                                        $dados_form['masp'] = str_replace('-', '', $dados_form['masp']);
                                        $dados_form['masp'] = str_replace('/', '', $dados_form['masp']);
                                        $this -> Pastas_model -> update_pasta('in_masp', $dados_form['masp'], $pasta);
                                        $this -> Pastas_model -> update_pasta('in_admissao', $dados_form['admissao'], $pasta);
                                        //$this -> Pastas_model -> update_pasta('ch_cpf', $dados_form['cpf'], $pasta);
                                        $this -> Pastas_model -> update_pasta('en_tipo', $tipo, $pasta);

                                        $this -> Usuarios_model -> log('sucesso', 'Pastas/edit', "Pasta {$pasta} editada com sucesso pelo usuário ".$this -> session -> uid, 'tb_pastas', $pasta);

                                        $dados['sucesso'] = 'Pasta transformada com sucesso.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                                        $dados['erro'] = '';
                                }
                        }
                }
                $this -> load -> view('pastas', $dados);
        }

        public function delete(){
                $this -> load -> model('Usuarios_model');

                $pagina['menu1'] = 'Pastas';
                $pagina['menu2'] = 'delete';
                $pagina['url'] = 'Pastas/delete';
                $pagina['nome_pagina'] = 'Desativar pasta';
                $pagina['icone'] = 'fa fa-folder-open';
                $dados = $pagina;

                $dados_form = $this -> input -> post(null,true);
                $pasta = $this -> uri -> segment(3);
				$tipo = $this -> uri -> segment(4);
                if(isset($dados_form['codigo']) && $dados_form['codigo'] > 0){
                        $pasta = $dados_form['codigo'];
                }
                if(strlen($tipo) == 0){
                        $tipo = 'normal';
                }
                if($this -> session -> perfil != 3 && $this -> session -> perfil != 5){
                        $this -> Usuarios_model -> log('seguranca', 'Pastas/delete', "Tentativa de desativar pasta {$pasta} pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_pastas', $pasta);
                        $dados['sucesso'] = '';
                        if($tipo == 'normal'){
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                        }
                        else{
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index/externo').'" class="btn btn-light">Voltar</a>';
                        }
                }
                else{
                        $dados_pasta = $this -> Pastas_model -> get_pastas ($pasta);
                        if($this -> session -> perfil == 5 && $dados_pasta[0] -> es_instituicao_exercicio != $this -> session -> instituicao){
                                $this -> Usuarios_model -> log('seguranca', 'Pastas/delete', "Tentativa de desativar pasta {$pasta} pelo usuário ".$this -> session -> uid.' de outra instituição de exercício.', 'tb_pastas', $pasta);
                                $dados['sucesso'] = '';
                                if($tipo == 'normal'){
                                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                                }
                                else{
                                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index/externo').'" class="btn btn-light">Voltar</a>';
                                }
                        }
                        else{
                                $this -> Pastas_model -> update_pasta('bl_ativo', '0', $pasta);
                                if($tipo == 'normal'){
                                        $dados['sucesso'] = "Pasta desativada com sucesso.<br/><br/><a href=\"".base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                                }
                                else{
                                        $dados['sucesso'] = "Pasta desativada com sucesso.<br/><br/><a href=\"".base_url('Pastas/index/externo').'" class="btn btn-light">Voltar</a>';
                                }
                                $dados['erro'] = '';
                                $this -> Usuarios_model -> log('sucesso', 'Pastas/delete', "Pasta {$pasta} desativada pelo usuário ".$this -> session -> uid, 'tb_pastas', $pasta);
                        }
                }
                $this -> load -> view('pastas', $dados);
        }
	public function reactivate(){
                $this -> load -> model('Usuarios_model');
                $pagina['menu1'] = 'Pastas';
                $pagina['menu2'] = 'reactivate';
                $pagina['url'] = 'Pastas/reactivate';
                $pagina['nome_pagina'] = 'Reativar pasta';
                $pagina['icone'] = 'fa fa-folder-open';
                $dados = $pagina;

                $dados_form = $this -> input -> post(null,true);
                $pasta = $this -> uri -> segment(3);
                $tipo = $this -> uri -> segment(4);
                if(isset($dados_form['codigo']) && $dados_form['codigo'] > 0){
                        $pasta = $dados_form['codigo'];
                }
                if(strlen($tipo) == 0){
                        $tipo = 'normal';
                }

                if($this -> session -> perfil != 3 && $this -> session -> perfil != 5){
                        $this -> Usuarios_model -> log('seguranca', 'Pastas/reactivate', "Tentativa de reativar pasta {$pasta} pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_pastas', $pasta);
                        $dados['sucesso'] = '';
                        if($tipo == 'normal'){
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                        }
                        else{
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index/externo').'" class="btn btn-light">Voltar</a>';
                        }
                }
                else{
                        $dados_pasta = $this -> Pastas_model -> get_pastas ($pasta, '', false, false);
                        //se a pasta for de instituição diferente do usuário logado e perfil = 5
                        if(($dados_pasta[0] -> es_instituicao_exercicio != $this -> session -> instituicao) && $this -> session -> perfil == 5){
                                $this -> Usuarios_model -> log('seguranca', 'Pastas/reactivate', "Tentativa de reativar pasta {$pasta} pelo usuário ".$this -> session -> uid.' de outra instituição de exercício.', 'tb_pastas', $pasta);
                                $dados['sucesso'] = '';
                                if($tipo == 'normal'){
                                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                                }
                                else{
                                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index/externo').'" class="btn btn-light">Voltar</a>';
                                }
                        }
                        else{
                                $this -> Pastas_model -> update_pasta('bl_ativo', '1', $pasta);
                                if($tipo == 'normal'){
                                        $dados['sucesso'] = "Pasta reativada com sucesso.<br/><br/><a href=\"".base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                                }
                                else{
                                        $dados['sucesso'] = "Pasta reativada com sucesso.<br/><br/><a href=\"".base_url('Pastas/index/externo').'" class="btn btn-light">Voltar</a>';
                                }
                                $dados['erro'] = '';
                                $this -> Usuarios_model -> log('sucesso', 'Pastas/reactivate', "Pasta {$pasta} reativada pelo usuário ".$this -> session -> uid, 'tb_pastas', $pasta);
                        }
                }
                $this -> load -> view('pastas', $dados);
        }
	public function view(){
                $this -> load -> model('Instituicoes_model');
                $this -> load -> model('Usuarios_model');

                $pasta = $this -> uri -> segment(3);
                $dados_form = $this -> input -> post(null,true);
                if(isset($dados_form['codigo']) && $dados_form['codigo'] > 0){
                        $pasta = $dados_form['codigo'];
                }
                $pagina['menu1'] = 'Pastas';
                $pagina['menu2'] = 'view';
                $pagina['url'] = 'Pastas/view';
                $pagina['nome_pagina'] = 'Instituições visualizadoras';
                $pagina['icone'] = 'fa fa-folder-open';
                $dados = $pagina;

                if($this -> session -> perfil != 3){
                        $this -> Usuarios_model -> log('seguranca', 'Pastas/view', "Tentativa de editar visualizadores de pasta {$pasta} pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_pastas', $pasta);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $dados['adicionais'] = array('datatables' => true);
                        $dados_pasta = $this -> Pastas_model -> get_pastas ($pasta);
                        $dados['codigo'] = $pasta;
                        $dados += (array) $dados_pasta[0];
                        //$vaga = $this -> uri -> segment(3);
                        //var_dump($this -> input -> post());
                        if (strlen($this -> input -> post ('novainst'))>0){
                                $this -> Pastas_model -> add_viewer($pasta, $this -> input -> post ('novainst'));
                                $this -> Usuarios_model -> log('sucesso', 'Pastas/view', "Pasta {$pasta} associada com sucesso à instituicao ".$this -> input -> post ('novainst'), 'tb_pastas', $pasta);
                        }
                        $dados['sucesso'] = '';
                        $dados['erro'] = '';

                        $dados['visualizadores'] = $this -> Instituicoes_model -> get_instituicoes ('', $pasta, 'object');
                        $dados['instituicoes'] = $this -> Instituicoes_model -> get_instituicoes ('', '', 'object');
                }
                $this -> load -> view('pastas', $dados);
	}
	public function deleteview(){
                $this -> load -> model('Instituicoes_model');
                $this -> load -> model('Usuarios_model');

                $pagina['menu1'] = 'Pastas';
                $pagina['menu2'] = 'view';
                $pagina['url'] = 'Pastas/view';
                $pagina['nome_pagina'] = 'Instituições visualizadoras';
                $pagina['icone'] = 'fa fa-folder-open';

                $dados = $pagina;
                $inst = $this -> uri -> segment(3);
                $pasta = $this -> uri -> segment(4);
                $dados_pasta = $this -> Pastas_model -> get_pastas ($pasta);
                //var_dump($dados_vaga);
                $dados['codigo'] = $pasta;
                $dados += (array) $dados_pasta[0];

                $this -> Pastas_model -> delete_view($inst, $pasta);
                $dados['sucesso'] = "Acesso excluído com sucesso.<br/><br/><a href=\"".base_url('Pastas/view/'.$pasta).'" class="btn btn-light">Voltar</a>';
                $dados['erro'] = '';
                $this -> Usuarios_model -> log('sucesso', 'Pastas/deleteview', "Acesso à pasta {$pasta} para a instituição {$inst} excluída pelo usuário ".$this -> session -> uid, 'tb_pastas', $pasta);

                $dados['visualizadores'] = $this -> Instituicoes_model -> get_instituicoes ('', $pasta, 'object');
                $dados['instituicoes'] = $this -> Instituicoes_model -> get_instituicoes ('', '', 'object');
                $this -> load -> view('pastas', $dados);
        }
	public function search($tipo = 'normal'){
                if($this -> session -> perfil != 2 && $this -> session -> perfil != 3){ //diferente de administrador e visualizador geral
                        redirect('Pastas/index');
                }
                $this -> load -> model('Instituicoes_model');
                $this -> load -> model('Usuarios_model');

                $pagina['menu1'] = 'Pastas';
                $pagina['menu2'] = 'search';
                $pagina['url'] = 'Pastas/index';
                $pagina['nome_pagina'] = 'Pesquisar em todas as pastas';
                $pagina['icone'] = 'fa fa-folder-open';

                $dados = $pagina;
                $dados['adicionais'] = array('jquery-ui' => true, 'typeahead' => true);
                $dados['sucesso'] = '';
                $dados['erro'] = '';

                $dados['tipo'] = $tipo;
                if($tipo == 'normal'){
                        $dados['pastas'] = $this -> Pastas_model -> get_pastas('', '', false, false, true, false, ''); //para o autocomplete
                }
                else{
                        $dados['pastas'] = $this -> Pastas_model -> get_pastas('', '', false, false, true, false, '','','','1'); //para o autocomplete

                }
                $this -> load -> view('pastas', $dados);
	}
	public function link(){
                $this -> load -> helper('string');
                $codigo = random_string ('alnum', 36);
                if($this -> input -> post ('id')){
                        $pastapublica = $this -> Pastas_model -> create_link ($this -> input -> post ('id'), $codigo);
                        if(strlen($pastapublica) > 0){
                                echo base_url('AcessoPasta/index/'.$codigo);
                        }
                }
	}
}
