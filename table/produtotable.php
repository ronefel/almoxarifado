<div id="produtotable">
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
    <div id="produtotable-body">
        <div class="toolbar" style="font-size: 1.3em;"><b>Produtos Cadastrados</b></div>
        <br/>
        <div>
            <button 
                id="bnovoCadastro" 
                data-evento="produto" 
                data-titulo="Cadastrar Produto">
                Novo Produto
            </button>
            <button 
                id="bimprimir" 
                data-evento="produto" 
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
                        <th style="width: 100px;">Código</th>
                        <th style="width: auto;">Descrição</th>
                        <th style="width: auto;">Unid.</th>
                        <th style="width: auto;">Total Atual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($produtos); $i++) { ?>
                        <tr>
                            <td>
                                <button 
                                    class="acao-editar" 
                                    data-evento="produto" 
                                    data-titulo="Editar Produto" 
                                    data-id="<?= $produtos[$i]->getProdutoid() ?>">
                                    Editar
                                </button>
                                <button 
                                    class="acao-excluir" 
                                    data-evento="produto" 
                                    data-titulo="Excluir Produto" 
                                    data-id="<?= $produtos[$i]->getProdutoid() ?>">
                                    Excluir
                                </button>
                            </td>
                            <td>
                                <?= $produtos[$i]->getProdutoid() ?>
                            </td>
                            <td>
                                <?= $produtos[$i]->getProdutonome(TRUE) ?>
                            </td>
                            <td>
                                <?= $produtos[$i]->getUnd(TRUE) ?>
                            </td>
                            <td>
                                <?= $produtos[$i]->getEstoqueatual() ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>