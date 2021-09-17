<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorios extends CI_Controller {
        function __construct() {
                parent::__construct();
                $this -> load -> model('Documentos_model');
        }
	public function index(){
                $this -> load -> helper('date');
                $this -> load -> model('Pastas_model');
                $this -> load -> model('Usuarios_model');

                $pagina['menu1'] = 'Relatorios';
                $pagina['menu2'] = 'index';
                $pagina['url'] = 'Relatorios/index';
                $pagina['nome_pagina'] = 'Relatórios';
                $pagina['icone'] = 'fa fa-line-chart';

                $dados = $pagina;
                $dados['adicionais'] = array('datatables' => true);
                $id = $this -> uri -> segment(3);
                $dados['codigo'] = $id;
                $dados['sucesso'] = '';
                $dados['erro'] = '';

                if($id == 1){
                        if($this -> session -> perfil != 3 && $this -> session -> perfil != 5){ //administrador e RH Arquivo
                                $this -> Usuarios_model -> log('seguranca', 'Relatorios/index', "Tentativa de acesso a relatório {$id} pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_usuarios', $this -> session -> uid);
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Relatorios').'" class="btn btn-light">Voltar</a>';
                                $dados['menu2'] = '';
                                $dados['codigo'] = 99;
                        }
                        else{
                                $dados['nome_pagina'] = 'Criação de links de acesso para pastas';
                                if($this -> session -> perfil == 5){ //RH Arquivo
                                        $dados['pastaspublicas'] = $this -> Pastas_model -> get_pastas_publicas('', $this -> session -> instituicao);
                                }
                                else if($this -> session -> perfil == 3){ //administradores
                                        $dados['pastaspublicas'] = $this -> Pastas_model -> get_pastas_publicas('');
                                }
                        }
                }
                else if($id == 2){
                        if($this -> session -> perfil != 3){ //administrador
                                $this -> Usuarios_model -> log('seguranca', 'Relatorios/index', "Tentativa de acesso a relatório {$id} pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_usuarios', $this -> session -> uid);
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Relatorios').'" class="btn btn-light">Voltar</a>';
                                $dados['menu2'] = '';
                                $dados['codigo'] = 99;
                        }
                        else{
                                $dados['nome_pagina'] = 'Alterações de pastas e documentos';
                                $dados['alteracoes'] = $this -> Pastas_model -> get_alteracoes('');
                        }
                }
                else if($id == 3){
                        if($this -> session -> perfil != 3 && $this -> session -> perfil != 5){ //administrador e RH Arquivo
                                $this -> Usuarios_model -> log('seguranca', 'Relatorios/index', "Tentativa de acesso a relatório {$id} pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_usuarios', $this -> session -> uid);
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Relatorios').'" class="btn btn-light">Voltar</a>';
                                $dados['menu2'] = '';
                                $dados['codigo'] = 99;
                        }
                        else{
                                $dados['nome_pagina'] = 'Desativações de documentos';
                                if($this -> session -> perfil == 5){ //RH Arquivo
                                        $dados['remocoes'] = $this -> Pastas_model -> get_remocoes('', $this -> session -> instituicao);
                                }
                                else if($this -> session -> perfil == 3){ //Administrador
                                        $dados['remocoes'] = $this -> Pastas_model -> get_remocoes('');
                                }
                        }
                }
                else if(strlen($id) > 0){
                        $this -> Usuarios_model -> log('seguranca', 'Relatorios/index', "Tentativa de acesso a relatório que não existe pelo usuário ".$this -> session -> uid, 'tb_usuarios', $this -> session -> uid);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Relatorios').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                        $dados['codigo'] = 99;
                }

                $this -> load -> view('relatorios', $dados);
	}
}
