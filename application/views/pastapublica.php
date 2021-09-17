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
        echo " - Pasta de {$vc_nome} (". exibir_MASP($in_masp)." - Adm. $in_admissao)";
}
echo "</h4>
                                                                    </div>";

if(strlen($erro) > 0){
        echo "
                                                                </div>
                                                                <div class=\"alert alert-danger background-danger\" role=\"alert\">
                                                                    <div class=\"alert-text\">
                                                                        <strong>ERRO</strong>:<br /> $erro
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>";
//$erro='';
}
else{
        echo "
                                                                        <div class=\"dt-responsive table-responsive\">
                                                                            <table class=\"table table-striped table-bordered table-hover\" id=\"documentos_table\">
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
                                                                                        <td class=\"text-center\">".$linha -> ch_sei."</td>
                                                                                        <td>".$linha -> vc_tipo_processo."</td>
                                                                                        <td>".$linha -> vc_documento."</td>
                                                                                        <td class=\"text-center\">".$linha -> vc_sigla."</td>
                                                                                        <td class=\"text-center\">".show_date($linha -> dt_sei)."</td>
                                                                                        <td class=\"text-center\">".show_date($linha -> dt_cadastro)."</td>
                                                                                        <td class=\"text-center\" style=\"white-space:nowrap\">";
                        echo anchor($linha -> vc_link, '<i class="fa fa-lg mr-0 fa-eye"></i>', " class=\"btn btn-sm btn-square btn-info\" title=\"Visualizar documento\" target=\"_blank\"");
                        echo anchor('AcessoPasta/download/'.$linha -> pr_documento, '<i class="fa fa-lg mr-0 fa-download"></i>', " class=\"btn btn-sm btn-square btn-info\" title=\"Baixar documento\"");
                        echo "
                                                                                        </td>
                                                                                    </tr>";
                }
        }
        echo "
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>";

        $pagina['js'] = "
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
$this -> load -> view('internaRodape', $pagina);
?>