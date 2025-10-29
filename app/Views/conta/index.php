<div class="container">

    <?php include 'app/Views/shared/flash_messages.php'; ?>

    <h2>Gerenciamento de Contas</h2>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><?= $titulo; ?></h5>
                    <div class=" mb-3 d-flex">
                        <a href="/conta/novo" class="btn btn-outline-secondary me-2" title="Cadastrar Conta">
                            <i class="bi bi-plus-square"></i>
                        </a>
                        <a href="/home" class="btn btn-outline-secondary" title="Voltar"><i class="bi bi-house"></i></a>
                    </div>




                    <form action="/conta/index/1" method="GET" class="mb-4">
                        <div>
                            <label for="filtroBusca" class="form-label visually-hidden">Buscar Conta</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="filtroBusca" name="q"
                                    placeholder="Buscar por ID ou Nome..."
                                    value="<?= htmlspecialchars($_GET['q'] ?? ''); ?>">

                                <button class="btn btn-outline-secondary btn-sm" type="submit" id="btnFiltrar" title="Filtrar">
                                    <i class="bi bi-search"></i>
                                </button>

                                <a href="/conta/index/1" class="btn btn-outline-danger btn-sm" id="btnLimparFiltro" title="Limpar">
                                    <i class="bi bi-x-square"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <?php if (!empty($contas)): ?>
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Titular</th>
                                        <th>Banco</th>
                                        <th>Agência/Conta</th>
                                        <th>Tipo</th>
                                        <th>Chave Pix</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    /** @var \app\Models\Entity\Conta[] $contas */
                                    foreach ($contas as $conta):
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($conta->getId()); ?></td>
                                            <td><?= htmlspecialchars($conta->getUsuario() ? $conta->getUsuario()->getNome() : 'N/A'); ?></td>
                                            <td><?= htmlspecialchars($conta->getBanco() ? $conta->getBanco()->getNome() : 'N/A'); ?></td>
                                            <td><?= htmlspecialchars($conta->getAgencia()); ?>/<?= htmlspecialchars($conta->getNumero()); ?>-<?=htmlspecialchars($conta->getDigito()); ?></td>
                                            <td><?= htmlspecialchars($conta->getTipo()); ?></td>
                                            <td><?= htmlspecialchars($conta->getChavePix() ?? '-'); ?></td>
                                            <td>
                                                <span class="badge bg-<?= $conta->getStatus() == 1 ? 'success' : 'danger'; ?>">
                                                    <?= $conta->getStatus() == 1 ? 'Ativa' : 'Inativa'; ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="/conta/editar/<?= $conta->getId(); ?>" class="btn btn-sm btn-primary btn-editar" title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button data-id="<?= $conta->getId(); ?>" class="btn btn-sm btn-danger btn-excluir" title="Excluir">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-warning" role="alert">
                                Nenhuma conta encontrada com os critérios de busca.
                            </div>
                        <?php endif; ?>
                        <?php
                        $maxLinks = 5; // Número máximo de links de página para mostrar
                        $startPage = max(1, $currentPage - floor($maxLinks / 2));
                        $endPage = min($totalPages, $startPage + $maxLinks - 1);
                        if ($endPage - $startPage + 1 < $maxLinks) {
                            $startPage = max(1, $endPage - $maxLinks + 1);
                        }
                        $linkBase = $baseUrl . $queryParam; // Ex: /banco/index/?q=termo
                        ?>

                        <nav aria-label="Paginação de Bancos">
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

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const deleteButtons = document.querySelectorAll('.btn-excluir');

                        deleteButtons.forEach(button => {
                            button.addEventListener('click', function(event) {
                                event.preventDefault();
                                const contaId = this.dataset.id;

                                const confirmation = confirm("Você tem certeza que deseja excluir esta Conta?");

                                if (confirmation) {
                                    window.location.href = '/conta/deletar/' + contaId;
                                }
                            });
                        });
                    });
                </script>