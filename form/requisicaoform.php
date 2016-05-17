<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/requisicaoAction.php';
require_once '../action/usuarioAction.php';
require_once '../action/produtoAction.php';

$requisicao = new requisicaoModel();
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $requisicao->setRequisicaoid($_GET['id']);
    $requisicao = requisicaoAction::getRequisicao($requisicao);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {
    $situacao = $_GET['situacao'];
}

$usuarios = array(new usuarioModel());
$usuarios = usuarioAction::listUsuario();

$produtos = array(new produtoModel());
$produtos = produtoAction::listProduto();
?>

<div id="requisicaobody">
    <script>
        $(function () {

            situacao = "";
            $("#requisicaoid").val("");
<?php if ($situacao != "") { ?>
                situacao = "<?= $situacao ?>";
                $("#requisicaoid").val(<?= $requisicao->getRequisicaoid() ?>);
<?php } ?>

//            $("#produtoselect").selectmenu({width: '31.5em'}).selectmenu("menuWidget").addClass("overflow");
//            $("#usuarioselect").selectmenu({width: '40.3em'}).selectmenu("menuWidget").addClass("overflow");

            $("#produtoselect, #usuarioselect").combobox();
            $("#brequisicaosubmit").button({
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

            $("#requisicaoemissao, #requisicaoaprovacao, #requisicaorecebimento").datepicker({
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

            $("#controleestoquequantidade").change(function () {
                var quantidade = $("#controleestoquequantidade").val().replace(',', '.');
                var valor = $("#controleestoquevalor").val().replace(',', '.');
                var total = quantidade * valor;
                $("#controleestoquetotal").val(total.toFixed(2).replace(".", ","));
            });


            var tips = $("#requisicaobody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }
            function removeTips(t) {
                tips.html(t).removeClass("ui-state-error");
            }

            $("#requisicaoform").submit(function () {
                return false;
            });
            $("#requisicaoitemform").submit(function () {
                return false;
            });

            $("#requisicaoform button").css("width", "110px");

            $("#brequisicaosubmit").click(function () {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/requisicaocontroler.php",
                    data: $("#requisicaoform").serialize(),
                    dataType: "text",
                    cache: false,
                    success: function (html) {
                        html = html.split("=");
                        if (html[0] !== "sucesso") {
                            updateTips(html);
                        } else {
                            setTimeout(function () {
                                if ($("#requisicaoid").val() === "") {
                                    $("#brequisicaosubmit").button("option", "disabled", true);
                                    $("#controleestoquerequisicaoid").val(html[1]);
                                    requisicaoid = html[1];
                                } else {
                                    $("#controleestoquerequisicaoid").val($("#requisicaoid").val());
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

            $("#brequisicaofechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#brequisicaofechar").click(function () {
                $("#dialog-form").dialog('close');
            });

            $("#incluiritem").button({
                icons: {primary: "ui-icon-circle-plus"}
            });
            $("#incluiritem").click(function () {
                removeTips("Todos os campos com * são obrigatórios.");
                var requisicaoid = $("#controleestoquerequisicaoid").val();
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/estoquemovimentocontroler.php",
                    data: $("#requisicaoitemform").serialize(),
                    dataType: "text",
                    cache: false,
                    success: function (html) {
                        if (html !== "sucesso") {
                            updateTips(html);
                        } else {
                            setTimeout(function () {
                                var param = {requisicaoid: requisicaoid, situacao: "<?= $situacao ?>"};
                                carregarProdutoitemTable(param);
                                $("#requisicaoitemform").each(function () {
                                    this.reset();
                                });
                                $("#produtoselect").selectmenu("refresh");
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

            if ($("#requisicaoid").val() !== "") {
                requisicaoid = $("#requisicaoid").val();
                var param = {requisicaoid: requisicaoid, situacao: "<?= $situacao ?>"};
                carregarProdutoitemTable(param);
                $(".desabilitado").hide();
            }

            if (situacao !== "novo" && situacao !== "editar") {
                $("#usuarioselect").combobox("disable");
                $("#usuarioselect-button").removeClass("ui-state-disabled");
                $("#requisicaoemissao").attr("disabled", "disabled");
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

        <form id="requisicaoform">
            <div class="ui-widget-header form-header">Dados da Requisicao</div>
            <div class="linha-form" style="width: 708px;">
                <div class="coluna-form" style="width: 380px;">
                    <label> Requisitante* </label>
                    <select 
                        id="usuarioselect" 
                        name="usuarioid" 
                        title="Usuario da Requisicao" 
                        required="required" >
                            <?php if ($requisicao->getUsuarioid() == "") { ?>
                            <option selected="selected" disabled="disabled"></option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($usuarios); $i++) { ?>
                            <option
                            <?php if ($requisicao->getUsuarioid() == $usuarios[$i]->getUsuarioid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $usuarios[$i]->getUsuarioid() ?>"><?= $usuarios[$i]->getUsuarionome(TRUE) ?></option>
                            <?php } ?>
                    </select>
                </div>
                <div class="coluna-form">
                    <label> Data* </label>
                    <input 
                        id="requisicaoemissao"
                        name="requisicaoemissao" 
                        type="text" 
                        title="Data de emissão da requisicao" 
                        style="width: 100px"
                        maxlength="10"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" <?php if ($requisicao->getRequisicaoemissao(TRUE) != "") { ?>
                            value="<?= $requisicao->getRequisicaoemissao(TRUE) ?>"
                        <?php } else { ?>
                            value="<?= util::getData() ?>"
                        <?php } if ($requisicao->getRequisicaosituacao() > 1 && $situacao != "") { ?>
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
                            id="requisicaoaprovacao"
                            name="requisicaoaprovacao" 
                            type="text" 
                            title="Data de Aprovação da requisicao" 
                            size="16"
                            maxlength="10"
                            required="required" 
                            class="text ui-widget-content ui-corner-all" 
                            <?php if ($requisicao->getRequisicaoaprovacao(TRUE) != "") { ?>
                                value="<?= $requisicao->getRequisicaoaprovacao(TRUE) ?>"
                            <?php } else if ($situacao != "consultar") { ?>
                                value="<?= util::getData() ?>"
                            <?php } if ($situacao == "consultar" || $situacao == "receber") { ?>
                                disabled="disabled"
                            <?php } ?>
                            >
                    </div>
                <?php } if ($situacao == "entregar" || $situacao == "consultar") { ?>
                    <div class="coluna-form">
                        <label> Data da Entrega* </label>
                        <input 
                            id="requisicaorecebimento"
                            name="requisicaoentrega" 
                            type="text" 
                            title="Data da entrega da requisicao" 
                            size="16"
                            maxlength="10"
                            required="required" 
                            class="text ui-widget-content ui-corner-all" 
                            <?php if ($requisicao->getRequisicaoentrega(TRUE) != "") { ?>
                                value="<?= $requisicao->getRequisicaoentrega(TRUE) ?>"
                            <?php } else if ($situacao != "consultar") { ?>
                                value="<?= util::getData() ?>"
                            <?php } if ($situacao == "consultar") { ?>
                                disabled="disabled"
                            <?php } ?>
                            >
                    </div>
                <?php } if ($requisicao->getRequisicaosituacao() == 4) { ?>
                    <div class="coluna-form">
                        <label> Data da Reprovação </label>
                        <input 
                            type="text" 
                            title="Data da reprovação da requisicao" 
                            size="16"
                            maxlength="10"
                            class="text ui-widget-content ui-corner-all" 
                            <?php if ($requisicao->getRequisicaoreprovacao(TRUE) != "") { ?>
                                value="<?= $requisicao->getRequisicaoreprovacao(TRUE) ?>"
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
                                        id="brequisicaosubmit" 
                                        role="button">
                                            <?php if ($situacao == "novo") { ?>
                                            Gravar
                                            <?php
                                        } else {
                                            echo ucfirst($situacao);
                                        }
                                        ?>
                                    </button>
                                <?php } if ($requisicao->getRequisicaosituacao() == 4) { ?>
                                    <button 
                                        id="acao-reprovar" 
                                        data-evento="requisicaoreprovar" 
                                        data-titulo="Informações Sobre a Reprovação" 
                                        data-id="<?= $requisicao->getRequisicaoid() ?>">
                                        Motivo da Reprovação
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                        <div>
                            <button 
                                id="brequisicaofechar" 
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
                id="requisicaoid"
                name="requisicaoid" 
                value="<?= $requisicao->getRequisicaoid() ?>" >
            <input 
                type="hidden" 
                id="control"
                name="control" 
                value="<?= $situacao ?>" >

        </form>
    </fieldset>


    <div id="itemcontainer">
        <fieldset class="ui-widget-content">
            <div class="ui-widget-header form-header">Produtos da Requisicao</div>
            <div class="desabilitado" title="Clique em Gravar para abilitar os produtos da requisição."></div>
            <?php if ($situacao == "novo" || $situacao == "editar") { ?>
                <form id="requisicaoitemform">
                    <div class="linha-form" style="width: 708px;">
                        <div class="coluna-form">
                            <label> Código* </label>
                            <input 
                                id="produtoid"
                                name="produtoid" 
                                type="text" 
                                title="Código do Produto" 
                                style="width: 100px"
                                maxlength="10"
                                required="required" 
                                class="text ui-widget-content ui-corner-all" 
                                value="">
                        </div>
                        <div class="coluna-form"  style="width: 380px;">
                            <label> Descrição do Produto* </label>
                            <select 
                                id="produtoselect" 
                                title="Produto da Requisicao" 
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
                                style="width: 100px"
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
                                style="text-align: right;width: 121px"
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
                                style="text-align: right;width: 121px"
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
                                disabled="disabled"
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
                                id="controleestoquerequisicaoid"
                                name="estoquemovimentorequisicaoid" 
                                value="<?= $requisicao->getRequisicaoid() ?>" >
                            <input
                                type="hidden"
                                name="control"
                                value="incluiritem">
                        </div>
                        <div class="coluna-form">
                            <div class="button-form" style="position: relative; bottom: -21px;">
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
            <div id="produtoitem" style="margin: 0 0 0.5em; height: 181px;">
                <?php if ($requisicao->getRequisicaoid() != "") { ?>
                    <div style="text-align: center;">
                        <img src="imagens/carregando.gif">
                    </div>
                <?php } ?>
            </div>
        </fieldset>
    </div>
</div>