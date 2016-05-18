<div id="fornecedorgrupotable">
    <?php include '../script.php'; ?>
    <script>
        $(function(){
            $('.table').dataTable({
                "scrollX": true,
                "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                "order": [[2, 'asc']]
            });
        });
    </script>
    <div id="fornecedorgrupotable-body">
        <div class="toolbar"><b>Grupos de Fornecedores</b></div>
        <br/>
        <div>
            <button 
                id="bnovoCadastro" 
                data-evento="fornecedorgrupo" 
                data-titulo="Cadastrar Grupo de Fornecedor">
                Novo Grupo
            </button>
        </div>
        <br/>
        <div class="ui-widget-content table-content">
            <table class="table" width="967px" cellspacing="0">
                <thead class="ui-widget-header">
                    <tr>
                        <th style="width: 100px;">Ações</th>
                        <th style="width: 100px;">Código</th>
                        <th style="width: auto;">Grupo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($fornecedorgrupo); $i++) { ?>
                        <tr>
                            <td>
                                <button 
                                    class="acao-editar" 
                                    data-evento="fornecedorgrupo" 
                                    data-titulo="Editar Grupo de Fornecedor" 
                                    data-id="<?= $fornecedorgrupo[$i]->getFornecedorgrupoid() ?>">
                                    Editar
                                </button>
                                <button 
                                    class="acao-excluir" 
                                    data-evento="fornecedorgrupo" 
                                    data-titulo="Excluir Grupo de Fornecedor" 
                                    data-id="<?= $fornecedorgrupo[$i]->getFornecedorgrupoid() ?>">
                                    Excluir
                                </button>
                            </td>
                            <td>
                                <?= $fornecedorgrupo[$i]->getFornecedorgrupoid() ?>
                            </td>
                            <td>
                                <?= $fornecedorgrupo[$i]->getFornecedorgruponome(TRUE) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>