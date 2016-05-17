<?php
include_once 'config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/util/sessao.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/action/usuarioAction.php';
if (!isset($_SESSION)) {
    Sessao::sair();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/head.php'; ?>
        <script>
            $(function () {

                $("#blogin").button();

                $("#login").dialog({
                    autoOpen: true,
                    modal: true,
                    width: '26.9em',
                    closeOnEscape: true,
                    closeText: "Fechar",
                    draggable: true
                });
                $('form').submit(function () {

                    //return false;
                });
                var tips = $("#login .validateTips");
                function updateTips(t) {
                    tips.html(t).addClass("ui-state-error");
                }
                function removeTips(t) {
                    tips.html(t).removeClass("ui-state-error");
                }
<?php if (isset($_GET['erro'])) { ?>
                    updateTips("Login ou Senha incorreto!");
<?php } ?>
            });
        </script>
        <style>
            .ui-dialog-titlebar button{ display: none !important; }
            .ui-dialog .ui-dialog-buttonpane{ width: 25.1em !important; margin-left: -0.8em !important; }
        </style>
    </head>
    <body>


        <div id="login" title="Login" style="display: none;">
            <p class="validateTips">Informe seu login e senha</p>
            <form action="controler/sessioncontroler.php" method="POST">
                <fieldset>
                    Login
                    <input 
                        type="text" 
                        required="required" 
                        style="width: 350px;"
                        name="login"
                        class="text ui-widget-content ui-corner-all">
                    Senha
                    <input 
                        type="password" 
                        required="required" 
                        style="width: 350px;"
                        name="senha"
                        class="text ui-widget-content ui-corner-all">
                    <input 
                        type="hidden"
                        name="control"
                        value="logar">
                </fieldset>
                <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
                    <div class="ui-dialog-buttonset">
                        <!--<a href="recuperarsenha.php" style="margin-right: 40px; color: #427fed;">Precisa de ajuda?</a>-->
                        <button id="blogin" type="submit" role="button">
                            Entrar no sistema
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>