<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$pagina['menu1'] = $menu1;
$pagina['menu2'] = $menu2;
$pagina['url'] = $url;
$pagina['nome_pagina'] = $nome_pagina;
$pagina['icone'] = $icone;
if(isset($adicionais)){
        $pagina['adicionais'] = $adicionais;
}

$this -> load -> view('internaCabecalho', $pagina);

echo "
                            <div class=\"col-12\">
                                <div class=\"tsm-inner-content\">
                                    <div class=\"main-body\">
                                        <div class=\"page-wrapper\">
                                            <div class=\"page-body\">
                                                <div class=\"row\">
                                                    <div class=\"col-sm-12\">
                                                        <div class=\"card\">
                                                            <div class=\"card-block\">
                                                                <div class=\"row sub-title\" style=\"letter-spacing:0px\">
                                                                    <div class=\"col-lg-8\">
                                                                        <h4><i class=\"$icone\" style=\"color:black\"></i> &nbsp; {$nome_pagina}";
if(isset($vc_nome) && strlen($vc_nome) > 0){
		if($in_admissao > 0){
				echo " - Pasta de {$vc_nome} (". exibir_MASP($in_masp)." / $in_admissao)";
		}
		else{
				echo " - Pasta de {$vc_nome} ({$in_masp})";
		}
}
echo "</h4>
                                                                    </div>";
if($menu2 == 'index'){
        echo "
                                                                    <div class=\"col-lg-4 text-right\">";
        if($this -> session -> perfil == 3){ //administrador ou RH arquivo da instituição de exercício
                echo "
                                                                        <a href=\"javascript:/\" class=\"btn btn-sm btn-square btn-danger\" title=\"Desativar pasta\" onclick=\"confirm_delete2($codigo);\"><i class=\"fa fa-lg mr-0 fa-times-circle\"></i></a>
                                                                        <a href=\"".base_url('Pastas/edit/'.$codigo)."\" class=\"btn btn-sm btn-warning\" title=\"Editar pasta\"><i class=\"fa fa-lg mr-0 fa-pen\"></i></a>";
        }
        if($this -> session -> perfil == 1 || $this -> session -> perfil == 3 || $this -> session -> perfil == 5){ //administrador ou RH Arquivo ou RH visualizador
                echo "
                                                                        <a href=\"javscript:/\" class=\"btn btn-sm btn-success\" title=\"Gerar link da pasta\" onclick=\"create_link({$codigo})\"><i class=\"fa fa-lg mr-0 fa-link\"></i></a>";
        }
        if($this -> session -> perfil == 3 || $this -> session -> perfil == 5){ //administrador ou RH Arquivo
                echo "
                                                                        <a href=\"".base_url('Documentos/create/'.$codigo)."\" class=\"btn btn-sm btn-info\" title=\"Novo documento\"><i class=\"fa fa-lg mr-0 fa-plus-circle\"></i></a>";
        }
        echo "
                                                                    </div>";
}
else if($menu2 == 'create' || $menu2 == 'edit'){
        echo "
                                                                    <div class=\"col-lg-4 text-right\">
                                                                        <button type=\"button\" class=\"btn btn-primary\" onclick=\"document.getElementById('form_pastas').submit();\"> Salvar </button>
                                                                        <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"window.location='".base_url('Usuarios/index')."'\">Cancelar</button>
                                                                    </div>";
}
echo "
                                                                </div>";
