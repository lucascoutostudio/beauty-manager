<?php

class BancoDAO extends BaseDAO
{
    const ITEMS_PER_PAGE = 10;
    const DEPENDENCY_ERROR = 'dependency_error';

    /**
     * Busca uma lista de bancos com paginação e filtro.
     * @param int $page Página atual solicitada.
     * @param string $searchTerm Termo de busca (ID, Nome, ou Descrição).
     * @return array Contendo 'bancos' (items) e 'totalCount'.
     */
    public function findPaginated(int $page = 1, string $searchTerm = ''): array
    {

        $offset = ($page - 1) * self::ITEMS_PER_PAGE;

        // 🚨 NOVO: Inicia as variáveis de filtro
        $whereClause = "";
        $params = [];
        if ($searchTerm !== '') {
            $whereClause = " WHERE CAST(id AS CHAR) LIKE :id OR nome LIKE :nome OR descricao LIKE :descricao";
            $params[':id'] = '%' . $searchTerm . '%';
            // O PDO espera o wildcard (%) no valor, não na query
            $params[':nome'] = '%' . $searchTerm . '%';
            $params[':descricao'] = '%' . $searchTerm . '%';
        }
        // Adiciona filtro para excluir = 0, se ainda não houver WHERE
        if ($whereClause === "") {
            $whereClause = " WHERE excluir = 0";
        } else {
            // Se já houver WHERE, adicionamos a condição de exclusão
            $whereClause .= " AND excluir = 0";
        }
        // Constrói a query completa
        $sql = "SELECT id, nome, descricao, status FROM bancos"
            . $whereClause
            . " ORDER BY id DESC";

        // Chamada ao método de paginação, agora com filtro
        $data = $this->execDQLPaginated(
            $sql,
            $params,
            'Banco',
            self::ITEMS_PER_PAGE,
            $offset
        );

        return [
            'bancos' => $data['items'],
            'totalCount' => $data['totalCount'],
            'perPage' => self::ITEMS_PER_PAGE
        ];
    }

    public function listarBancos()
    {
        // Usa o método da classe pai
        $bancos = $this->execDQLClass("SELECT id, nome, descricao, excluir, dataexclusao FROM bancos ORDER BY id", [], 'Banco');
                
        return $bancos;
    }

    public function listarBancosAtivos()
    {
        // Usa o método da classe pai
        $results = $this->execDQLClass("SELECT id, nome, descricao, status FROM bancos WHERE excluir = 0 ORDER BY id", [], 'Banco');

        $bancos = [];
        // Converte cada resultado em um objeto Banco
        foreach ($results as $row) {
            $bancos[] = new Banco($row);
        }
        return $bancos;
    }

    public function bancoPorId(int $id): ?Banco
    {
        $sql = "SELECT id, nome, descricao, status FROM bancos WHERE id = :id";
        $params = [':id' => $id];
        $banco = $this->execSingleDQLClass($sql, $params, 'Banco');

        return empty($banco) ? null : $banco;
    }

    public function atualizarBanco(Banco $banco): bool
    {
        $sql = "UPDATE bancos SET nome = :nome, descricao = :descricao WHERE id = :id";
        $params = [
            ':nome' => $banco->getNome(),
            ':descricao' => $banco->getDescricao(),
            ':id' => $banco->getId()
        ];

        return $this->execDML($sql, $params);
    }

    // Exemplo de Inserção (usando execDML)
    public function inserirBanco(Banco $banco): bool
    {
        $sql = "INSERT INTO bancos (id, nome, descricao) VALUES (:id, :nome, :descricao)";
        $params = [
            ':id' => $banco->getId(),
            ':nome' => $banco->getNome(),
            ':descricao' => $banco->getDescricao()
        ];

        $success = $this->execDML($sql, $params);

        /*if ($success) {
            // Se for bem-sucedido, atribui o novo ID ao objeto Entity
            $banco->setId($this->lastInsertId());
            return true;
        }*/
        return $success;
    }

    /**
     * Realiza a exclusão lógica do banco, verificando dependências.
     * @param int $id ID do banco a ser excluído.
     * @return bool|string Retorna true (sucesso), false (erro genérico) ou self::DEPENDENCY_ERROR.
     */
    public function softDeleteBanco(int $id): bool|string {
        try {
            // 1. VERIFICAÇÃO DE DEPENDÊNCIA
            // Assumindo que a tabela 'contas' tem a coluna 'banco_id' e que 'excluir = 0' significa ativa.
            $sqlCheck = "SELECT COUNT(*) FROM contas WHERE idbanco = :id AND excluir = 0";
            $stmCheck = $this->conn->prepare($sqlCheck);
            $stmCheck->bindValue(':id', $id, PDO::PARAM_INT);
            $stmCheck->execute();
            
            // Se houver contas ativas vinculadas, impede a exclusão
            if ($stmCheck->fetchColumn() > 0) {
                return self::DEPENDENCY_ERROR;
            }

            // 2. EXCLUSÃO LÓGICA (SOFT DELETE)
            // Define 'excluir = 1' e 'dataexclusao' para 30 dias no futuro
            $sqlDelete = "UPDATE bancos 
                          SET excluir = 1, 
                              dataexclusao = DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY) 
                          WHERE id = :id AND excluir = 0"; // Excluir = 0 impede a exclusão de registros já excluídos

            $stmDelete = $this->conn->prepare($sqlDelete);
            $stmDelete->bindValue(':id', $id, PDO::PARAM_INT);
            $stmDelete->execute();

            return $stmDelete->rowCount() > 0; // True se uma linha foi afetada
            
        } catch (PDOException $e) {
            error_log("Erro Soft Delete Banco: " . $e->getMessage());
            return false;
        }
    }
}
