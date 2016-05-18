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
                    <td width="75%"><br/><span style="font-size: 16pt;">Almoxarifado</span><br/><span style="font-size: 16pt; font-weight: bold; ">Relatório de Movimento</span></td>
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
        if (is_array($filters->operacao) && count($filters->operacao) > 0) {

            for ($i = 0; $i < count($filters->operacao); $i++) {
                if($filters->operacao[$i] == 1){
                    $ofs .= "Entrada; ";
                }
                if($filters->operacao[$i] == 2){
                    $ofs .= "Saída; ";
                }
            }
        } else {

            $ofs = "Entrada; Saída;";
        }
        ?>
        <div style="font-size: 9pt;">Operação: <?= $ofs ?></div>
        
        <div style="text-align: right; font-size: 7pt;">Data Emissão: <?= util::getData() ?></div>

        mpdf-->
        <table class="items" width="100%" style="border-collapse: collapse;" cellpadding="8">
            <thead>
                <tr>
                    <th width="10%">Data</th>
                    <th width="32%">Descrição do Produto</th>
                    <th width="6%">Unid.</th>
                    <th width="8%">Quant.</th>
                    <th width="10%">V. Total</th>
                    <th width="8%">Oper.</th>
                    <th width="26%">Fornecedor/Requisitante</th>
                </tr>
            </thead>
            <tbody>
                <!-- ITEMS HERE -->
                <?php
                for ($i = 0; $i < count($estoquemovimento); $i++) {
                    $estoquetotal += $estoquemovimento[$i]->getEstoqueatual();
                    ?>
                    <tr>
                        <td>
                            <?= $estoquemovimento[$i]->getEstoquemovimentodata(TRUE) ?>
                        </td>
                        <td>
                            <?= $estoquemovimento[$i]->getProdutonome(TRUE) ?>
                        </td>
                        <td style="text-align: center;">
                            <?= $estoquemovimento[$i]->getUnd(TRUE) ?>
                        </td>
                        <td style="text-align: right;">
                            <?= $estoquemovimento[$i]->getQuantidade("form") ?>
                        </td>
                        <td style="text-align: right;">
                            <?= $estoquemovimento[$i]->getValortotal("form") ?>
                        </td>
                        <td>
                            <?= $estoquemovimento[$i]->getOperacaonome("form") ?>
                        </td>
                        <td>
                            <?= $estoquemovimento[$i]->getFantazia() ?><?= $estoquemovimento[$i]->getUsuarionome() ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <div style=" margin-top: 80px;"></div>
        <table class="items" width="76%" style="border-collapse: collapse;" cellpadding="8">
            <thead>
                <tr>
                    <th width="46%">Descrição do Produto</th>
                    <th width="10%">Unid.</th>
                    <th width="10%">Entrada</th>
                    <th width="10%">Saída</th>
                </tr>
            </thead>
            <tbody>
                <!-- ITEMS HERE -->
                <?php
                for ($i = 0; $i < count($estoquemovimentototal); $i++) {
                    ?>
                    <tr>
                        <td>
                            <?= $estoquemovimentototal[$i]->getProdutonome(TRUE) ?>
                        </td>
                        <td style="text-align: center;">
                            <?= $estoquemovimentototal[$i]->getUnd(TRUE) ?>
                        </td>
                        <td style="text-align: right;">
                            <?= $estoquemovimentototal[$i]->getTotalentrada("form") ?>
                        </td>
                        <td style="text-align: right;">
                            <?= $estoquemovimentototal[$i]->getTotalsaida("form") ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>