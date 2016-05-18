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

$requisicao = new requisicaoModel();
if (isset($_GET['id']) && !empty($_GET['id'])) {

    $requisicao->setRequisicaoid($_GET['id']);
    $requisicao = requisicaoAction::getRequisicao($requisicao);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="reprovarbody">
    <script>
        $(function() {
            $("#breprovarsubmit").button({
                icons: {primary: "ui-icon-disk"}
            });

            $("#requisicaoreprovacao").datepicker({
                dateFormat: "dd/mm/yy"
            });

            var tips = $("#reprovarbody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }

            $("#breprovarsubmit").click(function() {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/requisicaocontroler.php",
                    data: $("#reprovarform").serialize(),
                    dataType: "text",
                    cache: false,
                    success: function(html) {
                        if (html !== "sucesso") {
                            updateTips(html);
                        } else {
                            $("#dialog-subform").dialog('close');
                            setTimeout(function() {
                                carregarIndex(pagina);
                            }, 1);
                        }
                    }
                });
            });

            $("#breprovarfechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#breprovarfechar").click(function() {
                $("#dialog-subform").dialog('close');
            });

        });
    </script>
    <form id="reprovarform">
        <fieldset>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Data da Reprovação </label>
                    <input 
                        id="requisicaoreprovacao"
                        name="requisicaoreprovacao" 
                        type="text" 
                        title="Data da reprovação da requisicao" 
                        size="16"
                        maxlength="10"
                        class="text ui-widget-content ui-corner-all" 
                        <?php if ($requisicao->getRequisicaoreprovacao(TRUE) != "") { ?>
                            value="<?= $requisicao->getRequisicaoreprovacao(TRUE) ?>"
                        <?php } else { ?>
                            value="<?= util::getData() ?>"
                        <?php } if ($requisicao->getRequisicaosituacao() == 4) { ?>
                            disabled="disabled"
                        <?php } ?>
                        value="<?= util::getData() ?>" >
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <textarea 
                        id="requisicaoreprovacaotxt"
                        name="requisicaoreprovacaotxt" 
                        title="Informações Sobre a Reprovação" 
                        cols="80"
                        rows="10"
                        <?php if ($requisicao->getRequisicaosituacao() == 4) { ?>
                            disabled="disabled"
                        <?php } ?>
                        class="text ui-widget-content ui-corner-all" ><?= $requisicao->getRequisicaoreprovacaotxt() ?></textarea>
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
        </fieldset>
    </form>
    <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
        <div class="ui-dialog-buttonset">
            <button id="breprovarfechar" type="reset" role="button">Fechar</button>
            <?php if ($requisicao->getRequisicaosituacao() != 4) { ?>
                <button id="breprovarsubmit" type="submit" role="button">Reprovar</button>
            <?php } ?>
        </div>
    </div>
</div>