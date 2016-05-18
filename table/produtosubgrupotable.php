<div id="produtosubgrupotable">
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
    <div id="produtosubgrupotable-body">
        <div class="toolbar"><b>Subgrupos de Produtos</b></div>
        <br/>
        <div>
            <button 
                id="bnovoCadastro" 
                data-evento="produtosubgrupo" 
                data-titulo="Cadastrar Subgrupo de Produto">
                Novo Subgrupo
            </button>
        </div>
        <br/>
        <div class="ui-widget-content table-content">
            <table class="table" width="auto" cellspacing="0">
                <thead class="ui-widget-header">
                    <tr>
                        <th style="width: 100px;">Ações</th>
                        <th style="width: 100px;">Código</th>
                        <th style="width: auto;">Subgrupo</th>
                        <th style="width: auto;">Grupo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($produtosubgrupos); $i++) { ?>
                        <tr>
                            <td>
                                <button 
                                    class="acao-editar" 
                                    data-evento="produtosubgrupo" 
                                    data-titulo="Editar Grupo de Produto" 
                                    data-id="<?= $produtosubgrupos[$i]->getProdutosubgrupoid() ?>">
                                    Editar
                                </button>
                                <button 
                                    class="acao-excluir" 
                                    data-evento="produtosubgrupo" 
                                    data-titulo="Excluir Grupo de Produto" 
                                    data-id="<?= $produtosubgrupos[$i]->getProdutosubgrupoid() ?>">
                                    Excluir
                                </button>
                            </td>
                            <td>
                                <?= $produtosubgrupos[$i]->getProdutosubgrupoid() ?>
                            </td>
                            <td>
                                <?= $produtosubgrupos[$i]->getNome(TRUE) ?>
                            </td>
                            <td>
                                <?= $produtosubgrupos[$i]->getprodutogrupomodel()->getNome(TRUE) ?>
                            </td>
                        </tr>
                    <?php
                    
                    
                    
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>