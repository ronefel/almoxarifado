<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/produtosubgrupoAction.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/produtogrupoAction.php';

$produtogrupos = new produtogrupoModel();
$produtogrupos = produtogrupoAction::listProdutogrupo();

$produtosubgrupo = new produtosubgrupoModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $produtosubgrupo->setProdutosubgrupoid($_GET['id']);

    $produtosubgrupo = produtosubgrupoAction::getProdutosubgrupo($produtosubgrupo);
}

if (isset($_GET['grupoid']) && !empty($_GET['grupoid'])) {
    $produtosubgrupo->getProdutogrupomodel()->setProdutogrupoid($_GET['grupoid']);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="produtosubgrupobody">
    <script>
        $(function() {
            $("#bprodutosubgruposubmit").button({
                icons: {primary: "ui-icon-disk"}
            });

            $("#produtogruposelectform").selectmenu({width: '51.3em'}).selectmenu("menuWidget").addClass("overflow");

            var tips = $("#produtosubgrupobody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }
            
            $(".select-plus").button({
                icons: {primary: "ui-icon-circle-plus"},
                text: false
            });
            $(".select-plus .ui-button-text").css("padding", "1.05em");
            
            $("#bprodutosubgruposubmit").click(function() {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/produtosubgrupocontroler.php",
                    data: $("#produtosubgrupoform").serialize(),
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
                                    carregarSelect(select, grupoid);
                                }, 1);
                            }
                        }
                    }
                });
            });

            $("#bprodutosubgrupofechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bprodutosubgrupofechar").click(function() {
                if (select === "") {
                    $("#dialog-form").dialog('close');
                } else {
                    $("#dialog-subform").dialog('close');
                }
            });

        });
    </script>
    <style>
        .overflow{height: 140px;}
    </style>
    <p class="validateTips">Todos os campos são obrigatórios.</p>

    <form id="produtosubgrupoform">
        <fieldset>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Grupo de Produtos* </label>
                    <select 
                        id="produtogruposelectform" 
                        name="produtogrupoid" 
                        required="required">
                            <?php if ($produtosubgrupo->getProdutogrupomodel()->getProdutogrupoid() == "") { ?>
                            <option selected="selected" disabled="disabled">Selecione...</option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($produtogrupos); $i++) { ?>
                            <option 
                            <?php if ($produtosubgrupo->getProdutogrupomodel()->getProdutogrupoid() == $produtogrupos[$i]->getProdutogrupoid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $produtogrupos[$i]->getProdutogrupoid() ?>"><?= $produtogrupos[$i]->getNome(TRUE) ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    Descrição do Subgrupo de Produtos*
                    <input 
                        type="text" 
                        name="produtosubgruponome"  
                        size="62"
                        maxlength="100"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $produtosubgrupo->getNome(TRUE) ?>">
                </div>
            </div>
            <input 
                type="hidden" 
                name="produtosubgrupoid" 
                value="<?= $produtosubgrupo->getProdutosubgrupoid() ?>" >
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
                id="bprodutosubgrupofechar" 
                type="reset" 
                role="button">
                Fechar
            </button>
            <button 
                id="bprodutosubgruposubmit" 
                type="submit" 
                role="button">
                Salvar
            </button>
        </div>
    </div>
</div>