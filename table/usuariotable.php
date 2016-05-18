<div id="usuariotable">
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
    <div id="usuariotable-body">
        <div class="toolbar"><b>Usuários Cadastrados</b></div>
        
        <br/>
        <div>
            <button 
                id="bnovoCadastro" 
                data-evento="usuario" 
                data-titulo="Cadastrar Usuário">
                Novo Usuário
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
                        <th style="width: auto;">Login</th>
                        <th style="width: auto;">Tipo de Usuário</th>
                        <th style="width: auto;">Departamento - Local</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($usuario); $i++) { ?>
                        <tr>
                            <td>
                                <button 
                                    class="acao-editar" 
                                    data-evento="usuario" 
                                    data-titulo="Editar Usuário" 
                                    data-id="<?= $usuario[$i]->getUsuarioid() ?>">
                                    Editar
                                </button>
                                <button 
                                    class="acao-excluir" 
                                    data-evento="usuario" 
                                    data-titulo="Excluir Usuário" 
                                    data-id="<?= $usuario[$i]->getUsuarioid() ?>">
                                    Excluir
                                </button>
                            </td>
                            <td>
                                <?= $usuario[$i]->getUsuarioid() ?>
                            </td>
                            <td>
                                <?= $usuario[$i]->getUsuarionome(TRUE) ?>
                            </td>
                            <td>
                                <?= $usuario[$i]->getLogin(TRUE) ?>
                            </td>
                            <td>
                                <?php if ($usuario[$i]->getTipousuario() == 1) { ?>
                                    Almoxarife/Administrador
                                <?php } ?>
                                <?php if ($usuario[$i]->getTipousuario() == 2) { ?>
                                    Requisitante
                                <?php } ?>
                            </td>
                            <td>
                                <?= $usuario[$i]->getDepartamentonome(TRUE) ?> - 
                                <?= $usuario[$i]->getLocalnome(TRUE) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>