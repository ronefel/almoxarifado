<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/compraAction.php';
require_once '../action/fornecedorAction.php';
require_once '../action/produtoAction.php';

$compra = new compraModel();
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $compra->setCompraid($_GET['id']);
    $compra = compraAction::getCompra($compra);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {
    $situacao = $_GET['situacao'];
}

$fornecedores = array(new fornecedorModel());
$fornecedores = fornecedorAction::listFornecedor();

$produtos = array(new produtoModel());
$produtos = produtoAction::listProduto();
?>

<div id="comprabody">
    <script>
        $(function () {

            situacao = "";
            $("#compraid").val("");
<?php if ($situacao != "") { ?>
                situacao = "<?= $situacao ?>";
                $("#compraid").val(<?= $compra->getCompraid() ?>);
<?php } ?>

//            $("#produtoselect").selectmenu({width: '31.5em'}).selectmenu("menuWidget").addClass("overflow");
//            $("#fornecedorselect").selectmenu({width: '40.3em'}).selectmenu("menuWidget").addClass("overflow");

            $("#produtoselect, #fornecedorselect").combobox();
            $("#bcomprasubmit").button({
                icons: {primary: "ui-icon-disk"}
            });

            $("#produtoselect").combobox({
                select: function (event, ui) {
                    var option = $("#produtoselect option:selected");
                    if (option.val() !== "") {
                        $("#produtoid").val(option.val());
                        $("#produtound").val(option.attr("data-und"));
                        $("#controleestoquequantidade").val("1,000");
                        $("#produtoestoqueatual").val(option.attr("data-estoqueatual"));
                        $("#controleestoquevalor, #controleestoquetotal").val(option.attr("data-customedio"));
                    }
                }
            });

            function refreshItem() {
                $("#produtoid").val(" ");
                $("#produtound").val(" ");
                $("#controleestoquequantidade").val("1,000");
                $("#produtoestoqueatual").val("");
                $("#controleestoquevalor, #controleestoquetotal").val("");
            }
            ;

            $("#compraemissao, #compraaprovacao, #comprarecebimento").datepicker({
                dateFormat: "dd/mm/yy"
            });


            $("#controleestoquevalor, #controleestoquetotal").maskMoney({
                prefix: 'R$ ',
                allowNegative: false,
                thousands: '',
                decimal: ',',
                affixesStay: false
            });
            $("#produtoestoqueatual, #controleestoquequantidade").maskMoney({
                allowNegative: false,
                thousands: '',
                decimal: ',',
                affixesStay: false,
                precision: 3
            });

            $("#controleestoquequantidade, #controleestoquevalor").change(function () {
                var quantidade = $("#controleestoquequantidade").val().replace(',', '.');
                var valor = $("#controleestoquevalor").val().replace(',', '.');
                var total = quantidade * valor;
                $("#controleestoquetotal").val(total.toFixed(2).replace(".", ","));
            });


            var tips = $("#comprabody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }
            function removeTips(t) {
                tips.html(t).removeClass("ui-state-error");
            }

            $("#compraform").submit(function () {
                return false;
            });
            $("#compraitemform").submit(function () {
                return false;
            });

            $("#compraform button").css("width", "110px");

            $("#bcomprasubmit").click(function () {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/compracontroler.php",
                    data: $("#compraform").serialize(),
                    dataType: "text",
                    cache: false,
                    success: function (html) {
                        html = html.split("=");
                        if (html[0] !== "sucesso") {
                            updateTips(html);
                        } else {
                            setTimeout(function () {
                                if ($("#compraid").val() === "") {
                                    $("#bcomprasubmit").button("option", "disabled", true);
                                    $("#controleestoquecompraid").val(html[1]);
                                    compraid = html[1];
                                } else {
                                    $("#controleestoquecompraid").val($("#compraid").val());
                                }
<?php if ($situacao == "novo" || $situacao == "editar") { ?>
                                    $(".desabilitado").hide();
                                    removeTips("Todos os campos com * são obrigatórios.");
<?php } else { ?>
                                    $("#dialog-form").dialog('close');
<?php } ?>
                                carregarIndex(pagina);
                            }, 1);
                        }
                    }
                });
            });

            $("#bcomprafechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bcomprafechar").click(function () {
                $("#dialog-form").dialog('close');
            });

            $("#incluiritem").button({
                icons: {primary: "ui-icon-circle-plus"}
            });
            $("#incluiritem").click(function () {
                var compraid = $("#controleestoquecompraid").val();
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/estoquemovimentocontroler.php",
                    data: $("#compraitemform").serialize(),
                    dataType: "text",
                    cache: false,
                    success: function (html) {
                        if (html !== "sucesso") {
                            updateTips(html);
                        } else {
                            setTimeout(function () {
                                var param = {compraid: compraid, situacao: "<?= $situacao ?>"};
                                carregarProdutoitemTable(param);
                                $("#requisicaoitemform").each(function () {
                                    this.reset();
                                });
                                $("#produtoselect").combobox("refresh");
                                refreshItem();
                                removeTips();
                            }, 1);
                        }
                    }
                });
            });

            $("#acao-reprovar").button({icons: {primary: "ui-icon-newwin"}});
            $("#acao-reprovar .ui-button-text").css('font-size', '0.9em');

            $("#acao-reprovar").click(function () {
                var event = $(this).attr("data-evento");
                var title = $(this).attr("data-titulo");
                var id = $(this).attr("data-id");
                var param = {id: id, situacao: "reprovar"};
                openSubform(event, title, param);
            });

            if ($("#compraid").val() !== "") {
                compraid = $("#compraid").val();
                var param = {compraid: compraid, situacao: "<?= $situacao ?>"};
                carregarProdutoitemTable(param);
                $(".desabilitado").hide();
            }

            if (situacao !== "novo" && situacao !== "editar") {
                $("#fornecedorselect").combobox("disable");
                $("#fornecedorselect-button").removeClass("ui-state-disabled");
                $("#compraemissao").attr("disabled", "disabled");
            }

            $("#produtoid, #produtound, #produtoestoqueatual").keydown(function () {
                return false;
            });

        });
    </script>
    <p class="validateTips">
        <?php if ($situacao != "consultar") { ?>
            Todos os campos com * são obrigatórios.
<?php } ?>
    </p>

    <fieldset class="ui-widget-content">

        <form id="compraform">
            <div class="ui-widget-header form-header">Dados do Pedido de Compra</div>
            <div class="linha-form" style="width: 710px;">
                <div class="coluna-form" style="width: 380px;">
                    <label> Fornecedor* </label>
                    <select 
                        id="fornecedorselect" 
                        name="fornecedorid" 
                        title="Fornecedor da Compra" 
                        required="required" >
                        <?php if ($compra->getFornecedorid() == "") { ?>
                            <option selected="selected" disabled="disabled"></option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($fornecedores); $i++) { ?>
                            <option
                                <?php if ($compra->getFornecedorid() == $fornecedores[$i]->getFornecedorid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $fornecedores[$i]->getFornecedorid() ?>"><?= $fornecedores[$i]->getFantazia(TRUE) ?></option>
<?php } ?>
                    </select>
                </div>
                <div class="coluna-form">
                    <label> Data* </label>
                    <input 
                        id="compraemissao"
                        name="compraemissao" 
                        type="text" 
                        title="Data de emissão da compra" 
                        style="width: 100px"
                        maxlength="10"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" <?php if ($compra->getCompraemissao(TRUE) != "") { ?>
                            value="<?= $compra->getCompraemissao(TRUE) ?>"
                        <?php } else { ?>
                            value="<?= util::getData() ?>"
                        <?php } if ($compra->getComprasituacao() > 1 && $situacao != "") { ?>
                            disabled="disabled"
<?php } ?>
                        >
                </div>
            </div>
            <div class="linha-form">
