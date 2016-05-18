<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once '../action/estoquemovimentoAction.php';

$movimento = "";
$situacao = "";
if (isset($_GET['situacao']) && $_GET['situacao'] != "") {

    $situacao = $_GET['situacao'];
}

if (isset($_GET['compraid']) && $_GET['compraid'] != "") {

    $estoquemovimento = new estoquemovimentoModel();
    $estoquemovimento->setCompraid($_GET['compraid']);

    $estoquemovimentos = array(new estoquemovimentoModel());
    $estoquemovimentos = estoquemovimentoAction::listEstoquemovimentocompra($estoquemovimento);

    $movimento = "compra";
}
if (isset($_GET['requisicaoid']) && $_GET['requisicaoid'] != "") {

    $estoquemovimento = new estoquemovimentoModel();
    $estoquemovimento->setRequisicaoid($_GET['requisicaoid']);

    $estoquemovimentos = array(new estoquemovimentoModel());
    $estoquemovimentos = estoquemovimentoAction::listEstoquemovimentorequisicao($estoquemovimento);

    $movimento = "requisicao";
}
?>


<script>
    $(function() {

        $('#itemtable').dataTable({
            "scrollY": "139px",
            "scrollX": false,
            "filter": false,
            "info": false,
            "oLanguage": {
                "sEmptyTable": "Nenhum item adicionado"
            },
            "aoColumns": [
                {"sWidth": "40px", "bSortable": false},
                {"sWidth": "50px", "bSortable": false},
                null,
                {"sWidth": "40px"},
                {"sWidth": "40px", "bSortable": false},
                {"sWidth": "90px", "bSortable": false},
                {"sWidth": "80px", "bSortable": false}
            ]
        });

        setTimeout(function() {
            $("div.dataTables_scrollHeadInner table thead").removeClass("ui-widget-header");
            $("div.dataTables_scrollHeadInner table thead").css("color", "#ffffff");
            $("div.dataTables_scrollBody table thead").removeClass("ui-widget-header");
            $("#produtoitem div.dataTables_scrollBody").css("height", "").css("min-height", "25px").css("max-height", "143px");
            $("div.dataTables_scrollHead").addClass("ui-widget-header");
            $("div.dataTables_filter input").addClass(" ui-widget-content ui-corner-all");
            $("div.dataTables_scrollHeadInner table thead th").css("padding", "3px 3px 3px 10px");
        }, 200);

        $(".acao-excluir-item").button({icons: {primary: "ui-icon-trash"}, text: false});
        $(".acao-excluir-item .ui-button-text").css('padding', '0');
        $(".acao-excluir-item").css('width', '2em');

        $(".acao-excluir-item").click(function() {
            var event = $(this).attr("data-evento");
            var title = $(this).attr("data-titulo");
            var id = $(this).attr("data-id");
            var situacao = $(this).attr("data-situacao");
            var param = {estoquemovimentoid: id, control: "excluir"};

            $.ajax({
                type: "POST",
                url: "<?= $urlroot ?>/controler/" + event + "controler.php",
                data: param,
                dataType: "html",
                cache: false,
                success: function(html) {
                    if (html === "sucesso") {
                        setTimeout(function() {
<?php if ($movimento == "compra") { ?>

                                carregarProdutoitemTable({compraid: compraid, situacao: situacao});
<?php } if ($movimento == "requisicao") { ?>

                                carregarProdutoitemTable({requisicaoid: requisicaoid, situacao: situacao});
<?php } ?>
                        }, 10);
                    } else {
                        $("#dialog-alert p").html("<span class='ui-icon ui-icon-info' style='float:left; margin:0 7px 50px 0;'></span>" + html);
                        $("#dialog-alert").dialog('open');
                    }
                }
            });
        });


    });
</script>

