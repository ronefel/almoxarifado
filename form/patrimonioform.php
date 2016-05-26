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

if ($patrimonio->getLocalid() != "") {
    $departamentos = departamentoAction::listdepartamentoToLocal($patrimonio->getLocalid());
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="patrimoniobody">
    <script>
        $(function () {

            $("#fornecedorselect, #produtoselect, #departamentoTolocalselect, #localselect").combobox();

            $("#estadoconservacao").selectmenu({width: '18.5em'}).selectmenu("menuWidget").css('background', 'white none repeat scroll 0 0');

            $("#bpatrimoniosubmit").button({
                icons: {primary: "ui-icon-disk"}
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

            $("#datacompra, #dataimplantacao, #fimgarantia").datepicker({
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

            $(".select-plus").click(function () {
                var event = $(this).attr("data-evento");
                var title = $(this).attr("data-titulo");
                var param = {"localid": $("#localselect").val(), "situacao": "novo"};
                select = $(this).attr("data-id");
                openSubform(event, title, param);

            });

            var tips = $("#patrimoniobody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }

            $("#bpatrimoniosubmit").click(function () {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/patrimoniocontroler.php",
                    data: $("#patrimonioform").serialize(),
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

            $("#bpatrimoniofechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bpatrimoniofechar").click(function () {
                $("#dialog-form").dialog('close');
            });
        });
    </script>
    <p class="validateTips">Todos os campos com * são obrigatórios.</p>

    <form id="patrimonioform">
        <fieldset>
            <?php if ($situacao == "novo") { ?>
                <div class="linha-form" style="height: 3em;">
                    <div class="coluna-form">
                        <input id="lote"
                               name="lote"
                               type="checkbox">                    
                        <label for="lote">Cadastrar em Lote</label>
                    </div>
                </div>
            <?php } ?>
            <div class="linha-form">
                <div id="divpatrimonioid" class="coluna-form">
                    <label> Tombamento* </label>
                    <input
                        id="patrimonioid"
                        type="text" 
                        title="Tombamento do Patrimônio" 
                        style="width: 100px;"
                        maxlength="10"
                        <?php if ($situacao == "editar") { ?>
                            disabled="disabled"
                        <?php } else { ?>
                            name="patrimonioid" 
                            required="required"
                        <?php } ?>
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $patrimonio->getPatrimonioid(TRUE) ?>"
                        autofocus="true">
                </div>
                <?php if ($situacao == "novo") { ?>
                    <div id="divlote" class="coluna-form" style="display: none;">
                        <label> Tombamentos* <span class="ui-state-default" style="font-size: 12px;">ATENÇÃO: Digite os tombamentos separados por vírgula sem espaço ex: 11111,22222,33333</span></label>
                        <textarea 
                            id="patrimoniosids"
                            style="width: 42em; height: 2em;"
                            name="patrimoniosids" 
                            title="Cadastre vários tombamentos de uma só vez" 
                            class="text ui-widget-content ui-corner-all"><?= $patrimonio->getObs() ?></textarea>
                    </div>
                <?php } ?>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Produto* </label>
                    <select 
                        id="produtoselect" 
                        name="produtoid" 
                        title="Produto do Patrimônio" >
                            <?php if ($patrimonio->getProdutoid() == "") { ?>
                            <option selected="selected" disabled="disabled"></option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($produtos); $i++) { ?>
                            <option
                            <?php if ($patrimonio->getProdutoid() == $produtos[$i]->getProdutoid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $produtos[$i]->getProdutoid() ?>"><?= $produtos[$i]->getProdutonome(TRUE) ?></option>
                            <?php } ?>
                    </select>
                </div>
                <div class="coluna-form">
                    <label> Local </label>
                    <select 
                        id="localselect" 
                        name="localid" 
                        title="Local do Patrimônio" >
                            <?php if ($patrimonio->getLocalid() == "") { ?>
                            <option selected="selected" disabled="disabled"></option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($locais); $i++) { ?>
                            <option
                            <?php if ($patrimonio->getLocalid() == $locais[$i]->getLocalid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $locais[$i]->getLocalid() ?>"><?= $locais[$i]->getLocalnome(TRUE) ?></option>
                            <?php } ?>
                    </select>
                    <div class="select-plus" 
                         title="Cadastrar novo Local"
                         data-id="localselect"
                         data-titulo="Cadastrar Local" 
                         data-evento="local"></div>
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Fornecedor </label>
                    <select 
                        id="fornecedorselect" 
                        name="fornecedorid" 
                        title="Fornecedor do Patrimônio" >
                            <?php if ($patrimonio->getFornecedorid() == "") { ?>
                            <option selected="selected" disabled="disabled"></option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($fornecedores); $i++) { ?>
                            <option
                            <?php if ($patrimonio->getFornecedorid() == $fornecedores[$i]->getFornecedorid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $fornecedores[$i]->getFornecedorid() ?>"><?= $fornecedores[$i]->getFantazia(TRUE) ?></option>
                            <?php } ?>
                    </select>
                </div>
                <div class="coluna-form">
                    <label> Departamento </label>
                    <select 
                        id="departamentoTolocalselect" 
                        name="departamentoid" 
                        title="Departamento do Patrimônio" >
                            <?php if ($patrimonio->getDepartamentoid() == "") { ?>
                            <option selected="selected" disabled="disabled">Selecione um local</option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($departamentos); $i++) { ?>
                            <option
                            <?php if ($patrimonio->getDepartamentoid() == $departamentos[$i]->getDepartamentoid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $departamentos[$i]->getDepartamentoid() ?>"><?= $departamentos[$i]->getDepartamentonome(TRUE) ?></option>
                            <?php } ?>
                    </select>
                    <div class="select-plus" 
                         title="Cadastrar novo departamento"
                         data-id="departamentoTolocalselect"
                         data-titulo="Cadastrar Departamento" 
                         data-evento="departamento"></div>
                </div>
            </div>
            <div class="linha-form">
                <!--                <div class="coluna-form">
                                    <label> Número de Série </label>
                                    <input 
                                        type="text" 
                                        name="serie" 
                                        title="Número de Série do patrimônio" 
                                        style="width: 100px;"
                                        maxlength="20"
                                        class="text ui-widget-content ui-corner-all" 
                                        value="<?= $patrimonio->getSerie(TRUE) ?>">
                                </div>-->
                <!--                <div class="coluna-form">
                                    <label> Nota Fiscal </label>
                                    <input 
                                        id="notafiscal"
                                        type="text" 
                                        name="notafiscal" 
                                        title="Nota Fiscal do Patrimonio" 
                                        style="width: 100px;"
                                        maxlength="20"
                                        class="text ui-widget-content ui-corner-all" 
                                        value="<?= $patrimonio->getNotafiscal(TRUE) ?>">
                                </div>-->
                <div class="coluna-form">
                    <label> Estado de Conservação* </label>
                    <select 
                        id="estadoconservacao" 
                        name="estadoconservacao"
                        required="required">
                            <?php if ($patrimonio->getEstadoconservacao() == "") { ?>
                            <option selected="selected" disabled="disabled">Selecione...</option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($estadoconservacoes); $i++) { ?>
                            <option
                            <?php if ($patrimonio->getEstadoconservacao() == $estadoconservacoes[$i]) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $estadoconservacoes[$i] ?>"><?= $estadoconservacoes[$i] ?></option>
                            <?php } ?>
                    </select>
                </div>
                <div class="coluna-form">
                    <label> Valor R$</label>
                    <input 
                        id="valor"
                        type="text" 
                        name="valor" 
                        title="Valor do Patrimonio" 
                        style="width: 100px;"
                        maxlength="20"
                        placeholder="R$ 0.00"
                        class="text ui-widget-content ui-corner-all" 
                        style="text-align: right;"
                        value="<?= $patrimonio->getValor("form") ?>">
                </div>                
                <div class="coluna-form">
                    <label> Data da Compra </label>
                    <input 
                        type="text" 
                        name="datacompra" 
                        id="datacompra"
                        title="Data de compra do patrimônio" 
                        style="width: 100px;"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $patrimonio->getDatacompra(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Data Implantação </label>
                    <input 
                        type="text" 
                        name="dataimplantacao" 
                        id="dataimplantacao"
                        title="Data de implantação do patrimônio" 
                        style="width: 100px;"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $patrimonio->getDataimplantacao(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Fim da Garantia </label>
                    <input 
                        type="text" 
                        name="fimgarantia" 
                        id="fimgarantia"
                        title="Data de implantação do patrimônio" 
                        style="width: 100px;"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $patrimonio->getFimgarantia(TRUE) ?>">
                </div>
            </div>
            <label> Observações </label>
            <textarea 
                style="width: 51em; height: 4em;"
                name="obs" 
                title="Observações sobre o Patrimonio" 
                class="text ui-widget-content ui-corner-all"><?= $patrimonio->getObs() ?></textarea>
                <?php if ($situacao == "editar") { ?>
                <input 
                    type="hidden" 
                    name="patrimonioid" 
                    value="<?= $patrimonio->getPatrimonioid() ?>" >
                <?php } ?>
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
                id="bpatrimoniofechar" 
                type="reset" 
                role="button">
                Fechar
            </button>
            <button 
                id="bpatrimoniosubmit" 
                type="submit" 
                role="button">
                Salvar
            </button>
        </div>
    </div>
</div>