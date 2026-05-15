<?php
require_once "../Model/UsuarioModel.php";

class UsuarioController
{
    private $usuarioModel;

    public function __construct($pdo)
    {
        $this->usuarioModel = new UsuarioModel($pdo);
    }

   
    public function buscarUsuario($id)
    {
        $usuario = $this->usuarioModel->buscarUsuario($id);
        return $usuario;
    }

    public function cadastrar($nome, $email, $senha, $cargo)
    {
        return $this->usuarioModel->cadastrar($nome, $email, $senha, $cargo);
    }
 

    public function login($email, $senha)
    {

        $usuario = $this->usuarioModel->login($email, $senha);

        if ($usuario) {

            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['cargo'] = $usuario['cargo'];

            header("Location: ../index.php");

        } else {

            echo "<script>alert('Erro: Senha ou email errado!');</script>";
        }
    }

    public function deletar($id)
    {
        $usuario = $this->usuarioModel->deletar($id);
        return $usuario;
    }

    public function atualizar($id, $nome, $email, $senha, $cargo){
    return $this->usuarioModel->atualizar($id, $nome, $email, $senha, $cargo);
}
}