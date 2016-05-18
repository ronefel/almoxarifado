<div id="produtogrupotable">
    <?php include '../script.php'; ?>
    <script>
        $(function(){
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
    <div id="produtogrupotable-body">
        <div class="toolbar"><b>Grupos de Produtos</b></div>
        <br/>
        <div>
            <button 
                id="bnovoCadastro" 
                data-evento="produtogrupo" 
                data-titulo="Cadastrar Grupo de Produto">
                Novo Grupo
            </button>
        </div>
        <br/>
        <div class="ui-widget-content table-content">
            <table class="table" width="auto" cellspacing="0">
                <thead class="ui-widget-header">
                    <tr>
                        <th style="width: 100px;">Ações</th>
                        <th style="width: 100px;">Código</th>
                        <th style="width: auto;">Grupo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($produtogrupos); $i++) { ?>
                        <tr>
                            <td>
                                <button 
                                    class="acao-editar" 
                                    data-evento="produtogrupo" 
                                    data-titulo="Editar Grupo de Produto" 
                                    data-id="<?= $produtogrupos[$i]->getProdutogrupoid() ?>">
                                    Editar
                                </button>
                                <button 
                                    class="acao-excluir" 
                                    data-evento="produtogrupo" 
                                    data-titulo="Excluir Grupo de Produto" 
                                    data-id="<?= $produtogrupos[$i]->getProdutogrupoid() ?>">
                                    Excluir
                                </button>
                            </td>
                            <td>
                                <?= $produtogrupos[$i]->getProdutogrupoid() ?>
                            </td>
                            <td>
                                <?= $produtogrupos[$i]->getNome(TRUE) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>