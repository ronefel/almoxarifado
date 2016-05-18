<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/fornecedorgrupoAction.php';

$fornecedorgrupo = new fornecedorgrupoModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $fornecedorgrupo->setFornecedorgrupoid($_GET['id']);

    $fornecedorgrupo = fornecedorgrupoAction::getFornecedorgrupo($fornecedorgrupo);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="fornecedorgrupobody">
    <script>
        $(function() {
            $("#bfornecedorgruposubmit").button({
                icons: {primary: "ui-icon-disk"}
            });

            var tips = $("#fornecedorgrupobody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }

            $("#bfornecedorgruposubmit").click(function() {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/fornecedorgrupocontroler.php",
                    data: $("#fornecedorgrupoform").serialize(),
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

            $("#bfornecedorgrupofechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bfornecedorgrupofechar").click(function() {
                if (select === "") {
                    $("#dialog-form").dialog('close');
                } else {
                    $("#dialog-subform").dialog('close');
                }
            });

        });
    </script>
    <p class="validateTips">Todos os campos são obrigatórios.</p>

    <form id="fornecedorgrupoform">
        <fieldset>
            Nome do Grupo
            <input 
                type="text" 
                name="fornecedorgruponome" 
                title="Nome do Grupo de Fornecedor" 
                size="50"
                maxlength="50"
                required="required" 
                class="text ui-widget-content ui-corner-all" 
                value="<?= $fornecedorgrupo->getFornecedorgruponome(TRUE) ?>">
            <input 
                type="hidden" 
                name="fornecedorgrupoid" 
                value="<?= $fornecedorgrupo->getFornecedorgrupoid() ?>" >
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
                id="bfornecedorgrupofechar" 
                type="reset" 
                role="button">
                Fechar
            </button>
            <button 
                id="bfornecedorgruposubmit" 
                type="submit" 
                role="button">
                Salvar
            </button>
        </div>
    </div>
</div>