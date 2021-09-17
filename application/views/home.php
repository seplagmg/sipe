<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$pagina['menu1'] = $menu1;
$pagina['menu2'] = $menu2;
$pagina['url'] = $url;
$pagina['nome_pagina'] = $nome_pagina;
if(isset($adicionais)){
        $pagina['adicionais'] = $adicionais;
}

$this -> load -> view('publicoCabecalho');
echo "
        <section class=\"login-block\">
            <!-- Container-fluid starts -->
            <div class=\"container\">
                <div class=\"row\">
                    <div class=\"col-sm-12\">
                        <!-- Authentication card start -->
                        ";
$attributes = array('class' => 'md-float-material form-material');
echo form_open($url, $attributes);
echo "
                            <div class=\"text-center\">
                                <a href=\"".base_url()."\">
                                    <img src=\"".base_url('images/logo_sipe.svg')."\" alt=\"".$this -> config -> item('nome')."\" style=\"height:150px\" />
                                    <img src=\"".base_url('images/2.png')."\" alt=\"2.0\" style=\"width:90px\" />
                                </a>
                                <h5>Sistema de Indexação de Documentos em Pastas Funcionais Eletrônicas</h5><br/>
                            </div>
                            <div class=\"alert alert-warning\" style=\"width:90%;margin:0 auto;background-color:#f9e491;\">
                                <div class=\"alert-text\" style=\"color: #7f6704;\">
                                    <h4 style=\"text-align:center\">AVISOS</h4>
                                    1) Este sistema é restrito às àreas de Recursos Humanos do Estado de Minas Gerais. Caso seja servidor profissional de RH, requisite à sua chefia que envie uma solicitação de acesso para a Administração deste sistema.<br/>
                                    2) Caso seja servidor e necessite de acesso de visualização à sua pasta funcional eletrônica, entre em contato com a àrea de Recursos Humanos da sua instituição.<br/>
                                    3) Você está acessando um sistema governamental, de responsabilidade do Governo do Estado de Minas Gerais, que deverá ser utilizado de acordo com a legislação vigente.<br/>
                                    4) A utilização do sistema é monitorada constantemente, sendo que para entrar você deve concordar em ceder dados de uso e informações pessoais que podem ficar registradas para aplicações legais.<br/>
                                    5) O uso não autorizado do sistema é proibido.<br/><br/>
                                    Download do manual de utilização do sistema: <a href=\"".base_url('Publico/download_publico/Manual_SIPE_V3.pdf')."\">Manual_SIPE_V3.pdf</a>
                                </div>
                            </div><br/>

                            <div class=\"auth-box card\">
                                <div class=\"card-block\">
                                    ";

if(strlen($erro) > 0){
        echo "
                                    <div class=\"alert alert-danger background-danger\">
                                        <div class=\"alert-text\">
                                            <strong>ERRO</strong>: {$erro}
                                        </div>
                                    </div>";
}
if(strlen($sucesso) > 0){
        echo "
                                    <div class=\"alert alert-success background-success\">
                                        <div class=\"alert-text\">
                                            <strong>{$sucesso}</strong>
                                        </div>
                                    </div>";
}
echo "
                                    <div class=\"form-group form-primary\">";
