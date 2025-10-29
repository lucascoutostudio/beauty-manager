<?php

class ContaController extends BaseController {
    
    public function index($page = 1) {
        
        $page = max(1, (int)$page); 
        
        // 🚨 NOVO: Captura o termo de busca da URL (GET)
        $searchTerm = trim($_GET['q'] ?? '');
        
        $contaDao = new ContaDAO();
        
        // 🚨 NOVO: Passa o termo de busca para o DAO
        $paginationData = $contaDao->findPaginated($page, $searchTerm); 
        
        $totalItems = $paginationData['totalCount'];
        $itemsPerPage = $paginationData['perPage'];
        $totalPages = ceil($totalItems / $itemsPerPage);
        
        // 🚨 NOVO: Cria a base da URL da paginação, mantendo o parâmetro 'q'
        $queryParam = $searchTerm ? '?q=' . urlencode($searchTerm) : '';
        $baseUrl = '/conta/index/';
        
        // Prepara os dados para a View
        $this->view('conta/index', [
            'titulo' => 'Listagem de Contas',
            'contas' => $paginationData['contas'],
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'baseUrl' => $baseUrl, // /banco/index/
            'queryParam' => $queryParam // ?q=termo
        ]);
    }

    public function novo() {
        $bancoDao = new BancoDAO();
        $bancos = $bancoDao->listarBancos();
        //var_dump($bancos); die("ContaController::novo");

        $usuarioDao = new UserDAO();
        $usuarios = $usuarioDao->listarUsers();
        
        $this->view('conta/novo', [
            'titulo' => 'Nova Conta',
            'bancos' => $bancos,
            'usuarios' => $usuarios
        ]);
    }

    public function criar() {
        $conta = new Conta();
        $conta->setIdBanco((int)($_POST['idbanco'] ?? 0));
        $conta->setIdUsuario((int)($_POST['idusuario'] ?? 0));
        $conta->setAgencia(trim($_POST['agencia'] ?? ''));
        $conta->setNumero(trim($_POST['numero'] ?? ''));
        $conta->setDigito(trim($_POST['digito'] ?? ''));
        $conta->setTipo(trim($_POST['tipo'] ?? ''));
        $conta->setChavePix(trim($_POST['chavepix'] ?? ''));
                        
        $contaDao = new ContaDAO();
        $success = $contaDao->inserirConta($conta);
        
        if ($success) {
            // Sucesso
            BaseController::setFlash("Banco criado com sucesso!", 'success');
        } else {
            // Erro
            BaseController::setFlash("Erro ao criar o banco. Verifique os dados.", 'danger');
        }
        
        header('Location: /conta/index/1');
        exit;
    }

    public function editar($id) {
        $contaDao = new ContaDAO();
        $conta = $contaDao->contaPorId($id);

        $bancoDao = new BancoDAO();
        $bancos = $bancoDao->listarBancos();

        $usuarioDao = new UserDAO();
        $usuarios = $usuarioDao->listarUsers();
        
        if (!$conta) {
            // Redireciona se a conta não for encontrada
            header('Location: /conta/index/1');
            exit;
        }
        
        $this->view('conta/editar', [
            'titulo' => 'Editar Conta',
            'conta' => $conta,
            'bancos' => $bancos,
            'usuarios' => $usuarios
        ]);
    }

    public function atualizar($id){
        $contaDao = new ContaDAO();
        $conta = $contaDao->contaPorId($id);
        
        if (!$conta) {
            // Redireciona se a conta não for encontrada
            header('Location: /conta/index/1');
            exit;
        }
        
        // Atualiza os dados da conta
        $conta->setIdBanco((int)($_POST['idbanco'] ?? 0));
        $conta->setIdUsuario((int)($_POST['idusuario'] ?? 0));
        $conta->setAgencia(trim($_POST['agencia'] ?? ''));
        $conta->setNumero(trim($_POST['numero'] ?? ''));
        $conta->setDigito(trim($_POST['digito'] ?? ''));
        $conta->setTipo(trim($_POST['tipo'] ?? ''));
        $conta->setChavePix(trim($_POST['chavepix'] ?? ''));
        
        // Salva as alterações no banco de dados
        $success = $contaDao->atualizarConta($conta);
        if ($success) {
            // Sucesso
            BaseController::setFlash("Conta atualizada com sucesso!", 'success');
        } else {
            // Erro
            BaseController::setFlash("Erro ao atualizar a conta. Verifique os dados.", 'danger');
        }
        
        // Redireciona de volta para a lista de contas
        header('Location: /conta/index/1/');
        exit;

    }

    public function deletar($id) {
        $contaDao = new ContaDAO();
        $result = $contaDao->softDeleteConta($id);
        
        if ($result === ContaDAO::DEPENDENCY_ERROR) {
            BaseController::setFlash("Não é possível excluir esta conta porque existem despesas vinculadas a ela.", 'warning');
        } elseif ($result) {
            BaseController::setFlash("Conta excluída com sucesso! A conta será removida permanentemente em 30 dias.", 'success');
        } else {
            BaseController::setFlash("Erro ao excluir a conta. Tente novamente.", 'danger');
        }
        
        header('Location: /conta/index/1/');
        exit;
    }
}
?>