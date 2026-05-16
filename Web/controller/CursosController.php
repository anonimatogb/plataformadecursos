<?php
require_once "../Model/CursosModel.php";

class CursosController
{
    private $cursosModel;

    public function __construct($pdo)
    {
        $this->cursosModel = new CursosModel($pdo);
    }

    public function todos($id)
    {
        $cursos = $this->cursosModel->buscarcursos($id);
        return $cursos;
    }

    public function cadastrar($nome, $descricao, $carga_horaria, $professor)
    {
        return $this->cursosModel->cadastrarcursos($nome, $descricao, $carga_horaria, $professor);
    }

    public function atualizar($id, $nome, $descricao, $carga_horaria)
    {
        return $this->cursosModel->atualizar($id, $nome, $descricao, $carga_horaria);
    }

}
