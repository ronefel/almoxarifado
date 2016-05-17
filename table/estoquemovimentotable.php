<div id="estoquemovimentotable">
    <?php include_once '../script.php'; ?>
    <script>
        $(function() {

            //$("#fornseltable, #requiseltable").selectmenu({width: '28em'}).selectmenu("menuWidget").addClass("overflow");
            //$("#requiseltable").selectmenu({width: '28em'}).selectmenu("menuWidget").addClass("overflow");
            
            $("#requiseltable, #prodseltable").combobox();
            
            $("#datainicial, #datafinal").datepicker({
                dateFormat: "dd/mm/yy"
            });

            $('.table').dataTable({
                "columnDefs": [
                    {
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    },
                    {
                        "targets": [1],
                        "visible": false,
                        "orderable": false,
                        "searchable": false
                    }
                ],
                "order": [[1, 'desc']]
            });
        });
    </script>
    <div id="estoquemovimentotable-body">
        <div class="linha-form">
            <div class="coluna-form" style="width: 180px;">
                <div class="toolbar" style="font-size: 1.3em;"><b>Movimentação do Estoque</b></div>
                <br/>
                <div>
                    <button 
                        id="bnovoCadastro" 
                        data-evento="estoquemovimento" 
                        data-titulo="Novo Movimento do Estoque">
                        Novo Movimento
                    </button>
                </div>
            </div>
            <div class="coluna-form" style="float: right;">
                <div class="table-search" style="margin-bottom: -5px;">
                    <form id="formsearch">
                        <div class="linha-form">
                            <div class="coluna-form ui-widget-content" style="width: 110px; height: 97px;">
                                <div class="ui-widget-header">Mostrar</div>
                                <div>
                                    <input
                                        type="checkbox"
                                        name="operacao[]"
                                        class="checkbox"
                                        <?php
                                        if (isset($_POST['operacao'])) {
                                            if (in_array("1", $_POST['operacao'])) {
                                                ?>
                                                checked="checked"
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            checked="checked"
                                        <?php } ?>
                                        value="1"> Entradas
                                </div>
                                <div>
                                    <input 
                                        type="checkbox"
                                        name="operacao[]"
                                        class="checkbox"
                                        <?php
                                        if (isset($_POST['operacao'])) {
                                            if (in_array("2", $_POST['operacao'])) {
                                                ?>
                                                checked="checked"
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            checked="checked"
                                        <?php } ?>
                                        value="2"> Saídas
                                </div>
                            </div>
                            <div class="coluna-form ui-widget-content" style="width: 42em; margin: 0 -3px 10px 4px;">
                                <div class="ui-widget-header">Filtro</div>
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
                                    <div class="coluna-form" style="width: 380px;">
<!--                                        <label> Fornecedor </label>
                                        <select 
                                            id="fornseltable" 
                                            name="fornecedorid" >
                                            <option value="">&nbsp;</option>
                                            <?php for ($i = 0; $i < count($fornecedores); $i++) { ?>
                                                <option value="<?= $fornecedores[$i]->getFornecedorid() ?>"
                                                <?php if ((isset($_POST['fornecedorid'])) && $_POST['fornecedorid'] == $fornecedores[$i]->getFornecedorid()) { ?>
                                                            selected="selected"
                                                        <?php } ?>><?= $fornecedores[$i]->getFantazia(TRUE) ?></option>
                                                    <?php } ?>
                                        </select>
                                        <label> Requisitante </label>-->
                                                        
                                        <label> Produto </label>
                                        <select 
                                            id="prodseltable" 
                                            name="produtoid" >
                                            <option value="">&nbsp;</option>
                                            <?php for ($i = 0; $i < count($produtos); $i++) { ?>
                                                <option value="<?= $produtos[$i]->getProdutoid() ?>"
                                                <?php if ((isset($_POST['produtoid'])) && $_POST['produtoid'] == $produtos[$i]->getProdutoid()) { ?>
                                                            selected="selected"
                                                        <?php } ?>><?= $produtos[$i]->getProdutonome(TRUE) ?></option>
                                                    <?php } ?>
                                        </select>
                                        <label> Requisitante </label>
                                        <select
                                            id="requiseltable" 
                                            name="usuarioid" >
                                            <option value="">&nbsp;</option>
                                            <?php for ($i = 0; $i < count($requisitantes); $i++) { ?>
                                                <option value="<?= $requisitantes[$i]->getUsuarioid() ?>"
                                                <?php if ((isset($_POST['usuarioid'])) && $_POST['usuarioid'] == $requisitantes[$i]->getUsuarioid()) { ?>
                                                            selected="selected"
                                                        <?php } ?>><?= $requisitantes[$i]->getUsuarionome(TRUE) ?></option>
                                                    <?php } ?>
                                        </select>
                                    </div>
                                    <div class="coluna-form" style="height: 116px;">    
                                        <button 
                                            id="bimprimirfiltro" 
                                            style="position: relative; padding: 4px 4px 3px 4px; top: 19px;"
                                            data-evento="estoquemovimento" 
                                            data-titulo="Relatório de Movimento no Estoque">
                                            Imprimir
                                        </button>
                                        <br/>
                                        <button 
                                            id="filtrar" 
                                            style="position: relative; padding: 4px 4px 3px 4px; top: 48px;">
                                            Filtrar
                                        </button>
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
            <table class="table" width="auto" cellspacing="0">
                <thead class="ui-widget-header">
                    <tr>
                        <th style="width: 100px;">Ações</th>
                        <th style="width: auto;">Data</th>
                        <th style="width: auto;">D. Emissão</th>
                        <th style="width: auto;">Descrição do Produto</th>
                        <th style="width: auto;">Unid.</th>
                        <th style="width: auto; text-align: right;">Quantidade</th>
                        <th style="width: auto; text-align: right;">Valor Total</th>
                        <th style="width: auto;">Operação</th>
                        <th style="width: auto;">Fornecedor/Requisitante</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($estoquemovimento); $i++) { ?>
                        <tr>
                            <td>
                                <div style="width: 90px;">
                                    <?php if ($estoquemovimento[$i]->getCompraid() == "" && $estoquemovimento[$i]->getRequisicaoid() == "") { ?>
                                        <button 
                                            class="acao-editar" 
                                            data-evento="estoquemovimento" 
                                            data-titulo="Editar Movimento do Estoque" 
                                            data-id="<?= $estoquemovimento[$i]->getEstoquemovimentoid() ?>">
                                            Editar
                                        </button>
                                        <button 
                                            class="acao-excluir" 
                                            data-evento="estoquemovimento" 
                                            data-titulo="Excluir Movimento do Estoque" 
                                            data-id="<?= $estoquemovimento[$i]->getEstoquemovimentoid() ?>">
                                            Excluir
                                        </button>
                                    <?php } ?>
                                    <button 
                                        class="acao-consultar" 
                                        data-evento="estoquemovimento" 
                                        data-titulo="Consultar Movimento do Estoque" 
                                        data-id="<?= $estoquemovimento[$i]->getEstoquemovimentoid() ?>">
                                        Consultar
                                    </button>
                                </div>
                            </td>
                            <td>
                                <?= $estoquemovimento[$i]->getEstoquemovimentodata() ?>
                            </td>
                            <td>
                                <?= util::dateToBR($estoquemovimento[$i]->getEstoquemovimentodata()) ?>
                            </td>
                            <td>
                                <?= $estoquemovimento[$i]->getProdutonome(TRUE) ?>
                            </td>
                            <td>
                                <?= $estoquemovimento[$i]->getUnd(TRUE) ?>
                            </td>
                            <td style="text-align: right;">
                                <?= $estoquemovimento[$i]->getQuantidade("form") ?>
                            </td>
                            <td style="text-align: right;">
                                <?= number_format($estoquemovimento[$i]->getQuantidade() * $estoquemovimento[$i]->getValorunitario(), 2, ',', '') ?>
                            </td>
                            <td>
                                <?= $estoquemovimento[$i]->getOperacaonome(TRUE) ?>
                            </td>
                            <td>
                                <?= $estoquemovimento[$i]->getFantazia() ?><?= $estoquemovimento[$i]->getUsuarionome() ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>