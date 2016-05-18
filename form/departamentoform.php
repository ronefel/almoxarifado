<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/departamentoAction.php';
require_once '../action/localAction.php';

$locais = new localModel();
$locais = localAction::listLocal();

$departamento = new departamentoModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $departamento->setDepartamentoid($_GET['id']);

    $departamento = departamentoAction::getDepartamento($departamento);
}

if (isset($_GET['localid']) && !empty($_GET['localid'])) {
    $departamento->setLocalid($_GET['localid']);
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="departamentobody">
    <script>
        $(function() {
            $("#bdepartamentosubmit").button({
                icons: {primary: "ui-icon-disk"}
            });

            $("#localselectform").selectmenu({width: '27.9em'}).selectmenu("menuWidget").addClass("overflow");

            var tips = $("#departamentobody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }
            
            $(".select-plus").button({
                icons: {primary: "ui-icon-circle-plus"},
                text: false
            });
            $(".select-plus .ui-button-text").css("padding", "1.05em");
            
            $("#departamentoform").submit(function(){return false;});
            
            $("#bdepartamentosubmit").click(function() {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/departamentocontroler.php",
                    data: $("#departamentoform").serialize(),
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
                                    carregarSelect(select, localid);
                                }, 1);
                            }
                        }
                    }
                });
            });

            $("#bdepartamentofechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bdepartamentofechar").click(function() {
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

    <form id="departamentoform">
        <fieldset>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Local* </label>
                    <select 
                        id="localselectform" 
                        name="localid" 
                        required="required">
                            <?php if ($departamento->getLocalid() == "") { ?>
                            <option selected="selected" disabled="disabled">Selecione...</option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($locais); $i++) { ?>
                            <option 
                            <?php if ($departamento->getLocalid() == $locais[$i]->getLocalid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $locais[$i]->getLocalid() ?>"><?= $locais[$i]->getLocalnome(TRUE) ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    Nome do Departamento*
                    <input 
                        type="text" 
                        name="departamentonome"  
                        size="40"
                        maxlength="50"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $departamento->getDepartamentonome(TRUE) ?>">
                </div>
            </div>
            <input 
                type="hidden" 
                name="departamentoid" 
                value="<?= $departamento->getDepartamentoid() ?>" >
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
                id="bdepartamentofechar" 
                type="reset" 
                role="button">
                Fechar
            </button>
            <button 
                id="bdepartamentosubmit" 
                type="submit" 
                role="button">
                Salvar
            </button>
        </div>
    </div>
</div>