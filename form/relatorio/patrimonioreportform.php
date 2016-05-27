<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/patrimonioAction.php';
require_once '../action/fornecedorAction.php';
require_once '../action/localAction.php';
require_once '../action/departamentoAction.php';
require_once '../action/produtoAction.php';

$patrimonio = new patrimonioModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $patrimonio->setPatrimonioid($_GET['id']);
    $patrimonio = patrimonioAction::getPatrimonio($patrimonio);
}

$produtos = new produtoModel();
$produtos = produtoAction::listProduto();

$fornecedores = new fornecedorModel();
$fornecedores = fornecedorAction::listFornecedor();

$locais = new localModel();
$locais = localAction::listLocal();

$estadoconservacoes = Array('Excelente', 'Bom', 'Médio', 'Mau', 'Péssimo', 'Não se aplica');

$departamentos = array();
if ($patrimonio->getLocalid() != "") {
    $departamentos = departamentoAction::listdepartamentoToLocal($patrimonio->getLocalid());
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>
<style>
    label{
        text-align: right;
        width: 170px;
        display: inline-block;
    }
    input{
        display: inline;
    }
    input.text{
        margin-bottom: 0;
    }
    .linha-form{
        height: 45px;
    }
    .tablereport td{
        height: 45px;
        vertical-align: middle;
    }
    .custom-combobox{
        margin-bottom: 0px;
    }
</style>

<div id="patrimoniobody">
    <?php include_once '../script.php'; ?>
    <script>
        $(function () {

            $("#fornecedorselect, #produtoselect, #departamentoTolocalselect, #localselect").combobox();

            $("#estadoconservacao").selectmenu({width: '18.5em'}).selectmenu("menuWidget").css('background', 'white none repeat scroll 0 0');

            $("#bpatrimoniosubmit").button({
                icons: {primary: "ui-icon-print"}
            });
            $(".select-plus").button({
                icons: {primary: "ui-icon-circle-plus"},
                text: false
            });
            $(".select-plus .ui-button-text").css("padding", "1.05em");

            $("#lote").button().click(function () {
                if ($(this).is(':checked')) {
                    $("#divpatrimonioid").hide();
                    $("#divlote").show();
                    $("#patrimoniosids").select();
                } else {
                    $("#divlote").hide();
                    $("#divpatrimonioid").show();
                    $("#patrimonioid").select();
                }
            });

            $(".data").datepicker({
                dateFormat: "dd/mm/yy"
            });

            $("#valor").maskMoney({
                prefix: 'R$ ',
                allowNegative: false,
                thousands: '',
                decimal: ',',
                affixesStay: false
            });
            $("#notafiscal").maskMoney({
                allowNegative: false,
                thousands: '',
                decimal: '',
                affixesStay: false
            });

            $("#localselect").combobox({
                select: function (event, ui) {
                    if ($("#localselect").val() !== localid) {
                        localid = $("#localselect").val();
                        carregarSelect("departamentoTolocalselect", localid);
                        //$("#departamentoTolocalselect").combobox("refresh");
                    }
                }
            });

            var tips = $("#patrimoniobody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }

//            $("#bpatrimoniosubmit").click(function () {
//                $.ajax({
//                    type: "POST",
//                    url: "<?= $urlroot ?>/controler/patrimoniocontroler.php",
//                    data: $("#patrimonioform").serialize(),
//                    dataType: "text",
//                    cache: false,
//                    success: function (html) {
//                        if (html !== "sucesso") {
//                            updateTips(html);
//                        } else {
//                            $("#dialog-form").dialog('close');
//                            setTimeout(function () {
//                                carregarIndex(pagina);
//                            }, 1);
//                        }
//                    }
//                });
//            });

            $("#bpatrimoniolimpar").button({
                icons: {primary: "ui-icon-closethick"}
            }).click(function () {
                $("#patrimonioreportform").each(function () {
                    this.reset();
                });
            });
        });
    </script>
    <div class="toolbar" style="font-size: 1.3em;"><b>Relatório de Patrimônios</b></div>

    <form id="formsearch">
        <fieldset>
            <table class="tablereport">
                <tr>
                    <td></td>
                    <td colspan="3">
                        <div>
                            <button 
                                id="bpatrimoniolimpar" 
                                type="reset" 
                                role="button">
                                Limpar
                            </button>
                            <button 
                                id="bimprimirfiltro" 
                                data-evento="patrimonio" 
                                data-titulo="Relatório de Patrimônio">
                                Gerar Relatório
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label> Tombamento: </label>
                    </td>
                    <td colspan="3">
                        <input
                            id="patrimonioid"
                            type="text" 
                            title="Tombamento do Patrimônio" 
                            style="width: 100px;"
                            maxlength="10"
                            name="patrimonioid" 
                            class="text ui-widget-content ui-corner-all" 
                            autofocus="true">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label> Produto: </label>
                    </td>
                    <td colspan="3">
                        <select 
                            id="produtoselect" 
                            name="produtoid" 
                            title="Produto do Patrimônio" >
                                <?php if ($patrimonio->getProdutoid() == "") { ?>
                                <option selected="selected">Selecione...</option>
                            <?php } ?>
                            <?php for ($i = 0; $i < count($produtos); $i++) { ?>
                                <option
                                <?php if ($patrimonio->getProdutoid() == $produtos[$i]->getProdutoid()) { ?>
                                        selected="selected"
                                    <?php } ?>
                                    value="<?= $produtos[$i]->getProdutoid() ?>"><?= $produtos[$i]->getProdutonome(TRUE) ?></option>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label> Local: </label>
                    </td>
                    <td colspan="3">
                        <select 
                            id="localselect" 
                            name="localid" 
                            title="Local do Patrimônio" >
                                <?php if ($patrimonio->getLocalid() == "") { ?>
                                <option selected="selected">Selecione...</option>
                            <?php } ?>
                            <?php for ($i = 0; $i < count($locais); $i++) { ?>
                                <option
                                <?php if ($patrimonio->getLocalid() == $locais[$i]->getLocalid()) { ?>
                                        selected="selected"
                                    <?php } ?>
                                    value="<?= $locais[$i]->getLocalid() ?>"><?= $locais[$i]->getLocalnome(TRUE) ?></option>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label> Departamento: </label>
                    </td>
                    <td colspan="3">
                        <select 
                            id="departamentoTolocalselect" 
                            name="departamentoid" 
                            title="Departamento do Patrimônio" >
                                <?php if ($patrimonio->getDepartamentoid() == "") { ?>
                                <option selected="selected">Selecione um local</option>
                            <?php } ?>
                            <?php for ($i = 0; $i < count($departamentos); $i++) { ?>
                                <option
                                <?php if ($patrimonio->getDepartamentoid() == $departamentos[$i]->getDepartamentoid()) { ?>
                                        selected="selected"
                                    <?php } ?>
                                    value="<?= $departamentos[$i]->getDepartamentoid() ?>"><?= $departamentos[$i]->getDepartamentonome(TRUE) ?></option>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label> Fornecedor: </label>
                    </td>
                    <td colspan="3">
                        <select 
                            id="fornecedorselect" 
                            name="fornecedorid" 
                            title="Fornecedor do Patrimônio" >
                                <?php if ($patrimonio->getFornecedorid() == "") { ?>
                                <option selected="selected">Selecione...</option>
                            <?php } ?>
                            <?php for ($i = 0; $i < count($fornecedores); $i++) { ?>
                                <option
                                <?php if ($patrimonio->getFornecedorid() == $fornecedores[$i]->getFornecedorid()) { ?>
                                        selected="selected"
                                    <?php } ?>
                                    value="<?= $fornecedores[$i]->getFornecedorid() ?>"><?= $fornecedores[$i]->getFantazia(TRUE) ?></option>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label> Estado de Conservação: </label>
                    </td>
                    <td colspan="3">
                        <select 
                            id="estadoconservacao" 
                            name="estadoconservacao">
                                <?php if ($patrimonio->getEstadoconservacao() == "") { ?>
                                <option selected="selected">Selecione...</option>
                            <?php } ?>
                            <?php for ($i = 0; $i < count($estadoconservacoes); $i++) { ?>
                                <option
                                <?php if ($patrimonio->getEstadoconservacao() == $estadoconservacoes[$i]) { ?>
                                        selected="selected"
                                    <?php } ?>
                                    value="<?= $estadoconservacoes[$i] ?>"><?= $estadoconservacoes[$i] ?></option>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label> Data da Compra Inicial: </label>
                    </td>
                    <td style="width: 0px;">
                        <input 
                            type="text" 
                            name="datacomprainicial" 
                            title="Data de compra do patrimônio" 
                            style="width: 100px;"
                            maxlength="20"
                            class="text ui-widget-content ui-corner-all data" 
                            value="<?= $patrimonio->getDatacompra(TRUE) ?>">
                    </td>
                    <td style="width: 0px;">
                        <span> Final: </span>
                    </td>
                    <td>
                        <input 
                            type="text" 
                            name="datacomprafinal" 
                            title="Data de compra do patrimônio" 
                            style="width: 100px;"
                            maxlength="20"
                            class="text ui-widget-content ui-corner-all data" 
                            value="<?= $patrimonio->getDatacompra(TRUE) ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label> Data Implantação Inicial: </label>
                    </td>
                    <td>
                        <input 
                            type="text" 
                            name="dataimplantacaoinicial" 
                            title="Data de implantação do patrimônio" 
                            style="width: 100px;"
                            maxlength="20"
                            class="text ui-widget-content ui-corner-all data" 
                            value="<?= $patrimonio->getDataimplantacao(TRUE) ?>">
                    </td>
                    <td>
                        <span> Final: </span>
                    </td>
                    <td>
                        <input 
                            type="text" 
                            name="dataimplantacaofinal"
                            title="Data de compra do patrimônio" 
                            style="width: 100px;"
                            maxlength="20"
                            class="text ui-widget-content ui-corner-all data" 
                            value="<?= $patrimonio->getDatacompra(TRUE) ?>">
                    </td>   
                </tr>
                <tr>
                    <td>
                        <label> Fim da Garantia Inicial: </label>
                    </td>
                    <td>
                        <input 
                            type="text" 
                            name="fimgarantiainicial" 
                            title="Data de implantação do patrimônio" 
                            style="width: 100px;"
                            maxlength="20"
                            class="text ui-widget-content ui-corner-all data" 
                            value="<?= $patrimonio->getFimgarantia(TRUE) ?>">
                    </td>
                    <td>
                        <span> Final: </span>
                    </td>
                    <td>
                        <input 
                            type="text" 
                            name="fimgarantiafinal" 
                            title="Data de compra do patrimônio" 
                            style="width: 100px;"
                            maxlength="20"
                            class="text ui-widget-content ui-corner-all  data" 
                            value="<?= $patrimonio->getDatacompra(TRUE) ?>">
                    </td>
                </tr>
                <tr>
                    <td> <label>Exibir observações: <label> </td>
                                <td colspan="3">
                                    <input type="checkbox" name="exibeobs" style="position: absolute; margin-top: -16px;">
                                </td>
                                </tr>
                                </table>
                                <input 
                                    type="hidden" 
                                    id="control"
                                    name="control" 
                                    value="relatorio" >
                                </fieldset>
                                </form>
                                </div>