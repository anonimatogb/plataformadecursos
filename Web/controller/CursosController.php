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

    public function cadastrar($nome, $descricao, $carga_horaria, $professor, $fotocapa = null, $certificado = null)
    {
        return $this->cursosModel->cadastrarcursos($nome, $descricao, $carga_horaria, $professor, $fotocapa, $certificado);
    }

    public function atualizar($id, $nome, $descricao, $carga_horaria, $fotocapa = null, $certificado = null)
    {
        return $this->cursosModel->atualizar($id, $nome, $descricao, $carga_horaria, $fotocapa, $certificado);
    }

    public function desativar($id)
    {
        return $this->cursosModel->desativar($id);
    }

    public function todosaluno($id)
    {
        return $this->cursosModel->todosaluno($id);
    }
    public function obterCursoPorId($id)
    {
        return $this->cursosModel->obterCursoPorId($id);
    }
}

