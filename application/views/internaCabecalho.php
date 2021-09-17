<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($this -> session -> trocasenha && ($menu1 != 'Interna' || $menu2 != 'index')){
        redirect(base_url('Interna/index'));
}

$pagina['menu1'] = $menu1;
$pagina['menu2'] = $menu2;
$pagina['url'] = $url;
$pagina['nome_pagina'] = $nome_pagina;
$pagina['icone'] = $icone;

if(strlen($this -> session -> nome) > 0){
        $nome = explode(' ', $this -> session -> nome);
        $primeironome = $nome[0];
        $ultimonome = $nome[count($nome)-1];
        if(strlen($primeironome) + strlen($ultimonome) > 30){
                $ultimonome = substr($ultimonome, 0, 1).'.';
        }
}
echo "<!DOCTYPE html>
<html lang=\"pt-br\" >
    <head>
        <meta charset=\"utf-8\"/>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />

        <title>".$this -> config -> item('nome')."</title>
        <meta name=\"description\" content=\"Sistema ".$this -> config -> item('nome')."\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">

        <link href=\"https://fonts.googleapis.com/css?family=Open+Sans:400,600,800\" rel=\"stylesheet\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"".base_url('assets/vendor/fontawesome-free/css/all.min.css')."\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"".base_url('assets/css/component.css')."\">

        <link rel=\"stylesheet\" type=\"text/css\" href=\"".base_url('assets/css/sb-admin-2.min.css')."\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"".base_url('assets/css/sipe-override.css')."\">

        <link rel=\"stylesheet\" type=\"text/css\" href=\"".base_url('bower_components/sweetalert2/dist/sweetalert2.css')."\">
";
//if(isset($adicionais['jquery-ui'])){
        echo "
        <link rel=\"stylesheet\" type=\"text/css\" href=\"".base_url('bower_components/jquery-ui/jquery-ui.min.css')."\">";
//}
if(isset($adicionais['datatables'])){
        echo "
        <!-- Data Table Css -->
        <link rel=\"stylesheet\" type=\"text/css\" href=\"".base_url('bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css')."\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"".base_url('assets\pages\data-table\css\buttons.dataTables.min.css')."\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"".base_url('bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css')."\">";
}
if(isset($adicionais['pickers'])){
        echo "
        <link href=\"".base_url('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css')."\" rel=\"stylesheet\" type=\"text/css\" />
        <link href=\"".base_url('bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css')."\" rel=\"stylesheet\" type=\"text/css\" />
        <link href=\"".base_url('bower_components/bootstrap-timepicker/css/bootstrap-timepicker.css')."\" rel=\"stylesheet\" type=\"text/css\" />
        <link href=\"".base_url('bower_components/bootstrap-daterangepicker/daterangepicker.css')."\" rel=\"stylesheet\" type=\"text/css\" />";
}
if(isset($adicionais['wizard'])){
        echo "
        <!--forms-wizard css-->
        <link rel=\"stylesheet\" type=\"text/css\" href=\"".base_url('bower_components\jquery.steps\css\jquery.steps.css')."\">";
}
if(isset($adicionais['select2'])){
        echo "
        <!-- Select 2 css -->
        <link rel=\"stylesheet\" href=\"".base_url('bower_components/select2/css/select2.min.css')."\">";
}
echo "
        <link rel=\"shortcut icon\" href=\"".base_url('images/favicon.ico')."\" />
    </head>";
/*
echo "
    <body class=\"page-footer-fixed\">
        <!-- Menu header start -->
        <div id=\"pcoded\" class=\"pcoded\">
            <div class=\"pcoded-overlay-box\"></div>
            <div class=\"pcoded-container navbar-wrapper\">
                <nav class=\"navbar header-navbar pcoded-header\" header-theme=\"theme6\">
                    <div class=\"navbar-wrapper\">
";*/
echo "
    <body id=\"page-top\">
        <div class=\"theme-loader\">
            <div class=\"loader\"></div>
        </div>
        <div id=\"wrapper\">
            <ul class=\"navbar-nav bg-gradient-primary sidebar sidebar-dark accordion\" id=\"accordionSidebar\">
                <a class=\"sidebar-brand d-flex align-items-center justify-content-center bg-purple\" href=\"".base_url()."\">
                    <img class=\"img-fluid\" alt=\"Logo\" src=\"".base_url('images/logo_sipe_inv.svg')."\" width=\"80\">
                </a>";
