<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/localAction.php';
$locais = new localModel();
$locais = localAction::listLocal();
?>

<option selected="selected" disabled="disabled">Selecione...</option>
<?php for ($i = 0; $i < count($locais); $i++) { ?>
<option value="<?= $locais[$i]->getLocalid() ?>"><?= $locais[$i]->getLocalnome(TRUE) ?></option>
<?php } ?>