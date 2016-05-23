<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/marcaAction.php';
$locais = new marcaModel();
$locais = marcaAction::listMarca();
?>

<option selected="selected" disabled="disabled"></option>
<?php for ($i = 0; $i < count($locais); $i++) { ?>
<option value="<?= $locais[$i]->getMarcaid() ?>"><?= $locais[$i]->getMarcanome(TRUE) ?></option>
<?php } ?>  