if($menu2 == 'index' || $menu2 == 'recuperar'){
        $attributes = array('name' => 'cpf',
                            'id' => 'cpf',
                            'maxlength'=>'14',
                            'class' => 'form-control',
                            'autocomplete'=>'off',
                            'placeholder'=>'CPF');
        if(strstr($erro, 'CPF')){
                $attributes['class'] = 'form-control is-invalid';
        }
        echo form_input($attributes, set_value('cpf'));
        echo "
                                        <span class=\"form-bar\"></span>
                                    </div>";
}
if($menu2 == 'index'){
        echo "
                                    <div class=\"form-group form-primary\">
                                                                                ";
        //$attributes = array('class' => 'control-label visible-ie8 visible-ie9');
        //echo form_label('Senha', 'senha', $attributes);

        $attributes = array('name' => 'senha',
                            'id' => 'senha',
                            'class' => 'form-control',
                            'value'=>'',
                            'placeholder'=>'Senha');
        echo form_password($attributes);
        echo "
                                        <span class=\"form-bar\"></span>
                                    </div>";
}
if($menu2 == 'contato'){
        echo "
                                    <div class=\"form-group form-primary\">
                                        ";
        $attributes = array('name' => 'nome',
                            'id' => 'nome',
                            'maxlength'=>'100',
                            'class' => 'form-control',
                            'placeholder'=>'Nome completo');
        echo form_input($attributes, set_value('nome'));
        echo "
                                    </div>
                                    <div class=\"form-group form-primary\">
                                        ";
        $attributes = array('name' => 'email',
                            'id' => 'email',
                            'maxlength'=>'100',
                            'class' => 'form-control',
                            'placeholder'=>'E-mail');
        echo form_input($attributes, set_value('email'));
        echo "
                                    </div>
                                    <div class=\"form-group form-primary\">
                                        ";
        $attributes = array('name' => 'assunto',
                            'id' => 'assunto',
                            'maxlength'=>'100',
                            'class' => 'form-control',
                            'placeholder'=>'Assunto');
        echo form_input($attributes, set_value('assunto'));
        echo "
                                    </div>
                                    <div class=\"form-group form-primary\">
                                        ";
        $attributes = array('name' => 'msg',
                            'id' => 'msg',
                            'rows'=>'3',
                            'class' => 'form-control',
                            'placeholder' => 'Mensagem',
                            'style' => 'height:100px');
        echo form_textarea($attributes, set_value('msg'));
        echo "
                                    </div>";
}
if($menu2 == 'index'){
        echo "
                                    <div class=\"text-center center-block\">
                                        ";
        $attributes = array('class' => 'btn btn-primary btn-md btn-inline mt-2 waves-effect waves-light text-center text-uppercase',
                            'style'=>'width:60%');
        echo form_submit('logar_sistema', 'Login', $attributes);
        echo "
                                    </div>
                                    <hr/>
                                    <div class=\"row m-t-25 text-center\">
                                        <div class=\"col-12\">
                                            <a href=\"".base_url('Publico/recuperar')."\">Esqueceu sua senha?</a><br/>
                                            <a href=\"".base_url('Publico/contato')."\">Fale conosco</a><br/>
                                        </div>
                                    </div>";
}
else if($menu2 == 'recuperar'){
        echo "
                                    <div class=\"text-center center-block\">
                                        ";
        $attributes = array('class' => 'btn btn-primary btn-md btn-inline mt-2 waves-effect waves-light text-center text-uppercase',
                            'style'=>'width:60%');
        echo form_submit('enviado', 'Recuperar', $attributes);
        echo "
                                    </div>
                                    <hr/>
                                    <div class=\"row m-t-25 text-center\">
                                        <div class=\"col-12\">
                                            <a href=\"".base_url('Publico/index')."\">Login</a>
                                        </div>
                                    </div>";
}
else if($menu2 == 'contato'){
        echo "
                                    <div class=\"text-center center-block\">";
        $attributes = array('class' => 'btn btn-primary btn-md btn-inline mt-2 waves-effect waves-light text-center text-uppercase',
                            'style'=>'width:60%');
        echo form_submit('enviado', 'Enviar', $attributes);
        echo "
                                    </div>
                                    <hr/>
                                    <div class=\"row m-t-25 text-center\">
                                        <div class=\"col-12\">
                                            <a href=\"".base_url('Publico/index')."\">Login</a>
                                        </div>
                                    </div>";
}
echo "
                                </div>
                            </div>
                        </form>
                                <!-- end of form -->
                    </div>
                    <!-- end of col-sm-12 -->
                </div>
                <!-- end of row -->
                <div class=\"text-center\" style=\"margin-top: 10px\">
                        <br/>SUGESP - SEPLAG © Layout Adminty
                </div>
            </div>
            <!-- end of container-fluid -->
        </section>
        <!--[if lt IE 10]>
            <div class=\"ie-warning\">
                <h1>Alerta!!</h1>
                <p>Voc� est� usando uma vers�o desatualizada de um navegador n�o suportado. Favor fazer o download de algum dos navegadores abaixo.</p>
                <div class=\"iew-container\">
                    <ul class=\"iew-download\">
                        <li>
                            <a href=\"http://www.google.com/chrome/\">
                                <img src=\"".base_url('assets/images/browser/chrome.png')."\" alt=\"Chrome\">
                                <div>Chrome</div>
                            </a>
                        </li>
                        <li>
                            <a href=\"https://www.mozilla.org/en-US/firefox/new/\">
                                <img src=\"".base_url('assets/images/browser/firefox.png')."\" alt=\"Firefox\">
                                <div>Firefox</div>
                            </a>
                        </li>
                        <li>
                            <a href=\"http://www.opera.com\">
                                <img src=\"".base_url('assets/images/browser/opera.png')."\" alt=\"Opera\">
                                <div>Opera</div>
                            </a>
                        </li>
                        <li>
                            <a href=\"https://www.apple.com/safari/\">
                                <img src=\"".base_url('assets/images/browser/safari.png')."\" alt=\"Safari\">
                                <div>Safari</div>
                            </a>
                        </li>
                    </ul>
                </div>
                <p>Nos desculpe pela inconveniência!</p>
            </div>
        <![endif]-->";

$pagina['js'] = "
        <script type=\"text/javascript\">
            $(document).ready(function(){
                $('#cpf').inputmask('999.999.999-99');
            });
        </script>";
$this -> load -> view('publicoRodape', $pagina);
?>