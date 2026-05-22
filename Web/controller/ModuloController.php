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

        if ($video['error'] !== 0) {
            return "Erro no upload";
        }

        $ext = pathinfo($video['name'], PATHINFO_EXTENSION);
        $nomeFinal = uniqid() . '.' . $ext;

        // Pasta base (relativa a este arquivo): Web/videos/
        $pasta = "../videos/";
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $caminho = $pasta . $nomeFinal;

        if (!move_uploaded_file($video['tmp_name'], $caminho)) {
            return "Erro ao salvar o upload do vídeo";
        }

        // professor logado
        if (!isset($_SESSION['id'])) {
            return "Sessão de professor inválida";
        }

        $resultado = $this->moduloModel->cadastrar($titulo, $cursos_id, (int)$_SESSION['id'], $caminho);

        if ($resultado !== "OK") {
            return $resultado;
        }

        return "Módulo cadastrado com sucesso";
    }
    public function porprof($professor_id) {
        return $this->moduloModel->porprof($professor_id);
    }
}
