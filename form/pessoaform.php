<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/usuarioAction.php';
require_once '../action/departamentoAction.php';
require_once '../action/localAction.php';

$usuario = new usuarioModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $usuario->setUsuarioid($_GET['id']);

    $usuario = usuarioAction::getUsuario($usuario);
}

$locais = new localModel();
$locais = localAction::listLocal();

$departamentos = array(new departamentoModel());
if ($usuario->getLocalid() != "") {
    $departamentos = departamentoAction::listdepartamentoToLocal($usuario->getLocalid());
}

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>

<div id="usuariobody">
    <script>
        $(function() {

            $("#departamentoTolocalselect").selectmenu({width: '48.5em'}).selectmenu("menuWidget").addClass("overflow");
            $("#localselect").selectmenu({width: '48.5em'}).selectmenu("menuWidget").addClass("overflow");
            $("#tipousuario").selectmenu({width: '26.2em'});
            $("#usuarioativo").selectmenu({width: '8em'});
            $("#busuariosubmit").button({
                icons: {primary: "ui-icon-disk"}
            });
            $(".select-plus").button({
                icons: {primary: "ui-icon-circle-plus"},
                text: false
            });
            $(".select-plus .ui-button-text").css("padding", "1.05em");
            
            localid = "";

            $("#localselect").on("selectmenuselect", function() {
                if ($("#localselect").val() !== localid) {
                    localid = $("#localselect").val();
                    carregarSelect("departamentoTolocalselect", localid);
                }
            });

            $(".select-plus").click(function() {
                var event = $(this).attr("data-evento");
                var title = $(this).attr("data-titulo");
                var param = {"localid": $("#localselect").val(), "situacao": "novo"};
                select = $(this).attr("data-id");
                localid = $("#localselect").val();
                openSubform(event, title, param);

            });

            var tips = $("#usuariobody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }

            $("#busuariosubmit").click(function() {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/usuariocontroler.php",
                    data: $("#usuarioform").serialize(),
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

            $("#busuariofechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#busuariofechar").click(function() {
                $("#dialog-form").dialog('close');
            });

        });
    </script>
    <p class="validateTips">Todos os campos com * são obrigatórios.</p>

    <form id="usuarioform">
        <fieldset>

            <div class="linha-form">
                <div class="coluna-form">
                    <label> Nome do Usuario* </label>
                    <input 
                        type="text" 
                        name="usuarionome" 
                        title="Nome do Usuario" 
                        size="46"
                        maxlength="50"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $usuario->getUsuarionome(TRUE) ?>">
                </div><div class="coluna-form">
                    <label> Login* </label>
                    <input 
                        type="text" 
                        name="usuariologin" 
                        title="Login do Usuario" 
                        size="19"
                        maxlength="8" 
                        required="required"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $usuario->getLogin(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Senha* </label>
                    <input 
                        type="text" 
                        name="usuariosenha" 
                        title="Senha do usuario" 
                        size="20"
                        maxlength="8"
                        required="required"
                        class="text ui-widget-content ui-corner-all" 
                        value='<?= $usuario->getSenha(TRUE) ?>'>
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Local* </label>
                    <select 
                        id="localselect" 
                        name="localid" 
                        title="Local do Departamento" 
                        required="required" >
                            <?php if ($usuario->getLocalid() == "") { ?>
                            <option selected="selected" disabled="disabled">Selecione...</option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($locais); $i++) { ?>
                            <option
                            <?php if ($usuario->getLocalid() == $locais[$i]->getLocalid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $locais[$i]->getLocalid() ?>"><?= $locais[$i]->getLocalnome(TRUE) ?></option>
                            <?php } ?>
                    </select>
                    <div class="select-plus" 
                         title="Cadastrar Novo Local"
                         data-id="localselect"
                         data-titulo="Cadastrar Novo Local" 
                         data-evento="local"></div>
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Departamento* </label>
                    <select 
                        id="departamentoTolocalselect" 
                        name="usuariodepartamentoid" 
                        title="Departamento do Usuario" 
                        required="required" >
                            <?php if ($usuario->getDepartamentoid() == "") { ?>
                            <option selected="selected" disabled="disabled">Selecione o Local</option>
                        <?php } ?>
                        <?php
                        for ($i = 0; $i < count($departamentos); $i++) {
                            if ($departamentos[$i]->getDepartamentoid() != "") {
                                ?>
                                <option
                                <?php if ($usuario->getDepartamentoid() == $departamentos[$i]->getDepartamentoid()) { ?>
                                        selected="selected"
                                    <?php } ?>
                                        value="<?= $departamentos[$i]->getDepartamentoid() ?>"><?= $departamentos[$i]->getDepartamentonome(TRUE) ?></option>
                                    <?php
                                }
                            }
                            ?>
                    </select>
                    <div class="select-plus" 
                         title="Cadastrar Novo Departamento"
                         data-id="departamentoTolocalselect"
                         data-titulo="Cadastrar Novo Departamento" 
                         data-evento="departamento"></div>
                </div>
            </div>


            <div class="linha-form">
                <div class="coluna-form">
                    <label> Email* </label>
                    <input 
                        type="text" 
                        name="usuarioemail" 
                        title="Email do usuario" 
                        size="46"
                        maxlength="50"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $usuario->getEmail(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Tipo de Usuário </label>
                    <select 
                        id="tipousuario" 
                        name="tipousuario">
                        <option <?php if ($usuario->getTipousuario() == "1") { ?>
                                selected="selected"
                            <?php } ?>
                            value="1">Almoxarife/Administrador</option>
                        <option <?php if ($usuario->getTipousuario() == "2") { ?>
                                selected="selected"
                            <?php } ?>
                            value="2">Requisitante</option>
                    </select>
                </div>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <label> Ativo/Inativo </label>
                    <select 
                        id="usuarioativo" 
                        name="usuarioativo">
                        <option <?php if ($usuario->getAtivo() == "1") { ?>
                                selected="selected"
                            <?php } ?>
                            value="1">Ativo</option>
                        <option <?php if ($usuario->getAtivo() == "0") { ?>
                                selected="selected"
                            <?php } ?>
                            value="0">Inativo</option>
                    </select>
                </div>
            </div>
            <input 
                type="hidden" 
                name="usuarioid" 
                value="<?= $usuario->getUsuarioid() ?>" >
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
                id="busuariofechar" 
                type="reset" 
                role="button">
                Fechar
            </button>
            <button 
                id="busuariosubmit" 
                type="submit" 
                role="button">
                Salvar
            </button>
        </div>
    </div>
</div>