<?php
include_once 'config.php'; 
//
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/util/sessao.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/action/usuarioAction.php';
$usuario = new usuarioModel();
if (Sessao::existe('usuario')) {
    $usuario = Sessao::get('usuario');
    if ($usuario->getTipousuario() == 2 && !strpos($url, "requisitante")) {
        header("location: " + $urlroot + "/requisitante.php");
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include './head.php'; ?>
        <script>

            var pagina = "";
            var select = "";
            var grupoid = "";
            var localid = "";
            var compraid = "";
            var requisicaoid = "";
            var situacao = "";

            //carrega a pagina solicitada no menu
            function carregarIndex(pagina, param) {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/" + pagina + "controler.php",
                    dataType: "html",
                    data: {"control": "view"},
                    cache: false,
                    success: function (html) {
                        $("#index-body").html(html);
                    }
                });
            }

            function search(param) {
                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/" + pagina + "controler.php",
                    dataType: "html",
                    data: param,
                    cache: false,
                    success: function (html) {
                        $("#index-body").html(html);
                    }
                });
            }

            function carregarProdutoitemTable(param) {
                $.ajax({
                    type: "GET",
                    url: "<?= $urlroot ?>/table/produtoitemTable.php",
                    dataType: "html",
                    data: param,
                    cache: false,
                    success: function (html) {
                        $("#produtoitem").html(html);
                    }
                });
            }
            ;

            /*
             * carrega os options de um select especificado pelo seu id
             * 
             *  parametro String select: Id do select que quer atualizar
             *  
             *  retorno String:
             *  Retorna uma string contendo as tags options do select  
             */
            function carregarSelect(select, id) {
                $.ajax({
                    type: "GET",
                    url: "<?= $urlroot ?>/form/select/" + select + ".php",
                    data: {"id": id},
                    dataType: "html",
                    cache: false,
                    success: function (html) {
                        $("#" + select).html(html);
                        //$("#" + select).selectmenu("refresh");
                    }
                });
            }

            //Muda a primeira letra da string em maiuscula
            function firstUpperCase(str) {
                var pieces = str;
                var j = pieces.charAt(0).toUpperCase();
                pieces = j + pieces.substr(1);
                return pieces;
            }

            //abre o dialog-form 
            function openForm(event, title, param) {

                $("#dialog-form").dialog("option", "title", title);
                $("#dialog-form").dialog("option", "position", {my: "right top", at: "left top", of: $("#bnovoCadastro")});
                $("#dialog-form").dialog("option", "width", 'auto');
                $.ajax({
                    type: "GET",
                    url: "<?= $urlroot ?>/form/" + event + "form.php",
                    data: param,
                    dataType: "html",
                    cache: false,
                    success: function (html) {
                        $("#dialog-form").html(html);
                        $("#dialog-form").dialog('open');
                        $("#carregando").dialog("close");
                    }
                });
            }

            function openSubform(event, title, param) {

                $("#dialog-subform").dialog("option", "title", title);
                //$("#dialog-subform").dialog("option", "position", {my: "right top", at: "left top", of: $("#bnovoCadastro")});
                $("#dialog-subform").dialog("option", "width", 'auto');
                $.ajax({
                    type: "GET",
                    url: "<?= $urlroot ?>/form/" + event + "form.php",
                    data: param,
                    dataType: "html",
                    cache: false,
                    success: function (html) {
                        $("#dialog-subform").html(html);
                        $("#dialog-subform").dialog('open');
                        $("#carregando").dialog("close");
                    }
                });
            }

            function openReport(pagina, title, param) {

                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/" + pagina + "controler.php",
                    dataType: "html",
                    data: param,
                    cache: false,
                    success: function (html) {
                        var tmp = html.split("=");

                        if (tmp[0] === "sucesso") {

                            window.open("http://<?= $_SERVER['SERVER_NAME'] ?><?= $urlroot ?>/report/tmp/" + tmp[1], "_blank");
                            setTimeout(function () {
                                deleteTmp(tmp[1]);
                            }, 10);
                        } else if (tmp[0] === "erro") {

                            $("#dialog-alert p").html("<br/><b>" + tmp[1] + "</b><br/><br/>");
                            $("#dialog-alert").dialog("open");
                        } else {
                            $("#dialog-alert p").html("<br/>" + html + "<br/><br/>");
                            $("#dialog-alert").dialog("open");
                        }
                    }
                });
            }

            function deleteTmp(tmp) {

                $.ajax({
                    type: "POST",
                    url: "<?= $urlroot ?>/controler/tmpcontroler.php",
                    dataType: "html",
                    data: {tmp: tmp},
                    cache: false,
                    beforeSend: function () {
                        $("#carregando").dialog("close");
                    }
                });
            }


            $(function() {
                $("#menu").menu();
                //configuração global para requisições ajax

                $("*", document.body).click(function (event) {
                    event.stopPropagation();
                    $(this).each(function () {
                        if ($(this).hasClass('lastmenu') || $(this).hasClass('report')) {
                            //alert('clico no ultimo menu');
                            $("#menu").hide();
                        } else
                        if (!$(this).hasClass('ui-menu-item')) {
                            //alert('clico fora do menu');
                            $("#menu").hide();
                        }
                    });
//                    var domElement = $( this ).get(-1);
//                    alert(domElement.nodeName);
                });

                $("#bmenu").button();
                $("#bmenu").click(function () {
                    if ($("#menu").is(":visible")) {
                        //$("#menu").hide();
                    } else {
                        $("#menu").show();
                    }
                });

                $("#menu").hover(
                        function () {
                            //$("#menu").show();
                        },
                        function () {
                            $("#menu").hide();
                            $("#menu ul").css("display", "none");
                            //tira o foco do menu 
                            $(document).click();
                        }
                );
                $("#bsair").button({
                    icons: {primary: "ui-icon-power"},
                    text: false
                });
                $("#binicio").button({
                    icons: {primary: "ui-icon-home"},
                    text: false
                });


                $("#menu li.lastmenu").click(function () {
                    pagina = $(this).attr("id");
                    select = "";
                    var control = $(this).attr("data-control");
                    var param = {control: control};
                    carregarIndex(pagina, param);
                });

                $("#menu li.report").click(function () {
                    var event = $(this).attr("data-event");
                    var title = $(this).attr("data-title");
                    openReport(event, title);
                });


                $("#dialog-form, #dialog-subform").dialog({
                    autoOpen: false,
                    modal: true,
                    closeText: "Fechar",
                    resizable: false,
                    open: function () {
                        //remover a legenda do botão fechar ao abrir o dialog
                        //motivo: certos momentos fica congelado na tela do browser
                        $(".ui-tooltip").remove();
                    },
                    close: function () {
                        $(this).html('');
                    }
                });

                $("#dialog-alert").dialog({
                    modal: true,
                    autoOpen: false,
                    width: 400,
                    close: function () {
                        $(this).dialog("option", "width", 350);
                        $(this).dialog("option", "height", "auto");
                    }
                });

                $("#alert-ok").button();
                $("#alert-ok").click(function () {
                    $("#dialog-alert").dialog("close");
                });

                $("#confirm-nao").button();
                $("#confirm-sim").button();

                $("#dialog-confirm").dialog({
                    autoOpen: false,
                    modal: false,
                    width: 'auto',
                    closeText: "Fechar",
                    resizable: false,
                    open: function () {
                        $("#confirm-nao").focus();
                    },
                    close: function () {
                        $("#dialog-confirm p").html('');
                    }
                });

                $("#carregando").dialog({
                    autoOpen: false,
                    modal: true,
                    width: '21.5em',
                    closeOnEscape: true,
                    closeText: "Fechar",
                    draggable: false,
                    resizable: false,
                    create: function (event, ui) {
                        $("div[aria-describedby='carregando'] .ui-dialog-titlebar").remove()
                        // ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix

                    }
                });

                $(".bsair").click(function () {
                    $.ajax({
                        type: "POST",
                        url: "<?= $urlroot ?>/controler/sessioncontroler.php",
                        data: {"control": "sair"},
                        dataType: "html",
                        cache: false,
                        success: function (html) {
                            window.location.href = "<?= $urlroot ?>/login.php";
                        }
                    });
                });

            });
        </script>
        <style>
            #bmenu{position: absolute !important; top: 0.12em; left: 0.2em;}
            #bmenu span{padding: 0 0.5em !important;}
            #bsair, #binicio{top: 0.15em; right: 1em;}
            #bsair span, #binicio span{padding: 0 0 !important;}
        </style>
    </head>
    <body>
        <div class="ui-widget">

            <div class="ui-widget-header reader1" style="display: block;">
                <div style="padding: 0.2em 0 0 5em; float: left;">FAROL Controle de Estoque <?php if (DBHOST == 'localhost') {echo " <span style='color: red;'>LOCALHOST</strong>";} ?></div>
                <div style="float: right;">
                    <button id="bsair" class="bsair">Sair</button>
                </div>
                <div style="float: right; padding: 0.2em 2em 0 0">
                    <?= $usuario->getUsuarionome() ?>
                </div>
                <div style="float: right;">
                    <button id="binicio">Início</button>
                </div>
            </div>
            <button id="bmenu">Menu</button>
            <ul id="menu" style="display: none; position: absolute; top: 1.41em; left: 0.2em">
                <li>Cadastro
                    <ul>
                        <li>Produto
                            <ul>
                                <li class="lastmenu" id="produto" data-control="view">Produto</li>
                                <li class="lastmenu" id="produtogrupo" data-control="view">Grupo de Produto</li>
                                <li class="lastmenu" id="produtosubgrupo" data-control="view">Subgrupo de Produto</li>
                            </ul>
                        </li>
                        <li>Fornecedor
                            <ul>
                                <li class="lastmenu" id="fornecedor" data-control="view">Fornecedor</li>
                                <li class="lastmenu" id="fornecedorgrupo" data-control="view">Grupo de Fornecedor</li>
                            </ul>
                        </li>
                        <li>Usuário/Requisitante
                            <ul>
                                <li class="lastmenu" id="usuario" data-control="view">Usuário/Requisitante</li>
                                <li class="lastmenu" id="local" data-control="view">Local</li>
                                <li class="lastmenu" id="departamento" data-control="view">Departamento</li>
                            </ul>
                        </li>
                        <li class="lastmenu" id="cidade" data-control="view">Cidade</li>
                    </ul>
                </li>
                <li class="lastmenu" id="estoquemovimento" data-control="view">Movimentação de Estoque</li>
                <li class="lastmenu" id="requisicao" data-control="view">Requisição</li>
                <li class="lastmenu" id="compra" data-control="view">Compra</li>
                <li class="lastmenu" id="patrimonio" data-control="view">Patrimônio</li>
                <li>Relatórios
                    <ul>
                        <li class="report" data-event="movimento" data-title="Relatório de Movimentações">
                            Relatório de Movimentações
                        </li>
                        <li class="report" data-event="produto" data-title="Relatório de Estoque">
                            Relatório de Estoque
                        </li>
                        <li>
                            Relatório de Entradas
                        </li>
                        <li>
                            Relatório de Saídas
                        </li>
                        <li>
                            Relatório de Requisições
                        </li>
                        <li>
                            Relatório de Compras
                        </li>
                    </ul>
                </li>
                <li>Configuração
                    <ul>
                        <li class="lastmenu" id="tema" data-control="view">Temas</li>
                        <!--<li>Tabela de Parâmetros</li>
                        <li>Consulta de Logs</li>-->
                    </ul>
                </li>

                <li class="bsair">Sair</li>
            </ul>
            <div class="" id="index-body" style="width: 970px; height: 70%; margin: 20px;">

            </div>
        </div>
        <div id="dialog-form" style="display: none;"></div>
        <div id="dialog-subform" style="display: none;"></div>
        <div id="dialog-alert" title="Aviso !" style="display: none;">
            <p>
                <span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 50px 0;"></span>
            </p>
            <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
                <div class="ui-dialog-buttonset">
                    <button id="alert-ok" type="button">Ok</button>
                </div>
            </div>
        </div>
        <div id="dialog-confirm" style="display: none;">
            <p style="margin: 1em 0;"></p>

            <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
                <div class="ui-dialog-buttonset">
                    <button id="confirm-nao" type="button">Não</button>
                    <button id="confirm-sim" type="button">Sim</button>
                </div>
            </div>
        </div>
        <div id="carregando" style="display: none;">
            <img src="imagens/carregando.gif">
        </div>
    </body>
</html>
<?php require_once './controler/sessioncontroler.php'; ?>