<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/categoriaAction.php';
$locais = new categoriaModel();
$locais = categoriaAction::listCategoria();
?>

<option selected="selected" disabled="disabled"></option>
<?php for ($i = 0; $i < count($locais); $i++) { ?>
<option value="<?= $locais[$i]->getCategoriaid() ?>"><?= $locais[$i]->getCategorianome(TRUE) ?></option>
<?php } ?>  