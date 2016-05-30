<?php
include_once 'config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Consultar Patrimônio</title>
        <meta name=viewport content="width=device-width, initial-scale=1">
        <meta content="imagens/farol-icon2.png" itemprop="image"/>
        <link href='imagens/farol-icon2.png' rel='icon' type='image/x-icon' /> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="js/jquery-1.11.2.min.js"></script>
        <script src="themes/redmond/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="css/css.css">        
        <link rel="stylesheet" href="themes/redmond/jquery-ui.min.css">
        <script>
            $(function () {
                $("#consultar").button({
                    icons: {primary: "ui-icon-search"}
                }).click(function () {

//                    $.getJSON("<?= $urlroot ?>/controler/consultacontroler.php", $("#form").serialize(), function (data) {
//                        var items = [];
//                        $.each(data, function (key, val) {
//                            items.push("<li id='" + key + "'>" + val + "</li>");
//                        });
//                    });

                    $.ajax({
                        type: "POST",
                        url: "<?= $urlroot ?>/controler/consultacontroler.php",
                        dataType: "html",
                        data: $("#form").serialize(),
                        cache: false,
                        success: function (html) {
                            montaTabela(html);
                        }
                    });

                    return false;
                });

                function montaTabela(json) {

                    var tabela = $("#tabela");
                    tabela.html("");
                    var tr = "";
                    for ($i = 0; $i < json.length; $i++) {
                        tr += "<tr><td>" + key + ": </td><td>" + val + "</td></tr>";
                    }

                    tabela.append(tr);
                }
                ;

            });
        </script>

    </head>
    <body>

        <div class="ui-widget-content" id="index-body" style="min-width: 200px; max-width: 600px; height: 70%; margin: 20px;">
            <div class="linha-form">
                <div class="ui-widget-header">Consultar Patrimônio</div>
                <br/>
                <form id="form">
                    <div class="coluna-form">
                        <label>Número</label>
                        <input type="text" 
                               name="patrimonioid"                            
                               class="text ui-widget-content ui-corner-all"
                               style="width: 100px;">
                    </div>
                    <div class="coluna-form">
                        <br/>
                        <button 
                            id="consultar" 
                            style="position: relative; padding: 4px 4px 3px 4px;"
                            data-evento="requisicao" 
                            data-titulo="Relatório de Requisição">
                            Consultar
                        </button>
                    </div>
                    <input type="hidden" 
                           name="control" 
                           value="consulta">
                </form>
            </div>
            <div class="linha-form">
                <div class="coluna-form">
                    <table id="tabela">
                        <tr>
                            <td>oi</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>