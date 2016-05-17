<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/fornecedorgrupoAction.php';
$fornecedorgrupos = new fornecedorgrupoModel();
$fornecedorgrupos = fornecedorgrupoAction::listFornecedorgrupo();
?>

<option selected="selected" disabled="disabled">Selecione...</option>
<?php for ($i = 0; $i < count($fornecedorgrupos); $i++) { ?>
<option value="<?= $fornecedorgrupos[$i]->getFornecedorgrupoid() ?>"><?= $fornecedorgrupos[$i]->getFornecedorgruponome(TRUE) ?></option>
<?php } ?>