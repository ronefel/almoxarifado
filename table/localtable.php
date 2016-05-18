<div id="localtable">
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
    <div id="localtable-body">
        <div class="toolbar"><b>Locais Cadastrados</b></div>
        <br/>
        <div>
            <button 
                id="bnovoCadastro" 
                data-evento="local" 
                data-titulo="Cadastrar Novo Local">
                Novo Local
            </button>
        </div>
        <br/>
        <div class="ui-widget-content table-content">
            <table class="table" width="auto" cellspacing="0">
                <thead class="ui-widget-header">
                    <tr>
                        <th style="width: 100px;">Ações</th>
                        <th style="width: 100px;">Código</th>
                        <th style="width: auto;">Local</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($local); $i++) { ?>
                        <tr>
                            <td>
                                <button 
                                    class="acao-editar" 
                                    data-evento="local" 
                                    data-titulo="Editar Local" 
                                    data-id="<?= $local[$i]->getLocalid() ?>">
                                    Editar
                                </button>
                                <button 
                                    class="acao-excluir" 
                                    data-evento="local" 
                                    data-titulo="Excluir Local" 
                                    data-id="<?= $local[$i]->getLocalid() ?>">
                                    Excluir
                                </button>
                            </td>
                            <td>
                                <?= $local[$i]->getLocalid() ?>
                            </td>
                            <td>
                                <?= $local[$i]->getLocalnome(TRUE) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>