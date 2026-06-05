<?php

require_once "../Model/ModuloModel.php";

class ModuloController
{
    private $moduloModel;

    public function __construct($pdo)
    {
        $this->moduloModel = new ModuloModel($pdo);
    }

    public function cadastrar($titulo, $cursos_id, $video) {
    

        $resultado = $this->moduloModel->cadastrar($titulo, $cursos_id, (int)$_SESSION['id'], $video);

        if ($resultado !== "OK") {
            return $resultado;
        }

        return "Módulo cadastrado com sucesso";
    }
    public function porprof($professor_id) {
        return $this->moduloModel->porprof($professor_id);
    }

    public function porcurso($cursoId) {
        return $this->moduloModel->porcurso((int)$cursoId);
    }

    public function desativar($id) {
        return $this->moduloModel->desativar($id);
    }
}



