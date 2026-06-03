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
            cursos.id,
            cursos.nome,
            cursos.descricao,
            cursos.carga_horaria,
            cursos.fotocapa
        FROM matriculas
        INNER JOIN cursos
            ON matriculas.cursos_id = cursos.id
        WHERE matriculas.aluno_id = :aluno_id"
        ;

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
    public function cadastrarcursos($nome, $descricao, $carga_horaria, $professor, $fotocapaPath, $certificadoPath = null)
    {
        try {
            $sql = "INSERT INTO cursos (nome, descricao, carga_horaria, professor, fotocapa, certificado) VALUES (:nome, :descricao, :carga_horaria, :professor, :fotocapa, :certificado)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':carga_horaria' => $carga_horaria,
                ':professor' => $professor,
                ':fotocapa' => $fotocapaPath,
                ':certificado' => $certificadoPath
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


    public function atualizar($id, $nome, $descricao, $carga_horaria, $fotocapaPath = null, $certificadoPath = null)
    {
        // Se trocou certificado, remove arquivo antigo (quando houver)
        if ($certificadoPath !== null && $certificadoPath !== '') {
            $certificadoAntigo = $this->buscarCertificadoPorId($id);
            $this->removerArquivoPorCaminho($certificadoAntigo);
        }

        if ($fotocapaPath !== null && $fotocapaPath !== '' && $certificadoPath !== null && $certificadoPath !== '') {
            $sql = "UPDATE cursos 
                SET nome = :nome,
                    descricao = :descricao,
                    carga_horaria = :carga_horaria,
                    fotocapa = :fotocapa,
                    certificado = :certificado
                WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':carga_horaria' => $carga_horaria,
                ':fotocapa' => $fotocapaPath,
                ':certificado' => $certificadoPath
            ]);
        }

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

        if ($certificadoPath !== null && $certificadoPath !== '') {
            $sql = "UPDATE cursos 
                SET nome = :nome,
                    descricao = :descricao,
                    carga_horaria = :carga_horaria,
                    certificado = :certificado
                WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':carga_horaria' => $carga_horaria,
                ':certificado' => $certificadoPath
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
        $cursoId = (int)$id;

        try {
            $this->pdo->beginTransaction();

            // 1) Buscar módulos do curso (para remover vídeos do disco)
            $sqlModulos = "SELECT id, video FROM modulo WHERE cursos_id = :cursoId";
            $stmtModulos = $this->pdo->prepare($sqlModulos);
            $stmtModulos->bindValue(':cursoId', $cursoId, PDO::PARAM_INT);
            $stmtModulos->execute();
            $modulos = $stmtModulos->fetchAll(PDO::FETCH_ASSOC);

            // 2) Remover vídeos dos módulos do disco
            foreach ($modulos as $modulo) {
                if (!isset($modulo['video'])) {
                    continue;
                }
                $this->removerVideoDoModulo($modulo['video']);
            }

            // 3) Remover matrículas relacionadas ao curso
            $sqlMat = "DELETE FROM matriculas WHERE cursos_id = :cursoId";
            $stmtMat = $this->pdo->prepare($sqlMat);
            $stmtMat->bindValue(':cursoId', $cursoId, PDO::PARAM_INT);
            $stmtMat->execute();

            // 4) Remover módulos do banco
            $sqlModDel = "DELETE FROM modulo WHERE cursos_id = :cursoId";
            $stmtModDel = $this->pdo->prepare($sqlModDel);
            $stmtModDel->bindValue(':cursoId', $cursoId, PDO::PARAM_INT);
            $stmtModDel->execute();

            // 5) Remover arquivos do curso (capa e certificado)
            $fotocapa = $this->buscarFotocapaPorId($cursoId);
            $this->removerArquivoPorCaminho($fotocapa);

            $certificado = $this->buscarCertificadoPorId($cursoId);
            $this->removerArquivoPorCaminho($certificado);

            // 6) Remover curso
            $sql = "DELETE FROM cursos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $cursoId]);

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log('Erro ao deletar curso completo (id=' . $cursoId . '): ' . $e->getMessage());
            return false;
        }
    }

    private function removerVideoDoModulo($caminho): void
    {
        if (!$caminho) {
            return;
        }

        $baseDir = __DIR__ . '/../'; // .../Web/
        $videosDir = $baseDir . 'videos/';

        $caminho = trim((string)$caminho);

        // 1) Se for só o nome do arquivo (ex.: 123.mp4)
        if (!str_contains($caminho, '/') && !str_contains($caminho, '\\')) {
            $candidato = $videosDir . $caminho;
            if (is_file($candidato)) {
                @unlink($candidato);
            }
            return;
        }

        // 2) Se estiver em ../videos/arquivo.mp4 (como está no upload)
        $fullPath1 = realpath($baseDir . $caminho);
        if ($fullPath1 !== false && is_file($fullPath1)) {
            @unlink($fullPath1);
            return;
        }

        // 3) Tenta pegar o filename e remover dentro de videos/
        $filename = basename(str_replace(['\\', '/'], '/', $caminho));
        if ($filename) {
            $candidatoVideos = $videosDir . $filename;
            if (is_file($candidatoVideos)) {
                @unlink($candidatoVideos);
            }
        }
    }
}