if($menu2 == 'index'){
        echo "
                                                                <div class=\"dt-responsive table-responsive\">
                                                                    <table class=\"table compact table-striped table-bordered table-hover\" id=\"documentos_table\">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Nº doc.</th>
                                                                                <th>Nº proc.</th>
                                                                                <th>Processo</th>
                                                                                <th>Documento</th>
                                                                                <th>Instituição</th>
                                                                                <th>Data SEI</th>
                                                                                <th>Data SIPE</th>
                                                                                <th>Ações</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>";
        //var_dump($documentos);
        if(isset($documentos)){
                foreach ($documentos as $linha){
                        echo "
                                                                            <tr>
                                                                                <td class=\"text-center\">".$linha -> in_documento."</td>
                                                                                <td class=\"text-center\">";
                        if(strlen($linha -> vc_link_processo) > 0){
                                echo anchor($linha -> vc_link_processo, $linha -> ch_sei, " title=\"Detalhes do processo\" target=\"_blank\"");
                        }
                        else{
                                echo $linha -> ch_sei;
                        }
                        echo "</td>
                                                                                <td><a href=\"javascript://\" title=\"Alterar tipo de processo\" onclick=\"alterar_tipo_processo(".$linha -> pr_processo.', '.$codigo.")\" style=\"color:#007bff\">".$linha -> vc_tipo_processo."</a></td>
                                                                                <td>".$linha -> vc_documento."</td>
                                                                                <td class=\"text-center\">".$linha -> vc_sigla."</td>
                                                                                <td class=\"text-center\">".show_date($linha -> dt_sei)."</td>
                                                                                <td class=\"text-center\">".show_date($linha -> dt_cadastro)."</td>
                                                                                <td class=\"text-center\" style=\"white-space:nowrap\">";
                        //<a href=\"javscript:/\" class=\"btn btn-sm btn-success\" title=\"Gerar link da pasta\" onclick=\"create_link({$codigo})\"><i class=\"fa fa-lg mr-0 fa-link\"></i></a>

                        echo anchor($linha -> vc_link, '<i class="fa fa-lg mr-0 fa-eye"></i>', " class=\"btn btn-sm btn-square btn-info\" title=\"Visualizar documento\" target=\"_blank\"");
                        echo anchor('Documentos/download/'.$linha -> pr_documento, '<i class="fa fa-lg mr-0 fa-download"></i>', " class=\"btn btn-sm btn-square btn-info\" title=\"Baixar documento\"");
                        if($in_admissao >0){
                                echo anchor('Documentos/associar_pastas/'.$linha -> pr_documento, '<i class="fa fa-lg mr-0 fa-folder-open"></i>', " class=\"btn btn-sm btn-square btn-info\" title=\"Associar documento a várias pastas\"");
                        }
                        if($this -> session -> perfil == 3 || $this -> session -> perfil == 5 ){ //administrador ou RH Arquivo
                                if(count($pastas)>=1){
                                        echo "<a href=\"javascript://\" class=\"btn btn-sm btn-square btn-warning\" title=\"Mover para pasta de outra admissão\" onclick=\"alterar_admissao(".$linha -> pr_documento.")\"><i class=\"fa fa-lg mr-0 fa-exchange-alt\"></i></a>";
                                }
                                echo "<a href=\"javascript:/\" class=\"btn btn-sm btn-square btn-danger\" title=\"Desativar documento\" onclick=\"confirm_delete(".$linha -> pr_documento.", $codigo);\"><i class=\"fa fa-lg mr-0 fa-times-circle\"></i></a>";
                        }
                        echo "
                                                                                </td>
                                                                            </tr>";
                }
        }
        echo "
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>";

        $pagina['js'] = "
        <script type=\"text/javascript\">
            function confirm_delete(id, pasta){
                $(document).ready(function(){
                    swal.fire({
                        title: 'Você confirma essa desativação?',
                        text: 'O documento em questão será retirado desta pasta e será marcado como desativado se não estiver em outras pastas.',
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Não, cancele',
                        confirmButtonText: 'Sim, desative'
                    })
                    .then(function(result) {
                        if (result.value) {
                            $(location).attr('href', '".base_url('Documentos/delete/')."' + id + '/' + pasta)
                        }
                    });
                });
            }
            function confirm_delete2(id){
                $(document).ready(function(){
                    swal.fire({
                        title: 'Você confirma essa desativação?',
                        text: 'A pasta em questão será marcada como desativada e não aparecerá nas listas.',
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Não, cancele',
                        confirmButtonText: 'Sim, desative'
                    })
                    .then(function(result) {
                        if (result.value) {
                            $(location).attr('href', '".base_url('Pastas/delete/')."' + id)
                        }
                    });
                });
            }
            function confirm_reactivate(id, pasta){
                $(document).ready(function(){
                    swal.fire({
                        title: 'Você confirma essa reativação?',
                        text: 'O documento em questão voltará a ser considerada pelo sistema.',
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Não, cancele',
                        confirmButtonText: 'Sim, reative'
                    })
                    .then(function(result) {
                        if (result.value) {
                            $(location).attr('href', '".base_url('Documentos/reactivate/')."' + id + '/' + pasta)
                        }
                    });
                });
            }
            function create_link(id){
                $(document).ready(function(){
                    $.ajax({
                        url:\"".base_url()."Pastas/link\",
                        method:\"POST\",
                        data:{id},
                        success:function(data){
                            $('#modal_link_body').html(data);
                            $('#modal_link').modal('show');
                        }
                    })
                });
            }
            function alterar_tipo_processo(processo, pasta){
                $(document).ready(function(){
                    $.ajax({
                        url:\"".base_url()."Documentos/fetch_tipos_processos\",
                        method:\"POST\",
                        data:{processo, pasta},
                        success:function(data){
                            $('#modal_tipo_processo_body').html(data);
                            $('#modal_tipo_processo').modal('show');
                        }
                    })
                });
            }
            function alterar_admissao(documento){
                $(document).ready(function(){
                    $('#form_admissao').append('<input type=\"hidden\" name=\"documento\" value=\"' + documento + '\" />');
                    $('#modal_admissao').modal('show');
                });
            }
        </script>
        <script type=\"text/javascript\">
            $('#documentos_table').DataTable({
                'pageLength': 10,
                'lengthMenu': [
                    [ 10, 25, 50, -1 ],
                    [ '10', '25', '50', 'Todos' ]
                ],
                'order': [
                    [0, 'asc']
                ],
                columnDefs: [
                    {  // set default column settings
                        'orderable': false,
                        'targets': [-1]
                    },
                    {
                        'searchable': false,
                        'targets': [-1]
                    }
                ],
                language: {
                    \"decimal\":        \"\",
                    \"emptyTable\":     \"Nenhum item encontrado\",
                    \"info\":           \"Mostrando de  _START_ até _END_ de _TOTAL_ itens\",
                    \"infoEmpty\":      \"Mostrando 0 até 0 de 0 itens\",
                    \"infoFiltered\":   \"(filtrado de _MAX_ itens no total)\",
                    \"infoPostFix\":    \"\",
                    \"thousands\":      \",\",
                    \"lengthMenu\":     \"Mostrar _MENU_\",
                    \"loadingRecords\": \"Carregando...\",
                    \"processing\":     \"Carregando...\",
                    \"search\":         \"Pesquisar:\",
                    \"zeroRecords\":    \"Nenhum item encontrado\",
                    \"paginate\": {
                        \"first\":      \"Primeira\",
                        \"last\":       \"Última\",
                        \"next\":       \"Próxima\",
                        \"previous\":   \"Anterior\"
                    },
                    \"aria\": {
                        \"sortAscending\":  \": clique para ordenar de forma crescente\",
                        \"sortDescending\": \": clique para ordenar de forma decrescente\"
                    }
                }
            });
        </script>";
}
else if($menu2 == 'create'){
        if(strlen($erro)>0){
                echo "
                                                            <div class=\"alert alert-danger background-danger\" role=\"alert\">
                                                                    <div class=\"alert-text\">
                                                                            <strong>ERRO</strong>:<br /> $erro
                                                                    </div>
                                                            </div>";
        //$erro='';
        }
        else if(strlen($sucesso) > 0){
                echo "
                                                            <div class=\"alert alert-success background-success\" role=\"alert\">
                                                                    <div class=\"alert-text\">
                                                                            $sucesso
                                                                    </div>
                                                            </div>";
        }
        if(strlen($sucesso) == 0){
                if(strlen(set_value('num')) == 0){
                        $num = 1;
                }
                else{
                        $num = set_value('num');
                }
                $attributes = array('class' => 'kt-form',
                                    'id' => 'form_pastas');
                echo form_open($url, $attributes, array('codigo' => $codigo, 'num' => $num));
                echo "
                                                                            <div class=\"kt-portlet__body\">
                                                                                    <fieldset id=\"numeros\">
                                                                                            <legend>Documentos</legend>";
                //echo "NomeProcedimento: $NomeProcedimento<br>";
                //echo "IdTipoProcedimento: $IdTipoProcedimento<br>";
                for($i = 1; $i <= $num; $i++){
                        //echo "link{$i}: {$link[$i]}<br>";
                        //echo "nome{$i}: {$nome[$i]}<br><br>";
                        echo "
                                                                                            <div class=\"form-group row validated\">";
                        $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                        echo form_label("Nº documento SEI - {$i} <abbr title=\"Obrigatório\">*</abbr>", 'numero', $attributes);
                        echo "
                                                                                                    <div class=\"col-lg-2\">";
                        $attributes = array('name' => 'numero'.$i,
                                            'maxlength'=>'8',
                                            'class' => 'form-control',
                                            'onkeypress' => 'return numbersonly(this, event)');
                        if(isset($link[$i]) && strlen($link[$i]) > 0){
                                $attributes['disabled'] = 'disabled';
                        }
                        if(strstr($erro, "'Nº documento SEI'")){
                                $attributes['class'] = 'form-control is-invalid';
                        }
                        echo form_input($attributes, set_value('numero'.$i));
                        echo "
                                                                                                    </div>";
                }
                echo "
                                                                                            </div>
                                                                                    </fieldset>";
                if(isset($IdTipoProcedimento) && strlen($IdTipoProcedimento) > 0){
                        echo "
                                                                                    <fieldset id=\"numeros\">
                                                                                            <legend>Tipo de processo</legend>
                                                                                            <div class=\"form-group row validated\">";
                        $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                        echo form_label("Tipo do processo <abbr title=\"Obrigatório\">*</abbr>", 'tipo', $attributes);
                        echo "
                                                                                                    <div class=\"col-lg-4\">";
                        foreach ($tipos as $linha){
                                $dados_tipos[$linha -> pr_tipo_processo] = $linha -> vc_tipo_processo;
                        }
                        if(strstr($erro, "'Tipo'")){
                                echo form_dropdown('tipo', $dados_tipos, $IdTipoProcedimento, "class=\"form-control is-invalid\"");
                        }
                        else{
                                echo form_dropdown('tipo', $dados_tipos, $IdTipoProcedimento, "class=\"form-control\"");
                        }
                        echo "
                                                                                                    </div>
                                                                                            </div>
                                                                                    </fieldset>
                                                                            </div>
                                                                            <div class=\"j-footer\">
                                                                                    <div class=\"kt-form__actions\">
                                                                                            <div class=\"row\">
                                                                                                    <div class=\"col-lg-12 text-center\">";
                        $attributes = array('class' => 'btn btn-primary');
                        echo form_submit('salvar_documentos', 'Salvar', $attributes);
                        echo "
                                                                                                            <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"window.location='".base_url('Documentos/index/'.$codigo)."'\">Cancelar</button>
                                                                                                    </div>
                                                                                            </div>
                                                                                    </div>
                                                                            </div>
                                                                    </form>
                                                            </div>";
                }
                else{
                        echo "
                                                                            </div>
                                                                            <div class=\"j-footer\"><div class=\"row\"><div class=\"col-lg-12 text-center\"><button type=\"button\" id=\"adicionar_documento\" class=\"btn btn-warning\"><i class=\"fa fa-lg mr-0 fa-plus\"></i> Adicionar outro documento deste processo</button></div></div></div><br/>
                                                                            <div class=\"j-footer\">
                                                                                    <div class=\"row\">
                                                                                            <div class=\"col-lg-12 text-center\">";
                        $attributes = array('class' => 'btn btn-primary');
                        //echo form_submit('verificar_documento', 'Verificar tipo de processo', $attributes);
                        echo form_submit('salvar_documentos', 'Salvar', $attributes);
                        echo "
                                                                                                    <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"window.location='".base_url('Documentos/index/'.$codigo)."'\">Cancelar</button>
                                                                                            </div>
                                                                                    </div>
                                                                            </div>
                                                                    </form>
                                                            </div>";
                }
                $pagina['js']="

                <script type=\"text/javascript\">

                    $( '#adicionar_documento' ).click(function() {
                            var valor_num = $('input[name=num]').val();
                            valor_num++;
                            var newElement = '<div class=\"form-group row validated\">";

                            $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                            $pagina['js'] .=  form_label('Nº documento SEI - \' + valor_num + \' <abbr title="Obrigatório">*</abbr>', "numero' + valor_num + '", $attributes);
                            $pagina['js'] .= "<div class=\"col-lg-2\">";
                            $pagina['js'] .= "<input type=\"text\" name=\"numero' + valor_num + '\" value=\"\" id=\"numero' + valor_num + '\" maxlength=\"8\" class=\"form-control\" onkeypress=\"return numbersonly(this, event)\" /></div>";
                            $pagina['js'] .= "';
                            $( '#numeros' ).append( $(newElement) );
                            $('input[name=num]').val(valor_num);
                    });
                </script>";
        }
}
else if($menu2 == 'associar_pastas'){
        if(strlen($erro)>0){
                echo "
                                                            <div class=\"alert alert-danger background-danger\" role=\"alert\">
                                                                    <div class=\"alert-text\">
                                                                            <strong>ERRO</strong>:<br /> $erro
                                                                    </div>
                                                            </div>";
        //$erro='';
        }
        else if(strlen($sucesso) > 0){
                echo "
                                                            <div class=\"alert alert-success background-success\" role=\"alert\">
                                                                    <div class=\"alert-text\">
                                                                            $sucesso
                                                                    </div>
                                                            </div>";
        }

        if(strlen(set_value('num')) > 0){

                $num = set_value('num');
        }
        $attributes = array('class' => 'kt-form',
                            'id' => 'form_pastas');
        echo form_open($url, $attributes, array('codigo' => $codigo, 'num' => $num));
        echo "
                                                                    <div class=\"kt-portlet__body\">
                                                                            <fieldset id=\"numeros\">
                                                                                    <legend>Pastas</legend>";
        //echo "NomeProcedimento: $NomeProcedimento<br>";
        //echo "IdTipoProcedimento: $IdTipoProcedimento<br>";
        for($i = 1; $i <= $num; $i++){
                //echo "link{$i}: {$link[$i]}<br>";
                //echo "nome{$i}: {$nome[$i]}<br><br>";
                echo "
                                                                                    <div class=\"form-group row validated\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                echo form_label("MASP Pasta - {$i} <abbr title=\"Obrigatório\">*</abbr>", 'masp'.$i, $attributes);
                echo "
                                                                                            <div class=\"col-lg-2\">";
                $attributes = array('name' => 'masp'.$i,
                                    'maxlength'=>'8',
                                    'class' => 'form-control',
                                    'onkeypress' => 'return numbersonly(this, event)');
                if(isset($link[$i]) && strlen($link[$i]) > 0){
                        $attributes['disabled'] = 'disabled';
                }
                if(strstr($erro, "'MASP Pasta - {$i}'")){
                        $attributes['class'] = 'form-control is-invalid';
                }

                if(!isset($masp[$i]) || (strlen($masp[$i]) == 0 && strlen(set_value('masp'.$i)) > 0)){
                        $masp[$i] = set_value('masp'.$i);
                }
                echo form_input($attributes, $masp[$i]);

                echo "
                                                                                            </div>
                                                                                    </div>";
                echo "
                                                                                    <div class=\"form-group row validated\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                echo form_label("Admissão Pasta - {$i} <abbr title=\"Obrigatório\">*</abbr>", 'admissao'.$i, $attributes);
                echo "
                                                                                            <div class=\"col-lg-1\">";
                $attributes = array('name' => 'admissao'.$i,
                                    'maxlength'=>'1',
                                    'class' => 'form-control',
                                    'onkeypress' => 'return numbersonly(this, event)');
                if(isset($link[$i]) && strlen($link[$i]) > 0){
                        $attributes['disabled'] = 'disabled';
                }
                if(strstr($erro, "'Admissão Pasta - {$i}'")){
                        $attributes['class'] = 'form-control is-invalid';
                }
                if(!isset($admissao[$i]) || (strlen($admissao[$i]) == 0 && strlen(set_value('admissao'.$i)) > 0)){
                        $admissao[$i] = set_value('admissao'.$i);
                }
                echo form_input($attributes, $admissao[$i]);

                echo "
                                                                                            </div>
                                                                                    </div>";
        }
        echo "


                                                                            </fieldset>";

        echo "
                                                                    </div>
                                                                    <div class=\"j-footer\"><div class=\"row\"><div class=\"col-lg-12 text-center\"><button type=\"button\" id=\"adicionar_documento\" class=\"btn btn-warning\"><i class=\"fa fa-lg mr-0 fa-plus\"></i> Adicionar outra pasta nesse processo</button></div></div></div><br/>
                                                                    <div class=\"j-footer\">
                                                                            <div class=\"row\">
                                                                                    <div class=\"col-lg-12 text-center\">";
        $attributes = array('class' => 'btn btn-primary');
        //echo form_submit('verificar_documento', 'Verificar tipo de processo', $attributes);
        echo form_submit('salvar_documentos', 'Salvar', $attributes);
        echo "
                                                                                            <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"window.location='".base_url('Pastas/index/')."'\">Cancelar</button>
                                                                                    </div>
                                                                            </div>
                                                                    </div>
                                                            </form>
                                                    </div>";

        $pagina['js']="

        <script type=\"text/javascript\">
            function numbersonly(myfield, e, dec)
            {
                    var key;
                    var keychar;

                    if (window.event)
                            key = window.event.keyCode;
                    else if (e)
                            key = e.which;
                    else
                            return true;
                    keychar = String.fromCharCode(key);

                    // control keys
                    if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
                            return true;

                    // numbers
                    else if (((\"0123456789\").indexOf(keychar) > -1))
                            return true;

                    // decimal point jump
                    else if (dec && (keychar == \".\"))
                    {
                            myfield.form.elements[dec].focus();
                            return false;
                    }
                    else
                            return false;

            }
            $( '#adicionar_documento' ).click(function() {
                    var valor_num = $('input[name=num]').val();
                    valor_num++;
                    var newElement = '<div class=\"form-group row validated\">";

                    $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                    $pagina['js'] .=  form_label('MASP Pasta - \' + valor_num + \' <abbr title="Obrigatório">*</abbr>', "masp' + valor_num + '", $attributes);
                    $pagina['js'] .= "<div class=\"col-lg-2\">";
                    $pagina['js'] .= "<input type=\"text\" name=\"masp' + valor_num + '\" value=\"\" id=\"masp' + valor_num + '\" maxlength=\"8\" class=\"form-control\" onkeypress=\"return numbersonly(this, event)\" /></div>";
                    $pagina['js'] .= "';
                    $( '#numeros' ).append( $(newElement) );

                    var newElement2 = '<div class=\"form-group row validated\">";

                    $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                    $pagina['js'] .=  form_label('Admissão Pasta - \' + valor_num + \' <abbr title="Obrigatório">*</abbr>', "admissao' + valor_num + '", $attributes);
                    $pagina['js'] .= "<div class=\"col-lg-1\">";
                    $pagina['js'] .= "<input type=\"text\" name=\"admissao' + valor_num + '\" value=\"\" id=\"admissao' + valor_num + '\" maxlength=\"1\" class=\"form-control\" onkeypress=\"return numbersonly(this, event)\" /></div>";
                    $pagina['js'] .= "';
                    $( '#numeros' ).append( $(newElement2) );

                    $('input[name=num]').val(valor_num);
            });
        </script>";

}
else{
        if(strlen($erro)>0){
                echo "
                                                            <div class=\"alert alert-danger background-danger\" role=\"alert\">
                                                                    <div class=\"alert-text\">
                                                                            <strong>ERRO</strong>:<br /> $erro
                                                                    </div>
                                                            </div>";
        //$erro='';
        }
        else if(strlen($sucesso) > 0){
                echo "
                                                            <div class=\"alert alert-success background-success\" role=\"alert\">
                                                                    <div class=\"alert-text\">
                                                                            $sucesso
                                                                    </div>
                                                            </div>";
        }
}
echo "
                                                            </div>
                                                    </div>
                                                    <div class=\"modal fade\" id=\"modal_link\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                                                            <div class=\"modal-dialog modal-lg\" role=\"document\">
                                                                    <div class=\"modal-content\">
                                                                            <div class=\"modal-header\">
                                                                                    <h5 class=\"modal-title\">Link de acesso à pasta</h5>
                                                                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Fechar\">
                                                                                    </button>
                                                                            </div>
                                                                            <div class=\"modal-body\" id=\"modal_link_body\"></div>
                                                                            <div class=\"modal-footer\">
                                                                                    <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Fechar</button>
                                                                            </div>
                                                                    </div>
                                                            </div>
                                                    </div>
                                                    <div class=\"modal fade\" id=\"modal_tipo_processo\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                                                            <div class=\"modal-dialog modal-lg\" role=\"document\">
                                                                    <div class=\"modal-content\">
                                                                            <div class=\"modal-header\">
                                                                                    <h5 class=\"modal-title\">Alterar tipo do processo</h5>
                                                                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Fechar\"></button>
                                                                            </div>
                                                                            <form method=\"post\" action=\"".base_url('Documentos/edit')."\" id=\"form_tipo_processo\">
                                                                                    <div class=\"modal-body\" id=\"modal_tipo_processo_body\"></div>
                                                                                    <div class=\"modal-footer\">
                                                                                            <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Fechar</button>
                                                                                            <button type=\"submit\" class=\"btn btn-primary\">Alterar</button>
                                                                                    </div>
                                                                            </form>
                                                                    </div>
                                                            </div>
                                                    </div>";
if(isset($codigo) && $codigo > 0){
        echo "
                                                    <div class=\"modal fade\" id=\"modal_admissao\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                                                            <div class=\"modal-dialog\" role=\"document\">
                                                                    <div class=\"modal-content\">
                                                                            <div class=\"modal-header\">
                                                                                    <h5 class=\"modal-title\">Mover documento para pasta de outra admissão</h5>
                                                                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Fechar\"></button>
                                                                            </div>
                                                                            <form method=\"post\" action=\"".base_url('Documentos/admissao')."\" id=\"form_admissao\">
                                                                                    <input type=\"hidden\" name=\"codigo\" value=\"{$codigo}\" />
                                                                                    <div class=\"modal-body\" id=\"modal_admissao_body\">
                                                                                            <div class=\"form-group row validated\">";
        $attributes = array('class' => 'col-lg-3 col-form-label text-right');
        echo form_label("Admissão <abbr title=\"Obrigatório\">*</abbr>", 'pasta', $attributes);
        echo "
                                                                                                            <div class=\"col-lg-3\">";
        foreach ($pastas as $linha){
                $dados_admissao[$linha -> pr_pasta] = $linha -> in_admissao;
        }
        echo form_dropdown('pasta', $dados_admissao, $codigo, "class=\"form-control\"");

        echo "
                                                                                                    </div>
                                                                                            </div>
                                                                                    </div>
                                                                                    <div class=\"modal-footer\">
                                                                                            <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Fechar</button>
                                                                                            <button type=\"submit\" class=\"btn btn-primary\">Alterar</button>
                                                                                    </div>
                                                                            </form>
                                                                    </div>
                                                            </div>
                                                    </div>";
}
$this -> load -> view('internaRodape', $pagina);
?>