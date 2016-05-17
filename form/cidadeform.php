<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/cidadeAction.php';

$cidade = new cidadeModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $cidade->setCidadeid($_GET['id']);

    $cidade = cidadeAction::getCidade($cidade);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="cidadebody">
    <script>
        $(function() {
            $("#bcidadesubmit").button({
                icons: {primary: "ui-icon-disk"}
            });

            var tips = $("#cidadebody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }

            $("#bcidadesubmit").click(function() {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/cidadecontroler.php",
                    data: $("#cidadeform").serialize(),
                    dataType: "text",
                    cache: false,
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

            $("#bcidadefechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bcidadefechar").click(function() {
                if (select === "") {
                    $("#dialog-form").dialog('close');
                } else {
                    $("#dialog-subform").dialog('close');
                }
            });

        });
    </script>
    <p class="validateTips">Todos os campos são obrigatórios.</p>

    <form id="cidadeform">
        <fieldset>
            <div class="linha-form">
                <div class="coluna-form">
                    Nome da Cidade
                    <input 
                        type="text" 
                        name="cidadenome" 
                        title="Nome da Cidade" 
                        size="50"
                        maxlength="50"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $cidade->getNome(TRUE) ?>"
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    UF
                    <input 
                        type="text" 
                        name="cidadeuf" 
                        title="UF da Cidade" 
                        size="2"
                        maxlength="2"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $cidade->getUf(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    CEP
                    <input 
                        type="text" 
                        name="cidadecep" 
                        title="CEP da Cidade" 
                        size="10"
                        maxlength="10"
                        required="required" 
                        maxlength="10" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $cidade->getCep(TRUE) ?>">
                </div>
                <input 
                    type="hidden" 
                    name="cidadeid" 
                    value="<?= $cidade->getCidadeid() ?>" >
                <input 
                    type="hidden" 
                    id="control"
                    name="control" 
                    value="<?= $situacao ?>" >
                </fieldset>
                </form>
                <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
                    <div class="ui-dialog-buttonset">
                        <button id="bcidadefechar" type="reset" role="button">Fechar</button>
                        <button id="bcidadesubmit" type="submit" role="button">Salvar</button>
                    </div>
                </div>
            </div>