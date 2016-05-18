<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/localAction.php';

$local = new localModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $local->setLocalid($_GET['id']);

    $local = localAction::getLocal($local);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="localbody">
    <script>
        $(function() {
            $("#blocalsubmit").button({
                icons: {primary: "ui-icon-disk"}
            });

            var tips = $("#localbody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }
            $("#localform").submit(function(){return false;});

            $("#blocalsubmit").click(function() {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/localcontroler.php",
                    data: $("#localform").serialize(),
                    dataType: "text",
                    success: function(html) {
                        if (html !== "sucesso") {
                            updateTips(html);
                        } else {
                            //se o parametro select não foi setado é sinal 
                            //que é pra carregar a pagina escolhida no menu
                            //na index do sistema
                            //e fechar o dialog-form
                            if (select === "") {
                                $("#dialog-form").dialog('close');
                                setTimeout(function() {
                                    carregarIndex(pagina);
                                }, 1);
                                //se o parametro select foi setado é sinal
                                //que é pra carrega o select setado
                                //e fechar o dialog-subform
                            } else {
                                $("#dialog-subform").dialog('close');
                                setTimeout(function() {
                                    carregarSelect(select);
                                }, 1);
                            }
                        }
                    }
                });
            });

            $("#blocalfechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#blocalfechar").click(function() {
                if (select === "") {
                    $("#dialog-form").dialog('close');
                } else {
                    $("#dialog-subform").dialog('close');
                }
            });

        });
    </script>
    <p class="validateTips">Todos os campos são obrigatórios.</p>

    <form id="localform">
        <fieldset>
            Nome do Local
            <input 
                type="text" 
                name="localnome" 
                title="Nome do Local" 
                size="50"
                maxlength="50"
                required="required" 
                class="text ui-widget-content ui-corner-all" 
                value="<?= $local->getLocalnome(TRUE) ?>">
            <input 
                type="hidden" 
                name="localid" 
                value="<?= $local->getLocalid() ?>" >
            <input 
                type="hidden" 
                id="control"
                name="control" 
                value="<?= $situacao ?>" >
        </fieldset>
    </form>
    <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
        <div class="ui-dialog-buttonset">
            <button 
                id="blocalfechar" 
                type="reset" 
                role="button">
                Fechar
            </button>
            <button 
                id="blocalsubmit" 
                type="submit" 
                role="button">
                Salvar
            </button>
        </div>
    </div>
</div>