<?php if ($situacao == "aprovar" || $situacao == "consultar") { ?>
                    <div class="coluna-form">
                        <label> Data da Aprovação* </label>
                        <input 
                            id="compraaprovacao"
                            name="compraaprovacao" 
                            type="text" 
                            title="Data de Aprovação da compra" 
                            size="16"
                            maxlength="10"
                            required="required" 
                            class="text ui-widget-content ui-corner-all" 
                            <?php if ($compra->getCompraaprovacao(TRUE) != "") { ?>
                                value="<?= $compra->getCompraaprovacao(TRUE) ?>"
                            <?php } else if ($situacao != "consultar") { ?>
                                value="<?= util::getData() ?>"
                            <?php } if ($situacao == "consultar" || $situacao == "receber") { ?>
                                disabled="disabled"
    <?php } ?>
                            >
                    </div>
<?php } if ($situacao == "receber" || $situacao == "consultar") { ?>
                    <div class="coluna-form">
                        <label> Data do Recebimento* </label>
                        <input 
                            id="comprarecebimento"
                            name="comprarecebimento" 
                            type="text" 
                            title="Data do recebimento da compra" 
                            size="16"
                            maxlength="10"
                            required="required" 
                            class="text ui-widget-content ui-corner-all" 
                            <?php if ($compra->getCompraentrega(TRUE) != "") { ?>
                                value="<?= $compra->getCompraentrega(TRUE) ?>"
                            <?php } else if ($situacao != "consultar") { ?>
                                value="<?= util::getData() ?>"
                            <?php } if ($situacao == "consultar") { ?>
                                disabled="disabled"
    <?php } ?>
                            >
                    </div>
<?php } if ($compra->getComprasituacao() == 4) { ?>
                    <div class="coluna-form">
                        <label> Data da Reprovação </label>
                        <input 
                            type="text" 
                            title="Data da reprovação da compra" 
                            size="16"
                            maxlength="10"
                            class="text ui-widget-content ui-corner-all" 
                            <?php if ($compra->getComprareprovacao(TRUE) != "") { ?>
                                value="<?= $compra->getComprareprovacao(TRUE) ?>"
                            <?php } if ($situacao == "consultar") { ?>
                                disabled="disabled"
    <?php } ?>
                            >
                    </div>
<?php } ?>
                <div style="float: right;">
                    <div class="coluna-form">
                        <div class="button-form">
                            <div>
