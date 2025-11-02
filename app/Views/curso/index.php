<div class="container">
    <?php include 'app/Views/shared/flash_messages.php'; ?>
    <h2>Cursos</h2>
    <h5 class="fst-italic">Treinamentos - Cursos</h5>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><?= $titulo; ?></h5>
                    <div class="mb-3">
                        <a class="btn btn-outline-secondary me-2" href="/curso/novo/" title="Cadastrar curso"><i class="bi bi-plus-square"></i></a>
                        <a href="/home" class="btn btn-outline-secondary" title="Voltar"><i class="bi bi-house"></i></a>
                    </div>
                    <div class="mb-3">
                        <form method="GET" action="/curso/index/1" id="formBusca">
                            <div>
                                <label for="filtroBusca" class="form-label visually-hidden">Buscar Curso</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="filtroBusca" name="q"
                                        placeholder="Buscar por ID, curso ou descricao"
                                        value="<?= htmlspecialchars($_GET['q'] ?? ''); ?>">

                                    <button class="btn btn-outline-secondary btn-sm" type="submit" id="btnFiltrar" title="Filtrar">
                                        <i class="bi bi-search"></i>
                                    </button>

                                    <a href="/curso/index/1" class="btn btn-outline-danger btn-sm" id="btnLimparFiltro" title="Limpar">
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
                                <th scope="col">Curso</th>                                
                                <th scope="col" title="Carga Horária">C.H.</th>
                                <th scope="col">Vagas</th>
                                <th scope="col">VIP</th>
                                <th scope="col">Preço</th>
                                <th scope="col">Status</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($cursos) === 0): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Nenhum curso cadastrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($cursos as $curso): ?>
                                    <tr>
                                        <th scope="row"><?php echo htmlspecialchars($curso->getId()); ?></th>
                                        <td><?= htmlspecialchars($curso->getCurso()); ?></td>                                        
                                        <td class="text-center"><?= htmlspecialchars($curso->getCargaHoraria()); ?>H</td>
                                        <td class="text-center"><?php if($curso->getVagas() !== null) { echo htmlspecialchars($curso->getVagas()) ;} else {echo " - ";} ?></td>
                                        <td class="text-center"><?php if($curso->getVip() === 0) { echo 'S' ;} else { echo 'N';} ?></td>
                                        <td>R$ <?= number_format($curso->getPreco(), 2, ',', '.'); ?></td>                                        
                                        <td>
                                            <span class="badge bg-<?= $curso->getStatus() == 1 ? 'success' : 'danger'; ?>">
                                                <?= $curso->getStatus() == 1 ? 'Ativa' : 'Inativa'; ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button data-id="<?= htmlspecialchars($curso->getId()); ?>" 
            class="btn btn-sm btn-primary btn-detalhes" 
            title="Ver Detalhes"
            data-bs-toggle="modal" 
            data-bs-target="#detalhesCursoModal"><i class="bi bi-card-heading"></i></button>
                                            <a href="/curso/editar/<?= $curso->getId(); ?>" class="btn btn-sm btn-primary btn-editar" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                            <button data-id="<?php echo $curso->getId(); ?>" class="btn btn-sm btn-danger btn-excluir" title="Excluir"><i class="bi bi-trash3"></i></button>
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
                    $linkBase = $baseUrl . $queryParam; // Ex: /curso/index/?q=termo
                    ?>

                    <nav aria-label="Paginação de Cursos">
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
    <div class="modal fade" id="detalhesCursoModal" tabindex="-1" aria-labelledby="detalhesCursoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalhesCursoLabel">Detalhes do Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cursoDetalhesBody">
                    Carregando informações...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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

                const cursoId = this.dataset.id;

                // Pergunta de Confirmação
                const confirmation = confirm("Você tem certeza que deseja excluir o registro?");

                if (confirmation) {
                    // Se confirmado, redireciona para a rota de exclusão no Controller
                    window.location.href = '/curso/deletar/' + cursoId;
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const detalhesModal = new bootstrap.Modal(document.getElementById('detalhesCursoModal'));
    const modalBody = document.getElementById('cursoDetalhesBody');
    const detalhesButtons = document.querySelectorAll('.btn-detalhes');

    detalhesButtons.forEach(button => {
        button.addEventListener('click', function() {
            const cursoId = this.dataset.id;
            
            // Limpa o conteúdo anterior e mostra carregando
            modalBody.innerHTML = '<p class="text-center">Carregando...</p>';

            // 1. Faz a requisição AJAX
            fetch(`/curso/detalhes/${cursoId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao buscar curso.');
                    }
                    return response.json();
                })
                .then(data => {
                    // 2. Monta o HTML do card
                    let htmlContent = `
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Curso:</strong> ${data.curso}</p>
                        <p><strong>Descrição:</strong> ${data.descricao}</p>
                        <p><strong>Conteúdo:</strong> ${data.conteudo}</p>
                        <p><strong>Carga Horária:</strong> ${data.cargahoraria}H</p>
                        <p><strong>Vagas:</strong> ${data.vagas}</p>
                        <p><strong>VIP:</strong> ${data.vip}</p>
                        <p><strong>Preço:</strong> R$ ${data.preco}</p>
                        <hr>
                        <p><strong>Status:</strong> <span class="badge bg-${data.status == 1 ? 'success' : 'danger'}">${data.status == 1 ? 'Ativo' : 'Inativo'}</span></p>
                    `;
                    
                    // 3. Insere o conteúdo no modal
                    modalBody.innerHTML = htmlContent;
                })
                .catch(error => {
                    modalBody.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
                    console.error('Erro:', error);
                });
        });
    });
});
</script>