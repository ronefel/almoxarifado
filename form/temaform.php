<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}


$situacao = "";
if (isset($_GET['situacao']) && !empty($_GET['situacao'])) {

    $situacao = $_GET['situacao'];
}
?>
<script>
    $(function () {

        $(".div-tema img").click(function () {
            $("#temaid").val($(this).attr("id"));
            var form = $("#temaform").serialize();
            $.ajax({
                type: "POST",
                url: "<?= $urlroot ?>/controler/temacontroler.php",
                dataType: "html",
                data: form,
                cache: false,
                success: function (html) {
                    if (html === "sucesso") {
                        window.location.reload();
                    } else {
                        $("#dialog-alert p").html("<br/>" + html + "<br/><br/>");
                        $("#dialog-alert").dialog("open");
                    }
                }
            });
        });
    })
</script>

<div id="temabody">

    <p class="validateTips"></p>

    <form id="temaform">
        <fieldset>
            <div class="div-tema">
                <h1 class="tema-header">ThemeRoller</h1>
                <div class="linha-form">
                    <div class="coluna-form">
                        <?php for ($i = 0; $i < count($tema); $i++) { ?>

                            <img id="<?= $tema[$i]->getTemaid() ?>"
                            <?php if ($tema[$i]->getTemaid() == $usr->getTemaid()) { ?>
                                     class="selected" 
                                 <?php } ?>

                                 src="<?= $urlroot ?>/imagens/temas/<?= $tema[$i]->getImg() ?>" title="<?= $tema[$i]->getNome() ?>">

                        <?php } ?>

                    </div>
                </div>
            </div>
            <input 
                type="hidden" 
                name="usuarioid" 
                value="<?= $usr->getUsuarioid() ?>" >
            <input 
                type="hidden" 
                name="temaid" 
                id="temaid" 
                value="" >
            <input 
                type="hidden" 
                id="control"
                name="control" 
                value="editar" >
        </fieldset>
    </form>
</div>