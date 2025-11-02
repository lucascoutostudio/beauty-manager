<?php
class ParceiroDAO extends BaseDAO
{
    const ITEMS_PER_PAGE = 10;
    const DEPENDENCY_ERROR = 'dependency_error';

    /**
     * Busca uma lista de parceiros com paginação e filtro.
     * @param int $page Página atual solicitada.
     * @param string $searchTerm Termo de busca (ID, Nome, ou Descrição).
     * @return array Contendo 'parceiros' (items) e 'totalCount'.
     */
    public function findPaginated(int $page = 1, string $searchTerm = ''): array
    {

        $offset = ($page - 1) * self::ITEMS_PER_PAGE;

        // 🚨 NOVO: Inicia as variáveis de filtro
        $whereClause = "";
        $params = [];
        if ($searchTerm !== '') {
                $whereClause = " WHERE CAST(id AS CHAR) LIKE :id OR nomeparceiro LIKE :nomeparceiro OR descricao LIKE :descricao OR tipo LIKE :tipo AND excluir = 0";
            $params[':id'] = '%' . $searchTerm . '%';
            // O PDO espera o wildcard (%) no valor, não na query
            $params[':nomeparceiro'] = '%' . $searchTerm . '%';
            $params[':descricao'] = '%' . $searchTerm . '%';
            $params[':tipo'] = '%' . $searchTerm . '%';
        }
        // Adiciona filtro para excluir = 0, se ainda não houver WHERE
        if ($whereClause === "") {
            $whereClause = " WHERE excluir = 0";
        } else {
            // Se já houver WHERE, adicionamos a condição de exclusão
            $whereClause .= " AND excluir = 0";
        }

        // Constrói a query completa
        $sql = "SELECT id, nomeparceiro, tipo, descricao, instagram, tipoparceria, status, excluir FROM parceiros"
            . $whereClause
            . " ORDER BY nomeparceiro";


        // Chamada ao método de paginação, agora com filtro
        $data = $this->execDQLPaginated(
            $sql,
            $params,
            'Parceiro',
            self::ITEMS_PER_PAGE,
            $offset
        );

        return [
            'parceiros' => $data['items'],
            'totalCount' => $data['totalCount'],
            'perPage' => self::ITEMS_PER_PAGE
        ];
    }

    public function listarParceiros()
    {
        // Usa o método da classe pai
        $parceiros = $this->execDQLClass("SELECT id, nomeparceiro, tipo, descricao, instagram, tipoparceria, excluir, dataexclusao FROM parceiros WHERE excluir = 0 ORDER BY nomeparceiro", [], 'Parceiro');
                
        return $parceiros;
    }


    public function listarParceirosAtivos()
    {
        // Usa o método da classe pai
        $parceiros = $this->execDQLClass("SELECT id, nomeparceiro, tipo, descricao, instagram, tipoparceria FROM parceiros WHERE status = 1 AND excluir = 0 ORDER BY nomeparceiro", [], 'Parceiro');
                
        return $parceiros;
    }

    public function parceiroPorId($id)
    {
        $sql = "SELECT id, nomeparceiro, tipo, descricao, instagram, tipoparceria, status FROM parceiros WHERE id = :id AND excluir = 0";
        $params = [':id' => $id];
        $parceiros = $this->execDQLClass($sql, $params, 'Parceiro');
        
        if (count($parceiros) === 0) {
            return null; // Retorna null se não encontrar
        }
        
        return $parceiros[0]; // Retorna o primeiro (e único) parceiro encontrado
    }

    public function atualizarParceiro(Parceiro $parceiro)
    {
        $sql = "UPDATE parceiros SET nomeparceiro = :nomeparceiro, tipo = :tipo, descricao = :descricao, instagram = :instagram, tipoparceria = :tipoparceria, status = :status WHERE id = :id";
        $params = [
            ':nomeparceiro' => $parceiro->getNomeParceiro(),
            ':tipo' => $parceiro->getTipo(),
            ':descricao' => $parceiro->getDescricao(),
            ':instagram' => $parceiro->getInstagram(),
            ':tipoparceria' => $parceiro->getTipoParceria(),
            ':status' => $parceiro->getStatus(),
            ':id' => $parceiro->getId()
        ];
        return $this->execDML($sql, $params);
    }

    public function criarParceiro(Parceiro $parceiro)
    {
        $sql = "INSERT INTO parceiros (nomeparceiro, tipo, descricao, instagram, tipoparceria) VALUES (:nomeparceiro, :tipo, :descricao, :instagram, :tipoparceria)";
        $params = [
            ':nomeparceiro' => $parceiro->getNomeParceiro(),
            ':tipo' => $parceiro->getTipo(),
            ':descricao' => $parceiro->getDescricao(),
            ':instagram' => $parceiro->getInstagram(),
            ':tipoparceria' => $parceiro->getTipoParceria()
        ];
        return $this->execDML($sql, $params);
    }    

    public function verificarDependencias($sql, int $id): bool {
        $sqlCheck = $sql;
        $stmCheck = $this->conn->prepare($sqlCheck);
        $stmCheck->bindValue(':id', $id, PDO::PARAM_INT);
        $stmCheck->execute();

        // Retorna true se houver dependências
        return $stmCheck->fetchColumn() > 0;
    }

    public function softDeleteParceiro($id)
    {
        try {
            $validaCliente = $this->verificarDependencias(
                "SELECT COUNT(*) FROM clientes WHERE idparceiro = :id AND excluir = 0",
                $id
            );
            
            if ($validaCliente) {
                return self::DEPENDENCY_ERROR;
            }

            // 2. EXCLUSÃO LÓGICA (SOFT DELETE)
            // Define 'excluir = 1' e 'dataexclusao' para 30 dias no futuro
            $sqlDelete = "UPDATE parceiros 
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

?>