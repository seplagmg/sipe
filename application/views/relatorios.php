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
                                                                    </div>
                                                                </div>";
if(strlen($codigo) == 0){
        echo "
                                                                <ul class=\"basic-list\">";
        if($this -> session -> perfil == 3 || $this -> session -> perfil == 5){ //administrador e RH Arquivo
                echo '
                                                                    <li><a href="'.base_url('Relatorios/index/1').'"><h5>Criação de links de acesso para pastas</h5></a></li>';
                /*
                echo '
                                                                    <li><a href="'.base_url('Relatorios/index/3').'"><h5>Desativações de documentos</h5></a></li>';*/
        }
        if($this -> session -> perfil == 3){ //administrador
                /*
                echo '
                                                                    <li><a href="'.base_url('Relatorios/index/2').'"><h5>Alterações de pastas e documentos</h5></a></li>';*/
        }
        echo "
                                                                </ul>
                                                            </div>";
}
else if($codigo == 1){
        echo "
                                                            <div class=\"dt-responsive table-responsive\">
                                                                <table class=\"table table-bordered table-hover\" id=\"relatorio_table\">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Pasta</th>
                                                                            <th>MASP</th>
                                                                            <th>Admissão</th>
                                                                            <th>Token</th>
                                                                            <th>Cadastro</th>
                                                                            <th>Usuário</th>
                                                                            <th>Instituição</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>";
        //var_dump($pastaspublicas);
        if(isset($pastaspublicas)){
                foreach ($pastaspublicas as $linha){
                        $dt_cadastro = strtotime($linha -> cadastro);
                        echo "
                                                                        <tr>
                                                                            <td class=\"text-center\">".$linha -> nome."</td>
                                                                            <td class=\"text-center\">".exibir_MASP($linha -> in_masp)."</td>
                                                                            <td class=\"text-center\">".$linha -> in_admissao."</td>
                                                                            <td class=\"text-center\">".$linha -> pr_pasta_publica."</td>
                                                                            <td class=\"text-center\" data-search=\"".show_date($linha -> cadastro)."\" data-order=\"$dt_cadastro\">".show_date($linha -> cadastro)."</td>
                                                                            <td class=\"text-center\">".$linha -> usuario."</td>
                                                                            <td class=\"text-center\">".$linha -> vc_sigla."</td>
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
            $('#relatorio_table').DataTable({
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
else if($codigo == 2){
        echo "
                                                            <div class=\"dt-responsive table-responsive\">
                                                                <table class=\"table table-bordered table-hover\" id=\"relatorio_table\">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Tipo</th>
                                                                            <th>Antes</th>
                                                                            <th>Depois</th>
                                                                            <th>Justificativa</th>
                                                                            <th>Cadastro</th>
                                                                            <th>Usuário</th>
                                                                            <th>Instituição</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>";
        //var_dump($alteracoes);
        if(isset($alteracoes)){
                foreach ($alteracoes as $linha){
                        $dt_cadastro = strtotime($linha -> dt_cadastro);
                        echo "
                                                                        <tr>
                                                                            <td class=\"text-center\">".$linha -> en_tipo."</td>
                                                                            <td class=\"text-center\">".$linha -> tx_antes."</td>
                                                                            <td class=\"text-center\">".$linha -> tx_depois."</td>
                                                                            <td class=\"text-center\">".$linha -> tx_justificativa."</td>
                                                                            <td class=\"text-center\" data-search=\"".show_date($linha -> dt_cadastro)."\" data-order=\"$dt_cadastro\">".show_date($linha -> dt_cadastro)."</td>
                                                                            <td class=\"text-center\">".$linha -> vc_nome."</td>
                                                                            <td class=\"text-center\">".$linha -> vc_sigla."</td>
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
            $('#relatorio_table').DataTable({
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
else if($codigo == 3){
        echo "
                                                            <div class=\"dt-responsive table-responsive\">
                                                                <table class=\"table table-bordered table-hover\" id=\"relatorio_table\">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Pasta</th>
                                                                            <th>MASP</th>
                                                                            <th>Admissão</th>
                                                                            <th>Documento</th>
                                                                            <th>Justificativa</th>
                                                                            <th>Desativação</th>
                                                                            <th>Usuário</th>
                                                                            <th>Instituição</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>";
        //var_dump($remocoes);
        if(isset($remocoes)){
                foreach ($remocoes as $linha){
                        $dt_remocao = strtotime($linha -> dt_remocao);
                        echo "
                                                                        <tr>
                                                                            <td class=\"text-center\">".$linha -> nome."</td>
                                                                            <td class=\"text-center\">".exibir_MASP($linha -> in_masp)."</td>
                                                                            <td class=\"text-center\">".$linha -> in_admissao."</td>
                                                                            <td class=\"text-center\">".$linha -> in_documento."</td>
                                                                            <td class=\"text-center\">".$linha -> tx_justificativa."</td>
                                                                            <td class=\"text-center\" data-search=\"".show_date($linha -> dt_remocao)."\" data-order=\"$dt_remocao\">".show_date($linha -> dt_remocao)."</td>
                                                                            <td class=\"text-center\">".$linha -> usuario."</td>
                                                                            <td class=\"text-center\">".$linha -> vc_sigla."</td>
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
            $('#relatorio_table').DataTable({
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
else{
        if(strlen($erro) > 0){
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