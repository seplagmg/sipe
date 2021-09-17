<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo "
                <li class=\"nav-item ";
if($menu1 == 'Interna' && $menu2 == 'index'){
        echo 'active';
}
echo "\">
                    <a class=\"nav-link\" href=\"".base_url('Interna/index')."\">
                        <i class=\"fas fa-home\"></i>
                        <span>Página inicial</span>
                    </a>
                </li>
                <li class=\"nav-item ";
if(!isset($tipo) || (isset($tipo) && $tipo == 'normal')){
        if($menu1 == 'Pastas' || $menu1 == 'Documentos'){
                echo ' active';
        }
}
echo "\">
                    <a class=\"nav-link\" href=\"".base_url('Pastas/index')."\">
                        <i class=\"fas fa-folder-open\"></i>
                        <span>Pastas funcionais</span>
                    </a>
                </li>
                <li class=\"nav-item ";
if(isset($tipo) && $tipo == 'externo'){
        if($menu1 == 'Pastas' || $menu1 == 'Documentos'){
                echo ' active';
        }
}
echo "\">
                    <a class=\"nav-link\" href=\"".base_url('Pastas/index/externo')."\">
                        <i class=\"fas fa-graduation-cap\"></i>
                        <span>Estagiários e externos</span>
                    </a>
                </li>";
if($this -> session -> perfil == 5 || $this -> session -> perfil == 3){
        echo "
                <li class=\"nav-item ";
        if($menu1 == 'Relatorios'){
                echo ' active';
        }
        echo "\">
                    <a class=\"nav-link\" href=\"".base_url('Relatorios/index')."\">
                        <i class=\"fas fa-chart-line\"></i>
                        <span>Relatórios</span>
                    </a>
                </li>";
}
if($this -> session -> perfil == 3){ //administrador
        echo "
                <li class=\"nav-item ";
        if($menu1 == 'Interna' && $menu2 == 'auditoria'){
                echo ' active';
        }
        echo "\">
                    <a class=\"nav-link\" href=\"".base_url('Interna/auditoria')."\">
                        <i class=\"fas fa-cog\"></i>
                        <span>Auditoria</span>
                    </a>
                </li>
                <li class=\"nav-item ";
        if($menu1 == 'Usuarios'){
                echo ' active';
        }
        echo "\">
                    <a class=\"nav-link\" href=\"".base_url('Usuarios/index')."\">
                        <i class=\"fas fa-users\"></i>
                        <span>Usuários</span>
                    </a>
                </li>";
}
echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"".base_url('Interna/logout')."\">
                        <i class=\"fas fa-sign-out-alt\"></i>
                        <span>Sair</span>
                    </a>
                </li>";
?>
