<?php

class CursoDAO extends BaseDAO
{

    const ITEMS_PER_PAGE = 10;
    const DEPENDENCY_ERROR = 'dependency_error';

    /**
     * Busca um único curso pelo ID.
     * @param int $id ID do curso.
     * @return Curso|null Objeto Curso ou null se não encontrada.
     */
    public function cursoPorId(int $id): ?Curso
    {
        $sql = "SELECT id, curso, descricao, conteudo, cargahoraria, vagas, vip, preco, status, excluir, dataexclusao 
                FROM cursos 
                WHERE id = :id AND excluir = 0 
                LIMIT 1";

        $params = [':id' => $id];

        // Usa o método da BaseDAO que retorna um único objeto
        $curso = $this->execSingleDQLClass($sql, $params, 'Curso');

        return $curso;
    }

    /**
     * Busca cursos com paginação e filtro por termo.
     * @param int $page Página atual.
     * @param string $searchTerm Termo de busca (ID, curso, descricao, etc.).
     * @return array Array contendo 'cursos', 'totalCount' e 'perPage'.
     */
    public function findPaginated(int $page = 1, string $searchTerm = ''): array
    {

        $offset = ($page - 1) * self::ITEMS_PER_PAGE;

        $whereClause = "";
        $params = [];

        if ($searchTerm !== '') {
            // Adaptação da busca universal para os campos da tabela 'cursos'
            $whereClause = " WHERE (
                                CAST(id AS CHAR) LIKE :id 
                                OR curso LIKE :curso
                                OR descricao LIKE :descricao
                                OR conteudo LIKE :conteudo
                             )";

            // Os parâmetros LIKE devem ser passados separadamente (sua correção anterior)
            $likeTerm = '%' . $searchTerm . '%';
            $params[':id'] = $likeTerm;
            $params[':curso'] = $likeTerm;
            $params[':descricao'] = $likeTerm;
            $params[':conteudo'] = $likeTerm;
        }

        // Adiciona filtro para excluir = 0, se ainda não houver WHERE
        if ($whereClause === "") {
            $whereClause = " WHERE excluir = 0";
        } else {
            // Se já houver WHERE, adicionamos a condição de exclusão
            $whereClause .= " AND excluir = 0";
        }


        // Constrói a query completa
        $sql = "SELECT id, curso, descricao, conteudo, cargahoraria, vagas, vip, preco, status, excluir, dataexclusao
                FROM cursos"
            . $whereClause
            . " ORDER BY curso";

        // Chamada ao método de paginação
        $data = $this->execDQLPaginated(
            $sql,
            $params,
            'Curso',
            self::ITEMS_PER_PAGE,
            $offset
        );

        return [
            'cursos' => $data['items'],
            'totalCount' => $data['totalCount'],
            'perPage' => self::ITEMS_PER_PAGE
        ];
    }

    /**
     * Insere uma nova curso no banco de dados.
     * @param Curso $curso O objeto Curso preenchido com os dados.
     * @return int O ID da curso recém-criada (lastInsertId).
     */
    public function inserirCurso(Curso $curso): int
    {
        $sql = "INSERT INTO cursos (
                curso, descricao, conteudo, cargahoraria, vagas, vip, preco                
            ) VALUES (
                :curso, :descricao, :conteudo, :cargahoraria, :vagas, :vip, :preco
            )";

        $params = [
            ':curso'   => $curso->getCurso(),
            ':descricao' => $curso->getDescricao(),
            ':conteudo'   => $curso->getConteudo() ?? NULL,
            ':cargahoraria'    => $curso->getCargaHoraria(),
            ':vagas'    => $curso->getVagas() ?? NULL,
            ':vip'      => $curso->getVip(),
            ':preco'      => $curso->getPreco()
        ];

        // O método execDML (assumindo que segue o padrão PDO) retorna o lastInsertId
        // se for uma operação INSERT.
        return $this->execDML($sql, $params);
    }

    public function atualizarCurso(Curso $curso): bool
    {
        $sql = "UPDATE cursos SET 
                curso = :curso, 
                descricao = :descricao, 
                conteudo = :conteudo, 
                cargahoraria = :cargahoraria, 
                vagas = :vagas, 
                vip = :vip, 
                preco = :preco
            WHERE id = :id AND excluir = 0";

        $params = [
            ':curso'   => $curso->getCurso(),
            ':descricao' => $curso->getDescricao(),
            ':conteudo'   => $curso->getConteudo() ?? NULL,
            ':cargahoraria'    => $curso->getCargaHoraria(),
            ':vagas'    => $curso->getVagas() ?? NULL,
            ':vip'      => $curso->getVip(),
            ':preco'  => $curso->getPreco(),
            ':id'        => $curso->getId()
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

    public function softDeleteCurso(int $id): bool|string {
        try {
            /*$validaTurmas = $this->verificarDependencias(
                "SELECT COUNT(*) FROM turmas WHERE idcurso = :id AND excluir = 0",
                $id
            );
            
            if ($validaTurmas) {
                return self::DEPENDENCY_ERROR;
            }*/

            // 2. EXCLUSÃO LÓGICA (SOFT DELETE)
            // Define 'excluir = 1' e 'dataexclusao' para 30 dias no futuro
            $sqlDelete = "UPDATE cursos 
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
