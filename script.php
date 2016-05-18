<?php
include_once 'config.php';
?>
<script>
    $(function() {
        $.extend( $.fn.dataTable.defaults, {
            "scrollY": "400px",
            "scrollX": true,
            "paging": false,
            "oLanguage": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Busca Rápida ",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            }
        });
        $("div.dataTables_scrollHeadInner table thead").removeClass("ui-widget-header");
        $("div.dataTables_scrollHeadInner table thead").css("color", "#ffffff");
        $("div.dataTables_scrollBody table thead").removeClass("ui-widget-header");
        $("div.dataTables_scrollBody").css("height", "").css("min-height", "25px").css("max-height", "400px");
        $("div.dataTables_scrollHead").addClass("ui-widget-header");
        $("div.dataTables_filter input").addClass(" ui-widget-content ui-corner-all");

        $(document).tooltip({track: true});
        $("#bnovoCadastro").button({icons: {primary: "ui-icon-plusthick"}});
        $("#bimprimir").button({icons: {primary: "ui-icon-print"}});
        $("#bimprimirfiltro").button({icons: {primary: "ui-icon-print"}});
        $("#filtrar").button({icons: {primary: "ui-icon-folder-open"}});
        $(".acao-editar").button({icons: {primary: "ui-icon-pencil"}, text: false});
        $(".acao-excluir").button({icons: {primary: "ui-icon-trash"}, text: false});
        $(".acao-consultar").button({icons: {primary: "ui-icon-search"}, text: false});
        $(".acao-aprovar").button({icons: {primary: "ui-icon-check"}, text: false});
        $(".acao-receber").button({icons: {primary: "ui-icon-cart"}, text: false});
        $(".acao-entregar").button({icons: {primary: "ui-icon-cart"}, text: false});
        $(".acao-reprovar").button({icons: {primary: "ui-icon-close"}, text: false});
        $(".acao-cancelar").button({icons: {primary: "ui-icon-circle-close"}, text: false});
        $(".acao-editar .ui-button-text, .acao-excluir .ui-button-text, .acao-consultar .ui-button-text, .acao-aprovar .ui-button-text, .acao-receber .ui-button-text, .acao-entregar .ui-button-text, .acao-reprovar .ui-button-text, .acao-cancelar .ui-button-text").css('padding', '0');
        $(".acao-editar,.acao-excluir,.acao-consultar,.acao-aprovar,.acao-receber,.acao-entregar,.acao-reprovar,.acao-cancelar").css('width', '2em').css("margin", "1px -1px");

        $("#bnovoCadastro").click(function() {
            var event = $(this).attr("data-evento");
            var title = $(this).attr("data-titulo");
            var param = {situacao: "novo"};
            openForm(event, title, param);

        });
        $("#bimprimir").click(function() {
            var event = $(this).attr("data-evento");
            var title = $(this).attr("data-titulo");
            var param = {control: "relatorio"};
            openReport(event, title, param);

        });

        $("#bimprimirfiltro").click(function() {
            var event = $(this).attr("data-evento");
            var title = $(this).attr("data-titulo");
            $("#formsearch input[type=hidden][name=control]").val("relatorio");
            var param = $("#formsearch").serialize();
            $("#formsearch input[type=hidden][name=control]").val("search");
            openReport(event, title, param);

        });

        $("#filtrar").click(function() {
            var param = $("#formsearch").serialize();
            search(param);
            return false;
        });

        $("#formsearch").submit(function() {

            return false;
        });

        $(".acao-editar").click(function() {
            var event = $(this).attr("data-evento");
            var title = $(this).attr("data-titulo");
            var id = $(this).attr("data-id");
            var param = {id: id, situacao: "editar"};
            openForm(event, title, param);
        });

        $(".acao-aprovar").click(function() {
            var event = $(this).attr("data-evento");
            var title = $(this).attr("data-titulo");
            var id = $(this).attr("data-id");
            var param = {id: id, situacao: "aprovar"};
            openForm(event, title, param);
        });

        $(".acao-consultar").click(function() {
            var event = $(this).attr("data-evento");
            var title = $(this).attr("data-titulo");
            var id = $(this).attr("data-id");
            var param = {id: id, situacao: "consultar"};
            openForm(event, title, param);
        });

        $(".acao-receber").click(function() {
            var event = $(this).attr("data-evento");
            var title = $(this).attr("data-titulo");
            var id = $(this).attr("data-id");
            var param = {id: id, situacao: "receber"};
            openForm(event, title, param);
        });

        $(".acao-entregar").click(function() {
            var event = $(this).attr("data-evento");
            var title = $(this).attr("data-titulo");
            var id = $(this).attr("data-id");
            var param = {id: id, situacao: "entregar"};
            openForm(event, title, param);
        });

        $(".acao-reprovar").click(function() {
            var event = $(this).attr("data-evento");
            var title = $(this).attr("data-titulo");
            var id = $(this).attr("data-id");
            var param = {id: id, situacao: "reprovar"};
            openSubform(event, title, param);
        });

        $(".acao-excluir").click(function() {
            var confirma = confirm("CUIDADO: Esta ação não poderá ser desfeita!\nSe realmente deseja excluir clique em OK.");
            if (confirma) {
                var event = $(this).attr("data-evento");
                var title = $(this).attr("data-titulo");
                var id = $(this).attr("data-id");
                var param = [
                    {name: event + "id", value: id},
                    {name: "control", value: "excluir"}
                ];

                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/" + event + "controler.php",
                    data: param,
                    dataType: "html",
                    cache: false,
                    success: function(html) {
                        if (html === "sucesso") {
                            setTimeout(function() {
                                carregarIndex(pagina);
                            }, 10);
                        } else {
                            $("#dialog-alert p").html("<span class='ui-icon ui-icon-info' style='float:left; margin:0 7px 50px 0;'></span>" + html);
                            $("#dialog-alert").dialog('open');
                        }
                    }
                });
            }
        });

    });
</script>