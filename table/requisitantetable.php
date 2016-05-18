<div id="requisicaotable">
    <?php include_once '../script.php'; ?>
    <script>
        $(function() {

            $("#fornseltable").selectmenu({width: '28.5em'}).selectmenu("menuWidget").addClass("overflow");

            $("#datainicial, #datafinal").datepicker({
                dateFormat: "dd/mm/yy"
            });
            $('.table').dataTable({
                "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                "order": [[5, 'asc']]
            });
        });
    </script>
    <div id="requisicaotable-body">
        <div class="linha-form">
            <div class="coluna-form">
                <div class="toolbar" style="font-size: 1.3em;"><b>Requisições</b></div>
                <br/>
                <div>
                    <button 
                        id="bnovoCadastro" 
                        data-evento="requisitante" 
                        data-titulo="Nova Requisição">
                        Nova Requisição
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
                                        value="3"> Entregues
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
                                            id="requisicaoid-search"
                                            name="requisicaoid"
                                            type="text"
                                            size="12"
                                            <?php if (isset($_POST['requisicaoid'])) { ?>
                                                value="<?= $_POST['requisicaoid'] ?>"
                                            <?php } else { ?>
                                                value=""
                                            <?php } ?>
                                            class="text ui-widget-content ui-corner-all" >
                                    </div>
                                    <div class="coluna-form">
                                        <label>Data Inicial</label>
                                        <input 
                                            id="datainicial"
                                            name="datainicial"
                                            type="text"
                                            placeholder="dd/mm/yyyy"
                                            size="10"
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
                                            size="10"
                                            <?php if (isset($_POST['datafinal'])) { ?>
                                                value="<?= $_POST['datafinal'] ?>"
                                            <?php } else { ?>
                                                value=""
                                            <?php } ?>
                                            class="text ui-widget-content ui-corner-all" >
                                    </div>                                 
                                    <div class="coluna-form">
                                        <button 
                                            id="filtrar" 
                                            style="position: relative; padding: 4px 4px 3px 4px; top: 19px;">
                                            Filtrar
                                        </button>
                                    </div>
                                    <div class="coluna-form">
                                        <button 
                                            id="bimprimirfiltro" 
                                            style="position: relative; padding: 4px 4px 3px 4px; top: 19px;"
                                            data-evento="requisitante" 
                                            data-titulo="Relatório de Requisição">
                                            Imprimir
                                        </button>
                                    </div>   
                                </div>
                            </div>
                        </div>
                        <input
                            type="hidden"
                            name="control"
                            value="search">
                        <input
                            type="hidden"
                            name="usuarioid"
                            value="<?= $usuario->getUsuarioid() ?>">
                    </form>
                </div>
            </div>
        </div>

        <div class="ui-widget-content table-content">
            <table class="table" width="auto" cellspacing="0">
                <thead class="ui-widget-header">
                    <tr>
                        <th style="width: 100px;">Ações</th>
                        <th style="width: 100px;">Código</th>
                        <th style="width: auto;">D. Emissão</th>
                        <th style="width: auto;">D. Aprovação</th>
                        <th style="width: auto;">D. Entrega</th>
                        <th style="width: auto;">Situação</th>
                        <th style="width: auto;">Nome do Requisitante</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($requisicoes); $i++) { ?>
                        <tr>
                            <td>
                                <div style="width: 90px;">
                                    <?php if ($requisicoes[$i]->getRequisicaosituacao() == 1) { ?>
                                        <button 
                                            class="acao-editar" 
                                            data-evento="requisitante" 
                                            data-titulo="Editar Requisicao" 
                                            data-id="<?= $requisicoes[$i]->getRequisicaoid() ?>">
                                            Editar
                                        </button>
                                        <button 
                                            class="acao-excluir" 
                                            data-evento="requisitante" 
                                            data-titulo="Excluir Requisicao" 
                                            data-id="<?= $requisicoes[$i]->getRequisicaoid() ?>">
                                            Excluir
                                        </button>
                                    <?php } ?>
                                    <button 
                                        class="acao-consultar" 
                                        data-evento="requisitante" 
                                        data-titulo="Consultar Requisicao" 
                                        data-id="<?= $requisicoes[$i]->getRequisicaoid() ?>">
                                        Consultar
                                    </button>
                                </div>
                            </td>
                            <td>
                                <?= $requisicoes[$i]->getRequisicaoid() ?>
                            </td>
                            <td>
                                <?= $requisicoes[$i]->getRequisicaoemissao(TRUE) ?>
                            </td>
                            <td>
                                <?= $requisicoes[$i]->getRequisicaoaprovacao(TRUE) ?>
                            </td>
                            <td>
                                <?= $requisicoes[$i]->getRequisicaoentrega(TRUE) ?>
                            </td>
                            <td>
                                <?= $requisicoes[$i]->getRequisicaosituacaonome() ?>
                            </td>
                            <td>
                                <?= $requisicoes[$i]->getUsuarionome() ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>