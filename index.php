<?php

define('ROOT_PATH', dirname(__FILE__));
define('APP_PATH', ROOT_PATH . '/app');

// Exibir erros (mudar para 0 em produção)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🚨 INCLUA O AUTOLOADER MANUALMENTE
require_once 'app/bootstrap/autoloader.php';
require_once ROOT_PATH.'/config/Database.php';

spl_autoload_register(function ($class) {

    // 1. Tenta carregar Controllers (ex: 'HomeController' em 'Controllers/Home/')
    if (str_ends_with($class, 'Controller')) {
        
        // Trata Controllers que estão em subdiretórios (ex: HomeController -> Home)
        $controller_dir = str_replace('Controller', '', $class); 
        $file = APP_PATH . '/Controllers/' . $controller_dir . '/' . $class . '.php'; 

        if (file_exists($file)) {
            require_once $file;
            return;
        }
        
        // 🚨 Tenta carregar classes Base/Utilitárias que estão na raiz de Controllers
        // (Isso é útil se você mantiver BaseController em app/Controllers/BaseController.php)
        $file = APP_PATH . '/Controllers/' . $class . '.php'; 

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // 🚨 Tenta carregar classes Base/Models que estão na raiz de 'app/'
    // (Se você mover o BaseController.php para app/BaseController.php)
    $file = APP_PATH . '/' . $class . '.php'; 
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // 3. Tenta carregar Models/Entities (ex: 'User' em 'app/Models/Entity/')
    $file = APP_PATH . '/Models/Entity/' . $class . '.php'; 
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // 4. Tenta carregar DAOs (ex: 'UserDao' em 'app/Models/Dao/')
    $file = APP_PATH . '/Models/DAO/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }

    // 5. Tenta carregar classes de núcleo (Database)
    $file = APP_PATH . '/Models/' . $class . '.php'; // Assumindo app/Models/Database.php
    if (file_exists($file)) {
        require_once $file;
        return;
    }
});


// 2. Captura e Processamento da URL

/// Roteamento:
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $uri);

// Se a URL estiver vazia (raiz /), o Controller é Home e Action é index
if (empty($segments[0])) {
    $controller_class = 'HomeController';
    $action = 'index';
    $params = [];
} else {
    // Controller será o primeiro segmento (ex: LoginController)
    $controller_class = ucfirst(array_shift($segments)) . 'Controller';
    // Action será o segundo segmento (ex: index, auth)
    $action = array_shift($segments) ?: 'index';
    // Parâmetros restantes
    $params = $segments;
}

// 1. Instancia o Controller (chama o construtor do BaseController para checar a sessão!)
if (class_exists($controller_class)) {
    $controller = new $controller_class();
    
    // 2. Chama a Action com os parâmetros
    if (method_exists($controller, $action)) {
        call_user_func_array([$controller, $action], $params);
    } else {
        // Tratar erro 404 para Action
        http_response_code(404);
        die('Erro 404: Action não encontrada.');
    }
} else {
    // Tratar erro 404 para Controller
    http_response_code(404);
    die('Erro 404: Controller não encontrado.');
}
