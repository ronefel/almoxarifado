<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/fornecedorAction.php';
require_once '../action/cidadeAction.php';
require_once '../action/fornecedorgrupoAction.php';

$fornecedor = new fornecedorModel();

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $fornecedor->setFornecedorid($_GET['id']);

    $fornecedor = fornecedorAction::getFornecedor($fornecedor);
}

$cidades = new cidadeModel();
$cidades = cidadeAction::listCidade();

$fornecedorgrupos = new fornecedorgrupoModel();
$fornecedorgrupos = fornecedorgrupoAction::listFornecedorgrupo();

$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
$dialog = "dialog-form";
if (isset($_GET['dialog']) && !empty($_GET['dialog'])) {

    $dialog = $_GET['dialog'];
}
?>

<div id="fornecedorbody">
    <script>
        $(function() {

            $("#fornecedorgruposelect").selectmenu({width: '25.3em'}).selectmenu("menuWidget").addClass("overflow");
            $("#cidadeselect").selectmenu({width: '25.3em'}).selectmenu("menuWidget").addClass("overflow");
            $("#fornecedorativo").selectmenu({width: '8em'});
            $("#bfornecedorsubmit").button({
                icons: {primary: "ui-icon-disk"}
            });
            $(".select-plus").button({
                icons: {primary: "ui-icon-circle-plus"},
                text: false
            });
            $(".select-plus .ui-button-text").css("padding", "1.05em");

            $("#fornecedordatacadastro").datepicker({
                dateFormat: "dd/mm/yy"
            });
            
            $("#fornecedordatacadastro").mask("99/99/9999");
            $("#fornecedortelefone").mask("(99)9999-9999");

            $(".select-plus").click(function() {
                var event = $(this).attr("data-evento");
                var title = $(this).attr("data-titulo");
                param = {"situacao": "novo"},
                select = $(this).attr("data-id");
                openSubform(event, title, param);

            });

            var tips = $("#fornecedorbody .validateTips");
            function updateTips(t) {
                tips.html(t).addClass("ui-state-error");
            }

            $("#bfornecedorsubmit").click(function() {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/fornecedorcontroler.php",
                    data: $("#fornecedorform").serialize(),
                    dataType: "text",
                    cache: false,
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
                                    carregarSelect(select);
                                }, 1);
                            }
                            
                        }
                    }
                });
            });

            $("#bfornecedorfechar").button({
                icons: {primary: "ui-icon-closethick"}
            });
            $("#bfornecedorfechar").click(function() {
                $("#<?=$dialog?>").dialog('close');
            });

        });
    </script>
    <p class="validateTips">Todos os campos com * são obrigatórios.</p>

    <form id="fornecedorform">
        <fieldset>

            <div class="linha-form">
                <div class="coluna-form">
                    <label> Nome Fantasia* </label>
                    <input 
                        type="text" 
                        name="fornecedorfantasia" 
                        title="Nome Fantasia do Fornecedor" 
                        size="40"
                        maxlength="50"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $fornecedor->getFantazia(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Razão Social* </label>
                    <input 
                        type="text" 
                        name="fornecedorrazao" 
                        title="Razão Social do Fornecedor" 
                        size="40"
                        maxlength="50"
                        required="required" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $fornecedor->getRazao(TRUE) ?>">
                </div>
            </div>

            <div class="linha-form">
                <div class="coluna-form">
                    <label> Endereço </label>
                    <input 
                        type="text" 
                        name="fornecedorendereco" 
                        title="Endereço do Fornecedor" 
                        size="40"
                        maxlength="50"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $fornecedor->getEndereco(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Número </label>
                    <input 
                        type="text" 
                        name="fornecedornumero" 
                        title="Número do Endereço do Fornecedor" 
                        size="8"
                        maxlength="10"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $fornecedor->getNumero(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Bairro </label>
                    <input 
                        type="text" 
                        name="fornecedorbairro" 
                        title="Bairro do Fornecedor" 
                        size="28"
                        maxlength="50"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $fornecedor->getBairro(TRUE) ?>">
                </div>
            </div>

            <div class="linha-form">
                <div class="coluna-form">
                    <label> Cidade* </label>
                    <select 
                        id="cidadeselect" 
                        name="fornecedorcidadeid" 
                        title="Cidade do Fornecedor" 
                        size="50"
                        required="required">
                            <?php if ($fornecedor->getCidadeid() == "") { ?>
                            <option selected="selected" disabled="disabled">Selecione...</option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($cidades); $i++) { ?>
                            <option 
                            <?php if ($fornecedor->getCidadeid() == $cidades[$i]->getCidadeid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                value="<?= $cidades[$i]->getCidadeid() ?>"><?= $cidades[$i]->getNome(TRUE) ?> - <?= $cidades[$i]->getUf(TRUE) ?></option>
                            <?php } ?>
                    </select>
                    <div class="select-plus" 
                         title="Cadastrar nova Cidade"
                         data-id="cidadeselect"
                         data-titulo="Cadastrar Cidade" 
                         data-evento="cidade"></div>
                </div>
                <div class="coluna-form">
                    <label> CNPJ/CPF </label>
                    <input 
                        type="text" 
                        name="fornecedorcnpj_cpf" 
                        title="CNPJ ou CPF do Fornecedor"
                        size="18"
                        maxlength="20" 
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $fornecedor->getCnpj_cpf(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Inscrição/RG </label>
                    <input 
                        type="text" 
                        name="fornecedorinscricao_rg" 
                        title="Inscrição Estadual ou RG do Fornecedor" 
                        size="18"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $fornecedor->getInscricao_rg(TRUE) ?>">
                </div>
            </div>

            <div class="linha-form">
                <div class="coluna-form">
                    <label> Telefone </label>
                    <input 
                        id="fornecedortelefone"
                        type="text" 
                        name="fornecedortelefone" 
                        title="Telefone do Fornecedor" 
                        size="20"
                        maxlength="20"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $fornecedor->getTelefone(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Email </label>
                    <input 
                        type="text" 
                        name="fornecedoremail" 
                        title="Email do Fornecedor" 
                        size="60"
                        maxlength="100"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $fornecedor->getEmail(TRUE) ?>">
                </div>
            </div>

            <div class="linha-form">
                <div class="coluna-form">
                    <label> Contato </label>
                    <input 
                        type="text" 
                        name="fornecedorcontato" 
                        title="Contato do Fornecedor" 
                        size="40"
                        maxlength="50"
                        class="text ui-widget-content ui-corner-all" 
                        value="<?= $fornecedor->getContato(TRUE) ?>">
                </div>
                <div class="coluna-form">
                    <label> Grupo de Fornecedor* </label>
                    <select 
                        id="fornecedorgruposelect" 
                        name="fornecedorgrupoid" 
                        title="Grupo do Fornecedor" 
                        size="50"
                        required="required" >
                            <?php if ($fornecedor->getFornecedorgrupoid() == "") { ?>
                            <option selected="selected" disabled="disabled">Selecione...</option>
                        <?php } ?>
                        <?php for ($i = 0; $i < count($fornecedorgrupos); $i++) { ?>
                            <option
                            <?php if ($fornecedor->getFornecedorgrupoid() == $fornecedorgrupos[$i]->getFornecedorgrupoid()) { ?>
                                    selected="selected"
                                <?php } ?>
                                    value="<?= $fornecedorgrupos[$i]->getFornecedorgrupoid() ?>"><?= $fornecedorgrupos[$i]->getFornecedorgruponome(TRUE) ?></option>
                            <?php } ?>
                    </select>
                    <div class="select-plus" 
                         title="Cadastrar Grupo de Fornecedor"
                         data-id="fornecedorselect"
                         data-titulo="Cadastrar Grupo de Fornecedor" 
                         data-evento="fornecedorgrupo"></div>
                </div>
            </div>

            <div class="linha-form">
                <div class="coluna-form">
                    <label> Data do Cadastro </label>
                    <input 
                        id="fornecedordatacadastro"
                        type="text" 
                        name="fornecedordatacadastro" 
                        title="Data do Cadastro do Fornecedor" 
                        class="text ui-widget-content ui-corner-all" 
                        <?php if ($fornecedor->getDatacadastro() != "") { ?>
                            value="<?= util::dateToBR($fornecedor->getDatacadastro()) ?>"
                        <?php } else { ?>
                            value="<?= util::getData() ?>"
                        <?php } ?>
                        >
                </div>
                <div class="coluna-form">
                    <label> Ativo/Inativo </label>
                    <select 
                        id="fornecedorativo" 
                        name="fornecedorativo">
                        <option <?php if ($fornecedor->getAtivo() == "1") { ?>
                                selected="selected"
                            <?php } ?>
                            value="1">Ativo</option>
                        <option <?php if ($fornecedor->getAtivo() == "0") { ?>
                                selected="selected"
                            <?php } ?>
                            value="0">Inativo</option>
                    </select>
                </div>
            </div>
            <label> Observações </label>
            <textarea 
                style="width: 51.8em; height: 4em;"
                name="fornecedorobservacao" 
                title="Observações sobre o Fornecedor" 
                class="text ui-widget-content ui-corner-all"><?= $fornecedor->getObservacao() ?></textarea>
            <input 
                type="hidden" 
                name="fornecedorid" 
                value="<?= $fornecedor->getFornecedorid() ?>" >
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
                id="bfornecedorfechar" 
                type="reset" 
                role="button">
                Fechar
            </button>
            <button 
                id="bfornecedorsubmit" 
                type="submit" 
                role="button">
                Salvar
            </button>
        </div>
    </div>
</div>