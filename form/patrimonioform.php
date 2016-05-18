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

$patrimonio = new patrimonioModel();

$fornecedores = new fornecedorModel();
$fornecedores = fornecedorAction::listFornecedor();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $patrimonio->setPatrimonioid($_GET['id']);
    $patrimonio = patrimonioAction::getPatrimonio($patrimonio);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="patrimoniobody">
    <script>
        $(function () {
            
            $("#fornecedorselect").combobox();

            $("#estadoconservacao").selectmenu({width: '18.5em'}).selectmenu("menuWidget").css('background', 'white none repeat scroll 0 0');
            $("#patrimoniogruposelect").selectmenu({width: '48.5em'}).selectmenu("menuWidget").addClass("overflow");
            $("#patrimonioativo").selectmenu({width: '8em'});
            $("#bpatrimoniosubmit").button({
                icons: {primary: "ui-icon-disk"}
            });
            $(".select-plus").button({
                icons: {primary: "ui-icon-circle-plus"},
                text: false
            });
            $(".select-plus .ui-button-text").css("padding", "1.05em");

            $("#patrimoniodatacadastro").datepicker({
                dateFormat: "dd/mm/yy"
            });

            $("#patrimoniocustomedio").maskMoney({
                prefix: 'R$ ',
                allowNegative: false,
                thousands: '',
                decimal: ',',
                affixesStay: false
            });
            $("#patrimonioestoqueminimo, #patrimonioestoquemaximo").maskMoney({
                allowNegative: false,
                thousands: '',
                decimal: ',',
                affixesStay: false,
                precision: 3
            });
            $("#patrimoniocodigobarras").maskMoney({
                allowNegative: false,
                thousands: '',
                decimal: '',
                affixesStay: false
            });


            $("#patrimoniogruposelect").on("selectmenuselect", function () {
                if ($("#patrimoniogruposelect").val() !== grupoid) {
                    grupoid = $("#patrimoniogruposelect").val();
                    carregarSelect("patrimoniosubgrupoTogruposelect", grupoid);
                }
            });

            $(".select-plus").click(function () {
                var event = $(this).attr("data-evento");
                var title = $(this).attr("data-titulo");
                var param = $(this).attr("data-param");
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

            <div class="linha-form">
                <div class="coluna-form">
                    <label> Tombamento* </label>
                    <input 
                        type="text" 
                        name="patrimonioid" 
                        title="Tombamento do Patrimônio" 
                        size="10"
                        maxlength="10"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="">
                </div>       
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Descrição* </label>
                    <input 
                        type="text" 
                        name="patrimoniodescricao" 
                        title="Descrição do Patrimonio" 
                        size="82"
                        maxlength="100"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $patrimonio->getPatrimoniodescricao(TRUE) ?>">
                </div>                
            </div>


            <div class="linha-form">
                <div class="coluna-form">
                    <label> Categoria </label>
                    <input 
                        id="categoriaid"
                        type="text" 
                        name="categoriaid" 
                        title="Categoria do patrimonio" 
                        size="30"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all"
                        value=''>
                </div>
                <div class="coluna-form">
                    <label> Data da Compra </label>
                    <input 
                        type="text" 
                        name="datacompra" 
                        title="Data de compra do patrimônio" 
                        size="12"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        value=''>
                </div>
                <div class="coluna-form">
                    <label> Data Implantação </label>
                    <input 
                        type="text" 
                        name="dataimplantacao" 
                        title="Data de implantação do patrimônio" 
                        size="12"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        value=''>
                </div>
                <div class="coluna-form">
                    <label> Fim da Garantia </label>
                    <input 
                        type="text" 
                        name="fimgarantia" 
                        title="Data de implantação do patrimônio" 
                        size="12"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        value=''>
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Marca </label>
                    <input 
                        type="text" 
                        name="marcaid" 
                        title="Marca do Patrimonio" 
                        size="30"
                        maxlength="20"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="">
                </div>
                    <div class="select-plus" 
                         title="Cadastrar nova marca"
                         data-id="marcaselect"
                         data-titulo="Cadastrar Marca" 
                         data-evento="marca"
                         data-param="situacao=novo&dialog=dialog-subform"></div>
                <div class="coluna-form">
                    <label> Número de Série </label>
                    <input 
                        type="text" 
                        name="serie" 
                        title="Número de Série do patrimônio" 
                        size="12"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        value=''>
                </div>
                <div class="coluna-form">
                    <label> Nota Fiscal </label>
                    <input 
                        type="text" 
                        name="notafiscal" 
                        title="Nota Fiscal do Patrimonio" 
                        size="12"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        value="">
                </div>
                <div class="coluna-form">
                    <label> Valor R$</label>
                    <input 
                        id="valor"
                        type="text" 
                        name="valor" 
                        title="Valor do Patrimonio" 
                        size="12"
                        maxlength="20"
                        placeholder="R$ 0.00"
                        class="text ui-widget-content ui-corner-all" 
                        style="text-align: right;"
                        value="">
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Departamento </label>
                    <input 
                        type="text" 
                        name="departamentoid" 
                        title="Departamento do Patrimonio" 
                        size="30"
                        maxlength="20"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="">
                </div>
                <div class="coluna-form">
                    <label> Fornecedor* </label>
                    <select 
                        id="fornecedorselect" 
                        name="fornecedorid" 
                        title="Fornecedor do Patrimônio" 
                        required="required" >
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
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Estado de Conservação </label>
                    <select 
                        id="estadoconservacao" 
                        name="estadoconservacao">
                        <option value="Não se aplica">Não se aplica</option>
                        <option value="Excelente">Excelente</option>
                        <option value="Bom">Bom</option>
                        <option value="Médio">Médio</option>
                        <option value="Mau">Mau</option>
                        <option value="Péssimo">Péssimo</option>
                    </select>
                </div>
            </div>
            <label> Observações </label>
            <textarea 
                style="width: 49.5em; height: 4em;"
                name="patrimonioobservacao" 
                title="Observações sobre o Patrimonio" 
                class="text ui-widget-content ui-corner-all"><?= $patrimonio->getObs() ?></textarea>
            <input 
                type="hidden" 
                name="patrimonioid" 
                value="<?= $patrimonio->getPatrimonioid() ?>" >
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