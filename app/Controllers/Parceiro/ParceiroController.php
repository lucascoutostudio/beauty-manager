<?php
class ParceiroController extends BaseController
{

    public function index($page = 1)
    {

        $page = max(1, (int)$page);

        // 🚨 NOVO: Captura o termo de busca da URL (GET)
        $searchTerm = trim($_GET['q'] ?? '');

        $parceiroDao = new ParceiroDAO();

        // 🚨 NOVO: Passa o termo de busca para o DAO
        $paginationData = $parceiroDao->findPaginated($page, $searchTerm);

        $totalItems = $paginationData['totalCount'];
        $itemsPerPage = $paginationData['perPage'];
        $totalPages = ceil($totalItems / $itemsPerPage);

        // 🚨 NOVO: Cria a base da URL da paginação, mantendo o parâmetro 'q'
        $queryParam = $searchTerm ? '?q=' . urlencode($searchTerm) : '';
        $baseUrl = '/parceiro/index/';

        // Prepara os dados para a View
        $this->view('parceiro/index', [
            'titulo' => 'Listagem de Parceiros',
            'parceiros' => $paginationData['parceiros'],
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'baseUrl' => $baseUrl, // /parceiro/index/
            'queryParam' => $queryParam // ?q=termo
        ]);
    }

    public function detalhes(int $id)
    {
        // 1. Instancia o DAO
        $parceiroDao = new ParceiroDAO(); // Ou PessoaDAO, dependendo de onde o Parceiro herda

        // 2. Busca o parceiro (usando o método Lazy Loading)
        $parceiro = $parceiroDao->parceiroPorId($id);

        // 3. Verifica se encontrou
        if (!$parceiro) {
            // Define o cabeçalho de erro e retorna uma mensagem
            http_response_code(404);
            echo json_encode(['error' => 'Parceiro não encontrado.']);
            exit;
        }

        // 4. Converte o objeto Parceiro para um array/JSON para a resposta AJAX
        // Nota: Você deve ter um método na sua entidade (toArray) ou mapear manualmente.
        $data = [
            'id'            => $parceiro->getId(),
            'nomeparceiro'  => $parceiro->getNomeParceiro(),
            'tipo'           => $parceiro->getTipo(),
            'descricao'         => $parceiro->getDescricao(),
            'instagram'      => $parceiro->getInstagram(),
            'tipoparceria'     => $parceiro->getTipoParceria(),
            // Adicione todos os campos aqui...
            'status'        => $parceiro->getStatus()
        ];

        // 5. Retorna a resposta JSON
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function editar($id)
    {
        $parceiroDao = new ParceiroDAO();
        $parceiro = $parceiroDao->parceiroPorId($id);

        if (!$parceiro) {
            // Redireciona se o parceiro não for encontrado
            header('Location: /parceiro/index/1');
            exit;
        }

        $this->view('parceiro/editar', [
            'titulo' => 'Editar Parceiro',
            'parceiro' => $parceiro
        ]);
    }

    public function atualizar($id)
    {
        $nomeparceiro = trim($_POST['nomeparceiro'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $instagram = trim($_POST['instagram'] ?? '');
        $tipoparceria = trim($_POST['tipoparceria'] ?? '');

        $parceiroDao = new ParceiroDAO();
        $parceiro = $parceiroDao->parceiroPorId($id);

        if (!$parceiro) {
            // Redireciona se o parceiro não for encontrado
            header('Location: /parceiro/index/1');
            exit;
        }

        // Atualiza os dados do parceiro
        $parceiro->setNomeParceiro($nomeparceiro);
        $parceiro->setTipo($tipo);
        $parceiro->setDescricao($descricao);
        $parceiro->setInstagram($instagram);
        $parceiro->setTipoParceria($tipoparceria);

        // Salva as alterações no banco de dados
        $parceiroDao->atualizarParceiro($parceiro);

        // Redireciona de volta para a lista de parceiros
        header('Location: /parceiro/index/1');
        exit;
    }

    public function novo()
    {
        $this->view('parceiro/novo', [
            'titulo' => 'Novo Parceiro'
        ]);
    }

    public function criar()
    {
        $nomeparceiro = trim($_POST['nomeparceiro'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $instagram = trim($_POST['instagram'] ?? '');
        $tipoparceria = trim($_POST['tipoparceria'] ?? '');

        $parceiro = new Parceiro();
        $parceiro->setNomeParceiro($nomeparceiro);
        $parceiro->setTipo($tipo);
        $parceiro->setDescricao($descricao);
        $parceiro->setInstagram($instagram);
        $parceiro->setTipoParceria($tipoparceria);

        $parceiroDao = new ParceiroDAO();
        $parceiroDao->criarParceiro($parceiro);

        // Redireciona de volta para a lista de parceiros
        header('Location: /parceiro/index/1');
        exit;
    }

    public function deletar($id)
    {
        $parceiroDao = new ParceiroDAO();
        $result = $parceiroDao->softDeleteParceiro($id);

        if ($result === ParceiroDAO::DEPENDENCY_ERROR) {
            BaseController::setFlash("Não é possível excluir este parceiro porque existem clientes vinculadas a ele.", 'warning');
        } elseif ($result) {
            BaseController::setFlash("Parceiro excluído com sucesso! O parceiro será removido permanentemente em 30 dias.", 'success');
        } else {
            BaseController::setFlash("Erro ao excluir o parceiro. Tente novamente.", 'danger');
        }

        header('Location: /parceiro/index/1/');
        exit;
    }
}
