<div id="fornecedortable">
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
    <div id="fornecedortable-body">
        <div class="toolbar"><b>Fornecedores Cadastrados</b></div>
        <br/>
        <div>
            <button 
                id="bnovoCadastro" 
                data-evento="fornecedor" 
                data-titulo="Cadastrar Fornecedor">
                Novo Fornecedor
            </button>
        </div>
        <br/>
        <div class="ui-widget-content table-content">
            <table class="table" width="auto" cellspacing="0">
                <thead class="ui-widget-header">
                    <tr>
                        <th style="width: 100px;">Ações</th>
                        <th style="width: 100px;">Código</th>
                        <th style="width: auto;">Nome Fantasia</th>
                        <th style="width: auto;">Razão Social</th>
                        <th style="width: auto;">Telefone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($fornecedores); $i++) { ?>
                        <tr>
                            <td>
                                <button 
                                    class="acao-editar" 
                                    data-evento="fornecedor" 
                                    data-titulo="Editar Fornecedor" 
                                    data-id="<?= $fornecedores[$i]->getFornecedorid() ?>">
                                    Editar
                                </button>
                                <button 
                                    class="acao-excluir" 
                                    data-evento="fornecedor" 
                                    data-titulo="Excluir Fornecedor" 
                                    data-id="<?= $fornecedores[$i]->getFornecedorid() ?>">
                                    Excluir
                                </button>
                            </td>
                            <td>
                                <?= $fornecedores[$i]->getFornecedorid() ?>
                            </td>
                            <td>
                                <?= $fornecedores[$i]->getFantazia() ?>
                            </td>
                            <td>
                                <?= $fornecedores[$i]->getRazao() ?>
                            </td>
                            <td>
                                <?= $fornecedores[$i]->getTelefone() ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>