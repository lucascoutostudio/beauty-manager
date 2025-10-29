<?php

class BaseController {

    public function __construct() {
        // Garante que a sessão está ativa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Lógica de proteção (veja Prática 1)
        $this->checkAuthentication(); 
    }
    
    protected function checkAuthentication() {        
        $currentController = get_class($this);
               
        if ($currentController === 'LoginController') {
            // Se for o LoginController, apenas redireciona se já estiver logado (para não mostrar o formulário)
            if (isset($_SESSION['user_id']) && $this->getCurrentAction() !== 'logout') {
                header('Location: /'); // Redireciona para a home
                exit();
            }
            return;
        }
        
        // --- REGRA DE PROTEÇÃO (ROTAS PRIVADAS) ---
        
        // Se o Controller não for o LoginController E o usuário NÃO estiver logado:
        if (!isset($_SESSION['user_id'])) {
            // Guarda a URL original para redirecionar depois do login (opcional)
            $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
            
            header("Location: /login"); 
            exit(); 
        }
    }

    /**
     * Define uma mensagem flash para ser exibida na próxima requisição.
     * @param string $message O conteúdo da mensagem.
     * @param string $type O tipo de alerta (ex: 'success', 'danger', 'warning').
     */
    public static function setFlash(string $message, string $type = 'success'): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_message'] = [
            'content' => $message,
            'type' => $type
        ];
    }

    /**
     * Obtém e remove a mensagem flash da sessão.
     * @return array|null A mensagem flash, ou null se não existir.
     */
    public static function getFlash(): ?array {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']); // 🚨 Remove a mensagem após leitura
            return $message;
        }
        return null;
    }

    /**
     * Retorna o nome do método que será executado no Controller.
     * Necessário para a exceção de 'logout'.
     * Nota: Este método depende da sua lógica de roteamento no index.php.
     */
    private function getCurrentAction() {
        // Esta lógica DEVE espelhar a lógica de roteamento em index.php
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);
        
        // A action é o segundo segmento (ou 'index' se não houver)
        return strtolower($segments[1] ?? 'index');
    }
    
    /**
     * Carrega uma View e injeta dados nela.
     * @param string $viewName O nome do arquivo da view (ex: 'home/index').
     * @param array $data Dados a serem passados para a view.
     */
    protected function view($viewName, $data = []) {
        // Extrai o array $data em variáveis individuais (ex: $data['titulo'] vira $titulo)
        extract($data); 

        // 1. Define o caminho completo da view
        // O caminho da view deve ser (ex): APP_PATH . '/Views/home/index.php'
        $viewPath = APP_PATH . '/Views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            // 2. Inclui o cabeçalho (opcional, para layout)
            $this->loadShared('header');
            
            // 3. Inclui o arquivo da view
            require_once $viewPath;

            // 4. Inclui o rodapé (opcional, para layout)
            $this->loadShared('footer');
            
        } else {
            // Se a view não for encontrada, exibe um erro
            die("Erro 500: View não encontrada: $viewPath");
        }
    }

    protected function emptyView($viewName, $data = []) {
        
        extract($data); 
        $viewPath = APP_PATH . '/Views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Se a view não for encontrada, exibe um erro
            die("Erro 500: View não encontrada: $viewPath");
        }
    }
    
    /**
     * Carrega partes compartilhadas (header ou footer).
     */
    protected function loadShared($partName) {
        $sharedPath = APP_PATH . '/Views/shared/' . $partName . '.php';
        if (file_exists($sharedPath)) {
            require_once $sharedPath;
        }
    }
}