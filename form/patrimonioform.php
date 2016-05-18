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
//require_once '../action/patrimoniosubgrupoAction.php';
//require_once '../action/patrimoniogrupoAction.php';

$patrimonio = new patrimonioModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $patrimonio->setPatrimonioid($_GET['id']);
    $patrimonio = patrimonioAction::getPatrimonio($patrimonio);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}

$patrimoniosubgrupos = array(new patrimoniosubgrupoModel());
if ($patrimonio->getPatrimoniogrupoid() != "") {

    $patrimoniosubgrupos = patrimoniosubgrupoAction::listpatrimoniosubgrupoToPatrimoniogrupo($patrimonio->getPatrimoniogrupoid());
}
?>

<div id="patrimoniobody">
    <script>
        $(function() {

            $("#patrimoniosubgrupoTogruposelect").selectmenu({width: '48.5em'}).selectmenu("menuWidget").addClass("overflow");
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


            $("#patrimoniogruposelect").on("selectmenuselect", function() {
                if ($("#patrimoniogruposelect").val() !== grupoid) {
                    grupoid = $("#patrimoniogruposelect").val();
                    carregarSelect("patrimoniosubgrupoTogruposelect", grupoid);
                }
            });

            $(".select-plus").click(function() {
                var event = $(this).attr("data-evento");
                var title = $(this).attr("data-titulo");
                var param = {"grupoid": $("#patrimoniogruposelect").val(), "situacao": "novo"};
                select = $(this).attr("data-id");
                grupoid = $("#patrimoniogruposelect").val();
                openSubform(event, title, param);

            });

            var tips = $("#patrimoniobody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }

            $("#bpatrimoniosubmit").click(function() {
                $.ajax({
                    type: "POST",
                    url: "<?=$urlroot?>/controler/patrimoniocontroler.php",
                    data: $("#patrimonioform").serialize(),
                    dataType: "text",
                    cache: false,
                    success: function(html) {
                        if (html !== "sucesso") {
                            updateTips(html);
                        } else {
                            $("#dialog-form").dialog('close');
                            setTimeout(function() {
                                carregarIndex(pagina);
                            }, 1);
                        }
                    }
                });
            });

            $("#bpatrimoniofechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bpatrimoniofechar").click(function() {
                $("#dialog-form").dialog('close');
            });

        });
    </script>
    <p class="validateTips">Todos os campos com * são obrigatórios.</p>

    <form id="patrimonioform">
        <fieldset>

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
                <div class="coluna-form">
                    <label> Unidade* </label>
                    <input 
                        type="text" 
                        name="patrimoniound" 
                        title="Unidade de Medida do patrimonio" 
                        size="10"
                        maxlength="10"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $patrimonio->getUnd(TRUE) ?>">
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Grupo de Patrimonios* </label>
                    <select 
                        id="patrimoniogruposelect" 
                        name="patrimoniogrupoid" 
                        title="Grupo de Patrimonio" 
                        required="required" >
                            <option selected="selected" disabled="disabled">Selecione...</option>
                        
                        
                    </select>
                    <div class="select-plus" 
                         title="Cadastrar Grupo de Patrimonio"
                         data-id="patrimoniogruposelect"
                         data-titulo="Cadastrar Grupo de Patrimonio" 
                         data-evento="patrimoniogrupo"></div>
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Subgrupo de Patrimonios* </label>
                    <select 
                        id="patrimoniosubgrupoTogruposelect" 
                        name="patrimoniosubgrupoid" 
                        title="Subgrupo de Patrimonio" 
                        required="required" >
                            
                            <option selected="selected" disabled="disabled">Selecione o grupo de patrimonios</option>
                        
                    </select>
                    <div class="select-plus" 
                         title="Cadastrar Subgrupo de Patrimonio"
                         data-id="patrimoniosubgrupoTogruposelect"
                         data-titulo="Cadastrar Subgrupo de Patrimonio" 
                         data-evento="patrimoniosubgrupo"></div>
                </div>
            </div>


            <div class="linha-form">
                <div class="coluna-form">
                    <label> Código de Barras </label>
                    <input 
                        id="patrimoniocodigobarras"
                        type="text" 
                        name="patrimoniocodigobarras" 
                        title="Código de Barras do Patrimonio" 
                        size="12"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        style="text-align: right;"
                        value="<?= $patrimonio->getCodigobarras(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Estoque Mínimo </label>
                    <input 
                        id="patrimonioestoqueminimo"
                        type="text" 
                        name="patrimonioestoqueminimo" 
                        title="Estoque mínimo do patrimonio" 
                        size="12"
                        maxlength="20"
                        placeholder="0,000"
                        class="text ui-widget-content ui-corner-all" 
                        style="text-align: right;"
                        value='<?= $patrimonio->getEstoqueminimo("form") ?>'>
                </div>
                <div class="coluna-form">
                    <label> Estoque Máximo </label>
                    <input 
                        id="patrimonioestoquemaximo"
                        type="text" 
                        name="patrimonioestoquemaximo" 
                        title="Estoque máximo do patrimonio" 
                        size="12"
                        maxlength="20"
                        placeholder="0,000"
                        class="text ui-widget-content ui-corner-all" 
                        style="text-align: right;"
                        value='<?= $patrimonio->getEstoquemaximo("form") ?>'>
                </div>
                <div class="coluna-form">
                    <label> Custo Médio R$</label>
                    <input 
                        id="patrimoniocustomedio"
                        type="text" 
                        name="patrimoniocustomedio" 
                        title="Custo médio do Patrimonio" 
                        size="12"
                        maxlength="20"
                        placeholder="R$ 0.00"
                        class="text ui-widget-content ui-corner-all" 
                        style="text-align: right;"
                        value="<?= $patrimonio->getCustomedio("form") ?>">
                </div>
                <div class="coluna-form">
                    <label> Ativo/Inativo </label>
                    <select 
                        id="patrimonioativo" 
                        name="patrimonioativo">
                        <option <?php if ($patrimonio->getAtivo() == "1") { ?>
                                selected="selected"
                            <?php } ?>
                            value="1">Ativo</option>
                        <option <?php if ($patrimonio->getAtivo() == "0") { ?>
                                selected="selected"
                            <?php } ?>
                            value="0">Inativo</option>
                    </select>
                </div>
            </div>
            <label> Observações </label>
            <textarea 
                style="width: 49.5em; height: 4em;"
                name="patrimonioobservacao" 
                title="Observações sobre o Patrimonio" 
                class="text ui-widget-content ui-corner-all"><?= $patrimonio->getObservacoes() ?></textarea>
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