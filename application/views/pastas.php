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
if($menu2 == 'view'){
        echo " - Pasta de {$vc_nome} (". exibir_MASP($in_masp)." / $in_admissao)";
}
echo "</h4>
                                                                    </div>";
if($menu2 == 'index'){
        echo "
                                                                    <div class=\"col-lg-4 text-right\">";
        if($this -> session -> perfil == 2 || $this -> session -> perfil == 3){ //administrador ou visualizador geral
                if($tipo == 'normal'){
						echo "
                                                                            <a href=\"".base_url('Pastas/search')."\" class=\"btn btn-danger btn-square\"> <i class=\"fa fa-lg mr-0 fa-eye\"></i> Todas as pastas </a>";
				}
				else{
						echo "
                                                                            <a href=\"".base_url('Pastas/search/externo')."\" class=\"btn btn-danger btn-square\"> <i class=\"fa fa-lg mr-0 fa-eye\"></i> Todas as pastas </a>";
				}
        }
        if($this -> session -> perfil == 3 || $this -> session -> perfil == 5){ //administrador ou RH Arquivo
                if($tipo == 'normal'){
						echo "
                                                                            <a href=\"".base_url('Pastas/create')."\" class=\"btn btn-primary btn-square\"> <i class=\"fa fa-lg mr-0 fa-plus-circle\"></i> Nova pasta funcional </a>";
				}
				else{
						echo "
                                                                            <a href=\"".base_url('Pastas/create/externo')."\" class=\"btn btn-primary btn-square\"> <i class=\"fa fa-lg mr-0 fa-plus-circle\"></i> Nova pasta funcional </a>";
				}
        }
        echo "
                                                                    </div>";
}


if(($this -> session -> perfil == 3 || $this -> session -> perfil == 5) && $menu2 != 'index' && $menu2 != 'view' && strlen($sucesso) == 0){
        echo "
                                                                    <div class=\"col-lg-4 text-right\">
                                                                            <button type=\"button\" class=\"btn btn-primary\" onclick=\"document.getElementById('form_pastas').submit();\"> ";
        if($menu2 == 'create' || $menu2 == 'edit' || $menu2 == 'transform'){
                echo 'Salvar';
        }
        else if($menu2 == 'search'){
                echo 'Pesquisar';
        }



        echo " </button>
                                                                            <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"window.location='".base_url('Pastas/index')."'\">Cancelar</button>";
        if($this -> session -> perfil == 3 || $this -> session -> perfil == 5){ //administrador ou RH Arquivo
                echo "

                                                                            <a href=\"".base_url('Pastas/create')."\" class=\"btn btn-primary btn-square\"> <i class=\"fa fa-lg mr-0 fa-plus-circle\"></i> Nova pasta funcional </a>

                        ";
        }
        echo "
                                                                    </div>";
}
echo "
                                                            </div>";

