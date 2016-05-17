<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/cidadeAction.php';
$cidades = new cidadeModel();
$cidades = cidadeAction::listCidade();
?>

<option selected="selected" disabled="disabled">Selecione...</option>
<?php for ($i = 0; $i < count($cidades); $i++) { ?>
    <option value="<?= $cidades[$i]->getCidadeid() ?>"><?= $cidades[$i]->getNome(TRUE) ?> - <?= $cidades[$i]->getUf(TRUE) ?></option>
<?php } ?>