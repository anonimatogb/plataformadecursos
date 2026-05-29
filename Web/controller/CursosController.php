<?php
require_once "../Model/CursosModel.php";

class CursosController
{
    private $cursosModel;

    public function __construct($pdo)
    {
        $this->cursosModel = new CursosModel($pdo);
    }
    public function buscarum($id)
    {
        $cursos = $this->cursosModel->buscarum($id);
        return $cursos;
    }
    public function todos()
    {
        $cursos = $this->cursosModel->buscarTodos();
        return $cursos;
    }
    public function todosprof($id)
    {
        $cursos = $this->cursosModel->buscarcursos($id);
        return $cursos;
    }

    public function tra($id)
    {
        $cursos = $this->cursosModel->tra($id);
        return $cursos;
    }

    public function cadastrar($nome, $descricao, $carga_horaria, $professor, $fotocapaPath = null)
    {
        return $this->cursosModel->cadastrarcursos($nome, $descricao, $carga_horaria, $professor, $fotocapaPath);
    }

    public function atualizar($id, $nome, $descricao, $carga_horaria, $fotocapaPath = null)
    {
        return $this->cursosModel->atualizar($id, $nome, $descricao, $carga_horaria, $fotocapaPath);
    }

    public function deletar($id)
    {
        return $this->cursosModel->deletar($id);
    }

    public function todosaluno($id)
    {
        return $this->cursosModel->todosaluno($id);
    }
}