if($menu2 == 'index'){
        echo "
                                                            ";
        $attributes = array('id' => 'form_pastas');
		if(!isset($tipo)||$tipo=="normal"){
				echo form_open($url, $attributes, array('enviado' => '1'));
		}
		else{
				echo form_open($url."/".$tipo, $attributes, array('enviado' => '1'));
		}
        echo "
                                                                    <input type=\"checkbox\" name=\"desativadas\"";
        if(isset($desativadas) && $desativadas == 'on'){
                echo ' checked="checked"';
        }
        echo " onclick=\"this.form.submit()\" /> <i class=\"fa fa-lg mr-0 fa-align-justify\" style=\"color: #7A708B40\"></i> Visualizar pastas desativadas<br/>";
        if($tipo == 'normal'){
				if($this -> session -> perfil == 1 || $this -> session -> perfil == 2 || $this -> session -> perfil == 3 || $this -> session -> perfil == 5){ //administrador ou RH Arquivo ou Geral visualizador
						echo "
																			<input type=\"checkbox\" name=\"outroexercicio\"";
						if(!isset($outroexercicio) || $outroexercicio == 'on'){
								echo ' checked="checked"';
						}
						echo " onclick=\"this.form.submit()\" /> <i class=\"fa fa-lg mr-0 fa-align-justify\" style=\"color: #ffc10780\"></i> Visualizar servidores em exercício em outra instituição<br/><br/>";
				}
		}
		else{
				if($this -> session -> perfil == 1 || $this -> session -> perfil == 2 || $this -> session -> perfil == 3 || $this -> session -> perfil == 5){ //administrador ou RH Arquivo ou Geral visualizador
						echo "
																			<input type=\"checkbox\" name=\"outroexercicio\"";
						if(!isset($outroexercicio) || $outroexercicio == 'on'){
								echo ' checked="checked"';
						}
						echo " onclick=\"this.form.submit()\" /> <i class=\"fa fa-lg mr-0 fa-align-justify\" style=\"color: #ffc10780\"></i> Visualizar pastas em exercício em outros órgãos<br/><br/>";
				}
		}
		if($tipo == "normal"){
				echo "
                                                            </form>
                                                            <div class=\"dt-responsive table-responsive\">
                                                                    <table class=\"table compact table-bordered table-hover\" id=\"pastas_table\">
                                                                            <thead>
                                                                                    <tr>
                                                                                            <th>Nome</th>
                                                                                            <th>MASP</th>
                                                                                            <th>Admissão</th>
                                                                                            <th>CPF</th>
                                                                                            <th>Exercício</th>
                                                                                            <th>Lotação</th>
                                                                                            <th>Ações</th>
                                                                                    </tr>
                                                                            </thead>
                                                                            <tbody>";
		}
		else{
				echo "
                                                            </form>
                                                            <div class=\"dt-responsive table-responsive\">
                                                                    <table class=\"table compact table-bordered table-hover\" id=\"pastas_table\">
                                                                            <thead>
                                                                                    <tr>
                                                                                            <th>Nome</th>
                                                                                            <th>CPF</th>
																							<th>Matrícula</th>
																							<th>Tipo</th>
                                                                                            <th>Exercício</th>
                                                                                            <th>Ações</th>
                                                                                    </tr>
                                                                            </thead>
                                                                            <tbody>";
		}
        //var_dump($pastas);
        if(isset($pastas)){
                foreach ($pastas as $linha){
						if($tipo == "normal"){
								if($linha -> es_instituicao_exercicio != $this -> session -> instituicao){
										$cor='bg-warning';
								}
								else if($linha -> bl_ativo == '0'){
										$cor='bg-default';
								}
								else{
										$cor='';
								}
								echo "
                                                                                    <tr>
                                                                                            <td class=\"$cor\">".$linha -> vc_nome."</td>
                                                                                            <td class=\"text-center $cor\" data-search=\"".$linha -> in_masp.' '.exibir_MASP($linha -> in_masp)."\">".exibir_MASP($linha -> in_masp)."</td>
                                                                                            <td class=\"text-center $cor\">".$linha -> in_admissao."</td>
                                                                                            <td class=\"text-center $cor\" data-search=\"".$linha -> ch_cpf.' '.exibir_cpf($linha -> ch_cpf)."\">".exibir_cpf($linha -> ch_cpf)."</td>
                                                                                            <td class=\"text-center $cor\">".$linha -> exercicio."</td>
                                                                                            <td class=\"text-center $cor\">".$linha -> lotacao."</td>
                                                                                            <td class=\"text-center $cor\" style=\"white-space:nowrap\">";
								if($linha -> bl_ativo == '1'){
										echo anchor('Documentos/index/'.$linha -> pr_pasta, '<i class="fa fa-lg mr-0 fa-folder-open"></i>', " class=\"btn btn-sm btn-square btn-info\" title=\"Visualizar pasta\"");
										if($this -> session -> perfil == 1 || $this -> session -> perfil == 3 || $this -> session -> perfil == 5){ //administrador ou RH Arquivo ou RH visualizador
												echo "<a href=\"javscript:/\" class=\"btn btn-sm btn-square btn-info\" title=\"Gerar link da pasta\" onclick=\"create_link(".$linha -> pr_pasta.")\"><i class=\"fa fa-lg mr-0 fa-link\"></i></a>";
										}
										if($this -> session -> perfil == 3 || $this -> session -> perfil == 5){ //administrador ou RH Arquivo
												echo anchor('Documentos/create/'.$linha -> pr_pasta, '<i class="fa fa-lg mr-0 fa-plus-circle"></i>', " class=\"btn btn-sm btn-square btn-primary\" title=\"Novo documento\"");
										}
										if($this -> session -> perfil == 3){ //administrador
												echo anchor('Pastas/view/'.$linha -> pr_pasta, '<i class="fa fa-lg mr-0 fa-eye"></i>', " class=\"btn btn-sm btn-square btn-warning\" title=\"Editar lista de visualizadores\"");
										}
										if($this -> session -> perfil == 3 || ($this -> session -> perfil == 5 && $linha -> es_instituicao_exercicio == $this -> session -> instituicao)){ //administrador ou RH arquivo da instituição de exercício
												echo anchor('Pastas/edit/'.$linha -> pr_pasta, '<i class="fa fa-lg mr-0 fa-edit"></i>', " class=\"btn btn-sm btn-square btn-warning\" title=\"Editar pasta\"");

												echo "<a href=\"javascript:/\" class=\"btn btn-sm btn-square btn-danger\" title=\"Desativar pasta\" onclick=\"confirm_delete(".$linha -> pr_pasta.");\"><i class=\"fa fa-lg mr-0 fa-times-circle\"></i></a>";
										}
								}
								else{
										echo anchor('Documentos/index/'.$linha -> pr_pasta, '<i class="fa fa-lg mr-0 fa-folder-open"></i>', " class=\"btn btn-sm btn-square btn-info\" title=\"Visualizar pasta\"");
										if($this -> session -> perfil == 3 || ($this -> session -> perfil == 5 && $linha -> es_instituicao_exercicio == $this -> session -> instituicao)){ //administrador ou RH arquivo da instituição de exercício
												echo "<a href=\"javascript:/\" class=\"btn btn-sm btn-square btn-danger\" title=\"Reativar pasta\" onclick=\"confirm_reactivate(".$linha -> pr_pasta.");\"><i class=\"fa fa-lg mr-0 fa-recycle\"></i></a>";
										}
								}
						}
						else{
								if($linha -> es_instituicao_exercicio != $this -> session -> instituicao){
										$cor='bg-warning';
								}
								else if($linha -> bl_ativo == '0'){
										$cor='bg-default';
								}
								else{
										$cor='';
								}
								echo "
                                                                                    <tr>
                                                                                            <td class=\"$cor\">".$linha -> vc_nome."</td>

                                                                                            <td class=\"text-center $cor\" data-search=\"".$linha -> ch_cpf.' '.exibir_cpf($linha -> ch_cpf)."\">".exibir_cpf($linha -> ch_cpf)."</td>
																							<td class=\"text-center $cor\">".($linha -> in_masp>0?$linha -> in_masp:"-")."</td>
																							<td class=\"text-center $cor\">".($linha -> en_tipo=='estagiario'?"Estagiário":($linha -> en_tipo == 'serventuario'?"Serventuario":"Externo"))."</td>
                                                                                            <td class=\"text-center $cor\">".$linha -> exercicio."</td>

                                                                                            <td class=\"text-center $cor\" style=\"white-space:nowrap\">";
								if($linha -> bl_ativo == '1'){
										echo anchor('Documentos/index/'.$linha -> pr_pasta, '<i class="fa fa-lg mr-0 fa-folder-open"></i>', " class=\"btn btn-sm btn-square btn-info\" title=\"Visualizar pasta\"");
										if($this -> session -> perfil == 1 || $this -> session -> perfil == 3 || $this -> session -> perfil == 5){ //administrador ou RH Arquivo ou RH visualizador
												echo "<a href=\"javscript:/\" class=\"btn btn-sm btn-square btn-info\" title=\"Gerar link da pasta\" onclick=\"create_link(".$linha -> pr_pasta.")\"><i class=\"fa fa-lg mr-0 fa-link\"></i></a>";
										}
										if($this -> session -> perfil == 3 || $this -> session -> perfil == 5){ //administrador ou RH Arquivo
												echo anchor('Documentos/create/'.$linha -> pr_pasta.'/externo', '<i class="fa fa-lg mr-0 fa-plus-circle"></i>', " class=\"btn btn-sm btn-square btn-primary\" title=\"Novo documento\"");
										}
										if($this -> session -> perfil == 3){ //administrador
												echo anchor('Pastas/view/'.$linha -> pr_pasta.'/externo', '<i class="fa fa-lg mr-0 fa-eye"></i>', " class=\"btn btn-sm btn-square btn-warning\" title=\"Editar lista de visualizadores\"");
										}
										if($this -> session -> perfil == 3 || ($this -> session -> perfil == 5 && $linha -> es_instituicao_exercicio == $this -> session -> instituicao)){ //administrador ou RH arquivo da instituição de exercício
												echo anchor('Pastas/edit/'.$linha -> pr_pasta.'/externo', '<i class="fa fa-lg mr-0 fa-edit"></i>', " class=\"btn btn-sm btn-square btn-warning\" title=\"Editar pasta\"");
                                                                                                if($linha -> en_tipo == 'serventuario'){
                                                                                                        echo anchor('Pastas/transform/'.$linha -> pr_pasta, '<i class="fa fa-lg mr-0 fa-edit"></i>', " class=\"btn btn-sm btn-square btn-danger\" title=\"Transformar em servidor\"");
                                                                                                }
												echo "<a href=\"javascript:/\" class=\"btn btn-sm btn-square btn-danger\" title=\"Desativar pasta\" onclick=\"confirm_delete(".$linha -> pr_pasta.");\"><i class=\"fa fa-lg mr-0 fa-times-circle\"></i></a>";
										}
								}
								else{
										echo anchor('Documentos/index/'.$linha -> pr_pasta.'/externo', '<i class="fa fa-lg mr-0 fa-folder-open"></i>', " class=\"btn btn-sm btn-square btn-info\" title=\"Visualizar pasta\"");
										if($this -> session -> perfil == 3 || ($this -> session -> perfil == 5 && $linha -> es_instituicao_exercicio == $this -> session -> instituicao)){ //administrador ou RH arquivo da instituição de exercício
												echo "<a href=\"javascript:/\" class=\"btn btn-sm btn-square btn-danger\" title=\"Reativar pasta\" onclick=\"confirm_reactivate(".$linha -> pr_pasta.");\"><i class=\"fa fa-lg mr-0 fa-recycle\"></i></a>";
										}
								}
						}
                        echo "
                                                                                            </td>
                                                                                    </tr>";
                }
        }
        echo "
                                                                            </tbody>
                                                                    </table>";

        echo "
                                                            </div>
                                                    </div>";

        $pagina['js'] = "
                                            <script type=\"text/javascript\">
                                                    function confirm_delete(id){
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
																			";
		if($tipo == "normal"){
				$pagina['js'] .= "$(location).attr('href', '".base_url('Pastas/delete/')."' + id)";
		}
		else{
				$pagina['js'] .= "$(location).attr('href', '".base_url('Pastas/delete/')."' + id + '/externo')";
		}

		$pagina['js'] .= "

                                                                        }
                                                                    });
                                                            });
                                                    }
                                                    function confirm_reactivate(id){
                                                            $(document).ready(function(){
                                                                    swal.fire({
                                                                        title: 'Você confirma essa reativação?',
                                                                        text: 'A pasta em questão voltará a ser considerada pelo sistema.',
                                                                        type: 'warning',
                                                                        showCancelButton: true,
                                                                        cancelButtonText: 'Não, cancele',
                                                                        confirmButtonText: 'Sim, reative'
                                                                    })
                                                                    .then(function(result) {
                                                                        if (result.value) {
																			";
		if($tipo == "normal"){
				$pagina['js'] .= "$(location).attr('href', '".base_url('Pastas/reactivate/')."' + id)";
		}
		else{
				$pagina['js'] .= "$(location).attr('href', '".base_url('Pastas/reactivate/')."' + id + '/externo')";
		}
		$pagina['js'] .= "

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
                                            </script>
                                            <script type=\"text/javascript\">
                                                    $('#pastas_table').DataTable({
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
else if($menu2 == 'view'){
        echo "
                                                                    <div class=\"kt-portlet__body\">";
        /*
        if(strlen($erro)>0){
                echo "
                                                                            <div class=\"alert alert-danger\" role=\"alert\">
                                                                                    <div class=\"alert-icon\">
                                                                                            <i class=\"fa fa-exclamation-triangle\"></i>
                                                                                    </div>
                                                                                    <div class=\"alert-text\">
                                                                                            <strong>ERRO</strong>:<br /> $erro
                                                                                    </div>
                                                                            </div>";
        //$erro='';
        }
        else if(strlen($sucesso) > 0){
                echo "
                                                                            <div class=\"alert alert-success\" role=\"alert\">
                                                                                    <div class=\"alert-icon\">
                                                                                            <i class=\"fa fa-check-circle\"></i>
                                                                                    </div>
                                                                                    <div class=\"alert-text\">
                                                                                            $sucesso
                                                                                    </div>
                                                                            </div>";
        }*/
        $attributes = array('class' => 'kt-form', 'id' => 'form_pastas');
        echo form_open($url, $attributes, array('codigo' => $codigo, 'num' => set_value('num')));
        echo "
                                                                            <table class=\"table table-striped table-bordered table-hover\" id=\"kt_table_1\">
                                                                                    <thead>
                                                                                            <tr>
                                                                                                    <th>Instituições visualizadoras</th>
                                                                                                    <th>Ações</th>
                                                                                            </tr>
                                                                                    </thead>
                                                                                    <tbody>";
        //var_dump($visualizadores);
        if(isset($visualizadores)){
                foreach ($visualizadores as $linha){
                        //var_dump($linha);
                        echo "
                                                                                            <tr>
                                                                                                    <td>".$linha -> vc_sigla."</td>
                                                                                                    <td class=\"text-center\" style=\"white-space:nowrap\">";
                        if($linha -> pr_instituicao != $es_instituicao_lotacao && $linha -> pr_instituicao != $es_instituicao_exercicio){
                                echo "<a href=\"javascript:/\" class=\"btn btn-sm btn-square btn-danger\" title=\"Retirar acesso\" onclick=\"confirm_delete(".$linha -> pr_instituicao.", $codigo);\"><i class=\"fa fa-lg mr-0 fa-eye-slash\"></i></a>";
                        }
                        else{
                                if($linha -> pr_instituicao == $es_instituicao_lotacao && $linha -> pr_instituicao == $es_instituicao_exercicio){
                                        echo '<span class="badge badge-warning">Lotação e exercício</span><br/>';
                                }
                                else if($linha -> pr_instituicao == $es_instituicao_lotacao){
                                        echo '<span class="badge badge-primary">Lotação</span><br/>';
                                }
                                else if($linha -> pr_instituicao == $es_instituicao_exercicio){
                                        echo '<span class="badge badge-success">Exercício</span><br/>';
                                }
                        }
                        echo "
                                                                                                    </td>
                                                                                            </tr>";
                }
                echo "
                                                                                            <tr>
                                                                                                    <td><select class=\"form-control\" id=\"novainst\" name=\"novainst\"><option></option>";
                if(isset($instituicoes)){
                        foreach ($instituicoes as $linha){
                                echo "<option value=\"".$linha -> pr_instituicao."\">".$linha -> vc_sigla."</option>";
                        }
                }
                echo "</select></td><td></td></tr>";
        }
        echo "
                                                                                    </tbody>
                                                                            </table>";
        /*
        echo "
                                                                                    <div class=\"kt-portlet__foot\"><div class=\"kt-form__actions\"><div class=\"row\"><div class=\"col-lg-12 text-center\"><button type=\"button\" id=\"adicionar_inst\" class=\"btn btn-outline-primary\"><i class=\"fa fa-lg mr-0 fa-plus\"></i> Adicionar visualizador</button></div></div></div></div>";
        */
        echo form_close();
        echo "
                                                                    </div>
                                                            </div>";
        $pagina['js'] = "
        <script type=\"text/javascript\">";
        /*
        $pagina['js'] .= "
                $( '#adicionar_inst' ).click(function() {
                        var newElement = '<tr><td><select class=\"form-control\" id=\"novainst\" name=\"novainst\"><option></option>";
        if(isset($instituicoes)){
                foreach ($instituicoes as $linha){
                        $pagina['js'] .= "<option value=\"".$linha -> pr_instituicao."\">".$linha -> vc_sigla."</option>";
                }
        }
        $pagina['js'] .= "</select></td><td></td></tr>';
                        $( '#kt_table_1' ).append( $(newElement) );
                });";*/
        $pagina['js'] .= "
                $('#novainst').change(function() {
                    $('#form_pastas').submit();
                });
        </script>";

        $pagina['js'] .= "
                                            <script type=\"text/javascript\">
                                                    function confirm_delete(inst, pasta){
                                                            $(document).ready(function(){
                                                                    swal.fire({
                                                                        title: 'Você confirma essa exclusão?',
                                                                        text: 'O acesso à pasta por esta instituição será excluída.',
                                                                        type: 'warning',
                                                                        showCancelButton: true,
                                                                        cancelButtonText: 'Não, cancele',
                                                                        confirmButtonText: 'Sim, exclua'
                                                                    })
                                                                    .then(function(result) {
                                                                        if (result.value) {
                                                                            $(location).attr('href', '".base_url('Pastas/deleteview/')."' + inst + '/' + pasta)
                                                                        }
                                                                    });
                                                            });
                                                    }
                                            </script>";

        /*
        $pagina['js'] .= "
                                            <script type=\"text/javascript\">
                                                    var KTDatatablesBasicHeaders = function() {
                                                            var initTable1 = function() {
                                                                    var table = $('#kt_table_1');
                                                                    table.DataTable({
                                                                            responsive: true,
                                                                            order: [
                                                                                [0, 'asc']
                                                                            ],
                                                                            columnDefs: [
                                                                                    {
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
                                                                            },
                                                                    });
                                                            };
                                                            return {
                                                                    init: function() {
                                                                            initTable1();
                                                                    },
                                                            };
                                                    }();
                                                    jQuery(document).ready(function() {
                                                            KTDatatablesBasicHeaders.init();
                                                    });
                                            </script>";*/
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

                $attributes = array('class' => 'kt-form',
                                    'id' => 'form_pastas');


                if($menu2 == 'edit' && isset($codigo) && $codigo > 0){
						if($tipo == 'normal'){
								echo form_open($url, $attributes, array('codigo' => $codigo));
						}
						else{
								echo form_open($url, $attributes, array('codigo' => $codigo, 'tipo' => $tipo));
						}
                }
                else{
						if($tipo == 'normal'){
								echo form_open($url, $attributes);
						}
						else{
								echo form_open($url, $attributes,array('tipo'=>$tipo));
						}
                }

                echo "
                                                                            <div class=\"kt-portlet__body\">
                                                                                    <div class=\"form-group row\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                echo form_label('Nome do servidor <abbr title="Obrigatório">*</abbr>', 'nome', $attributes);
                echo "
                                                                                            <div class=\"col-lg-6\">";
                if(!isset($vc_nome) || (strlen($vc_nome) == 0 && strlen(set_value('nome')) > 0)){
                        $vc_nome = set_value('nome');
                }
                $attributes = array('name' => 'nome',
                                    'maxlength'=>'100',
                                    'class' => 'form-control');
                if(strstr($erro, "'Nome do servidor'")){
                        $attributes['class'] = 'form-control is-invalid';
                }
                echo form_input($attributes, $vc_nome);
                echo "
                                                                                            </div>
                                                                                    </div>
                                                                                    <div class=\"form-group row\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
				if($tipo == 'normal'){
						echo form_label('MASP <abbr title="Obrigatório">*</abbr>', 'masp', $attributes);
				}
				else{
						echo form_label('Matrícula', 'masp', $attributes);
				}
                echo "
                                                                                            <div class=\"col-lg-2\">";
                if(!isset($in_masp) || (strlen($in_masp) == 0 && strlen(set_value('masp')) > 0)){
                        $in_masp = set_value('masp');
                }
                $attributes = array('name' => 'masp',
                                    'maxlength'=>'50',
                                    'class' => 'form-control',
                                    'onkeypress' => 'return numbersonly(this, event)');
                if(isset($codigo) && $codigo > 0){
                        $attributes['disabled'] = 'disabled';
                }
				if($tipo == 'normal'){
						if(strstr($erro, "'MASP'")){
								$attributes['class'] = 'form-control is-invalid';
						}
				}

                echo form_input($attributes, $in_masp);
                echo "
                                                                                            </div>
                                                                                    </div>";
				if($tipo == 'normal'){
						echo "
                                                                                    <div class=\"form-group row\">";
						$attributes = array('class' => 'col-lg-3 col-form-label text-right');
						echo form_label('Admissão <abbr title="Obrigatório">*</abbr>', 'admissao', $attributes);
						echo "
																									<div class=\"col-lg-1\">";
						if(!isset($in_admissao) || (strlen($in_admissao) == 0 && strlen(set_value('admissao')) > 0)){
								$in_admissao = set_value('admissao');
						}
						$attributes = array('name' => 'admissao',
                                    'maxlength'=>'1',
                                    'type'=>'number',
                                    'class' => 'form-control',
                                    'onkeypress' => 'return numbersonly(this, event)');
						if(isset($codigo) && $codigo > 0){
								$attributes['disabled'] = 'disabled';
						}
						if(strstr($erro, "'Admissão'")){
								$attributes['class'] = 'form-control is-invalid';
						}
						echo form_input($attributes, $in_admissao);
						echo "
                                                                                            </div>
                                                                                    </div>";
				}
				else{
						echo "
                                                                                    <div class=\"form-group row\">";
						$attributes = array('class' => 'col-lg-3 col-form-label text-right');
						echo form_label('Tipo <abbr title="Obrigatório">*</abbr>', 'tipo_pasta', $attributes);
						echo "
																									<div class=\"col-lg-3\">";
						if(!isset($en_tipo) || (strlen($en_tipo) == 0 && strlen(set_value('tipo_pasta')) > 0)){
								$en_tipo = set_value('en_tipo');
						}

						$tipos_pasta=array('estagiario'=>'Estagiário','externo'=>'Externo','serventuario'=>"Serventuario",'empregado_publico'=>"Empregado Público");
						if(strstr($erro, "'Instituição de exercício'")){
								echo form_dropdown('tipo_pasta', $tipos_pasta, $en_tipo, "class=\"form-control is-invalid\" id=\"tipo_pasta\"");
						}
						else{
								echo form_dropdown('tipo_pasta', $tipos_pasta, $en_tipo, "class=\"form-control\" id=\"tipo_pasta\"");
						}
						echo "
                                                                                            </div>
                                                                                    </div>
                                                                            ";
				}
				echo "
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
                                    'maxlength'=>'50',
                                    'class' => 'form-control',
                                    'onkeypress' => 'return numbersonly(this, event)');
                if(isset($codigo) && $codigo > 0){
                        $attributes['disabled'] = 'disabled';
                }
                if(strstr($erro, "'CPF'")){
                        $attributes['class'] = 'form-control is-invalid';
                }
                echo form_input($attributes, $ch_cpf);
                echo "
                                                                                            </div>
                                                                                    </div>";
				if($tipo == 'normal'){
						echo "
                                                                                    <div class=\"form-group row\">";
						$attributes = array('class' => 'col-lg-3 col-form-label text-right');
						echo form_label('Instituição de lotação <abbr title="Obrigatório">*</abbr>', 'lotacao', $attributes);

						echo "
                                                                                            <div class=\"col-lg-3\">";
                                                $semad_grupo=array(1371, 2101, 2241, 2091);
						if($this -> session -> perfil != 3 && !in_array($this -> session -> instituicao,$semad_grupo)){
								$attributes = array('name' => 'lotacao2',
													'id'=>'lotacao2',
													'maxlength'=>'50',
													'class' => 'form-control',
													'disabled' => 'disabled');


                                                                //$semad_string="1371, 2101, 2241, 2091";
								if(isset($codigo) && $codigo > 0 ){
										$attributes['disabled'] = 'disabled';
								}
								echo form_input($attributes, $instituicoes[$this -> session -> instituicao]);
						}
						else{
								if(!isset($es_instituicao_lotacao) || (strlen($es_instituicao_lotacao) == 0 && strlen(set_value('lotacao')) > 0)){
										$es_instituicao_lotacao = set_value('lotacao');
								}
                                                                if(in_array($this -> session -> instituicao,$semad_grupo) && isset($instituicoes2)){
                                                                        $instituicoes=array('' => '')+$instituicoes2;
                                                                }
                                                                else{
                                                                        $instituicoes=array('' => '')+$instituicoes;
                                                                }


								if(strstr($erro, "'Instituição de lotação'")){
										echo form_dropdown('lotacao', $instituicoes, $es_instituicao_lotacao, "class=\"form-control is-invalid\" id=\"filtro_instituicao\"");
								}
								else{
										echo form_dropdown('lotacao', $instituicoes, $es_instituicao_lotacao, "class=\"form-control\" id=\"filtro_instituicao\"");
								}
						}
						echo "
                                                                                            </div>
                                                                                    </div>";
				}
				echo "
                                                                                    <div class=\"form-group row\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                echo form_label('Instituição de exercício <abbr title="Obrigatório">*</abbr>', 'exercicio', $attributes);
                echo "
                                                                                            <div class=\"col-lg-3\">";
                if(!isset($es_instituicao_exercicio) || (strlen($es_instituicao_exercicio) == 0 && strlen(set_value('exercicio')) > 0)){
                        $es_instituicao_exercicio = set_value('exercicio');
                }
                //$instituicoes=array('' => '')+$instituicoes;

                if(strstr($erro, "'Instituição de exercício'")){
                        echo form_dropdown('exercicio', $instituicoes, $es_instituicao_exercicio, "class=\"form-control is-invalid\" id=\"filtro_instituicao\"");
                }
                else{
                        echo form_dropdown('exercicio', $instituicoes, $es_instituicao_exercicio, "class=\"form-control\" id=\"filtro_instituicao\"");
                }
                echo "
                                                                                            </div>
                                                                                    </div>
                                                                            </div>
                                                                            <div class=\"kt-portlet__foot\">
                                                                                    <div class=\"kt-form__actions\">
                                                                                            <div class=\"row\">
                                                                                                    <div class=\"col-lg-12 text-center\">";
                $attributes = array('class' => 'btn btn-primary');
                echo form_submit('salvar_pasta', 'Salvar', $attributes);
                if($tipo == 'normal'){
                        echo "
                                                                                                            <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"window.location='".base_url('Pastas/index')."'\">Cancelar</button>";
                }
                else{
                        echo "
                                                                                                            <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"window.location='".base_url('Pastas/index/externo')."'\">Cancelar</button>";
                }

                echo "
                                                                                                    </div>
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
else if($menu2 == 'transform'){
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

                $attributes = array('class' => 'kt-form',
                                    'id' => 'form_pastas');



                echo form_open($url, $attributes, array('codigo' => $codigo));


                echo "
                                                                            <div class=\"kt-portlet__body\">

                                                                                    <div class=\"form-group row\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');

                echo form_label('MASP <abbr title="Obrigatório">*</abbr>', 'masp', $attributes);

                echo "
                                                                                            <div class=\"col-lg-2\">";

                $attributes = array('name' => 'masp',
                                    'maxlength'=>'50',
                                    'class' => 'form-control',
                                    'onkeypress' => 'return numbersonly(this, event)');


                if(strstr($erro, "'MASP'")){
                        $attributes['class'] = 'form-control is-invalid';
                }


                echo form_input($attributes,set_value('masp'));
                echo "
                                                                                            </div>
                                                                                    </div>";

                echo "
                                                                                    <div class=\"form-group row\">";
                $attributes = array('class' => 'col-lg-3 col-form-label text-right');
                echo form_label('Admissão <abbr title="Obrigatório">*</abbr>', 'admissao', $attributes);
                echo "
                                                                                                    <div class=\"col-lg-1\">";

                $attributes = array('name' => 'admissao',
                            'maxlength'=>'1',
                            'type'=>'number',
                            'class' => 'form-control',
                            'onkeypress' => 'return numbersonly(this, event)');

                if(strstr($erro, "'Admissão'")){
                        $attributes['class'] = 'form-control is-invalid';
                }
                echo form_input($attributes, set_value('admissao'));
                echo "
                                                                                            </div>
                                                                                    </div>
                                                                            </div>
                                                                            <div class=\"kt-portlet__foot\">
                                                                                    <div class=\"kt-form__actions\">
                                                                                            <div class=\"row\">
                                                                                                    <div class=\"col-lg-12 text-center\">";
                $attributes = array('class' => 'btn btn-primary');
                echo form_submit('salvar_pasta', 'Salvar', $attributes);
                echo "
                                                                                                            <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"window.location='".base_url('Pastas/index/externo')."'\">Cancelar</button>
                                                                                                    </div>
                                                                                            </div>
                                                                                    </div>
                                                                            </div>
                                                                    </form>
                                                            </div>";

        }
}
else if($menu2 == 'search'){


        $attributes = array('class' => 'kt-form',
                            'id' => 'form_pastas');

        echo form_open($url, $attributes);

        echo "
                                                                            <div class=\"kt-portlet__body\">
                                                                                    <div class=\"form-group row\">";
        $attributes = array('class' => 'col-lg-3 col-form-label text-right');
        echo form_label('Nome/MASP/CPF do servidor <abbr title="Obrigatório">*</abbr>', 'nome', $attributes);

        echo "
                                                                                            <div class=\"col-lg-6\">";

        if(!isset($vc_nome) || (strlen($vc_nome) == 0 && strlen(set_value('nome')) > 0)){
                $vc_nome = set_value('nome');
        }
        $attributes = array('name' => 'nome',
                            'id' => 'nome',
                            'maxlength'=>'100',
                            'class' => 'form-control');
        if(strstr($erro, "'Nome/MASP/CPF do servidor'")){
                $attributes['class'] = 'form-control is-invalid';
        }
        echo form_input($attributes, $vc_nome);
        /*
        $pastas=array('' => '')+$pastas;
        echo form_dropdown('nome', $pastas, '', "class=\"form-control\" id=\"nome\"");*/
        echo "
                                                                                            </div>
                                                                                    </div>
                                                                            </div>
                                                                            <div class=\"kt-portlet__foot\">
                                                                                    <div class=\"kt-form__actions\">
                                                                                            <div class=\"row\">
                                                                                                    <div class=\"col-lg-12 text-center\">";
        $attributes = array('class' => 'btn btn-primary');
        echo form_submit('pesquisar', 'Pesquisar', $attributes);
        echo "
                                                                                                            <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"window.location='".base_url('Pastas/index')."'\">Cancelar</button>
                                                                                                    </div>
                                                                                            </div>
                                                                                    </div>
                                                                            </div>
                                                                    </form>
                                                            </div>";
        /*
        $pagina['js']="
                <script type=\"text/javascript\">
                        $(document).ready(function(){
                            $(\"#nome\").select2();
                        });
                </script>";*/

        $pagina['js']="
                <script type=\"text/javascript\">
                    $( function() {
                        var availableTags = [";
        foreach ($pastas as $linha){
                $pagina['js'].='"'.$linha -> vc_nome.' - '.exibir_MASP($linha -> in_masp).' - '.exibir_cpf($linha -> ch_cpf).'",';
        }
        $pagina['js'].="
                        ];
                        $( \"#nome\" ).autocomplete({
                            minLength: 3,
                            source: availableTags
                        });
                      } );
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
                                                                                    <h5 class=\"modal-title\" id=\"exampleModalLabel\">Link de acesso à pasta</h5>
                                                                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Fechar\">
                                                                                    </button>
                                                                            </div>
                                                                            <div class=\"modal-body\" id=\"modal_link_body\"></div>
                                                                            <div class=\"modal-footer\">
                                                                                    <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Fechar</button>
                                                                            </div>
                                                                    </div>
                                                            </div>
                                                    </div>";
$this -> load -> view('internaRodape', $pagina);
?>