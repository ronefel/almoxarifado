<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/estoquemovimentoAction.php';
require_once '../action/fornecedorAction.php';
require_once '../action/produtoAction.php';

$estoquemovimento = new estoquemovimentoModel();
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $estoquemovimento->setEstoquemovimentoid($_GET['id']);
    $estoquemovimento = estoquemovimentoAction::getEstoquemovimento($estoquemovimento);
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

<div id="estoquemovimentobody">
    <script>
        $(function () {

            var situacao = "";
<?php if ($situacao != "") { ?>
                situacao = "<?= $situacao ?>";
<?php } ?>

            //$("#produtoselect").selectmenu({width: '34em'}).selectmenu("menuWidget").addClass("overflow");
            $("#produtoselect").combobox();
            $("#radio").buttonset();
            $("#bestoquemovimentosubmit").button({
                icons: {primary: "ui-icon-disk"}
            });

            $("#estoquemovimentodata").datepicker({
                dateFormat: "dd/mm/yy"
            });
            
            if (situacao === "consultar") {
                $("#bestoquemovimentosubmit").button("option", "disabled", true);
                $("#produtoselect").combobox("disable");
                $(".ui-selectmenu-button").css("opacity", "1");
                $("#estoquemovimentodata").datepicker( "option", "disabled", true );
            }

            $("#produtoselect").combobox({
                select: function (event, ui) {
                    var option = $("#produtoselect option:selected");
                    if (option.val() !== "") {
                        $("#produtoid").val(option.val());
                        $("#produtound").val(option.attr("data-und"));
                        $("#estoquemovimentoquantidade").val("1,000");
                        $("#produtoestoqueatual").val(option.attr("data-estoqueatual"));
                        $("#estoquemovimentovalor").val(option.attr("data-customedio"));
                        total();
                    }
                }
            });

            $("#estoquemovimentovalor, #estoquemovimentototal").maskMoney({
                prefix: 'R$ ',
                allowNegative: false,
                thousands: '',
                decimal: ',',
                affixesStay: false
            });
            $("#produtoestoqueatual, #estoquemovimentoquantidade").maskMoney({
                allowNegative: false,
                thousands: '',
                decimal: ',',
                affixesStay: false,
                precision: 3
            });

            $("#estoquemovimentoquantidade, #estoquemovimentovalor").change(function () {
                total();
            });

            function total() {
                var quantidade = $("#estoquemovimentoquantidade").val().replace(',', '.');
                var valor = $("#estoquemovimentovalor").val().replace(',', '.');
                var total = quantidade * valor;
                $("#estoquemovimentototal").val(total.toFixed(2));
            }


            var tips = $("#estoquemovimentobody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }
            function removeTips(t) {
                tips.html(t).removeClass("ui-state-error");
            }

            $("#estoquemovimentoitemform").submit(function () {
                return false;
            });

            $("#bestoquemovimentofechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bestoquemovimentofechar").click(function () {
                $("#dialog-form").dialog('close');
            });

            $("#bestoquemovimentosubmit").click(function () {
                if (situacao !== "consultar") {
                    $.ajax({
                        type: "POST",
                        url: "<?= $urlroot ?>/controler/estoquemovimentocontroler.php",
                        data: $("#estoquemovimentoitemform").serialize(),
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
                }
            });


            $("#produtoid, #produtound, #produtoestoqueatual").keydown(function () {
                return false;
            });

        });
    </script>
    <p class="validateTips">
        Todos os campos com * são obrigatórios.
    </p>
    <div id="itemcontainer">
        <form id="estoquemovimentoitemform">
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Código* </label>
                    <input 
                        id="produtoid"
                        name="produtoid" 
                        type="text" 
                        title="Código do Produto" 
                        style="width: 80px"
                        maxlength="10"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $estoquemovimento->getProdutoid() ?>">
                </div>
                <div class="coluna-form" style="width: 380px;">
                    <label> Descrição do Produto* </label>
                    <select 
                        id="produtoselect" 
                        title="Produto da Estoquemovimento" 
                        required="required" >
                        <option selected="selected" disabled="disabled" value="">Selecione...</option>
                        <?php for ($i = 0; $i < count($produtos); $i++) { ?>
                            <option 
                                data-und="<?= $produtos[$i]->getUnd(TRUE) ?>"
                                data-customedio="<?= $produtos[$i]->getCustomedio("form") ?>"
                                data-estoqueatual="<?= $produtos[$i]->getEstoqueatual("form") ?>"
                                value="<?= $produtos[$i]->getProdutoid() ?>"
                                <?php if ($estoquemovimento->getProdutoid() == $produtos[$i]->getProdutoid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                ><?= $produtos[$i]->getProdutonome(TRUE) ?></option>
                            <?php } ?>
                    </select>
                </div>
                <div class="coluna-form">
                    <label> Unidade </label>
                    <input 
                        id="produtound"
                        type="text" 
                        title="Unidade de Medida do Produto" 
                        style="width: 80px"
                        maxlength="10"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $estoquemovimento->getUnd() ?>">
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
                        placeholder="0,000"
                        style="text-align: right; width: 120px;"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $estoquemovimento->getEstoqueatual("form") ?>">
                </div>
                <div class="coluna-form">
                    <label> Quantidade *</label>
                    <input 
                        id="estoquemovimentoquantidade"
                        name="estoquemovimentoquantidade" 
                        type="text" 
                        title="Quantidade de Unidade do Produto" 
                        maxlength="10"
                        required="required" 
                        placeholder="0,000"
                        style="text-align: right; width: 120px;"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $estoquemovimento->getQuantidade("form") ?>">
                </div>
                <div class="coluna-form">
                    <label> Valor Unitário R$</label>
                    <input 
                        id="estoquemovimentovalor"
                        name="estoquemovimentovalorunitario" 
                        type="text" 
                        title="Valor unitário do Produto" 
                        maxlength="10"
                        placeholder="R$ 0,00"
                        style="text-align: right; width: 120px;"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $estoquemovimento->getValorunitario("form") ?>">
                </div>
                <div class="coluna-form">
                    <label> Valor Total R$</label>
                    <input 
                        id="estoquemovimentototal"
                        type="text" 
                        maxlength="10"
                        placeholder="R$ 0,00"
                        style="text-align: right; width: 110px;"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $estoquemovimento->getValortotal("form") ?>">
                </div>
                <div class="coluna-form">
                    <label> Data</label>
                    <input 
                        id="estoquemovimentodata"
                        name="estoquemovimentodata"
                        type="text" 
                        title="Data do Movimento" 
                        maxlength="10"
                        placeholder="dd/mm/aaaa"
                        style="text-align: right; width: 110px;"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= util::dateToBR($estoquemovimento->getEstoquemovimentodata()) ?>">
                </div>
                <input 
                    type="hidden" 
                    id="estoquemovimentoid"
                    name="estoquemovimentoid" 
                    value="<?= $estoquemovimento->getEstoquemovimentoid() ?>" >
                <input
                    type="hidden"
                    name="control"
                    value="<?= $situacao ?>">
            </div>

            <div class="linha-form">
                <div class="coluna-form">
                    <label> Operação</label>
                    <div id="radio">
                        <input 
                            type="radio" 
                            id="radio1" 
                            name="estoquemovimentooperacao" 
                            <?php if ($estoquemovimento->getOperacao() == 1) { ?>
                                checked="checked" 
                            <?php } ?>
                            value="1"><label for="radio1">Entrada</label>
                        <input 
                            type="radio" 
                            id="radio2" 
                            name="estoquemovimentooperacao" 
                            <?php if ($estoquemovimento->getOperacao() == 2) { ?>
                                checked="checked" 
                            <?php } ?>
                            value="2"><label for="radio2">Saída</label>
                    </div>
                </div>
            </div>
        </form>
        <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
            <div class="ui-dialog-buttonset">
                <button 
                    id="bestoquemovimentofechar" 
                    type="reset" 
                    role="button">
                    Fechar
                </button>
                <button 
                    id="bestoquemovimentosubmit" 
                    type="submit" 
                    role="button">
                    Salvar
                </button>
            </div>
        </div>
    </div>
</div>