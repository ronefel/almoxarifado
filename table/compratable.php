<div id="compratable">
    <?php include_once '../script.php'; ?>
    <script>
        $(function() {

            //$("#fornseltable").selectmenu({width: '28em'}).selectmenu("menuWidget").addClass("overflow");

            $("#fornseltable").combobox();

            $("#datainicial, #datafinal").datepicker({
                dateFormat: "dd/mm/yy"
            });
            
            $('.table').dataTable({
            "scrollX": "970px",
                "columnDefs": [
                    {
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }
                ],
                "order": [[5, 'asc'],[1,'desc']]
            });
            
        });
    </script>
    <div id="compratable-body">
        <div class="linha-form">
            <div class="coluna-form">
                <div class="toolbar" style="font-size: 1.3em;"><b>Pedidos de Compras</b></div>
                <br/>
                <div>
                    <button 
                        id="bnovoCadastro" 
                        data-evento="compra" 
                        data-titulo="Novo Pedido de Compra">
                        Novo Pedido
                    </button>
                </div>
            </div>
            <div class="coluna-form" style="float: right;">
                <div class="table-search">
                    <form id="formsearch">
                        <div class="linha-form">
                            <div class="coluna-form ui-widget-content" style="width: 110px;">
                                <div class="ui-widget-header">Mostrar</div>
                                <div>
                                    <input
                                        type="checkbox"
                                        name="situacao[]"
                                        class="checkbox"
                                        <?php
                                        if (isset($_POST['situacao'])) {
                                            if (in_array("1", $_POST['situacao'])) {
                                                ?>
                                                checked="checked"
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            checked="checked"
                                        <?php } ?>
                                        value="1"> Abertos
                                </div>
                                <div>
                                    <input 
                                        type="checkbox"
                                        name="situacao[]"
                                        class="checkbox"
                                        <?php
                                        if (isset($_POST['situacao'])) {
                                            if (in_array("2", $_POST['situacao'])) {
                                                ?>
                                                checked="checked"
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            checked="checked"
                                        <?php } ?>
                                        value="2"> Aprovados
                                </div>
                                <div>
                                    <input 
                                        type="checkbox"
                                        name="situacao[]"
                                        class="checkbox"
                                        <?php
                                        if (isset($_POST['situacao'])) {
                                            if (in_array("3", $_POST['situacao'])) {
                                                ?>
                                                checked="checked"
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            checked="checked"
                                        <?php } ?>
                                        value="3"> Recebidos
                                </div>
                                <div>
                                    <input 
                                        type="checkbox"
                                        name="situacao[]"
                                        class="checkbox"
                                        <?php
                                        if (isset($_POST['situacao'])) {
                                            if (in_array("4", $_POST['situacao'])) {
                                                ?>
                                                checked="checked"
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            checked="checked"
                                        <?php } ?>
                                        value="4"> Reprovados
                                </div>
                            </div>
                            <div class="coluna-form ui-widget-content" style="width: 42em; margin-right: -3px;">
                                <div class="ui-widget-header">Filtro</div>
                                <div class="linha-form">
                                    <div class="coluna-form">
                                        <label>Código</label>
                                        <input 
                                            id="compraid"
                                            name="compraid"
                                            type="text"
                                            size="8"
                                            <?php if (isset($_POST['compraid'])) { ?>
                                                value="<?= $_POST['compraid'] ?>"
                                            <?php } else { ?>
                                                value=""
                                            <?php } ?>
                                            class="text ui-widget-content ui-corner-all" >
                                    </div>
                                    <div class="coluna-form" style="width: 380px;">
                                        <label> Fornecedor </label>
                                        <select 
                                            id="fornseltable" 
                                            name="fornecedorid" 
                                            title="Fornecedor da Compra" >
                                            <option value="">Todos</option>
                                            <?php for ($i = 0; $i < count($fornecedores); $i++) { ?>
                                                <option value="<?= $fornecedores[$i]->getFornecedorid() ?>"
                                                <?php if ((isset($_POST['fornecedorid'])) && $_POST['fornecedorid'] == $fornecedores[$i]->getFornecedorid()) { ?>
                                                            selected="selected"
                                                        <?php } ?>><?= $fornecedores[$i]->getFantazia(TRUE) ?></option>
                                                    <?php } ?>
                                        </select>
                                    </div>
                                    <div class="coluna-form">
                                        <button 
                                            id="bimprimirfiltro" 
                                            style="position: relative; padding: 4px 4px 3px 4px; top: 19px;"
                                            data-evento="compra" 
                                            data-titulo="Relatório de Compras">
                                            Imprimir
                                        </button>
                                    </div>
                                </div>
                                <div class="linha-form">
                                    <div class="coluna-form">
                                        <label>Data Inicial</label>
                                        <input 
                                            id="datainicial"
                                            name="datainicial"
                                            type="text"
                                            placeholder="dd/mm/yyyy"
                                            size="8"
                                            <?php if (isset($_POST['datainicial'])) { ?>
                                                value="<?= $_POST['datainicial'] ?>"
                                            <?php } else { ?>
                                                value=""
                                            <?php } ?>
                                            class="text ui-widget-content ui-corner-all" >
                                    </div>
                                    <div class="coluna-form">
                                        <label>Data Final</label>
                                        <input 
                                            id="datafinal"
                                            name="datafinal"
                                            type="text"
                                            placeholder="dd/mm/yyyy"
                                            size="8"
                                            <?php if (isset($_POST['datafinal'])) { ?>
                                                value="<?= $_POST['datafinal'] ?>"
                                            <?php } else { ?>
                                                value=""
                                            <?php } ?>
                                            class="text ui-widget-content ui-corner-all" >
                                    </div>
                                    <div class="coluna-form" style="width: 388px; text-align: right;">
                                        <button id="filtrar" style="position: relative; padding: 4px 4px 3px 4px; top: 19px;">Filtrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input
                            type="hidden"
                            name="control"
                            value="search">
                    </form>
                </div>
            </div>
        </div>

        
        <div class="ui-widget-content table-content">
            <table class="table" width="99.8%" cellspacing="0">
                <thead class="ui-widget-header">
                    <tr>
                        <th style="width: 100px;">Ações</th>
                        <th style="width: 100px;">Código</th>
                        <th style="width: auto;">D. Emissão</th>
                        <th style="width: auto;">D. Aprovação</th>
                        <th style="width: auto;">D. Recebimento</th>
                        <th style="width: auto;">Situação</th>
                        <th style="width: auto;">Nome do Fornecedor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($compras); $i++) { ?>
                        <tr>
                            <td>
                                <div style="width: 90px;">
                                    <?php if ($compras[$i]->getComprasituacao() == 1) { ?>
                                        <button 
                                            class="acao-editar" 
                                            data-evento="compra" 
                                            data-titulo="Editar Compra" 
                                            data-id="<?= $compras[$i]->getCompraid() ?>">
                                            Editar
                                        </button>
                                        <button 
                                            class="acao-aprovar" 
                                            data-evento="compra" 
                                            data-titulo="Aprovar Compra" 
                                            data-id="<?= $compras[$i]->getCompraid() ?>">
                                            Aprovar
                                        </button>
                                    <?php } if ($compras[$i]->getComprasituacao() <= 2) { ?>
                                        <button 
                                            class="acao-receber" 
                                            data-evento="compra" 
                                            data-titulo="Receber Compra" 
                                            data-id="<?= $compras[$i]->getCompraid() ?>">
                                            Receber
                                        </button>
                                    <?php } if ($compras[$i]->getComprasituacao() < 3) { ?>
                                        <button 
                                            class="acao-reprovar" 
                                            data-evento="comprareprovar" 
                                            data-titulo="Informações Sobre a Reprovação" 
                                            data-id="<?= $compras[$i]->getCompraid() ?>">
                                            Reprovar
                                        </button>
                                    <?php } ?>
                                    <button 
                                        class="acao-excluir" 
                                        data-evento="compra" 
                                        data-titulo="Excluir Compra" 
                                        data-id="<?= $compras[$i]->getCompraid() ?>">
                                        Excluir
                                    </button>
                                    <button 
                                        class="acao-consultar" 
                                        data-evento="compra" 
                                        data-titulo="Consultar Compra" 
                                        data-id="<?= $compras[$i]->getCompraid() ?>">
                                        Consultar
                                    </button>
                                </div>
                            </td>
                            <td>
                                <?= $compras[$i]->getCompraid() ?>
                            </td>
                            <td>
                                <?= $compras[$i]->getCompraemissao(TRUE) ?>
                            </td>
                            <td>
                                <?= $compras[$i]->getCompraaprovacao(TRUE) ?>
                            </td>
                            <td>
                                <?= $compras[$i]->getCompraentrega(TRUE) ?>
                            </td>
                            <td>
                                <?= $compras[$i]->getComprasituacaonome() ?>
                            </td>
                            <td>
                                <?= $compras[$i]->getFantazia() ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>