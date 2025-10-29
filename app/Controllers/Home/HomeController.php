<?php
// Certifique-se de que o BaseController é incluído antes, caso seu Autoload não lide com ele!

// Se BaseController for carregado pelo Autoload, remova a linha abaixo
// require_once APP_PATH . '/Controllers/BaseController.php'; 


// O nome da classe deve ser 'HomeController' (Exato)
class HomeController extends BaseController { 
    
    public function index() {
        // 1. Processamento (Simulação)
        $titulo = "Dashboard Principal";
        $usuario = "Administrador";
        
        // 2. Chamada da View, passando os dados
        $this->view('home/index', [
            'titulo' => $titulo,
            'usuario' => $usuario,
            'agora' => date('H:i:s')
        ]);
    }
    
    public function teste($param1 = null, $param2 = null) {
        // 1. Processamento (Simulação)
        $dados = [
            'param1' => $param1,
            'param2' => $param2,
            'titulo' => 'Página de Teste de Parâmetros'
        ];

        // 2. Chamada da View (View: app/Views/home/teste.php)
        $this->view('home/teste', $dados);
    }

    public function page($param1 = null, $param2 = null) {
        // 1. Processamento (Simulação)
        $dados = [
            'param1' => $param1,
            'param2' => $param2,
            'titulo' => 'Esta é uma página genérica'
        ];

        // 2. Chamada da View (View: app/Views/home/teste.php)
        $this->view('home/page', $dados);
    }
}