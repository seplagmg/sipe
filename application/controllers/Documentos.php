<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documentos extends CI_Controller {
        function __construct() {
                parent::__construct();
                if(!$this -> session -> logado){
                        redirect('Publico');
                }
                $this -> load -> model('Documentos_model');
        }

	public function index(){
                $this -> load -> helper('date');
                $this -> load -> model('Pastas_model');
                $this -> load -> model('Usuarios_model');

                $pagina['menu1'] = 'Documentos';
                $pagina['menu2'] = 'index';
                $pagina['url'] = 'Documentos/index';
                $pagina['nome_pagina'] = 'Documentos';
                $pagina['icone'] = 'fa fa-folder-open';

                $dados = $pagina;
                $dados['adicionais'] = array('datatables' => true);
                $pasta = $this -> uri -> segment(3);
                $dados_form = $this -> input -> post(null,true);
                if(isset($dados_form['codigo']) && $dados_form['codigo'] > 0){
                        $pasta = $dados_form['codigo'];
                }
                $dados_pasta = $this -> Pastas_model -> get_pastas ($pasta);
                $dados['codigo'] = $pasta;

                if($this -> session -> perfil != 1 && $this -> session -> perfil != 2 && $this -> session -> perfil != 3 && $this -> session -> perfil != 5 && $dados_pasta[0] -> es_instituicao_exercicio != $this -> session -> instituicao){
                        $this -> Usuarios_model -> log('seguranca', 'Documentos/index', "Tentativa de acesso a pasta {$pasta} pelo usuário ".$this -> session -> uid.' de outra institui��o de exerc�cio.', 'tb_pastas', $pasta);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                        $dados['menu2'] = '';
                }
                else{
                        $dados += (array) $dados_pasta[0];
                        $dados['documentos'] = $this -> Documentos_model -> get_documentos('', $pasta);
                }
                $dados['tipos'] = $this -> Documentos_model -> get_tipos_processos('');
                if($dados_pasta){
                        $dados['pastas'] = $this -> Pastas_model -> get_pastas('', '', false, true, false, false, '', $dados_pasta[0] -> in_masp);
                }
                else{
                        $dados['pastas'] = $this -> Pastas_model -> get_pastas($pasta);
                }
                $this -> load -> view('documentos', $dados);
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
                $mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                curl_close($ch);

                //var_dump($content);

                //header('Content-type: '.$dados_documento[0] -> vc_mime);
                header('Content-type: '.$mime);
                if(strstr($mime, 'application/pdf')){
                        $extensao = 'pdf';
                }
                else if(strstr($mime, 'text/html')){
                        $extensao = 'html';
                }
                else if(strstr($mime, 'text/csv')){
                        $extensao = 'csv';
                }
                else if(strstr($mime, 'text/plain')){
                        $extensao = 'txt';
                }
                else if(strstr($mime, 'text/xml')){
                        $extensao = 'xml';
                }
                else if(strstr($mime, 'image/jpeg')){
                        $extensao = 'jpg';
                }
                else if(strstr($mime, 'image/png')){
                        $extensao = 'png';
                }
                header('Content-Disposition: attachment; filename="'.$dados_documento[0] -> ch_sei.'-'.$dados_documento[0] -> in_documento.".{$extensao}\"");
                echo $content;
	}
	public function create(){
                $this -> load -> model('Pastas_model');
                $this -> load -> model('Usuarios_model');

                $pagina['menu1'] = 'Documentos';
                $pagina['menu2'] = 'create';
                $pagina['url'] = 'Documentos/create';
                $pagina['nome_pagina'] = 'Novo documento';
                $pagina['icone'] = 'fa fa-folder-open';

                $dados = $pagina;
                $pasta = $this -> uri -> segment(3);
                $dados_form = $this -> input -> post(null,true);
                if(isset($dados_form['codigo']) && $dados_form['codigo'] > 0){
                        $pasta = $dados_form['codigo'];
                }
                $dados_pasta = $this -> Pastas_model -> get_pastas ($pasta);
                $dados['codigo'] = $pasta;
                $dados += (array) $dados_pasta[0];

                if(!isset($dados_form['num']) || strlen($dados_form['num']) == 0){
                        $dados_form['num'] = 0;
                }
                for($i = 1;$i <= $dados_form['num']; $i++){
                        if(strlen($dados_form["numero{$i}"]) > 0){
                                $dados_form["numero{$i}"] = str_replace('.', '', $dados_form["numero{$i}"]);
                                $dados_form["numero{$i}"] = str_replace('/', '', $dados_form["numero{$i}"]);
                                $this -> form_validation -> set_rules("numero{$i}", "'Nº documento SEI - {$i}'", 'required|min_length[7]');
                        }
                }
                if ($this -> form_validation -> run() == FALSE){
                        $dados['sucesso'] = '';
                        $dados['erro'] = validation_errors();
                }
                else{
                        $dados_usuario = $this -> Usuarios_model -> get_usuarios ($this -> session -> uid);
                        $unidade = $dados_usuario[0] -> in_unidade_sei;

                        $strWSDL = 'http://www.sei.mg.gov.br/sei/controlador_ws.php?servico=sei';
                        $objWS = new SoapClient($strWSDL, array('encoding' => 'ISO-8859-1'));

                        $documentos='';
                        $excecao = false;
                        for($i = 1;$i <= $dados_form['num']; $i++){
                                if(strlen($dados_form["numero{$i}"]) >= 7){
                                        try{
                                                $ret = $objWS -> consultarDocumento($this -> config -> item('SEI_SiglaSistema'), $this -> config -> item('SEI_SiglaSistema'), $unidade, $dados_form["numero{$i}"], 'N', 'N', 'N');
                                                $ret2 = $objWS -> consultarProcedimento($this -> config -> item('SEI_SiglaSistema'), $this -> config -> item('SEI_SiglaSistema'), $unidade, $ret -> ProcedimentoFormatado, 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
                                                //var_dump($ret);
                                                //var_dump($ret2);

                                                //atualiza o nome do tipo do processo
                                                $this -> Documentos_model -> update_tipo_processo ('vc_tipo_processo', utf8_encode($ret2 -> TipoProcedimento -> Nome), $ret2 -> TipoProcedimento -> IdTipoProcedimento);

                                                //verifica se j� existe o processo
                                                $query = $this -> db -> query("SELECT * from tb_processos where ch_sei='".$ret2 -> ProcedimentoFormatado."'");
                                                if($query -> num_rows() > 0){
                                                        $result = $query -> result();
                                                        $processo = $result[0] -> pr_processo;
                                                        $this -> Documentos_model -> update_processo ('es_tipo_processo', $ret2 -> TipoProcedimento -> IdTipoProcedimento, $ret2 -> ProcedimentoFormatado);
                                                        $this -> Documentos_model -> update_processo ('in_codigo_sei', $ret2 -> IdProcedimento, $ret2 -> ProcedimentoFormatado);
                                                        $this -> Documentos_model -> update_processo ('vc_especificacao', utf8_encode($ret2 -> Especificacao), $ret2 -> ProcedimentoFormatado);
                                                        $this -> Documentos_model -> update_processo ('vc_link_processo', $ret2 -> LinkAcesso, $ret2 -> ProcedimentoFormatado);
                                                }
                                                else{
                                                        $processo = $this -> Documentos_model -> create_processo ($ret2);
                                                }
                                                if($processo > 0){
                                                        $query2 = $this -> db -> query("SELECT * from tb_documentos where in_documento=".$ret -> DocumentoFormatado);
                                                        if($query2 -> num_rows() > 0){
                                                                $result2 = $query2 -> result();
                                                                $documento = $result2[0] -> pr_documento;
                                                                $this -> Documentos_model -> update_documento ('in_unidade_sei', $unidade, $documento);
                                                                $this -> Documentos_model -> update_documento ('vc_documento', utf8_encode($ret -> Serie -> Nome), $documento);
                                                                $this -> Documentos_model -> update_documento ('vc_link', $ret -> LinkAcesso, $documento);
                                                                $this -> Documentos_model -> update_documento ('dt_sei', show_sql_date($ret -> Data), $documento);
                                                                $this -> Documentos_model -> update_documento ('bl_ativo', '1', $documento);
                                                                $this -> Documentos_model -> update_documento ('dt_desativacao', null, $documento);
                                                                $this -> Documentos_model -> update_documento ('es_desativador', null, $documento);
                                                                $this -> Documentos_model -> associa_documento_pasta ($documento, $pasta);
                                                                $documentos .= $documento.',';
                                                        }
                                                        else{
                                                                $ch = curl_init();
                                                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                                                curl_setopt($ch, CURLOPT_HEADER, false);
                                                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                                                curl_setopt($ch, CURLOPT_URL, $ret2 -> LinkAcesso);
                                                                curl_setopt($ch, CURLOPT_REFERER, $ret2 -> LinkAcesso);
                                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                                $content = curl_exec($ch);
                                                                $mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                                                                curl_close($ch);

                                                                $documento = $this -> Documentos_model -> create_documento ($ret, $processo, $unidade, $mime);
                                                                $this -> Documentos_model -> associa_documento_pasta ($documento, $pasta);

                                                                if($documento > 0){
                                                                        $documentos .= $documento.',';
                                                                }
                                                                else{
                                                                        $erro = $this -> db -> error();
                                                                        $this -> Usuarios_model -> log('erro', 'Documentos/create', 'Erro de criação do documento. Erro: '.$erro['message']);
                                                                }
                                                        }
                                                }
                                                else{
                                                        $erro = $this -> db -> error();
                                                        //$dados['sucesso'] = '';
                                                        //$dados['erro'] =  'Erro no cadastro do processo. Os responsáveis já foram avisados.<br/><br/><a href="'.base_url('Documentos/index').'" class="btn btn-light">Voltar</a>';
                                                        $this -> Usuarios_model -> log('erro', 'Documentos/create', 'Erro de criação do processo Tipo de processo: '.$ret2 -> TipoProcedimento -> IdTipoProcedimento.'. Erro: '.$erro['message']);
                                                }
                                        }
                                        catch(Exception $e){
                                                $excecao = true;
                                                $dados['sucesso'] = '';
                                                $dados['erro'] =  'Erro na obtenção de dados do SEI: '.utf8_encode($e->getMessage());
                                                $this -> Usuarios_model -> log('advertencia', 'Documentos/create', 'Erro na obtenção de dados do SEI: '.utf8_encode($e->getMessage()));
                                        }
                                }
                                else{
                                        $dados['sucesso'] = '';
                                        $dados['erro'] =  "O campo 'N� documento SEI - {$i}' deve ter no m�nimo 7 caracteres";
                                        $this -> Usuarios_model -> log('erro', 'Documentos/create', 'Erro de criação de documento. Erro: '.$dados['erro'].'-'.$dados_form["numero{$i}"]);
                                }
                        }
                        if(!$excecao){
                                if(strlen($documentos) > 0){
                                        $dados['sucesso'] = 'Documentos cadastrados com sucesso.<br/><br/><a href="'.base_url('Documentos/index/'.$pasta).'" class="btn btn-light">Voltar</a>';
                                        $dados['erro'] =  NULL;
                                        $this -> Usuarios_model -> log('sucesso', 'Documentos/create', "Documentos {$documentos} cadastrados/editados com sucesso no processo {$processo} pelo usuário ".$this -> session -> uid, 'tb_documentos', $documento);
                                }
                                else{
                                        $erro = $this -> db -> error();
                                        $dados['sucesso'] = '';
                                        $dados['erro'] =  'Erro no cadastro dos documentos. Os responsáveis já foram avisados.<br/><br/><a href="'.base_url('Documentos/index').'" class="btn btn-light">Voltar</a>';
                                        $this -> Usuarios_model -> log('erro', 'Documentos/create', 'Erro de criação de documento. Erro: '.$erro['message']);
                                }
                        }
                        $dados['tipos'] = $this -> Documentos_model -> get_tipos_processos('');
                }

                $this -> load -> view('documentos', $dados);
        }
	public function edit(){
                $this -> load -> model('Instituicoes_model');
                $this -> load -> model('Usuarios_model');

                $pagina['menu1'] = 'Documentos';
                $pagina['menu2'] = 'edit';
                $pagina['url'] = 'Documentos/edit';
                $pagina['nome_pagina'] = 'Editar tipo de processo';
                $pagina['icone'] = 'fa fa-folder-open';

                $dados = $pagina;
                $dados_form = $this -> input -> post(null,true);
                if($dados_form['processo'] > 0 && $dados_form['pasta'] > 0 && $dados_form['tipo'] > 0){
                        $this -> Documentos_model -> update_processo('es_tipo_processo', $dados_form['tipo'], $dados_form['processo']);

                        $this -> Usuarios_model -> log('sucesso', 'Documentos/edit', "Processo {$dados_form['processo']} editado com sucesso pelo usuário ".$this -> session -> uid, 'tb_processos', $dados_form['processo']);

                        $dados['sucesso'] = 'Processo editado com sucesso.<br/><br/><a href="'.base_url('Documentos/index/'.$dados_form['pasta']).'" class="btn btn-light">Voltar</a>';
                        $dados['erro'] = '';
                }
                $this -> load -> view('documentos', $dados);
        }
	public function admissao(){
                $this -> load -> model('Instituicoes_model');
                $this -> load -> model('Usuarios_model');

                $pagina['menu1'] = 'Documentos';
                $pagina['menu2'] = 'edit';
                $pagina['url'] = 'Documentos/admissao';
                $pagina['nome_pagina'] = 'Mover documento entre pastas';
                $pagina['icone'] = 'fa fa-folder-open';

                $dados = $pagina;
                $dados_form = $this -> input -> post(null,true);
                if($dados_form['documento'] > 0 && $dados_form['pasta'] > 0 && $dados_form['codigo'] > 0){
                        $this -> Documentos_model -> associa_documento_pasta($dados_form['documento'], $dados_form['pasta']);
                        $this -> db -> query("delete from rl_documentos_pastas where es_pasta={$dados_form['codigo']} and es_documento={$dados_form['documento']}");

                        $this -> Usuarios_model -> log('sucesso', 'Documentos/admissao', "Documento {$dados_form['documento']} alterado da pasta {$dados_form['codigo']} para a pasta {$dados_form['pasta']} pelo usuário ".$this -> session -> uid, 'tb_documentos', $dados_form['documento']);

                        $dados['sucesso'] = 'Documento movido com sucesso.<br/><br/><a href="'.base_url('Documentos/index/'.$dados_form['codigo']).'" class="btn btn-light">Voltar</a>';
                        $dados['erro'] = '';
                }
                $this -> load -> view('documentos', $dados);
        }
	public function delete(){
                $this -> load -> model('Usuarios_model');
                $this -> load -> model('Pastas_model');

                $dados_form = $this -> input -> post(null,true);
                $documento = $this -> uri -> segment(3);
                if(isset($dados_form['codigo']) && $dados_form['codigo'] > 0){
                        $documento = $dados_form['codigo'];
                }
                $pasta = $this -> uri -> segment(4);
                if(strlen($pasta) == 0){
                        $pasta = null;
                }

                $pagina['menu1'] = 'Documentos';
                $pagina['menu2'] = 'delete';
                $pagina['url'] = 'Documentos/delete';
                $pagina['nome_pagina'] = 'Desativar documento';
                $pagina['icone'] = 'fa fa-folder-open';
                $dados = $pagina;

                if($this -> session -> perfil != 3 && $this -> session -> perfil != 5){
                        $this -> Usuarios_model -> log('seguranca', 'Documentos/delete', "Tentativa de desativar documento {$documento} pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_documentos', $documento);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                }
                else{
                        $dados_documento = $this -> Documentos_model -> get_documentos ($documento, $pasta);
                        if($this -> session -> perfil == 5 && $dados_documento[0] -> es_instituicao_exercicio != $this -> session -> instituicao && $dados_documento[0] -> pr_instituicao != $this -> session -> instituicao){
                                $this -> Usuarios_model -> log('seguranca', 'Documentos/delete', "Tentativa de desativar documento {$documento} pelo usuário ".$this -> session -> uid.' de outra institui��o de exerc�cio.', 'tb_documentos', $documento);
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Documentos/index/'.$dados_documento[0] -> es_pasta).'" class="btn btn-light">Voltar</a>';
                        }
                        else{
                                $this -> Documentos_model -> exclui_associacao_documento_pasta($documento, $pasta);
                                $this -> Pastas_model -> create_remocao($documento, $pasta);

                                $dados['sucesso'] = "Documento desativado com sucesso.<br/><br/><a href=\"".base_url('Documentos/index/'.$pasta).'" class="btn btn-light">Voltar</a>';
                                $dados['erro'] = '';
                                $this -> Usuarios_model -> log('sucesso', 'Documentos/delete', "Documento {$documento} da Pasta {$pasta} desativado pelo usuário ".$this -> session -> uid, 'tb_documentos', $documento);
                        }
                }
                $this -> load -> view('documentos', $dados);
        }
	public function reactivate(){
                $this -> load -> model('Usuarios_model');

                $dados_form = $this -> input -> post(null,true);
                $documento = $this -> uri -> segment(3);
                if(isset($dados_form['codigo']) && $dados_form['codigo'] > 0){
                        $documento = $dados_form['codigo'];
                }
                $pagina['menu1'] = 'Documentos';
                $pagina['menu2'] = 'reactivate';
                $pagina['url'] = 'Documentos/reactivate';
                $pagina['nome_pagina'] = 'Reativar documento';
                $pagina['icone'] = 'fa fa-folder-open';
                $dados = $pagina;

                if($this -> session -> perfil != 3 && $this -> session -> perfil != 5){
                        $this -> Usuarios_model -> log('seguranca', 'Documentos/reactivate', "Tentativa de reativar documento {$documento} pelo usuário ".$this -> session -> uid.' que não tem o perfil adequado.', 'tb_documentos', $documento);
                        $dados['sucesso'] = '';
                        $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Pastas/index').'" class="btn btn-light">Voltar</a>';
                }
                else{

                        $dados_documento = $this -> Documentos_model -> get_documentos ($documento);
                        if($dados_documento[0] -> es_instituicao_exercicio != $this -> session -> instituicao && $dados_documento[0] -> pr_instituicao != $this -> session -> instituicao){
                                $this -> Usuarios_model -> log('seguranca', 'Documentos/reactivate', "Tentativa de reativar documento {$documento} pelo usuário ".$this -> session -> uid.' de outra institui��o de exerc�cio.', 'tb_documentos', $documento);
                                $dados['sucesso'] = '';
                                $dados['erro'] = 'Você não tem acesso a esta funcionalidade. Esta tentativa foi registrada para fins de auditoria.<br/><br/><a href="'.base_url('Documentos/index'.$dados_documento[0] -> es_pasta).'" class="btn btn-light">Voltar</a>';
                        }
                        else{
                                $this -> Documentos_model -> update_documento('bl_ativo', '1', $documento);
                                $dados['sucesso'] = "Pasta reativada com sucesso.<br/><br/><a href=\"".base_url('Documentos/index'.$dados_documento[0] -> es_pasta).'" class="btn btn-light">Voltar</a>';
                                $dados['erro'] = '';
                                $this -> Usuarios_model -> log('sucesso', 'Documentos/reactivate', "Pasta {$documento} reativada pelo usuário ".$this -> session -> uid, 'tb_documentos', $documento);
                        }
                }
                $this -> load -> view('documentos', $dados);
        }
        public function fetch_tipos_processos(){ //fun��o de preenchimento da combo da view de listagem de documentos
                if($this -> input -> post ('processo') > 0 && $this -> input -> post ('pasta') > 0){
                        $processo = $this -> Documentos_model -> get_processos($this -> input -> post ('processo'));
                        $tipos = $this -> Documentos_model -> get_tipos_processos ('');
                        echo "
                                                                                            <input type=\"hidden\" name=\"processo\" value=\"".$processo[0] -> ch_sei."\" />
                                                                                            <input type=\"hidden\" name=\"pasta\" value=\"".$this -> input -> post ('pasta')."\" />
                                                                                            <h5>Processo: ".$processo[0] -> ch_sei."</h5><br/><br/>
                                                                                            <div class=\"form-group row validated\">";
                        $attributes = array('class' => 'col-lg-2 col-form-label');
                        echo form_label("Tipo <abbr title=\"Obrigat�rio\">*</abbr>", 'tipo', $attributes);
                        echo "
                                                                                                    <div class=\"col-lg-9\">";
                        foreach ($tipos as $linha){
                                $dados_tipos[$linha -> pr_tipo_processo] = $linha -> vc_tipo_processo;
                        }
                        echo form_dropdown('tipo', $dados_tipos, $processo[0] -> pr_tipo_processo, "class=\"form-control\"");
                        echo "
                                                                                                    </div>
                                                                                            </div>";
                }
        }

        public function associar_pastas(){
                $this -> load -> model('Pastas_model');

                $pagina['menu1'] = 'Documentos';
                $pagina['menu2'] = 'associar_pastas';
                $pagina['url'] = 'Documentos/associar_pastas';
                $pagina['nome_pagina'] = 'Associar documentos a v�rias pastas';
                $pagina['icone'] = 'fa fa-folder-open';

                $dados = $pagina;
                $dados['adicionais'] = array('inputmasks' => true);

                $dados['erro'] = '';
                $dados['sucesso'] = '';

                $dados_form = $this -> input -> post(null, true);
                $documento = $this -> uri -> segment(3);
                if(isset($dados_form['codigo']) && $dados_form['codigo'] > 0){
                        $documento = $dados_form['codigo'];
                }
                $dados['codigo'] = $documento;

                if(isset($dados_form['num'])){
                        for($i = 1;$i <= $dados_form['num']; $i++){
                                if(strlen($dados_form["masp{$i}"]) > 0 && strlen($dados_form["admissao{$i}"]) > 0){
                                        $dados_form["masp{$i}"] = str_replace('.', '', $dados_form["masp{$i}"]);
                                        $dados_form["masp{$i}"] = str_replace('/', '', $dados_form["masp{$i}"]);
                                        $this -> form_validation -> set_rules("masp{$i}", "'MASP Pasta - {$i}'", 'required');

                                        $dados_form["admissao{$i}"] = str_replace('.', '', $dados_form["admissao{$i}"]);
                                        $dados_form["admissao{$i}"] = str_replace('/', '', $dados_form["admissao{$i}"]);
                                        $this -> form_validation -> set_rules("admissao{$i}", "'Admiss�o Pasta - {$i}'", 'required');
                                }
                                else{
                                        $this -> form_validation -> set_rules("masp{$i}", "'MASP Pasta - {$i}'", 'trim');
                                        $this -> form_validation -> set_rules("admissao{$i}", "'Admiss�o Pasta - {$i}'", 'trim');

                                }
                        }

                }
                if ($this -> form_validation -> run() == FALSE){
                        $dados['sucesso'] = '';
                        $dados['erro'] = validation_errors();
                }
                else{
                        if(isset($dados_form['num'])){
                                for($i=1;$i <= $dados_form['num']; $i++){
                                        $pastas = $this -> Pastas_model -> get_pastas('', '', false, true, false, false, '', $dados_form["masp{$i}"], $dados_form["admissao{$i}"]);
                                        $codigo_pasta = $pastas[0]->pr_pasta;

                                        $this -> Documentos_model -> associa_documento_pasta($documento, $codigo_pasta);
                                }
                                $dados['sucesso'] = 'Pastas associadas com sucesso nesse documento';
                                $dados['erro'] = '';
                        }
                }
                $pastas = $this -> Documentos_model -> get_documento_pasta($documento);
                $i = 1;
                if(isset($pastas)){
                        foreach($pastas as $pasta){
                                $dados['masp'][$i] = $pasta->in_masp;
                                $dados['admissao'][$i] = $pasta->in_admissao;
                                ++$i;
                        }
                }
                if($i == 1){
                        $dados['num'] = $i;
                }
                else{
                        $dados['num'] = $i - 1;
                }
                $this -> load -> view('documentos', $dados);
        }
}
