<?php

class LoginController extends BaseController {
    
    /**
     * Exibe o formulário de login.
     * Rota esperada: /login
     */
    public function index() {
        // Se o usuário já estiver logado, redireciona para a home
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        
        // Renderiza a view do formulário de login
        $this->emptyView('auth/login', [
            'titulo' => 'MANAGER - Karol Couto Studio',
            'error' => $_SESSION['login_error'] ?? null // Passa mensagem de erro, se houver
        ]);
        
        // Limpa a mensagem de erro da sessão após exibir
        unset($_SESSION['login_error']);
    }

    /**
     * Processa a submissão do formulário.
     * Rota esperada: /login/auth (ou um POST para /login)
     */
    public function auth() {
        // Valida se a requisição é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $identifier = trim($_POST['usuario_ou_email'] ?? '');
        $password = $_POST['senha'] ?? '';

        // 1. Busca o usuário no BD
        $userDao = new UserDAO();
        $user = $userDao->findByCredentials($identifier);
        //var_dump($_POST, $user);die();
        if ($user->getStatus() === 1 && 
            password_verify($password, $user->getSenha())) 
        {
            // 2. Autenticação BEM-SUCEDIDA
            
            // Inicia a sessão (se BaseController::__construct não o fez)
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            // Cria a sessão de login (apenas dados essenciais)
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_name'] = $user->getNome();
            $_SESSION['user_username'] = $user->getUsuario();
            $_SESSION['user_nivel'] = $user->getNivel();
            $_SESSION['debug'] = '';
            
            // Redireciona para a área restrita
            header('Location: /');
            exit;
            
        } else {
            // 3. Autenticação FALHOU
            
            // Define a mensagem de erro na sessão para exibição no index()
            $_SESSION['login_error'] = 'Usuário, email ou senha inválidos.';
            header('Location: /login');
            exit;
        }
    }
    
    /**
     * Encerra a sessão do usuário.
     * Rota esperada: /logout
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        
        header('Location: /login');
        exit;
    }
}