<?php

class ContaDAO extends BaseDAO
{

    const ITEMS_PER_PAGE = 10;
    const DEPENDENCY_ERROR = 'dependency_error';

    /**
     * Busca uma única conta pelo ID.
     * @param int $id ID da conta.
     * @return Conta|null Objeto Conta ou null se não encontrada.
     */
    public function contaPorId(int $id): ?Conta
    {
        $sql = "SELECT id, idbanco, idusuario, agencia, numero, digito, tipo, chavepix, status, excluir, dataexclusao 
                FROM contas 
                WHERE id = :id AND excluir = 0 
                LIMIT 1";

        $params = [':id' => $id];

        // Usa o método da BaseDAO que retorna um único objeto
        $conta = $this->execSingleDQLClass($sql, $params, 'Conta');

        return $conta;
    }

    /**
     * Busca contas com paginação e filtro por termo.
     * @param int $page Página atual.
     * @param string $searchTerm Termo de busca (ID, agência, número, etc.).
     * @return array Array contendo 'contas', 'totalCount' e 'perPage'.
     */
    public function findPaginated(int $page = 1, string $searchTerm = ''): array
    {

        $offset = ($page - 1) * self::ITEMS_PER_PAGE;

        $whereClause = "";
        $params = [];

        if ($searchTerm !== '') {
            // Adaptação da busca universal para os campos da tabela 'contas'
            $whereClause = " WHERE (
                                CAST(id AS CHAR) LIKE :id 
                                OR CAST(agencia AS CHAR) LIKE :agencia 
                                OR CAST(numero AS CHAR) LIKE :numero 
                                OR chavepix LIKE :chavepix
                             )";

            // Os parâmetros LIKE devem ser passados separadamente (sua correção anterior)
            $likeTerm = '%' . $searchTerm . '%';
            $params[':id'] = $likeTerm;
            $params[':agencia'] = $likeTerm;
            $params[':numero'] = $likeTerm;
            $params[':chavepix'] = $likeTerm;
        }

        // Adiciona filtro para excluir = 0, se ainda não houver WHERE
        if ($whereClause === "") {
            $whereClause = " WHERE excluir = 0";
        } else {
            // Se já houver WHERE, adicionamos a condição de exclusão
            $whereClause .= " AND excluir = 0";
        }


        // Constrói a query completa
        $sql = "SELECT id, idbanco, idusuario, agencia, numero, digito, tipo, chavepix, status, excluir, dataexclusao 
                FROM contas"
            . $whereClause
            . " ORDER BY id DESC";

        // Chamada ao método de paginação
        $data = $this->execDQLPaginated(
            $sql,
            $params,
            'Conta',
            self::ITEMS_PER_PAGE,
            $offset
        );

        return [
            'contas' => $data['items'],
            'totalCount' => $data['totalCount'],
            'perPage' => self::ITEMS_PER_PAGE
        ];
    }

    /**
     * Insere uma nova conta no banco de dados.
     * @param Conta $conta O objeto Conta preenchido com os dados.
     * @return int O ID da conta recém-criada (lastInsertId).
     */
    public function inserirConta(Conta $conta): int
    {
        $sql = "INSERT INTO contas (
                idbanco, 
                idusuario, 
                agencia, 
                numero, 
                digito, 
                tipo, 
                chavepix
                
            ) VALUES (
                :idbanco, 
                :idusuario, 
                :agencia, 
                :numero, 
                :digito, 
                :tipo, 
                :chavepix
            )";

        $params = [
            ':idbanco'   => $conta->getIdBanco(),
            ':idusuario' => $conta->getIdUsuario(),
            ':agencia'   => $conta->getAgencia(),
            ':numero'    => $conta->getNumero(),
            ':digito'    => $conta->getDigito(),
            ':tipo'      => $conta->getTipo(),
            // Chave Pix é opcional e pode ser NULL
            ':chavepix'  => $conta->getChavePix() ?? NULL
        ];

        // O método execDML (assumindo que segue o padrão PDO) retorna o lastInsertId
        // se for uma operação INSERT.
        return $this->execDML($sql, $params);
    }

    public function atualizarConta(Conta $conta): bool
    {
        $sql = "UPDATE contas SET 
                idbanco = :idbanco, 
                idusuario = :idusuario, 
                agencia = :agencia, 
                numero = :numero, 
                digito = :digito, 
                tipo = :tipo, 
                chavepix = :chavepix
            WHERE id = :id AND excluir = 0";

        $params = [
            ':idbanco'   => $conta->getIdBanco(),
            ':idusuario' => $conta->getIdUsuario(),
            ':agencia'   => $conta->getAgencia(),
            ':numero'    => $conta->getNumero(),
            ':digito'    => $conta->getDigito(),
            ':tipo'      => $conta->getTipo(),
            ':chavepix'  => $conta->getChavePix() ?? NULL,
            ':id'        => $conta->getId()
        ];

        // Executa o UPDATE e retorna true se pelo menos uma linha foi afetada
        $rowsAffected = $this->execDML($sql, $params);
        return $rowsAffected > 0;
    }

    public function verificarDependencias($sql, int $id): bool {
        $sqlCheck = $sql;
        $stmCheck = $this->conn->prepare($sqlCheck);
        $stmCheck->bindValue(':id', $id, PDO::PARAM_INT);
        $stmCheck->execute();

        // Retorna true se houver dependências
        return $stmCheck->fetchColumn() > 0;
    }

    public function softDeleteConta(int $id): bool|string {
        try {
            $validaDespesas = $this->verificarDependencias(
                "SELECT COUNT(*) FROM despesas WHERE idconta = :id AND excluir = 0",
                $id
            );
            $validaReceias = $this->verificarDependencias(
                "SELECT COUNT(*) FROM receitas WHERE idconta = :id AND excluir = 0",
                $id
            );
            $validaFaturas = $this->verificarDependencias(
                "SELECT COUNT(*) FROM faturas WHERE idconta = :id AND excluir = 0",
                $id
            );
            if ($validaDespesas || $validaReceias || $validaFaturas) {
                return self::DEPENDENCY_ERROR;
            }

            // 2. EXCLUSÃO LÓGICA (SOFT DELETE)
            // Define 'excluir = 1' e 'dataexclusao' para 30 dias no futuro
            $sqlDelete = "UPDATE contas 
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
