<?php
// O Autoload encontrará BaseDAO e User

class UserDAO extends BaseDAO { 
    // Não precisamos de __construct() se não fizermos nada além de chamar parent::__construct()
    // public function __construct() { parent::__construct(); }

    public function listarUsers() {
        // Usa o método da classe pai
        $users = $this->execDQLClass("SELECT id, cpf, nome, usuario, email, senha, nivel, datainclusao, status, excluir, dataexclusao FROM usuarios ORDER BY nome", [], 'User');
                
        return $users;
    }

    public function userPorId(int $id): ?User {
        $sql = "SELECT id,cpf, nome, usuario, email, senha, nivel, datainclusao, status, excluir, dataexclusao FROM usuarios WHERE id = :id";
        $params = [':id' => $id];
        
        // Usa o novo método SingleSelect
        $row = $this->execSingleDQLClass($sql, $params, 'User');
        
        return $row ?? null;
    }
    
    // Exemplo de Inserção (usando execDML)
    public function inserirUser(User $user): bool {
        $sql = "INSERT INTO usuarios (cpf, nome, usuario, email, senha, nivel) VALUES (:cpf, :nome, :usuario, :email, :senha, :nivel)";
        $params = [
            ':cpf' => $user->getCpf(),
            ':nome' => $user->getNome(),
            ':usuario' => $user->getUsuario(),
            ':email' => $user->getEmail(),
            ':senha' => $user->getSenha(),
            ':nivel' => $user->getNivel()
        ];
        
        $success = $this->execDML($sql, $params);
        
        if ($success) {
            // Se for bem-sucedido, atribui o novo ID ao objeto Entity
            $user->setId($this->lastInsertId());
            return true;
        }
        return false;
    }

    /**
     * Busca um usuário pelo nome de usuário ou email para autenticação.
     * @param string $identifier Nome de usuário ou email.
     * @return User|null O objeto User ou null se não encontrado.
     */
    public function findByCredentials(string $identifier): ?User {
        $sql = "SELECT id, cpf, nome, usuario, email, senha, nivel, datainclusao, status, excluir, dataexclusao
                FROM usuarios WHERE usuario = :user OR email = :email
                LIMIT 1";
        
        $params = [':user' => $identifier,
                   ':email' => $identifier];

        // Use o método para buscar um único resultado
        $row = $this->execSingleSelect($sql, $params);
        
        // Se a linha foi encontrada, retorna um novo objeto User
        return $row ? new User($row) : null;
        
    }
}
?>