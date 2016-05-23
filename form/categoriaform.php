<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/categoriaAction.php';

$categoria = new categoriaModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $categoria->setCategoriaid($_GET['id']);

    $categoria = categoriaAction::getCategoria($categoria);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="categoriabody">
    <script>
        $(function() {
            $("#bcategoriasubmit").button({
                icons: {primary: "ui-icon-disk"}
            });

            var tips = $("#categoriabody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }
            $("#categoriaform").submit(function(){return false;});

            $("#bcategoriasubmit").click(function() {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/categoriacontroler.php",
                    data: $("#categoriaform").serialize(),
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

            $("#bcategoriafechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bcategoriafechar").click(function() {
                if (select === "") {
                    $("#dialog-form").dialog('close');
                } else {
                    $("#dialog-subform").dialog('close');
                }
            });

        });
    </script>
    <p class="validateTips">Todos os campos são obrigatórios.</p>

    <form id="categoriaform">
        <fieldset>
            Nome do Categoria
            <input 
                type="text" 
                name="categorianome" 
                title="Nome do Categoria" 
                size="50"
                maxlength="50"
                required="required" 
                class="text ui-widget-content ui-corner-all" 
                value="<?= $categoria->getCategorianome(TRUE) ?>">
            <input 
                type="hidden" 
                name="categoriaid" 
                value="<?= $categoria->getCategoriaid() ?>" >
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
                id="bcategoriafechar" 
                type="reset" 
                role="button">
                Fechar
            </button>
            <button 
                id="bcategoriasubmit" 
                type="submit" 
                role="button">
                Salvar
            </button>
        </div>
    </div>
</div>