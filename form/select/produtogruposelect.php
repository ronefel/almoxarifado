<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/produtogrupoAction.php';
$produtogrupos = array(new produtogrupoModel());

$produtogrupos = produtogrupoAction::listProdutogrupo();
?>
<option selected="selected" disabled="disabled">Selecione...</option>

<?php for ($i = 0; $i < count($produtogrupos); $i++) { ?>
    <option value="<?= $produtogrupos[$i]->getProdutogrupoid() ?>"><?= $produtogrupos[$i]->getNome(TRUE) ?></option>
<?php } ?>