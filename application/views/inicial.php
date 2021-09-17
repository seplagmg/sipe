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
                                                                </div>
                                                                <div class=\"col-lg-12\">
                                                                    <!-- Default card start -->
                                                                    <div class=\"card\">
                                                                        <div class=\"card-block\">
                                                                            Bem vindo ao Sistema do ".$this -> config -> item('nome').".<br/><br/>
                                                                            Verifique se o seu nome completo está correto: <span class=\"alert-danger\">".$this -> session -> nome."</span>.<br/>
                                                                            Data e hora atual do sistema: <span class=\"alert-danger\">".date('d/m/Y - H:i:s')."</span>.<br/><br/>
                                                                            Caso haja algum problema com as verificações acima, saia do sistema e informe os responsáveis pelo sistema por meio do fale conosco (link na página de login).<br/><br/>
                                                                            Se os dados acima estiverem corretos, utilize o menu ao lado para iniciar suas atividades.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class=\"row\">
                                                                <div class=\"col-xl-6 col-md-12\">
                                                                    <div class=\"card\">
                                                                        <div class=\"card-header\">
                                                                            <h5>Documentos indexados</h5>
                                                                        </div>
                                                                        <div id=\"dash1\"></div>
                                                                    </div>
                                                                </div>
                                                                <div class=\"col-xl-6 col-md-12\">
                                                                    <div class=\"card\">
                                                                        <div class=\"card-header\">
                                                                            <h5>Tipos de documentos mais comuns</h5>
                                                                        </div>
                                                                        <div id=\"dash2\"></div>
                                                                    </div>
                                                                </div>
                                                                <div class=\"col-xl-6 col-md-12\">
                                                                    <div class=\"card\">
                                                                        <div class=\"card-header\">
                                                                            <h5>Digitalização</h5>
                                                                        </div>
                                                                        <div id=\"dash3\"></div>
                                                                    </div>
                                                                </div>
                                                                <div class=\"col-xl-6 col-md-12\">
                                                                    <div class=\"card\">
                                                                        <div class=\"card-header\">
                                                                            <h5>Tipos de processos mais comuns</h5>
                                                                        </div>
                                                                        <div id=\"dash4\"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";

//var_dump($dash4);

$pagina['js'] = "
        <script type=\"text/javascript\">
            google.charts.load('current', {'packages': ['corechart'], 'language':'pt_br'});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Mês', 'Documentos'],";
if(isset($dash1)){
        for($i = 6; $i >= 0; $i--){
                $pagina['js'] .= "
                    ['".date('m/Y', mktime(0, 0, 0, date('m')-$i, 1, date('Y')))."', {$dash1[$i]}],";
        }
}
$pagina['js'] .= "
                ]);
                var options = {
                    legend: { position: 'none' }
                };
                var chart = new google.visualization.LineChart(document.getElementById('dash1'));
                chart.draw(data, options);
            }
        </script>
        <script type=\"text/javascript\">
            google.charts.load('current', {'packages': ['corechart'], 'language':'pt_br'});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Tipo', 'Quantidade'],";
if(isset($dash2)){
        foreach($dash2 as $linha){
                $pagina['js'].="
                    ['{$linha -> vc_documento}', {$linha -> cont}],";
        }
}
$pagina['js'] .= "
                ]);
                var options = {
                    legend: { position: 'none' }
                };
                var chart = new google.visualization.ColumnChart(document.getElementById('dash2'));
                chart.draw(data, options);
            }
        </script>
        <script type=\"text/javascript\">
            google.charts.load('current', {'packages': ['corechart'], 'language':'pt_br'});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Tipo', 'Quantidade'],";
if(isset($dash3)){
        $pagina['js'] .= "
                    ['Com digitalização', {$dash3['digitalizadas']}],
                    ['Sem digitalização', ".($dash3['total']-$dash3['digitalizadas'])."],";

}
$pagina['js'] .= "
                ]);
                var options = {
                    legend: { position: 'bottom' }
                };
                var chart = new google.visualization.PieChart(document.getElementById('dash3'));
                chart.draw(data, options);
            }
        </script>
        <script type=\"text/javascript\">
            google.charts.load('current', {'packages': ['corechart'], 'language':'pt_br'});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Tipo', 'Quantidade'],";
if(isset($dash4)){
        foreach($dash4 as $linha){
                $pagina['js'] .= "
                    ['{$linha -> vc_tipo_processo}', {$linha -> cont}],";
        }
}
$pagina['js'] .= "
                ]);
                var options = {
                    legend: { position: 'none' }
                };
                var chart = new google.visualization.ColumnChart(document.getElementById('dash4'));
                chart.draw(data, options);
            }
        </script>";

$this -> load -> view('internaRodape', $pagina);
?>