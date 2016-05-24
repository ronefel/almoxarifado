<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/produtoAction.php';
require_once '../action/produtosubgrupoAction.php';
require_once '../action/produtogrupoAction.php';
require_once '../action/marcaAction.php';

$produto = new produtoModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $produto->setProdutoid($_GET['id']);
    $produto = produtoAction::getProduto($produto);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}

$marcas = new marcaModel();
$marcas = marcaAction::listMarca();

$produtogrupos = new produtogrupoModel();
$produtogrupos = produtogrupoAction::listProdutogrupo();

$produtosubgrupos = array(new produtosubgrupoModel());
if ($produto->getProdutogrupoid() != "") {

    $produtosubgrupos = produtosubgrupoAction::listprodutosubgrupoToProdutogrupo($produto->getProdutogrupoid());
}
?>

<div id="produtobody">
    <script>
        $(function () {

            $("#produtosubgrupoTogruposelect, #produtogruposelect, #marcaselect").combobox();
            
            $(".custom-combobox-input").css('width', '272px');
            
            $("#produtoativo").selectmenu({width: '8em'});
            $("#bprodutosubmit").button({
                icons: {primary: "ui-icon-disk"}
            });
            $(".select-plus").button({
                icons: {primary: "ui-icon-circle-plus"},
                text: false
            });
            $(".select-plus .ui-button-text").css("padding", "1.05em");

            $("#produtodatacadastro").datepicker({
                dateFormat: "dd/mm/yy"
            });

            $("#produtocustomedio").maskMoney({
                prefix: 'R$ ',
                allowNegative: false,
                thousands: '',
                decimal: ',',
                affixesStay: false
            });
            $("#produtoestoqueminimo, #produtoestoquemaximo").maskMoney({
                allowNegative: false,
                thousands: '',
                decimal: ',',
                affixesStay: false,
                precision: 3
            });
            $("#produtocodigobarras").maskMoney({
                allowNegative: false,
                thousands: '',
                decimal: '',
                affixesStay: false,
                precision: 0
            });


            $("#produtogruposelect").combobox({
                select: function (event, ui) {
                    if ($("#produtogruposelect").val() !== grupoid) {
                        grupoid = $("#produtogruposelect").val();
                        carregarSelect("produtosubgrupoTogruposelect", grupoid);
                    }
                }
            });

            $(".select-plus").click(function () {
                var event = $(this).attr("data-evento");
                var title = $(this).attr("data-titulo");
                var param = {"grupoid": $("#produtogruposelect").val(), "situacao": "novo"};
                select = $(this).attr("data-id");
                grupoid = $("#produtogruposelect").val();
                openSubform(event, title, param);

            });

            var tips = $("#produtobody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }

            $("#bprodutosubmit").click(function () {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/produtocontroler.php",
                    data: $("#produtoform").serialize(),
                    dataType: "text",
                    cache: false,
                    success: function (html) {
                        if (html !== "sucesso") {
                            updateTips(html);
                        } else {
                            $("#dialog-form").dialog('close');
                            setTimeout(function () {
                                carregarIndex(pagina);
                            }, 1);
                        }
                    }
                });
            });

            $("#bprodutofechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bprodutofechar").click(function () {
                $("#dialog-form").dialog('close');
            });

        });
    </script>
    <p class="validateTips">Todos os campos com * são obrigatórios.</p>

    <form id="produtoform">
        <fieldset>

            <div class="linha-form">
                <div class="coluna-form">
                    <label> Descrição do Produto* </label>
                    <input 
                        type="text" 
                        name="produtonome" 
                        title="Descrição do Produto" 
                        style="width: 639px;"
                        maxlength="100"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $produto->getProdutonome(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Unidade* </label>
                    <input 
                        type="text" 
                        name="produtound" 
                        title="Unidade de Medida do produto" 
                        style="width: 70px;"
                        maxlength="10"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $produto->getUnd(TRUE) ?>">
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Grupo de Produtos* </label>
                    <select 
                        id="produtogruposelect" 
                        name="produtogrupoid" 
                        title="Grupo de Produto" 
                        required="required" >
                            <?php if ($produto->getProdutogrupoid() == "") { ?>
                            <option selected="selected" disabled="disabled"></option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($produtogrupos); $i++) { ?>
                            <option
                            <?php if ($produto->getProdutogrupoid() == $produtogrupos[$i]->getProdutogrupoid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $produtogrupos[$i]->getProdutogrupoid() ?>"><?= $produtogrupos[$i]->getNome(TRUE) ?></option>
                            <?php } ?>
                    </select>
                    <div class="select-plus" 
                         title="Cadastrar Grupo de Produto"
                         data-id="produtogruposelect"
                         data-titulo="Cadastrar Grupo de Produto" 
                         data-evento="produtogrupo"></div>
                </div>
                <div class="coluna-form">
                    <label> Subgrupo de Produtos* </label>
                    <select 
                        id="produtosubgrupoTogruposelect" 
                        name="produtosubgrupoid" 
                        title="Subgrupo de Produto" 
                        required="required" >
                            <?php if ($produto->getProdutosubgrupoid() == "") { ?>
                            <option selected="selected" disabled="disabled">Selecione o grupo de produtos</option>
                        <?php } ?>
                        <?php
                        for ($i = 0; $i < count($produtosubgrupos); $i++) {
                            if ($produtosubgrupos[$i]->getProdutosubgrupoid() != "") {
                                ?>
                                <option
                                <?php if ($produto->getProdutosubgrupoid() == $produtosubgrupos[$i]->getProdutosubgrupoid()) { ?>
                                        selected="selected"
                                    <?php } ?>
                                    value="<?= $produtosubgrupos[$i]->getProdutosubgrupoid() ?>"><?= $produtosubgrupos[$i]->getNome(TRUE) ?></option>
                                    <?php
                                }
                            }
                            ?>
                    </select>
                    <div class="select-plus" 
                         title="Cadastrar Subgrupo de Produto"
                         data-id="produtosubgrupoTogruposelect"
                         data-titulo="Cadastrar Subgrupo de Produto" 
                         data-evento="produtosubgrupo"></div>
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Marca </label>
                    <select 
                        id="marcaselect" 
                        name="marcaid" 
                        title="Marca do Patrimônio" >
                            <?php if ($produto->getMarcaid() == "") { ?>
                            <option selected="selected" disabled="disabled"></option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($marcas); $i++) { ?>
                            <option
                            <?php if ($produto->getMarcaid() == $marcas[$i]->getMarcaid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $marcas[$i]->getMarcaid() ?>"><?= $marcas[$i]->getMarcanome(TRUE) ?></option>
                            <?php } ?>
                    </select>
                    <div class="select-plus" 
                         title="Cadastrar nova marca"
                         data-id="marcaselect"
                         data-titulo="Cadastrar Marca" 
                         data-evento="marca"></div>
                </div>
            </div>

            <div class="linha-form">
                <div class="coluna-form">
                    <label> Código de Barras </label>
                    <input 
                        id="produtocodigobarras"
                        type="text" 
                        name="produtocodigobarras" 
                        title="Código de Barras do Produto" 
                        style="width: 202px;"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        style="text-align: right;"
                        value="<?= $produto->getCodigobarras(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Estoque Mínimo </label>
                    <input 
                        id="produtoestoqueminimo"
                        type="text" 
                        name="produtoestoqueminimo" 
                        title="Estoque mínimo do produto" 
                        style="width: 120px;"
                        maxlength="20"
                        placeholder="0,000"
                        class="text ui-widget-content ui-corner-all" 
                        style="text-align: right;"
                        value='<?= $produto->getEstoqueminimo("form") ?>'>
                </div>
                <div class="coluna-form">
                    <label> Estoque Máximo </label>
                    <input 
                        id="produtoestoquemaximo"
                        type="text" 
                        name="produtoestoquemaximo" 
                        title="Estoque máximo do produto" 
                        style="width: 120px;"
                        maxlength="20"
                        placeholder="0,000"
                        class="text ui-widget-content ui-corner-all" 
                        style="text-align: right;"
                        value='<?= $produto->getEstoquemaximo("form") ?>'>
                </div>
                <div class="coluna-form">
                    <label> Custo Médio R$</label>
                    <input 
                        id="produtocustomedio"
                        type="text" 
                        name="produtocustomedio" 
                        title="Custo médio do Produto" 
                        style="width: 120px;"
                        maxlength="20"
                        placeholder="R$ 0.00"
                        class="text ui-widget-content ui-corner-all" 
                        style="text-align: right;"
                        value="<?= $produto->getCustomedio("form") ?>">
                </div>
                <div class="coluna-form">
                    <label> Ativo/Inativo </label>
                    <select 
                        id="produtoativo" 
                        name="produtoativo">
                        <option <?php if ($produto->getAtivo() == "1") { ?>
                                selected="selected"
                            <?php } ?>
                            value="1">Ativo</option>
                        <option <?php if ($produto->getAtivo() == "0") { ?>
                                selected="selected"
                            <?php } ?>
                            value="0">Inativo</option>
                    </select>
                </div>
            </div>
            <label> Observações </label>
            <textarea 
                style="width: 49.5em; height: 4em;"
                name="produtoobservacao" 
                title="Observações sobre o Produto" 
                class="text ui-widget-content ui-corner-all"><?= $produto->getObservacoes() ?></textarea>
            <input 
                type="hidden" 
                name="produtoid" 
                value="<?= $produto->getProdutoid() ?>" >
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
                id="bprodutofechar" 
                type="reset" 
                role="button">
                Fechar
            </button>
            <button 
                id="bprodutosubmit" 
                type="submit" 
                role="button">
                Salvar
            </button>
        </div>
    </div>
</div>