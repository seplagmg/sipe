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
                                                                        <h4><i class=\"$icone\" style=\"color:black\"></i> &nbsp; {$nome_pagina}</h4>
                                                                    </div>";
if($menu2 == 'index'){
        echo "
                                                                    <div class=\"col-lg-4 text-right\">
                                                                        <a href=\"".base_url('Usuarios/create')."\" class=\"btn btn-primary btn-square\"> <i class=\"fa fa-plus-circle\"></i> Novo usuário </a>
                                                                    </div>";
}
else if($menu2 == 'create' || $menu2 == 'edit'){
        echo "
                                                                    <div class=\"col-lg-4 text-right\">
                                                                        <button type=\"button\" class=\"btn btn-primary\" onclick=\"document.getElementById('form_usuarios').submit();\"> Salvar </button>
                                                                        <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"window.location='".base_url('Usuarios/index')."'\">Cancelar</button>
                                                                    </div>";
}
echo "
                                                                </div>";
if($menu2 == 'index'){
        echo "
                                                                <div class=\"dt-responsive table-responsive\">
                                                                    <table id=\"usuarios_table\" class=\"table table-striped table-bordered table-hover nowrap\">
                                                                        <thead>
                                                                                <tr>
                                                                                    <th>Nome</th>
                                                                                    <th>Instituição</th>
                                                                                    <th>Perfil</th>
                                                                                    <th>Cadastro</th>
                                                                                    <th>Último acesso</th>
                                                                                    <th width=\"100\">Ações</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>";
        //var_dump($usuarios);
        if(isset($usuarios)){
                foreach ($usuarios as $linha){
                        $dt_cadastro = strtotime($linha -> dt_cadastro);
                        $dt_ultimoacesso = strtotime($linha -> dt_ultimoacesso);
                        echo "
                                                                                <tr>
                                                                                    <td>".$linha -> vc_nome."</td>
                                                                                    <td>".$linha -> vc_instituicao."</td>
                                                                                    <td class=\"text-center\">".$linha -> vc_perfil."</td>
                                                                                    <td class=\"text-center\" data-search=\"".show_date($linha -> dt_cadastro)."\" data-order=\"$dt_cadastro\">".show_date($linha -> dt_cadastro).'</td>';
                        if($linha -> in_erros > 3){
                                echo "
                                                                                    <td class=\"text-center alert-danger\">Bloqueado</td>";
                        }
                        else{
                                echo "
                                                                                    <td class=\"text-center\" data-search=\"".show_date($linha -> dt_ultimoacesso)."\" data-order=\"$dt_ultimoacesso\">".show_date($linha -> dt_ultimoacesso).'</td>';
                        }
                        echo "
                                                                                    <td class=\"text-center\">";
                        if($linha -> bl_removido == '0'){
                                if($linha -> pr_usuario != $this -> session -> uid){
                                        echo "<a href=\"javascript:/\" class=\"btn btn-sm btn-square btn-warning\" title=\"Nova senha\" onclick=\"confirm_senha(".$linha -> pr_usuario.");\"><i class=\"fa fa-lg mr-0 fa-envelope\"></i></a>";
                                        echo anchor('Usuarios/edit/'.$linha -> pr_usuario, '<i class="fa fa-lg mr-0 fa-edit"></i>', " class=\"btn btn-sm btn-square btn-warning\" title=\"Editar\"");
                                        //echo anchor('#', '<i class="fa fa-lg mr-0 fa-envelope"></i>', " class=\"btn btn-sm btn-clean btn-icon btn-icon-md\" title=\"Nova senha\" onclick=\"confirma_senha(".$linha -> pr_usuario.");\"");
                                        //echo anchor('Usuarios/delete/'.$linha -> pr_usuario, '<i class="fa fa-lg mr-0 fa-times-circle"></i>', " class=\"btn btn-sm btn-clean btn-icon btn-icon-md\" title=\"Excluir\"");
                                        echo "<a href=\"javascript:/\" class=\"btn btn-sm btn-square btn-danger\" title=\"Desativar usuário\" onclick=\"confirm_delete(".$linha -> pr_usuario.");\"><i class=\"fa fa-lg mr-0 fa-times-circle\"></i></a>";
                                }

                        }
                        else{
                                if($linha -> pr_usuario != $this -> session -> uid){
                                        echo "<a href=\"javascript:/\" class=\"btn btn-sm btn-success btn-icon btn-icon-md\" title=\"Reativar usuário\" onclick=\"confirm_reactivate(".$linha -> pr_usuario.");\"><i class=\"fa fa-lg mr-0 fa-plus-circle\"></i></a>";
                                }
                        }
                        echo "</td>
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
            function confirm_senha(id){
                $(document).ready(function(){
                    swal.fire({
                        title: 'Você confirma o envio de nova senha?',
                        text: 'Será enviada uma nova senha para o e-mail do usuario.',
                        type: 'info',
                        showCancelButton: true,
                        cancelButtonText: 'Não, cancele',
                        confirmButtonText: 'Sim, envie'
                    })
                    .then(function(result) {
                        if (result.value) {
                            $(location).attr('href', '".base_url('Usuarios/novaSenha/')."' + id)
                        }
                    });
                });
            }
            function confirm_delete(id){
                $(document).ready(function(){
                    swal.fire({
                        title: 'Você confirma essa desativação?',
                        text: 'O usuário perderá o acesso e sua senha ficará como inativa.',
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Não, cancele',
                        confirmButtonText: 'Sim, desative'
                    })
                    .then(function(result) {
                        if (result.value) {
                            $(location).attr('href', '".base_url('Usuarios/delete/')."' + id)
                        }
                    });
                });
            }
            function confirm_reactivate(id){
                $(document).ready(function(){
                    swal.fire({
                        title: 'Você confirma essa reativação?',
                        text: 'O usuário voltará a ter acesso e receberá um e-mail com nova senha.',
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Não, cancele',
                        confirmButtonText: 'Sim, reative'
                    })
                    .then(function(result) {
                        if (result.value) {
                            $(location).attr('href', '".base_url('Usuarios/reactivate/')."' + id)
                        }
                    });
                });
            }
        </script>
        <script type=\"text/javascript\">
            $('#usuarios_table').DataTable({
                'pageLength': 15,
                'lengthMenu': [
                    [ 15, 25, 50, -1 ],
                    [ '15', '25', '50', 'Todos' ]
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
else if($menu2 == 'create' || $menu2 == 'edit'){
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
                $attributes = array('id' => 'form_usuarios');
                if($menu2 == 'edit' && isset($codigo) && $codigo > 0){
                        echo form_open($url, $attributes, array('codigo' => $codigo));
                }
                else{
                        echo form_open($url, $attributes);
                }
                echo "
                                                                                    <div class=\"form-group row\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                echo form_label('Nome completo <abbr title="Obrigatório">*</abbr>', 'NomeCompleto', $attributes);
                echo "
                                                                                            <div class=\"col-lg-6\">";
                if(!isset($vc_nome) || (strlen($vc_nome) == 0 && strlen(set_value('NomeCompleto')) > 0)){
                        $vc_nome = set_value('NomeCompleto');
                }
                $attributes = array('name' => 'NomeCompleto',
                                    'maxlength'=>'250',
                                    'class' => 'form-control');
                if(strstr($erro, "'Nome completo'")){
                        $attributes['class'] = 'form-control is-invalid';
                }
                echo form_input($attributes, $vc_nome);
                echo "
                                                                                            </div>
                                                                                    </div>
                                                                                    <div class=\"form-group row\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                echo form_label('CPF <abbr title="Obrigatório">*</abbr>', 'cpf', $attributes);
                echo "
                                                                                                    <div class=\"col-lg-2\">";
                if(!isset($ch_cpf) || (strlen($ch_cpf) == 0 && strlen(set_value('cpf')) > 0)){
                        $ch_cpf = set_value('cpf');
                }
                $attributes = array('name' => 'cpf',
                                    'id'=>'cpf',
                                    'maxlength'=>'14',
                                    'class' => 'form-control');
                if($menu2 == 'edit'){
                        $attributes['disabled'] = 'disabled';
                }
                if(strstr($erro, "'CPF'")){
                        $attributes['class'] = 'form-control is-invalid';
                }
                echo form_input($attributes, $ch_cpf);
                echo "
                                                                                            </div>
                                                                                    </div>
                                                                                    <div class=\"form-group row\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                echo form_label('E-mail <abbr title="Obrigatório">*</abbr>', 'Email', $attributes);
                echo "
                                                                                                    <div class=\"col-lg-6\">";
                if(!isset($vc_email) || (strlen($vc_email) == 0 && strlen(set_value('Email')) > 0)){
                        $vc_email = set_value('Email');
                }
                $attributes = array('name' => 'Email',
                                    'maxlength'=>'250',
                                    'class' => 'form-control');
                if(strstr($erro, "'E-mail'")){
                        $attributes['class'] = 'form-control is-invalid';
                }
                echo form_input($attributes, $vc_email);
                echo "
                                                                                            </div>
                                                                                    </div>
                                                                                    <div class=\"form-group row\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                echo form_label('Instituição <abbr title="Obrigatório">*</abbr>', 'instituicao', $attributes);
                echo "
                                                                                            <div class=\"col-lg-3\">";
                if(!isset($es_instituicao) || (strlen($es_instituicao) == 0 && strlen(set_value('instituicao')) > 0)){
                        $es_instituicao = set_value('perfil');
                }
                $instituicoes=array('' => '')+$instituicoes;

                if(strstr($erro, "'Instituição'")){
                        echo form_dropdown('instituicao', $instituicoes, $es_instituicao, "class=\"form-control is-invalid\" id=\"filtro_instituicao\"");
                }
                else{
                        echo form_dropdown('instituicao', $instituicoes, $es_instituicao, "class=\"form-control\" id=\"filtro_instituicao\"");
                }
                echo "
                                                                                            </div>
                                                                                    </div>
                                                                                    <div class=\"form-group row\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                echo form_label('Perfil <abbr title="Obrigatório">*</abbr>', 'perfil', $attributes);
                echo "
                                                                                            <div class=\"col-lg-3\">";
                if(!isset($es_perfil) || (strlen($es_perfil) == 0 && strlen(set_value('perfil')) > 0)){
                        $es_perfil = set_value('perfil');
                }
                $dados_perfis[''] = '';
                foreach ($perfis as $linha){
                        $dados_perfis[$linha -> pr_perfil] = $linha -> vc_perfil;
                }
                if(strstr($erro, "'Perfil'")){
                        echo form_dropdown('perfil', $dados_perfis, $es_perfil, "class=\"form-control is-invalid\"");
                }
                else{
                        echo form_dropdown('perfil', $dados_perfis, $es_perfil, "class=\"form-control\"");
                }
                echo "
                                                                                            </div>
                                                                                    </div>
                                                                                    <div class=\"form-group row\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                echo form_label('Unidade no SEI <abbr title="Obrigatório">*</abbr>', 'unidade', $attributes);
                echo "
                                                                                                    <div class=\"col-lg-2\">";
                if(!isset($in_unidade_sei) || (strlen($in_unidade_sei) == 0 && strlen(set_value('unidade')) > 0)){
                        $in_unidade_sei = set_value('unidade');
                }
                $attributes = array('name' => 'unidade',
                                    'maxlength'=>'10',
                                    'class' => 'form-control',
                                    'type' => 'number',
                                    'onkeypress' => 'return numbersonly(this, event)');
                if(strstr($erro, "'Unidade no SEI'")){
                        $attributes['class'] = 'form-control is-invalid';
                }
                echo form_input($attributes, $in_unidade_sei);
                echo "
                                                                                    </div>
                                                                            </div>

                                                                            <div class=\"j-footer\">
                                                                                    <div class=\"row\">
                                                                                            <div class=\"col-lg-12 text-center\">";
                $attributes = array('class' => 'btn btn-primary');
                echo form_submit('salvar_usuario', 'Salvar', $attributes);
                echo "
                                                                                                    <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"window.location='".base_url('Usuarios/index')."'\">Cancelar</button>
                                                                                            </div>
                                                                                    </div>
                                                                            </div>
                                                                    </form>
                                                            </div>";
                $pagina['js']="
                <script type=\"text/javascript\">
                    $(document).ready(function(){
                            $('#cpf').inputmask('999.999.999-99');
                    });
                </script>";
        }
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
                                                    </div>";
$this -> load -> view('internaRodape', $pagina);
?>