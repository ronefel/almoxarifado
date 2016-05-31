<?php
include_once 'config.php';
include_once './script.php';
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
        <script src="js/jquery.dataTables.min2.js"></script>
        <link rel="stylesheet" href="css/css.css">        
        <link rel="stylesheet" href="themes/redmond/jquery-ui.min.css">
        <link rel="stylesheet" href="css/jquery.dataTables_themeroller.css">
        <script>
            $(function () {

                var tips = $(".msg");
                function updateTips(t) {
                    tips.html(t).addClass("ui-state-error");
                }
                function removeTips(t) {
                    tips.html(t).removeClass("ui-state-error");
                }

                $("#consultar").button({
                    icons: {primary: "ui-icon-search"}
                });

                $("#form").submit(function () {

                    removeTips("");

                    $.ajax({
                        type: "POST",
                        url: "<?= $urlroot ?>/controler/consultacontroler.php",
                        dataType: "html",
                        data: $("#form").serialize(),
                        cache: false,
                        beforeSend: function () {
                            $("#consultar").button("disable");
                        },
                        success: function (html) {

                            if (html.substring(0, 1) === "{") {
                                html = html.replace(/(\r\n|\n|\r)/gm,"<br/>");
                                montaTabela($.parseJSON(html));
                                $("#patrimonioid").select();
                            } else {
                                updateTips(html);
                                $("#patrimonioid").select();
                            }
                            $("#consultar").button("enable");
                        }
                    });

                    return false;
                });

                function montaTabela(json) {

                    var tabela = $("#tabela tbody");
                    tabela.html("");
                    var tr = "";
                    $.each(json, function (key, val) {
                        tr += "<tr><td width='9%'>" + key + ": </td><td>" + val + "</td></tr>";
                    });

                    tabela.append(tr);
                }

                $('.table').dataTable({
                    "scrollX": "970px",
                    "columnDefs": [
                        {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        }
                    ]
                });

            });
        </script>

    </head>
    <body>

        <div class="ui-widget-content" id="index-body" style="min-width: 234px; max-width: 600px; margin: 20px;">
            <div class="linha-form">
                <div class="ui-widget-header">Consultar Patrimônio</div>
                <br/>
                <form id="form">
                    <div class="coluna-form">
                        <label>Número</label>
                        <input type="text" 
                               id="patrimonioid"
                               name="patrimonioid"                            
                               class="text ui-widget-content ui-corner-all"
                               style="width: 100px;"
                               maxlength="9"
                               required
                               autofocus>
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
                        <input type="hidden" 
                               name="control" 
                               value="consulta">
                    </div>
                    <div class="coluna-form msg" style="margin: 25px 0 5px 5px;">

                    </div>
                </form>
            </div>
            <div class="linha-form">
                <table id="tabela" class="table" cellspacing="0"  >
                    <thead class="ui-widget-header"> 
                        <tr>
                            <th colspan="2">
                                <br/>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="9%">Patrimônio: </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="9%">Produto: </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="9%">Local: </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="9%">Departamento: </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="9%">Data da Compra: </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="9%">Data Implantação: </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="9%">Fim da Garantia: </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="9%">Estado de Conservação: </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="9%">Fornecedor: </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="9%">Observação: </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>