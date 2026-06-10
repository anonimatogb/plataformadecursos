<?php

class MatriculaModel
{

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function confe($id): array
    {
        $stmt = $this->pdo->query("SELECT * FROM matriculas WHERE aluno_id = $id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function macho($id): array
    {
        $sql = "SELECT 
usuarios.nome AS aluno,
cursos.nome AS curso,
matriculas.ativo AS matricula_ativa,
matriculas.id,
matriculas.data_matricula,
matriculas.professor_id
FROM matriculas
INNER JOIN usuarios 
    ON matriculas.aluno_id = usuarios.id
INNER JOIN cursos 
    ON matriculas.cursos_id = cursos.id
WHERE matriculas.professor_id = $id";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function relacao(): array
    {
        $sql = "SELECT 
usuarios.nome AS aluno,
cursos.nome AS curso,
matriculas.ativo,
matriculas.id,
matriculas.data_matricula,
matriculas.professor_id
FROM matriculas
INNER JOIN usuarios 
    ON matriculas.aluno_id = usuarios.id
INNER JOIN cursos 
    ON matriculas.cursos_id = cursos.id";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function matricular($aluno_id, $cursos_id, $professor_id)
    {
        try {
            $sql = "INSERT INTO matriculas (aluno_id, cursos_id, professor_id, data_matricula,concluido,ativo) VALUES (:aluno_id, :cursos_id, :professor_id, :data_matricula, :concluido, 1)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':aluno_id' => $aluno_id,
                ':cursos_id' => $cursos_id,
                ':professor_id' => $professor_id,
                ':data_matricula' => date('Y-m-d H:i:s'),
                ':concluido' => 0
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "ERRO";
            }
            throw $e;
        }
    }

    public function concluirCurso(int $matriculaId): bool
    {
        try {
            $sql = "UPDATE matriculas SET concluido = 1 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $matriculaId]);
        } catch (PDOException $e) {
            error_log("Erro ao concluir curso: " . $e->getMessage());
            return false;
        }
    }
}
