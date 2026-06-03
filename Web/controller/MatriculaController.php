<?php

require_once "../Model/MatriculaModel.php";

class MatriculaController
{
    private $matriculaModel;


    public function __construct($pdo)
    {
        $this->matriculaModel = new MatriculaModel($pdo);
    }

    public function matricular($aluno_id, $cursos_id,$professor_id)
    {
        return $this->matriculaModel->matricular($aluno_id, $cursos_id,$professor_id);
    }

    public function confe($id)
    {
        return $this->matriculaModel->confe($id);
    }

    public function macho($id)
    {
        return $this->matriculaModel->macho($id);
    }
   
    public function relacao()
    {
        return $this->matriculaModel->relacao();
    }

    public function concluirCurso(int $matriculaId): bool
    {
        return $this->matriculaModel->concluirCurso($matriculaId);
    }
}

