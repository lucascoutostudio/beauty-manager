<?php
class CursoController extends BaseController {
    
    public function index($page = 1) {
        $page = max(1, (int)$page);

        // 🚨 NOVO: Captura o termo de busca da URL (GET)
        $searchTerm = trim($_GET['q'] ?? '');

        $cursoDao = new CursoDAO();

        // 🚨 NOVO: Passa o termo de busca para o DAO
        $paginationData = $cursoDao->findPaginated($page, $searchTerm);

        $totalItems = $paginationData['totalCount'];
        $itemsPerPage = $paginationData['perPage'];
        $totalPages = ceil($totalItems / $itemsPerPage);

        // 🚨 NOVO: Cria a base da URL da paginação, mantendo o parâmetro 'q'
        $queryParam = $searchTerm ? '?q=' . urlencode($searchTerm) : '';
        $baseUrl = '/curso/index/';

        // Prepara os dados para a View
        $this->view('curso/index', [
            'titulo' => 'Listagem de Cursos',
            'cursos' => $paginationData['cursos'],
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'baseUrl' => $baseUrl, // /parceiro/index/
            'queryParam' => $queryParam // ?q=termo
        ]);
    }

    public function novo() {
        $this->view('curso/novo', [
            'titulo' => 'Novo Curso'
        ]);
    }

    public function criar() {
        $curso = new Curso();
        $curso->setCurso(trim($_POST['curso'] ?? ''));
        $curso->setDescricao(trim($_POST['descricao'] ?? ''));
        $curso->setConteudo(trim($_POST['conteudo'] ?? NULL));
        $curso->setCargaHoraria((int)($_POST['cargahoraria'] ?? 0));
        $curso->setVagas((int)($_POST['vagas'] ?? 0));
        $curso->setVip((int)($_POST['vip'] ?? 0));
        $curso->setPreco((float)($_POST['preco'] ?? 0.0));

        $cursoDao = new CursoDAO();
        $success = $cursoDao->inserirCurso($curso);

        if ($success) {
            // Sucesso
            BaseController::setFlash("Curso criado com sucesso!", 'success');
        } else {
            // Erro
            BaseController::setFlash("Erro ao criar o banco. Verifique os dados.", 'danger');
        }
        
        header('Location: /curso/index/1');
        exit;
    }

    public function detalhes(int $id)
    {
        // 1. Instancia o DAO
        $cursoDao = new CursoDAO(); // Ou PessoaDAO, dependendo de onde o Curso herda

        // 2. Busca o curso (usando o método Lazy Loading)
        $curso = $cursoDao->cursoPorId($id);

        // 3. Verifica se encontrou
        if (!$curso) {
            // Define o cabeçalho de erro e retorna uma mensagem
            http_response_code(404);
            echo json_encode(['error' => 'Curso não encontrado.']);
            exit;
        }

        // 4. Converte o objeto Curso para um array/JSON para a resposta AJAX
        // Nota: Você deve ter um método na sua entidade (toArray) ou mapear manualmente.
        $data = [
            'id'            => $curso->getId(),
            'curso'  => $curso->getCurso(),
            'descricao'           => $curso->getDescricao(),
            'conteudo'         => $curso->getConteudo() ?? 'Não disponível',
            'cargahoraria'      => $curso->getCargaHoraria(),
            'vagas'     => $curso->getVagas(),
            'vip'        => $curso->getVip() === 1 ? 'Sim' : 'Não',
            'preco'        => number_format($curso->getPreco(), 2, ',', '.'),
            'status'        => $curso->getStatus()
        ];

        // 5. Retorna a resposta JSON
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function editar($id) {
        $cursoDao = new CursoDAO();
        $curso = $cursoDao->cursoPorId($id);
        
        if (!$curso) {
            // Redireciona se o curso não for encontrado
            BaseController::setFlash("Curso não encontrado.", 'danger');
            header('Location: /curso/index/1');
            exit;
        }
        
        $this->view('curso/editar', [
            'titulo' => 'Editar Curso',
            'curso' => $curso
        ]);
    }

    public function atualizar($id) {
        $cursoDao = new CursoDAO();    
        $curso = $cursoDao->cursoPorId($id);
        if (!$curso) {
            // Redireciona se o curso não for encontrado
            BaseController::setFlash("Curso não encontrado.", 'danger');
            header('Location: /curso/index/1');
            exit;
        }
        
        $curso->setCurso(trim($_POST['curso'] ?? ''));
        $curso->setDescricao(trim($_POST['descricao'] ?? ''));
        $curso->setConteudo(trim($_POST['conteudo'] ?? ''));
        $curso->setCargaHoraria((int)($_POST['cargahoraria'] ?? 0));
        $curso->setVagas((int)($_POST['vagas'] ?? 0));
        $curso->setVip((int)($_POST['vip'] ?? 0));
        $curso->setPreco((float)($_POST['preco'] ?? 0.0));

        $success = $cursoDao->atualizarCurso($curso);

        if ($success) {
            // Sucesso
            BaseController::setFlash("Curso atualizado com sucesso!", 'success');
        } else {
            // Erro
            BaseController::setFlash("Erro ao atualizar o curso. Verifique os dados.", 'danger');
        }
        
        header('Location: /curso/index/1');
        exit;
    }

    public function deletar($id) {
        $cursoDao = new CursoDAO();
        $result = $cursoDao->softDeleteCurso($id);

        if ($result === true) {
            BaseController::setFlash("Curso excluído com sucesso! Você poderá reverter esta operação por 30 dias.", 'success');
        } elseif ($result === CursoDAO::DEPENDENCY_ERROR) {
            BaseController::setFlash("Não é possível excluir este curso porque existem turmas vinculadas a ele.", 'warning');
        } else {
            BaseController::setFlash("Erro ao excluir o curso.", 'danger');
        }

        header('Location: /curso/index/1');
        exit;
    }
}
?>