<?php if ($situacao != "consultar") { ?>
                                    <button 
                                        id="bcomprasubmit" 
                                        role="button">
                                        <?php if ($situacao == "novo") { ?>
                                            Gravar
                                            <?php
                                        } else {
                                            echo ucfirst($situacao);
                                        }
                                        ?>
                                    </button>
<?php } if ($compra->getComprasituacao() == 4) { ?>
                                    <button 
                                        id="acao-reprovar" 
                                        data-evento="comprareprovar" 
                                        data-titulo="Informações Sobre a Reprovação" 
                                        data-id="<?= $compra->getCompraid() ?>">
                                        Motivo da Reprovação
                                    </button>
<?php } ?>
                            </div>
                        </div>
                        <div>
                            <button 
                                id="bcomprafechar" 
                                type="reset" 
                                style="margin-bottom: 12px;"
                                role="button">
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <input 
                type="hidden" 
                id="compraid"
                name="compraid" 
                value="<?= $compra->getCompraid() ?>" >
            <input 
                type="hidden" 
                id="control"
                name="control" 
                value="<?= $situacao ?>" >

        </form>
    </fieldset>
    <div id="itemcontainer" style="width: 710px;">
        <fieldset class="ui-widget-content">
            <div class="ui-widget-header form-header">Itens do Pedido de Compra</div>
