<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // Carrega as configurações (se não estiverem no index.php)
        // require_once ROOT_PATH . '/config/Database.php'; 

        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET, // Opcional, mas bom
            PDO::ATTR_CASE               => PDO::CASE_LOWER
        ];

        try {
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Em ambiente de produção, logue o erro em vez de exibi-lo
            die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
        }
    }

    // Método que garante que só uma instância da conexão exista (Singleton)
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Retorna o objeto PDO para ser usado pelos DAOs
    public function getConnection() {
        return $this->conn;
    }
}