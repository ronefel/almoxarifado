<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/controler/produtosubgrupocontroler.php';
$produtosubgrupos = array(new produtosubgrupoModel());
$id = "";
if (isset($_GET['id']) && $_GET['id'] != "") {
    $id = $_GET['id'];
    $produtosubgrupos = produtosubgrupoAction::listprodutosubgrupoToProdutogrupo($id);
} else {
    $produtosubgrupos = produtosubgrupoAction::listProdutosubgrupo();
}
if ($id == "" || !$produtosubgrupos) {
    ?>
    <option selected="selected" disabled="disabled">Este grupo de produtos não possui Subgrupos vinculados</option>
<?php } ?>
<?php for ($i = 0; $i < count($produtosubgrupos); $i++) { ?>
    <option value="<?= $produtosubgrupos[$i]->getProdutosubgrupoid() ?>"><?= $produtosubgrupos[$i]->getNome(TRUE) ?></option>
<?php } ?>