<?php if ($situacao == "novo" || $situacao == "editar") { ?>
                <form id="compraitemform">
                    <div class="linha-form">
                        <div class="coluna-form">
                            <label> Código* </label>
                            <input 
                                id="produtoid"
                                name="produtoid" 
                                type="text" 
                                title="Código do Produto" 
                                style="width: 102px"
                                maxlength="10"
                                required="required" 
                                class="text ui-widget-content ui-corner-all" 
                                value="">
                        </div>
                        <div class="coluna-form" style="width: 380px;">
                            <label> Descrição do Produto* </label>
                            <select 
                                id="produtoselect" 
                                title="Produto da Compra" 
                                required="required" >
                                <option selected="selected" disabled="disabled" value="">Selecione...</option>
    <?php for ($i = 0; $i < count($produtos); $i++) { ?>
                                    <option 
                                        data-und="<?= $produtos[$i]->getUnd() ?>"
                                        data-customedio="<?php
                                        if ($produtos[$i]->getValormedio() == "") {
                                            echo $produtos[$i]->getCustomedio("form");
                                        } else {
                                            echo $produtos[$i]->getValormedio("form");
                                        }
                                        ?>"
                                        data-estoqueatual="<?= $produtos[$i]->getEstoqueatual("form") ?>"
                                        value="<?= $produtos[$i]->getProdutoid() ?>"><?= $produtos[$i]->getProdutonome(TRUE) ?></option>
    <?php } ?>
                            </select>
                        </div>
                        <div class="coluna-form">
                            <label> Unidade </label>
                            <input 
                                id="produtound"
                                type="text" 
                                title="Unidade de Medida do Produto" 
                                style="width: 102px"
                                maxlength="10"
                                class="text ui-widget-content ui-corner-all" 
                                value="">
                        </div>
                    </div>
                    <div class="linha-form">
                        <div class="coluna-form">
                            <label> Quant. Atual </label>
                            <input 
                                id="produtoestoqueatual"
                                type="text" 
                                title="Quant. Atual do Produto no Estoque" 
                                maxlength="10"
                                disabled="disabled"
                                placeholder="0,000"
                                style="text-align: right; width: 121px;"
                                class="text ui-widget-content ui-corner-all" 
                                value="">
                        </div>
                        <div class="coluna-form">
                            <label> Quantidade *</label>
                            <input 
                                id="controleestoquequantidade"
                                name="estoquemovimentoquantidade" 
                                type="text" 
                                title="Quantidade de Unidade do Produto" 
                                maxlength="10"
                                required="required" 
                                placeholder="0,000"
                                style="text-align: right; width: 121px;"
                                class="text ui-widget-content ui-corner-all" 
                                value="">
                        </div>
                        <div class="coluna-form">
                            <label> Valor Unitário R$</label>
                            <input 
                                id="controleestoquevalor"
                                name="estoquemovimentovalorunitario" 
                                type="text" 
                                title="Valor unitário do Produto" 
                                maxlength="10"
                                placeholder="R$ 0,00"
                                style="text-align: right;width: 121px"
                                class="text ui-widget-content ui-corner-all" 
                                value="">
                        </div>
                        <div class="coluna-form">
                            <label> Valor Total R$</label>
                            <input 
                                id="controleestoquetotal"
                                type="text" 
                                title="Quantidade de Unidade do Produto" 
                                maxlength="10"
                                disabled="disabled"
                                placeholder="R$ 0,00"
                                style="text-align: right;width: 121px"
                                class="text ui-widget-content ui-corner-all" 
                                value="">
                            <input 
                                type="hidden" 
                                id="controleestoquecompraid"
                                name="estoquemovimentocompraid" 
                                value="<?= $compra->getCompraid() ?>" >
                            <input
                                type="hidden"
                                name="control"
                                value="incluiritem">
                        </div>
                        <div class="coluna-form">
                            <div class="button-form" style="position: relative; margin-bottom: 5px;">
                                <div class="ui-dialog-buttonset">
                                    <button 
                                        id="incluiritem"
                                        type="button"
                                        role="button">
                                        Incluir Item
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
<?php } ?>
            <div class="linha-form">
                <div id="produtoitem" style="margin: 0 0 0.5em; height: 181px;">
<?php if ($compra->getCompraid() != "") { ?>
                        <div style="text-align: center;">
                            <img src="imagens/carregando.gif">
                        </div>
<?php } ?>
                </div>
            </div>
            <div class="desabilitado" title="Clique em Gravar para abilitar os itens do pedido."></div>
        </fieldset>
    </div>
</div>