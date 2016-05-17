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
                    <td width="45%" align="center"><br/><span style="font-size: 16pt;">Almoxarifado</span><p><span style="font-size: 16pt; font-weight: bold; ">Requisição N° <?= $requisicaoDados->getRequisicaoid() ?></span></p></td>
                    <td width="30%" style="vertical-align: middle;" align="center"><span style="font-size: 20pt; font-weight: bold;"></span><span style="font-size: 10pt; font-weight: bold;"><br/>Situação: <?= $requisicaoDados->getRequisicaosituacaonome() ?></span></td>
                </tr>
        
            </table>
        </htmlpageheader>
        
        <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
        <sethtmlpagefooter name="myfooter" value="on" />

        <div style="margin: 20px 0;">
        <table style="height: 92px;" width="100%">
            <tbody>
                <tr>
                    <td><strong>Requisitante:</strong> <?= $requisicaoDados->getUsuarionome() ?></td>
                    <td><strong>Data Emiss&atilde;o:</strong> <?= $requisicaoDados->getRequisicaoemissao() ?></td>
                </tr>
                <tr>
                    <td><strong>Local:</strong> <?= $requisicaoDados->getLocalnome() ?></td>
                    <td><strong>Data Aprova&ccedil;&atilde;o:</strong> <?= $requisicao->getRequisicaoaprovacao() ?></td>
                </tr>
                <tr>
                    <td><strong>Departamento:</strong> <?= $requisicaoDados->getDepartamentonome() ?></td>
                    <td><strong>Data Entrega:</strong> <?= $requisicaoDados->getRequisicaoentrega() ?></td>
                </tr>
            </tbody>
        </table>
        
        </div>
        mpdf-->
        <table class="items" width="100%" style="border-collapse: collapse;" cellpadding="8">
            <thead>
                <tr>
                    <th width="76%">Descrição do Produto</th>
                    <th width="6%">Unid.</th>
                    <th width="8%">Quant.</th>
                    <th width="10%">V. Total</th>
                </tr>
            </thead>
            <tbody>
                <!-- ITEMS HERE -->
                <?php
                $valortotal = 0;
                for ($i = 0; $i < count($itens); $i++) {
                    $valortotal += $itens[$i]->getValortotal();
                    ?>
                    <tr>
                        <td>
                            <?= $itens[$i]->getProdutonome(TRUE) ?>
                        </td>
                        <td style="text-align: center;">
                            <?= $itens[$i]->getUnd(TRUE) ?>
                        </td>
                        <td style="text-align: right;">
                            <?= $itens[$i]->getQuantidade("form") ?>
                        </td>
                        <td style="text-align: right;">
                            <?= $itens[$i]->getValortotal("form") ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="blanktotal" colspan="2" rowspan="1"></td>
                    <td class="totals" colspan="1">Total:</td>
                    <td class="totals"><?= number_format($valortotal, 2, ',', '') ?></td>
                </tr>
            </tbody>
        </table>
        <div style="margin-top: 20px;">Declaro que recebi os itens acima listados</div>
        <div style="text-align: center; margin-top: 30px;">
            __________________________________________
            <br/>
            <?= $requisicaoDados->getUsuarionome() ?>
        </div>
    </body>
</html>