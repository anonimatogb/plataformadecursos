<?php
require_once "../Model/CursosModel.php";

class CursosController
{
    private $CursosModel;
private $pdo;  
    public function __construct($pdo)
    {

    $this->CursosModel = new CursosModel($pdo);
    $this->pdo = $pdo;

    }

     public function buscarcursos($id)
    {
        $cursos = $this->CursosModel->buscarcursos($id);
        return $cursos;
    }

    public function cadastrarcursos($nome, $descricao,$carga_horaria)
    {
        return $this->CursosModel->cadastrarcursos($nome, $descricao, $carga_horaria);
    }

}