<div style="border-bottom: 1px solid #a6c9e2;">
    <table width="100%" cellspacing="0" id="itemtable" style="border-bottom: 1px solid #d8dcdf;">
        <thead>
            <tr>
                <th>Ações</th>
                <th>Código</th>
                <th>Descrição do Produto</th>
                <th>Unid.</th>
                <th style="text-align: right;">Quantidade</th>
                <th style="text-align: right;">Valor Unitário</th>
                <th style="text-align: right;">Valor Total </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalPedido = "";
            for ($i = 0; $i < count($estoquemovimentos); $i++) {
                ?>
                <tr>
                    <td>
                        <?php if (($estoquemovimentos[$i]->getComprasituacao() == "1" || $estoquemovimentos[$i]->getRequisicaosituacao() == "1") && ($situacao == "novo" || $situacao == "editar")) { ?>
                            <button 
                                class="acao-excluir-item" 
                                data-evento="estoquemovimento" 
                                data-titulo="Remover item da <?= $movimento ?>" 
                                data-situacao ="<?= $situacao ?>"
                                data-id="<?= $estoquemovimentos[$i]->getEstoquemovimentoid() ?>">
                                Excluir
                            </button>
                        <?php } ?>
                    </td>
                    <td>
                        <?= $estoquemovimentos[$i]->getProdutoid() ?>
                    </td>
                    <td>
                        <?= $estoquemovimentos[$i]->getProdutonome() ?>
                    </td>
                    <td>
                        <?= $estoquemovimentos[$i]->getUnd() ?>
                    </td>
                    <td style="text-align: right;">
                        <?= $estoquemovimentos[$i]->getQuantidade("form") ?>
                    </td>
                    <td style="text-align: right;">
                        <?= $estoquemovimentos[$i]->getValorunitario("form") ?>
                    </td>
                    <td style="text-align: right;">
                        <?= $estoquemovimentos[$i]->getValortotal("form") ?>
                    </td>
                </tr>
                <?php
                $totalPedido += $estoquemovimentos[$i]->getValortotal();
            }
            ?>
        </tbody>
    </table>
</div>
<div style="display: table; width: 100%;">
    <div style="float: left; font-size: 13px; margin: 0.3em 0;">
        <?php
        if (count($estoquemovimentos) > 0) {
            if ($estoquemovimentos[0]->getComprasituacao() == 3) {
                $t = TRUE;
                for ($i = 0; $i < count($estoquemovimentos); $i++) {
                    if ($estoquemovimentos[$i]->getOperacao() != 1 && $estoquemovimentos[$i]->getComprasituacao() == 3) {
                        $t = FALSE;
                    }
                }
                if ($t) {
                    echo "<span style='color: green;'>Todos os itens deram entrada no estoque com sucesso.</span>";
                } else {
                    echo "<span style='color: red;'>ERRO: Alguns ou todos os itens não deram entrada no estoque.</span>";
                }
            }
            if ($estoquemovimentos[0]->getRequisicaosituacao() == 3) {
                $t = TRUE;
                for ($i = 0; $i < count($estoquemovimentos); $i++) {
                    if ($estoquemovimentos[$i]->getOperacao() != 2 && $estoquemovimentos[$i]->getRequisicaosituacao() == 3) {
                        $t = FALSE;
                    }
                }
                if ($t) {
                    echo "<span style='color: green;'>Todos os itens deram saída no estoque com sucesso.</span>";
                } else {
                    echo "<span style='color: red;'>ERRO: Alguns ou todos os itens não deram saída no estoque.</span>";
                }
            }
        }
        ?>
    </div>
    <div style="float: right; font-size: 13px; margin: 0.3em;">
        <?php
        if ($movimento == "compra") {
            if (strlen($totalPedido > 0)) {
                echo 'Valor Total do Pedido: R$ ' . number_format($totalPedido, 2, ',', '');
            } else {
                echo 'Valor Total do Pedido: R$ 0,00';
            }
        } else
        if ($movimento == "requisicao") {
            if (strlen($totalPedido > 0)) {
                echo 'Valor Total da Requisição: R$ ' . number_format($totalPedido, 2, ',', '');
            } else {
                echo 'Valor Total da Requisição: R$ 0,00';
            }
        }
        ?>
    </div>
</div>