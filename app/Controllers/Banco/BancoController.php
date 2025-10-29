<?php

class BancoController extends BaseController {
    
    public function index($page = 1) {
        
        $page = max(1, (int)$page); 
        
        // 🚨 NOVO: Captura o termo de busca da URL (GET)
        $searchTerm = trim($_GET['q'] ?? '');
        
        $bancoDao = new BancoDAO();
        
        // 🚨 NOVO: Passa o termo de busca para o DAO
        $paginationData = $bancoDao->findPaginated($page, $searchTerm); 
        
        $totalItems = $paginationData['totalCount'];
        $itemsPerPage = $paginationData['perPage'];
        $totalPages = ceil($totalItems / $itemsPerPage);
        
        // 🚨 NOVO: Cria a base da URL da paginação, mantendo o parâmetro 'q'
        $queryParam = $searchTerm ? '?q=' . urlencode($searchTerm) : '';
        $baseUrl = '/banco/index/';
        
        // Prepara os dados para a View
        $this->view('banco/index', [
            'titulo' => 'Listagem de Bancos',
            'bancos' => $paginationData['bancos'],
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'baseUrl' => $baseUrl, // /banco/index/
            'queryParam' => $queryParam // ?q=termo
        ]);
    }

    public function editar($id) {
        $bancoDao = new BancoDAO();
        $banco = $bancoDao->bancoPorId($id);
        
        if (!$banco) {
            // Redireciona se o banco não for encontrado
            header('Location: /banco/index/1');
            exit;
        }
        
        $this->view('banco/editar', [
            'titulo' => 'Editar Banco',
            'banco' => $banco
        ]);
    }

    public function atualizar($id) {
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        
        $bancoDao = new BancoDAO();
        $banco = $bancoDao->bancoPorId($id);
        
        if (!$banco) {
            // Redireciona se o banco não for encontrado
            header('Location: /banco/index/1');
            exit;
        }
        
        // Atualiza os dados do banco
        $banco->setNome($nome);
        $banco->setDescricao($descricao);
        
        // Salva as alterações no banco de dados
        $success = $bancoDao->atualizarBanco($banco);
        if ($success) {
            // Sucesso
            BaseController::setFlash("Banco atualizado com sucesso!", 'success');
        } else {
            // Erro
            BaseController::setFlash("Erro ao atualizar o banco. Verifique os dados.", 'danger');
        }
        
        // Redireciona de volta para a lista de bancos
        header('Location: /banco/index/1/');
        exit;
    }

    public function novo() {
        $this->view('banco/novo', [
            'titulo' => 'Adicionar Novo Banco'
        ]);
    }

    public function criar() {
        $id = trim($_POST['id'] ?? '');
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        
        $banco = new Banco();
        $banco->setId($id);
        $banco->setNome($nome);
        $banco->setDescricao($descricao);
        
        $bancoDao = new BancoDAO();
        $success = $bancoDao->inserirBanco($banco);
        
        if ($success) {
            // Sucesso
            BaseController::setFlash("Banco criado com sucesso!", 'success');
        } else {
            // Erro
            BaseController::setFlash("Erro ao criar o banco. Verifique os dados.", 'danger');
        }
        
        // Redireciona de volta para a lista de bancos
        header('Location: /banco/index/1/');
        exit;
    }

    public function delete(int $id) {
        
        $bancoDao = new BancoDAO();
        
        $result = $bancoDao->softDeleteBanco($id);
        
        if ($result === BancoDAO::DEPENDENCY_ERROR) {
            // Mensagem de erro de dependência
            BaseController::setFlash(
                "Não foi possível excluir o banco. Ele está vinculado a uma ou mais contas ativas.", 
                'danger'
            );
        } elseif ($result === true) {
            // Sucesso na exclusão
            BaseController::setFlash(
                "Registro excluído com sucesso. Você poderá reverter a exclusão em até 30 dias.", 
                'success'
            );
        } else {
            // Erro genérico do banco de dados
            BaseController::setFlash(
                "Ocorreu um erro inesperado ao tentar excluir o banco.", 
                'danger'
            );
        }
        
        // Redireciona para a listagem (mantendo os filtros de busca/paginação se houver)
        header('Location: /banco/index/1');
        exit;
    }

    // ...
}