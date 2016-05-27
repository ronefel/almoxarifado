<html>
    <head>
        <style>
            body { 
                font-family: sans-serif;
                width: 800px;
            }

            p { margin: 0pt; }

            td, th { vertical-align: top; }

            .items td, .items th {
                border-left: 0.1mm solid #000000;
                border-right: 0.1mm solid #000000;
                border-bottom: 0.1mm solid #000000;
            }
            .items td { font-size: 12px; padding: 5px;}

            table thead th { 
                background-color: #EEEEEE;
                text-align: center;
                border: 0.1mm solid #000000;
                font-size: 14px;
            }

            .items td.blanktotal {
                background-color: #FFFFFF;
                border: 0mm none #000000;
                border-top: 0.1mm solid #000000;
                border-right: 0.1mm solid #000000;
            }

            .items td.totals {
                text-align: right;
                border: 0.1mm solid #000000;
                background-color: #EEEEEE;
                font-weight: bold;
                font-size: 14px;
            }
        </style>
    </head>
    <body>

        <!--mpdf
        <htmlpageheader name="myheader">
            <table width="100%">
                <tr>
                    <td width="25%"><img src="../report/css/imagens/logo.jpg"></td>
                    <td width="75%"><br/><span style="font-size: 16pt;">Almoxarifado</span><br/><span style="font-size: 16pt; font-weight: bold; ">Relatório de Patrimônio</span></td>
                </tr>
            </table>
        </htmlpageheader>
        
        <htmlpagefooter name="myfooter">
            <div style="font-size: 9pt; text-align: center; padding-top: 3mm; ">
                Página {PAGENO} de {nb}
            </div>
        </htmlpagefooter>
        
        <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
        <sethtmlpagefooter name="myfooter" value="on" />

        <div style="text-align: right; font-size: 7pt;">Data Emissão: <?= util::getData() ?></div>

        mpdf-->
        <table class="items" width="100%" style="border-collapse: collapse;" cellpadding="8">
            <thead>
                <tr>
                    <th width="10%">Tomb.</th>
                    <th width="45%">Produto</th>
                    <th width="25%">Local - Departamento</th>
                    <th width="20%">Estado Conservação</th>
                    <?php if ($obs) { ?>
                        <th width="20%">Obsevação</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <!-- ITEMS HERE -->
                <?php for ($i = 0; $i < count($patrimonios); $i++) { ?>
                    <tr>
                        <td>
                            <?= $patrimonios[$i]->getPatrimonioid() ?>
                        </td>
                        <td>
                            <?= $patrimonios[$i]->getProdutonome(TRUE) ?>
                        </td>
                        <td>
                            <?= $patrimonios[$i]->getLocalnome(TRUE) ?> - <?= $patrimonios[$i]->getDepartamentonome(TRUE) ?>
                        </td>
                        <td>
                            <?= $patrimonios[$i]->getEstadoconservacao(TRUE) ?>
                        </td>
                        <?php if ($obs) { ?>
                        <td>
                            <?= $patrimonios[$i]->getObs(TRUE) ?>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>