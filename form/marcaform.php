<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/marcaAction.php';

$marca = new marcaModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $marca->setMarcaid($_GET['id']);

    $marca = marcaAction::getMarca($marca);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="marcabody">
    <script>
        $(function() {
            $("#bmarcasubmit").button({
                icons: {primary: "ui-icon-disk"}
            });

            var tips = $("#marcabody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }
            $("#marcaform").submit(function(){return false;});

            $("#bmarcasubmit").click(function() {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/marcacontroler.php",
                    data: $("#marcaform").serialize(),
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

            $("#bmarcafechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bmarcafechar").click(function() {
                if (select === "") {
                    $("#dialog-form").dialog('close');
                } else {
                    $("#dialog-subform").dialog('close');
                }
            });

        });
    </script>
    <p class="validateTips">Todos os campos são obrigatórios.</p>

    <form id="marcaform">
        <fieldset>
            Nome do Marca
            <input 
                type="text" 
                name="marcanome" 
                title="Nome do Marca" 
                size="50"
                maxlength="50"
                required="required" 
                class="text ui-widget-content ui-corner-all" 
                value="<?= $marca->getMarcanome(TRUE) ?>">
            <input 
                type="hidden" 
                name="marcaid" 
                value="<?= $marca->getMarcaid() ?>" >
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
                id="bmarcafechar" 
                type="reset" 
                role="button">
                Fechar
            </button>
            <button 
                id="bmarcasubmit" 
                type="submit" 
                role="button">
                Salvar
            </button>
        </div>
    </div>
</div>