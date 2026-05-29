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
            cursos.id,
            cursos.nome,
            cursos.descricao,
            cursos.carga_horaria
        FROM matriculas
        INNER JOIN cursos
            ON matriculas.cursos_id = cursos.id
        WHERE matriculas.aluno_id = :aluno_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':aluno_id', (int)$alunoId, PDO::PARAM_INT);
        $stmt->execute();

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
    public function cadastrarcursos($nome, $descricao, $carga_horaria, $professor, $fotocapaPath)
    {
        try {
            $sql = "INSERT INTO cursos (nome, descricao, carga_horaria, professor, fotocapa) VALUES (:nome, :descricao, :carga_horaria, :professor, :fotocapa)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':carga_horaria' => $carga_horaria,
                ':professor' => $professor,
                ':fotocapa' => $fotocapaPath
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

    public function atualizar($id, $nome, $descricao, $carga_horaria, $fotocapaPath = null)
    {
        if ($fotocapaPath !== null && $fotocapaPath !== '') {
            $sql = "UPDATE cursos 
                SET nome = :nome,
                    descricao = :descricao,
                    carga_horaria = :carga_horaria,
                    fotocapa = :fotocapa
                WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':carga_horaria' => $carga_horaria,
                ':fotocapa' => $fotocapaPath
            ]);
        }

        $sql = "UPDATE cursos 
            SET nome = :nome,
                descricao = :descricao,
                carga_horaria = :carga_horaria
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':carga_horaria' => $carga_horaria
        ]);
    }

    private function removerArquivoPorCaminho($path): void
    {
        if (!$path) {
            return;
        }

        $baseDir = __DIR__ . '/../uploads/';
        $fullPath = realpath($baseDir . $path);

        // Se realpath falhar (arquivo não existe), ainda assim checamos o caminho relativo simples.
        if ($fullPath === false) {
            $candidate = $baseDir . $path;
            if (is_file($candidate)) {
                @unlink($candidate);
            }
            return;
        }

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    public function deletar($id)
    {
        // tenta remover a capa antiga antes de deletar do banco
        $fotocapa = $this->buscarFotocapaPorId($id);
        $this->removerArquivoPorCaminho($fotocapa);

        $sql = "DELETE FROM cursos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);

        
    }
}
