<div id="cidadetable">
    <?php include_once '../script.php'; ?>
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
    <div id="cidadetable-body">
        <div class="toolbar"><b>Cidades Cadastradas</b></div>
        
        <br/>
        <div>
            <button 
                id="bnovoCadastro" 
                data-evento="cidade"
                data-titulo="Cadastrar Cidade">
                Nova Cidade
            </button>
        </div>
        
        <br/>
        <div class="ui-widget-content table-content">
            <table class="table" width="auto" cellspacing="0">
                <thead class="ui-widget-header">
                    <tr>
                        <th style="width: 100px;">Ações</th>
                        <th style="width: 100px;">Código</th>
                        <th style="width: auto;">Nome</th>
                        <th style="width: 100px;">UF</th>
                        <th style="width: 100px;">CEP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($cidade); $i++) { ?>
                        <tr>
                            <td>
                                <button 
                                    class="acao-editar" 
                                    data-evento="cidade" 
                                    data-titulo="Editar Cidade"
                                    data-id="<?= $cidade[$i]->getCidadeid() ?>">
                                    Editar
                                </button>
                                <button 
                                    class="acao-excluir" 
                                    data-evento="cidade" 
                                    data-titulo="Editar Cidade"
                                    data-id="<?= $cidade[$i]->getCidadeid() ?>">
                                    Excluir
                                </button>
                            </td>
                            <td>
                                <?= $cidade[$i]->getCidadeid() ?>
                            </td>
                            <td>
                                <?= $cidade[$i]->getNome(TRUE) ?>
                            </td>
                            <td>
                                <?= $cidade[$i]->getUf(TRUE) ?>
                            </td>
                            <td>
                                <?= $cidade[$i]->getCep(TRUE) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>