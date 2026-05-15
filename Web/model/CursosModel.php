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
    public function buscarcursos($id): array
    {
        $stmt = $this->pdo->query("SELECT * FROM cursos WHERE professor = $id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastrarcursos($nome, $descricao, $carga_horaria, $professor)
    {
        try {

            $sql = "INSERT INTO cursos (nome, descricao, carga_horaria, professor) VALUES (:nome, :descricao, :carga_horaria, :professor)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':carga_horaria' => $carga_horaria,
                ':professor' => $professor
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "ERRO";
            }
            throw $e;
        }
    }






    }