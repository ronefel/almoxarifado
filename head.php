<?php
$tema = "redmond";
if (Sessao::existe('usuario')) {
    $usuariot = Sessao::get('usuario');
    $usu = usuarioAction::getUsuario($usuariot);

    if (DBHOST != 'localhost') {
        $tema = $usu->getLink();
    } else {
        $tema = 'ui-lightness';
    }
}
?>

<title>FAROL - Almoxarifado</title>
<meta name=viewport content="width=device-width, initial-scale=1">
<meta content="imagens/farol-icon2.png" itemprop="image"/>
<link href='imagens/farol-icon2.png' rel='icon' type='image/x-icon' /> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="js/jquery-1.11.2.min.js"></script>
<script src="themes/<?= $tema ?>/jquery-ui.min.js"></script>
<script src="js/jquery.dataTables.min2.js"></script>
<script src="js/mask.js"></script>
<script src="js/jquery.maskMoney.js"></script>
<script src="js/jquery.download.js"></script>
<script src="js/combobox.js"></script>
<script src="js/jquery-ui-contextmenu.js"></script>
<link rel="stylesheet" href="css/css.css">
<link rel="stylesheet" href="themes/<?= $tema ?>/jquery-ui.min.css">
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.3/themes/ui-lightness/jquery-ui.css">-->
<link rel="stylesheet" href="css/jquery.dataTables_themeroller.css">
<script>
    $(function () {
        //configuração global para requisições ajax
        $(document).ajaxError(function (event, request, settings, thrownError) {
//            if (request.status === 0) {
//                alert('Sem conexão.\n Verifique sua rede.');
//            } else
            if (request.status === 404) {
                alert('A página solicitada não foi encontrada. [404]');
            } else if (request.status === 500) {
                alert('Erro interno no sistema [500].');
            } else if (thrownError === 'parsererror') {
                alert('Solicitação de análise JSON falhou.');
            } else if (thrownError === 'timeout') {
                alert('Tempo limite de erro.');
            } else if (thrownError === 'abort') {
                //alert('Pedido abortado.');
            }
        });
//        $.ajaxSetup({
//            contentType: "application/x-www-form-urlencoded;charset=ISO-8859-15"
//        });
        $(document).ajaxSend(function (event, request, settings) {
            var session = settings.url.search("sessioncontroler");
            var tmp = settings.url.search("tmpcontroler");

            if (session === -1 && tmp === -1) {
                $("#carregando").dialog("open");
                //alert(settings.url)
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/sessioncontroler.php",
                    data: {"control": "sessao"},
                    dataType: "text",
                    cache: false,
                    success: function (html) {
                        if (html === "0") {
                            $("#dialog-alert p").html("<br/><b>Sessão Expirada.</b><br/><br/>");
                            $("#dialog-alert").dialog("open");
                            //alert("Sessão Expirada.");
                            window.location.href = "/almoxarifado/login.php";
                        } else {

                        }
                    }
                });
            } else {
                // alert(settings.url)
            }
        });
        $(document).ajaxComplete(function (event, request, settings) {
            var session = settings.url.search("session");
            var tmp = settings.url.search("tmp");
            if (session === -1 && tmp === -1) {
                $("#carregando").dialog("close");
            }
        });

        $(document).unbind('keydown').bind('keydown', function (event) {
            var doPrevent = false;
            if (event.keyCode === 8) {
                var d = event.srcElement || event.target;
                if ((d.tagName.toUpperCase() === 'INPUT' && (d.type.toUpperCase() === 'TEXT' || d.type.toUpperCase() === 'SEARCH' || d.type.toUpperCase() === 'PASSWORD' || d.type.toUpperCase() === 'FILE'))
                        || d.tagName.toUpperCase() === 'TEXTAREA') {
                    doPrevent = d.readOnly || d.disabled;
                } else {
                    doPrevent = true;
                }
            }

            if (doPrevent) {
                event.preventDefault();
            }
        });
    });
</script>
