<?php

class BaseDAO
{

    /**
     * @var PDO Objeto de conexão PDO.
     */
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Executa comandos DML (INSERT, UPDATE, DELETE).
     * @param string $sql A query SQL com placeholders.
     * @param array $params Os valores a serem ligados (binds).
     * @return int|false Número de linhas afetadas ou false em caso de erro.
     */
    protected function execDML(string $sql, array $params = []): int|false
    {
        try {
            $stm = $this->conn->prepare($sql);
            // Usamos execute diretamente no PDO para DML simples
            $stm->execute($params);
            return $stm->rowCount();
        } catch (PDOException $e) {
            // Em ambiente de produção, é essencial logar o erro
            error_log("Erro DML: " . $e->getMessage() . " SQL: " . $sql);
            return false;
        }
    }

    protected function execDQL(string $sql, array $params = []): array
    {
        try {
            $stm = $this->conn->prepare($sql);

            // Simplesmente executa com o array de parâmetros.
            $stm->execute($params);

            return $stm->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro DQL: " . $e->getMessage() . " SQL: " . $sql);
            return [];
        }
    }

    /**
     * Executa um SELECT e mapeia os resultados diretamente para objetos de uma classe Entity.
     * @param string $sql A query SQL.
     * @param array $params Array associativo de parâmetros.
     * @param string $className Nome da classe Entity (e.g., 'Banco').
     * @return array Array de objetos da classe especificada.
     */
    protected function execDQLClass(string $sql, array $params, string $className): array {
        try {
            $stm = $this->conn->prepare($sql);
            
            $stm->execute($params); 
            
            // Configura o PDO para mapear as colunas diretamente para as propriedades públicas da classe
            $stm->setFetchMode(PDO::FETCH_CLASS, $className);
            
            return $stm->fetchAll();

        } catch (PDOException $e) {
            error_log("Erro DQL Class: " . $e->getMessage() . " SQL: " . $sql);
            return [];
        }
    }

    protected function execSingleDQLClass(string $sql, array $params, string $className): ?object
    {

        // Você pode ter feito uma busca na BaseDAO que retorna um array e pega o primeiro item.
        // Exemplo:
        $results = $this->execDQLClass($sql, $params, $className); // execDQLClass retorna array

        // Se o array de resultados está vazio, retorna null
        return empty($results) ? null : $results[0];
    }

    /**
     * Executa comandos SELECT, com suporte a bind manual e tipagem para paginação.
     * @param string $sql A query SQL.
     * @param array $params Parâmetros nomeados (ex: [':id' => 5]).
     * @return array Array de resultados (FETCH_ASSOC).
     */
    protected function execSelect(string $sql, array $params = []): array
    {
        try {
            $stm = $this->conn->prepare($sql);

            // Lógica de Bind crucial (adaptada do seu código original)
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    // Determina o tipo PDO para o bind
                    $pdoType = PDO::PARAM_STR;

                    if ($key === ':limite' || $key === ':offset' || is_int($value)) {
                        $pdoType = PDO::PARAM_INT;
                    }

                    // Nota: O seu código original usava bindValue para garantir a tipagem INT
                    $stm->bindValue($key, $value, $pdoType);
                }
            }

            $stm->execute();
            // Retorna como array associativo. Os DAOs filhos podem converter para Entity.
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro SELECT: " . $e->getMessage() . " SQL: " . $sql);
            return [];
        }
    }

    /**
     * Executa um SELECT e retorna apenas o primeiro resultado (ou false).
     * @param string $sql A query SQL.
     * @param array $params Parâmetros nomeados.
     * @return array|false Primeiro resultado como array associativo ou false.
     */
    protected function execSingleSelect(string $sql, array $params = []): array|false
    {
        $results = $this->execSelect($sql, $params);
        return empty($results) ? false : $results[0];
    }

    /**
     * Obtém o ID da última inserção.
     */
    protected function lastInsertId(): int
    {
        return (int)$this->conn->lastInsertId();
    }

    /**
     * Executa uma query SELECT com paginação (LIMIT/OFFSET) e retorna os dados e a contagem total.
     * * @param string $sql A query SQL sem LIMIT/OFFSET (ex: SELECT * FROM bancos WHERE...).
     * @param array $params Array associativo de parâmetros.
     * @param string $className Nome da classe Entity (e.g., 'Banco').
     * @param int $limit Número máximo de registros por página.
     * @param int $offset Ponto inicial para a busca (calculado como: (página - 1) * limite).
     * @return array Um array contendo ['items', 'totalCount'].
     */
    protected function execDQLPaginated(string $sql, array $params, string $className, int $limit, int $offset): array
    {
        try {
            // 1. Contagem Total de Registros
            // Usa a query original para contar o total (sem LIMIT)
            $countSql = "SELECT COUNT(*) FROM (" . $sql . ") AS count_alias";
            $stmCount = $this->conn->prepare($countSql);
            $stmCount->execute($params);
            $totalCount = (int) $stmCount->fetchColumn();

            // 2. Busca dos Registros da Página Atual
            // Adiciona LIMIT e OFFSET à query original
            $paginatedSql = $sql . " LIMIT :limite OFFSET :offset";

            $stm = $this->conn->prepare($paginatedSql);

            // Adiciona os parâmetros de paginação (DEVE ser INT para o PDO)
            $params[':limite'] = $limit;
            $params[':offset'] = $offset;

            // O PDO exige que LIMIT e OFFSET sejam bindados como INT
            $stm->bindValue(':limite', $limit, PDO::PARAM_INT);
            $stm->bindValue(':offset', $offset, PDO::PARAM_INT);

            // Bind dos parâmetros de busca originais (user_id, email_id, etc.)
            foreach ($params as $key => $value) {
                if ($key !== ':limite' && $key !== ':offset') {
                    // Presume string, mas você pode refinar a tipagem se necessário
                    $stm->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
                }
            }

            $stm->execute();

            // Mapeia diretamente para a classe (como fizemos para o login)
            $stm->setFetchMode(PDO::FETCH_CLASS, $className);
            $items = $stm->fetchAll();

            return [
                'items' => $items,
                'totalCount' => $totalCount
            ];
        } catch (PDOException $e) {
            error_log("Erro DQL Paginated: " . $e->getMessage());
            // Se houver erro, retorna um array vazio com contagem zero
            return ['items' => [], 'totalCount' => 0];
        }
    }
}
