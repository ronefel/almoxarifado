<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/departamentoAction.php';
$departamentos = array(new departamentoModel());
$id = "";
if (isset($_GET['id']) && $_GET['id'] != "" && is_numeric($_GET['id'])) {

    $id = $_GET['id'];
    $departamentos = departamentoAction::listdepartamentoTolocal($id);
}
if ($id == "") {
    ?>

    <option selected="selected">Selecione um local</option>

<?php } elseif (!$departamentos) { ?>
    <option selected="selected" disabled="disabled">Este Local não possui Departamento vinculado</option>
<?php } else { ?>
    <?php for ($i = 0; $i < count($departamentos); $i++) { ?>
        <option value="<?= $departamentos[$i]->getDepartamentoid() ?>"><?= $departamentos[$i]->getDepartamentonome(TRUE) ?></option>
        <?php
    }
}
?>