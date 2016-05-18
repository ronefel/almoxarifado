<div id="departamentotable">
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
    <div id="departamentotable-body">
        <div class="toolbar"><b>Departamentos Cadastrados</b></div>
        <br/>
        <div>
            <button 
                id="bnovoCadastro" 
                data-evento="departamento" 
                data-titulo="Cadastrar Novo Departamento">
                Novo Departamento
            </button>
        </div>
        <br/>
        
        <div class="ui-widget-content table-content">
            <table class="table" width="auto" cellspacing="0">
                <thead class="ui-widget-header">
                    <tr>
                        <th style="width: 100px;">Ações</th>
                        <th style="width: 100px;">Código</th>
                        <th style="width: auto;">Departamento</th>
                        <th style="width: auto;">Local</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($departamento); $i++) { ?>
                        <tr>
                            <td>
                                <button 
                                    class="acao-editar" 
                                    data-evento="departamento" 
                                    data-titulo="Editar Departamento" 
                                    data-id="<?= $departamento[$i]->getDepartamentoid() ?>">
                                    Editar
                                </button>
                                <button 
                                    class="acao-excluir" 
                                    data-evento="departamento" 
                                    data-titulo="Excluir Departamento" 
                                    data-id="<?= $departamento[$i]->getDepartamentoid() ?>">
                                    Excluir
                                </button>
                            </td>
                            <td>
                                <?= $departamento[$i]->getDepartamentoid() ?>
                            </td>
                            <td>
                                <?= $departamento[$i]->getDepartamentonome(TRUE) ?>
                            </td>
                            <td>
                                <?= $departamento[$i]->getLocalnome(TRUE) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>