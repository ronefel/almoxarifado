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
$fornecedors = new fornecedorModel();
$fornecedors = fornecedorAction::listFornecedor();
?>

<option selected="selected" disabled="disabled">Selecione...</option>
<?php for ($i = 0; $i < count($fornecedors); $i++) { ?>
<option value="<?= $fornecedors[$i]->getFornecedorid() ?>"><?= $fornecedors[$i]->getFornecedornome(TRUE) ?></option>
<?php } ?>