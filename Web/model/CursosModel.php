<?php
class CursosModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarTodos(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM cursos');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cursos que o aluno (aluno_id) está matriculado
    public function todosaluno($alunoId): array
    {
        $sql = "SELECT
        matriculas.concluido AS concluido,
        cursos.ativo AS ativo,
            cursos.id,
            cursos.nome,
            cursos.descricao,
            cursos.carga_horaria,
            cursos.fotocapa
        FROM matriculas
        INNER JOIN cursos
            ON matriculas.cursos_id = cursos.id
        WHERE matriculas.aluno_id = :aluno_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':aluno_id', (int)$alunoId, PDO::PARAM_INT);
            

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarcursos($id): array
    {
        $stmt = $this->pdo->query("SELECT * FROM cursos WHERE professor = $id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarum($id): array
    {
        $stmt = $this->pdo->query("SELECT * FROM cursos WHERE id = $id");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function tra($id): array
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            cursos.*,
            usuarios.nome AS professor_nome
        FROM cursos
        INNER JOIN usuarios 
            ON cursos.professor = usuarios.id
        WHERE cursos.id = :id
    ");

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function cadastrarcursos($nome, $descricao, $carga_horaria, $professor, $fotocapa = null, $certificado = null)
    {
        try {
            $sql = "INSERT INTO cursos (nome, descricao, carga_horaria, professor, fotocapa, certificado,ativo) VALUES (:nome, :descricao, :carga_horaria, :professor, :fotocapa, :certificado, 1)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':carga_horaria' => $carga_horaria,
                ':professor' => $professor,
                ':fotocapa' => $fotocapa,
                ':certificado' => $certificado
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "ERRO";
            }
            throw $e;
        }
    }

    public function buscarFotocapaPorId($id): ?string
    {
        $stmt = $this->pdo->prepare("SELECT fotocapa FROM cursos WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['fotocapa'] : null;
    }

    public function buscarCertificadoPorId($id): ?string
    {
        $stmt = $this->pdo->prepare("SELECT certificado FROM cursos WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['certificado'] : null;
    }




    public function desativar($id)
    {
        $cursoId = $id;

        // Desativa o curso
        $sql = "UPDATE cursos SET ativo = 0 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => (int)$cursoId]);

        // Desativa os módulos relacionados
        $sqlModulos = "UPDATE modulo SET ativo = 0 WHERE cursos_id = :cursoId";
        $stmtModulos = $this->pdo->prepare($sqlModulos);
        $stmtModulos->execute([':cursoId' => (int)$cursoId]);

        // Desativa as matrículas relacionadas
        $sqlMatriculas = "UPDATE matriculas SET ativo = 0 WHERE cursos_id = :cursoId";
        $stmtMatriculas = $this->pdo->prepare($sqlMatriculas);
        $stmtMatriculas->execute([':cursoId' => (int)$cursoId]);

        return true;
    }

    public function obterCursoPorId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cursos WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome, $descricao, $carga_horaria, $fotocapa = null, $certificado = null)
    {
        try {
            $sql = "UPDATE cursos SET nome = :nome, descricao = :descricao, carga_horaria = :carga_horaria, fotocapa = :fotocapa, certificado = :certificado WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => (int)$id,
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':carga_horaria' => $carga_horaria,
                ':fotocapa' => $fotocapa,
                ':certificado' => $certificado
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "ERRO";
            }
            throw $e;
        }
    }
}
