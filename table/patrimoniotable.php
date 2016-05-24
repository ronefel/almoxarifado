<div id="patrimoniotable">
    <?php include '../script.php'; ?>
    <script>
        $(function() {
            $('.table').dataTable({
                "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                "order": [[2, 'asc']]
            });
        });
    </script>
    <div id="patrimoniotable-body">
        <div class="toolbar" style="font-size: 1.3em;"><b>Patrimonios Cadastrados</b></div>
        <br/>
        <div>
            <button 
                id="bnovoCadastro" 
                data-evento="patrimonio" 
                data-titulo="Cadastrar Patrimonio">
                Novo Patrimonio
            </button>
            <button 
                id="bimprimir" 
                data-evento="patrimonio" 
                data-titulo="Relatório de Estoque">
                Imprimir
            </button>
        </div>
        <br/>
        <div class="ui-widget-content table-content">
            <table class="table" width="auto" cellspacing="0">
                <thead class="ui-widget-header">
                    <tr>
                        <th style="width: 100px;">Ações</th>
                        <th style="width: 100px;">Patrimônio</th>
                        <th style="width: auto;">Descrição</th>
                        <th style="width: auto;">Local - Departamento</th>
                        <th style="width: auto;">Categoria</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($patrimonios); $i++) { ?>
                        <tr>
                            <td>
                                <button 
                                    class="acao-editar" 
                                    data-evento="patrimonio" 
                                    data-titulo="Editar Patrimonio" 
                                    data-id="<?= $patrimonios[$i]->getPatrimonioid() ?>">
                                    Editar
                                </button>
                                <button 
                                    class="acao-excluir" 
                                    data-evento="patrimonio" 
                                    data-titulo="Excluir Patrimonio" 
                                    data-id="<?= $patrimonios[$i]->getPatrimonioid() ?>">
                                    Excluir
                                </button>
                            </td>
                            <td>
                                <?= $patrimonios[$i]->getPatrimonioid() ?>
                            </td>
                            <td>
                                <?= $patrimonios[$i]->getPatrimoniodescricao(TRUE) ?>
                            </td>
                            <td>
                                <?= $patrimonios[$i]->getLocalnome(TRUE) ?> - <?= $patrimonios[$i]->getDepartamentonome(TRUE) ?>
                            </td>
                            <td>
                                <?= $patrimonios[$i]->getCategorianome(TRUE) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>