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
                font-size: 12px;
                vertical-align: middle;
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
                font-size: 12px;
            }
        </style>
    </head>
    <body>

        <!--mpdf
        <htmlpageheader name="myheader">
            <table width="100%">
                <tr>
                    <td width="25%"><img src="../report/css/imagens/logo.jpg"></td>
                    <td width="75%"><br/><span style="font-size: 16pt;">Almoxarifado</span><br/><span style="font-size: 16pt; font-weight: bold; ">Relatório de Requisições</span></td>
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

        <div style="font-size: 9pt;">De: <?= $filters->datainicial ?> Até: <?= $filters->datafinal ?></div>
        <?php
        $ofs = "";
        if (is_array($filters->situacao) && count($filters->situacao) > 0) {

            for ($i = 0; $i < count($filters->situacao); $i++) {
                if($filters->situacao[$i] == 1){
                    $ofs .= "Abertos; ";
                }
                if($filters->situacao[$i] == 2){
                    $ofs .= "Aprovados; ";
                }
                if($filters->situacao[$i] == 3){
                    $ofs .= "Entregues; ";
                }
                if($filters->situacao[$i] == 4){
                    $ofs .= "Reprovados; ";
                }
            }
        } else {

            $ofs = "Abertos; Aprovados; Entregues; Reprovados;";
        }
        ?>
        <div style="font-size: 9pt;">Situação: <?= $ofs ?></div>
        
        <div style="text-align: right; font-size: 7pt;">Data Emissão: <?= util::getData() ?></div>

        mpdf-->
        <table class="items" width="100%" style="border-collapse: collapse;" cellpadding="8">
            <thead>
                <tr>
                    <th width="8%">Cód.</th>
                    <th width="12%">D. Emissão</th>
                    <th width="60%">Requisitante</th>
                    <th width="10%">Situação</th>
                    <th width="10%">Valor</th>
                </tr>
            </thead>
            <tbody>
                <!-- ITEMS HERE -->
                <?php
                $valortotal = 0;
                for ($i = 0; $i < count($requisicoes); $i++) {
                    $valortotal += $requisicoes[$i]->getRequisicaovalortotal();
                    ?>
                    <tr>
                        <td style="text-align: right;">
                            <?= $requisicoes[$i]->getRequisicaoid() ?>
                        </td>
                        <td style="text-align: center;">
                            <?= $requisicoes[$i]->getRequisicaoemissao(TRUE) ?>
                        </td>
                        <td>
                            <?= $requisicoes[$i]->getUsuarionome("form") ?>
                        </td>
                        <td>
                            <?= $requisicoes[$i]->getRequisicaosituacaonome() ?>
                        </td>
                        <td style="text-align: right;">
                            <?= $requisicoes[$i]->getRequisicaovalortotal("form") ?>
                        </td>
                    </tr>
                <?php } ?>
                    <tr>
                        <td class="blanktotal" colspan="3" rowspan="1"></td>
                        <td class="totals" colspan="1">Total:</td>
                        <td class="totals"><?= number_format($valortotal, 2, ',', '') ?></td>
                    </tr>
            </tbody>
        </table>
    </body>
</html>