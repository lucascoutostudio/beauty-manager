<div class="container">
    <?php include 'app/Views/shared/flash_messages.php';?>
    <h2>Parceiros</h2>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><?= $titulo; ?></h5>
                    <div class="mb-3">
                        <a class="btn btn-outline-secondary me-2" href="/parceiro/novo/" title="Cadastrar parceiro"><i class="bi bi-plus-square"></i></a>
                        <a href="/home" class="btn btn-outline-secondary" title="Voltar"><i class="bi bi-house"></i></a>
                    </div>
                    <div class="mb-3">
                        <form method="GET" action="/parceiro/index/1" id="formBusca">
                            <div>
                                <label for="filtroBusca" class="form-label visually-hidden">Buscar Parceiro</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="filtroBusca" name="q"
                                        placeholder="Buscar por ID ou Nome..."
                                        value="<?= htmlspecialchars($_GET['q'] ?? ''); ?>">

                                    <button class="btn btn-outline-secondary btn-sm" type="submit" id="btnFiltrar" title="Filtrar">
                                        <i class="bi bi-search"></i>
                                    </button>

                                    <a href="/parceiro/index/1" class="btn btn-outline-danger btn-sm" id="btnLimparFiltro" title="Limpar">
                                        <i class="bi bi-x-square"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table table-striped table-hover ">
                        <thead>
                            <tr class='table-dark'>
                                <th scope="col">Código</th>
                                <th scope="col">Parceiro</th>
                                <th scope="col">Tipo</th>                                
                                <th scope="col">Instagram</th>
                                <th scope="col">Tipo de parceria</th>
                                <th scope="col">Status</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($parceiros) === 0): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Nenhum parceiro cadastrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($parceiros as $parceiro): ?>
                                    <tr>
                                        <th scope="row"><?php echo htmlspecialchars($parceiro->getId()); ?></th>
                                        <td><?php echo htmlspecialchars($parceiro->getNomeParceiro()); ?></td>
                                        <td><?php echo htmlspecialchars($parceiro->getTipo()); ?></td>                                        
                                        <td><a target="_blank" href="<?php echo 'https://instagram.com/'.htmlspecialchars($parceiro->getInstagram()); ?>"><?=htmlspecialchars($parceiro->getInstagram()); ?></a></td>
                                        <td><?php echo htmlspecialchars($parceiro->getTipoParceria()); ?></td>
                                        <td>
                                                <span class="badge bg-<?= $parceiro->getStatus() == 1 ? 'success' : 'danger'; ?>">
                                                    <?= $parceiro->getStatus() == 1 ? 'Ativa' : 'Inativa'; ?>
                                                </span>
                                            </td>
                                        <td class="text-end">
                                            <a href="/parceiro/card/<?= $parceiro->getId(); ?>" class="btn btn-sm btn-primary btn-ver" title="Ver informações"><i class="bi bi-card-heading"></i></a>
                                            <a href="/parceiro/editar/<?= $parceiro->getId(); ?>" class="btn btn-sm btn-primary btn-editar" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                            <button data-id="<?php echo $parceiro->getId(); ?>" class="btn btn-sm btn-danger btn-excluir" title="Excluir"><i class="bi bi-trash3"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <?php
                    $maxLinks = 5; // Número máximo de links de página para mostrar
                    $startPage = max(1, $currentPage - floor($maxLinks / 2));
                    $endPage = min($totalPages, $startPage + $maxLinks - 1);
                    if ($endPage - $startPage + 1 < $maxLinks) {
                        $startPage = max(1, $endPage - $maxLinks + 1);
                    }
                    $linkBase = $baseUrl . $queryParam; // Ex: /parceiro/index/?q=termo
                    ?>

                    <nav aria-label="Paginação de Parceiros">
                        <ul class="pagination justify-content-center">

                            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= $linkBase . ($currentPage - 1); ?>" ...>Anterior</a>
                            </li>

                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : ''; ?>" <?= $i == $currentPage ? 'aria-current="page"' : ''; ?>>
                                    <a class="page-link" href="<?= $linkBase . $i; ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= $linkBase . ($currentPage + 1); ?>" ...>Próximo</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleciona todos os botões com a classe btn-excluir
    const deleteButtons = document.querySelectorAll('.btn-excluir');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Impede qualquer ação padrão

            const parceiroId = this.dataset.id;
            
            // Pergunta de Confirmação
            const confirmation = confirm("Você tem certeza que deseja excluir o registro?");
            
            if (confirmation) {
                // Se confirmado, redireciona para a rota de exclusão no Controller
                window.location.href = '/parceiro/delete/' + parceiroId;
            }
        });
    });
});
</script>
<?php if (!empty($message)): ?>
<script>
    alert("<?= htmlspecialchars($message); ?>");
</script>
<?php endif; ?>
