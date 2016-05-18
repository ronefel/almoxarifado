<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/fornecedorAction.php';
$fornecedores = new fornecedorModel();
$fornecedores = fornecedorAction::listFornecedor();
?>

<option selected="selected" disabled="disabled">Selecione...</option>
<?php for ($i = 0; $i < count($fornecedores); $i++) { ?>
<option value="<?= $fornecedores[$i]->getFornecedorid() ?>"><?= $fornecedores[$i]->getFantazia(TRUE) ?></option>
<?php } ?>