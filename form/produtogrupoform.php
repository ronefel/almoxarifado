<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/produtogrupoAction.php';

$produtogrupo = new produtogrupoModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $produtogrupo->setProdutogrupoid($_GET['id']);

    $produtogrupo = produtogrupoAction::getProdutogrupo($produtogrupo);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="produtogrupobody">
    <script>
        $(function () {
            $("#bprodutogruposubmit").button({
                icons: {primary: "ui-icon-disk"}
            });

            var tips = $("#produtogrupobody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }

            $("#bprodutogruposubmit").click(function () {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/produtogrupocontroler.php",
                    data: $("#produtogrupoform").serialize(),
                    dataType: "text",
                    success: function (html) {
                        if (html !== "sucesso") {
                            updateTips(html);
                        } else {
                            //se o parametro select não foi setado é sinal 
                            //que é pra carregar a pagina escolhida no menu
                            //na index do sistema
                            //e fechar o dialog-form
                            if (select === "") {
                                $("#dialog-form").dialog('close');
                                setTimeout(function () {
                                    carregarIndex(pagina);
                                }, 1);
                                //se o parametro select foi setado é sinal
                                //que é pra carrega o select setado
                                //e fechar o dialog-subform
                            } else {
                                $("#dialog-subform").dialog('close');
                                setTimeout(function () {
                                    carregarSelect(select);
                                }, 1);
                            }
                        }
                    }
                });
            });

            $("#bprodutogrupofechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bprodutogrupofechar").click(function () {
                if (select === "") {
                    $("#dialog-form").dialog('close');
                } else {
                    $("#dialog-subform").dialog('close');
                }
            });

        });
    </script>
    <p class="validateTips">Todos os campos são obrigatórios.</p>

    <form id="produtogrupoform">
        <fieldset>
            Descrição do Grupo de Produtos
            <input 
                type="text" 
                name="produtogruponome" 
                size="62"
                maxlength="100"
                required="required" 
                class="text ui-widget-content ui-corner-all" 
                value="<?= $produtogrupo->getNome(TRUE) ?>">
            <input 
                type="hidden" 
                name="produtogrupoid" 
                value="<?= $produtogrupo->getProdutogrupoid() ?>" >
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
                id="bprodutogrupofechar" 
                type="reset" 
                role="button">
                Fechar
            </button>
            <button 
                id="bprodutogruposubmit" 
                type="submit" 
                role="button">
                Salvar
            </button>
        </div>
    </div>
</div>