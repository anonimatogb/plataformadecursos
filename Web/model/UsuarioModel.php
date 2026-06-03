<?php
class UsuarioModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarTodos(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM usuarios');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarFotoPerfil($idUsuario)
    {
        $sql = "SELECT foto_perfil FROM usuarios WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $idUsuario
        ]);

        return $stmt->fetchColumn();
    }

    public function buscarUsuario($id): array
    {
        $stmt = $this->pdo->query("SELECT * FROM usuarios WHERE id = $id");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }




    public function cadastrar($nome, $email, $senha, $cargo, $fotoperfil)
    {
        try {

            $sql = "INSERT INTO usuarios (nome, email, senha, cargo, foto_perfil) VALUES (:nome, :email, :senha, :cargo, :foto_perfil)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha,
                ':cargo' => $cargo,
                ':foto_perfil' => $fotoperfil
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "duplicado";
            }
            throw $e;
        }
    }

    public function login($email, $senha)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha");
        $stmt->execute([':email' => $email, ':senha' => $senha]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
