<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AcessoPasta extends CI_Controller {
        function __construct() {
                parent::__construct();
                $this -> load -> model('Documentos_model');
        }

	public function index(){
                $this -> load -> helper('date');
                $this -> load -> model('Pastas_model');

                $pagina['menu1'] = 'AcessoPasta';
                $pagina['menu2'] = 'index';
                $pagina['url'] = 'AcessoPasta/index';
                $pagina['nome_pagina'] = 'Documentos';
                $pagina['icone'] = 'fa fa-folder-open';

                $dados = $pagina;
                $dados['adicionais'] = array('datatables' => true);
                $id = $this -> uri -> segment(3);
                if(strlen($id) > 0){
                        $dados_pasta = $this -> Pastas_model -> get_pastas_publicas ($id);

                        if($dados_pasta != null){
                                if((strtotime($dados_pasta[0] -> cadastro) + (7 * 24 * 60 * 60)) < time()){ //7 dias de acesso
                                        $dados['erro'] = 'Este código de acesso já está expirado.';
                                }
                                else{
                                        $dados += (array) $dados_pasta[0];
                                        $dados['documentos'] = $this -> Documentos_model -> get_documentos('', $dados_pasta[0] -> es_pasta);
                                        $dados['erro'] = '';
                                }
                        }
                        else{
                                $dados['erro'] = 'Não foi encontrada a pasta com esse código de acesso.';
                        }
                }
                else{
                        $dados['erro'] = 'Não foi encontrada a pasta com esse código de acesso.';
                }
                $this -> load -> view('pastapublica', $dados);
	}
	public function download(){
                $documento = $this -> uri -> segment(3);
                $dados_documento = $dados['documentos'] = $this -> Documentos_model -> get_documentos($documento);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_URL, $dados_documento[0] -> vc_link);
                curl_setopt($ch, CURLOPT_REFERER, $dados_documento[0] -> vc_link);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $content = curl_exec($ch);
                curl_close($ch);

                //var_dump($content);

                header('Content-type: '.$dados_documento[0] -> vc_mime);
                if(strstr($dados_documento[0] -> vc_mime, 'application/pdf')){
                        $extensao = 'pdf';
                }
                else if(strstr($dados_documento[0] -> vc_mime, 'text/html')){
                        $extensao = 'html';
                }
                else if(strstr($dados_documento[0] -> vc_mime, 'text/csv')){
                        $extensao = 'csv';
                }
                else if(strstr($dados_documento[0] -> vc_mime, 'text/plain')){
                        $extensao = 'txt';
                }
                else if(strstr($dados_documento[0] -> vc_mime, 'text/xml')){
                        $extensao = 'xml';
                }
                else if(strstr($dados_documento[0] -> vc_mime, 'image/jpeg')){
                        $extensao = 'jpg';
                }
                else if(strstr($dados_documento[0] -> vc_mime, 'image/png')){
                        $extensao = 'png';
                }
                header('Content-Disposition: attachment; filename="'.$dados_documento[0] -> ch_sei.'-'.$dados_documento[0] -> in_documento.".{$extensao}\"");
                echo $content;
	}
}