/*
echo "
                <a class=\"mobile-options\">
                    <i class=\"fa fa-align-justify\"></i>
                </a>";*/
echo "
                <hr class=\"sidebar-divider my-0\">";
if($menu1 == 'AcessoPasta'){
        echo "
            </ul>";
}
else{
        $this -> load -> view('internaMenu', $pagina);
        echo "
                <hr class=\"sidebar-divider d-none d-md-block\">
                <div class=\"text-center d-none d-md-inline\">
                    <button class=\"rounded-circle border-0\" id=\"sidebarToggle\"></button>
                </div>
            </ul>
            <div id=\"content-wrapper\" class=\"d-flex flex-column\">
                <div id=\"content\">
                    <nav class=\"navbar navbar-expand navbar-dark bg-purple topbar mb-4 static-top shadow text-white\">
                        <button id=\"sidebarToggleTop\" class=\"btn btn-link d-md-none rounded-circle mr-3\">
                            <i class=\"fa fa-bars\"></i>
                        </button>
                        <div class=\"d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100\">
                            <a href=\"javascript:void(0)\" id=\"toggleMinMaxScreen\" class=\"mr-5\">
                                <i id=\"minMaxScreenIcon\" class=\"fa fa-window-maximize text-white\"></i>
                            </a>";
        if($this -> session -> perfil == 1){
                echo 'RH Visualizador';
        }
        else if($this -> session -> perfil == 2){
                echo 'Visualizador Geral';
        }
        else if($this -> session -> perfil == 3){
                echo 'Administrador';
        }
        if($this -> session -> perfil == 5){
                echo 'RH Arquivo';
        }
        if(strlen($this -> session -> sigla) > 0){
                echo ' - '.$this -> session -> sigla;
        }
        echo "
                    </div>

                    <ul class=\"navbar-nav ml-auto\">
                        <li class=\"nav-item dropdown no-arrow mx-1\">
                            <a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"alertsDropdown\" role=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                <i class=\"fas fa-bell fa-fw\"></i>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class=\"dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in\" aria-labelledby=\"alertsDropdown\">
                                <h6 class=\"dropdown-header\">
                                    Notificações
                                </h6>
                            </div>
                        </li>
                        <div class=\"topbar-divider d-none d-sm-block\"></div>
                        <li class=\"nav-item dropdown no-arrow\">
                            <a class=\"nav-link dropdown-toggle text-white\" href=\"#\" id=\"userDropdown\" role=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                <span class=\"mr-2 d-none d-lg-inline small nav-username\">
                                    {$primeironome} {$ultimonome}
                                </span>
                                <img class=\"img-profile rounded-circle\" src=\"".base_url('Interna/avatar')."\" alt=\"User Profile Image\" />
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class=\"dropdown-menu dropdown-menu-right shadow animated--grow-in\" aria-labelledby=\"userDropdown\">
                                <a class=\"dropdown-item\" href=\"javascript://\" data-toggle=\"modal\" data-target=\"#trocarsenha\">
                                    <i class=\"fa fa-key\"></i>
                                    Alterar senha
                                </a>
                                <div class=\"dropdown-divider\"></div>
                                <a class=\"dropdown-item\" href=\"".base_url('Interna/logout')."\">
                                    <i class=\"fas fa-sign-out-alt\"></i>
                                    Sair
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>";
}
echo "
                <!-- Begin Page Content -->
                <div class=\"container-fluid\">
                    <div class=\"page-wrapper p-2\">
                        <div class=\"row\">";

?>