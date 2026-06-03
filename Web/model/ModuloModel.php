<?php
class ModuloModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function cadastrar($titulo, $cursos_id, $professor, $video)
    {
        try {
            $sql = "INSERT INTO modulo (titulo, cursos_id, professor, video)
                    VALUES (:titulo, :cursos_id, :professor, :video)";

            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':titulo' => $titulo,
                ':cursos_id' => $cursos_id,
                ':professor' => $professor,
                ':video' => $video,
            ]);

            if (!$ok) {
                return "ERRO: falha no INSERT";
            }

            return "OK";
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar módulo: " . $e->getMessage());
            return "ERRO: " . $e->getMessage();
        }
    }

    public function porprof($professor_id)
    {
        try {
            // Ordena primeiro pelo nome do curso (A-Z) e, dentro do curso, pelo menor id -> maior id
            $sql = "SELECT 
                        m.*, 
                        c.nome AS curso_nome
                    FROM modulo m
                    INNER JOIN cursos c ON m.cursos_id = c.id
                    WHERE c.professor = :professor_id
                    ORDER BY c.nome ASC, m.id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':professor_id' => $professor_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar módulos: " . $e->getMessage());
            return [];
        }

    }

    public function porcurso(int $cursoId): array
    {
        try {
            $sql = "SELECT *
                    FROM modulo
                    WHERE cursos_id = :cursoId
                    ORDER BY id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':cursoId', $cursoId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar módulos do curso: " . $e->getMessage());
            return [];
        }
    }

    private function removerArquivoPorCaminho($caminho): void
    {
        if (!$caminho) {
            return;
        }

        // Em cadastrar(): $caminho = "../videos/<nome>"
        // Persistimos esse valor na coluna `video`.
        $baseDir = __DIR__ . '/../';
        $fullPath = realpath($baseDir . $caminho);

        if ($fullPath === false) {
            $candidato = $baseDir . $caminho;
            if (is_file($candidato)) {
                @unlink($candidato);
            }
            return;
        }

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    public function deletar($id)
    {
        $sqlSelect = $this->pdo->prepare("SELECT video FROM modulo WHERE id = :id");
        $sqlSelect->execute([':id' => (int)$id]);
        $row = $sqlSelect->fetch(PDO::FETCH_ASSOC);

        if ($row && isset($row['video'])) {
            $this->removerArquivoPorCaminho($row['video']);
        }

        $sql = "DELETE FROM modulo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => (int)$id]);
    }
}





