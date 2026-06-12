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

    public function cadastrar($nome, $email, $senha, $cargo, $fotoperfil)
    {
        return $this->usuarioModel->cadastrar($nome, $email, $senha, $cargo, $fotoperfil);
    }

    public function login($email, $senha)
    {
        $usuarios = $this->usuarioModel->buscarTodos();
        foreach ($usuarios as $usuario) {
            if ($usuario['email'] === $email && $usuario['senha'] === $senha) {
                return $usuario;
            }
        }
        return false;
    }

    public function todos()
    {
        return $this->usuarioModel->buscarTodos();
    }

public function buscarFotoPerfil($idUsuario)
    {
        return $this->usuarioModel->buscarFotoPerfil($idUsuario);
    }

    public function atualizar($id, $nome, $email, $senha, $foto_perfil)
    {
        return $this->usuarioModel->atualizar($id, $nome, $email, $senha, $foto_perfil);
    }
    public function trans($id){
        return $this->usuarioModel->trans($id);
    }

}
