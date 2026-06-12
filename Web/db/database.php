<?php
    // =======================
    // RAILWAY (PRODUÇÃO)
    // =======================
    $host = 'localhost';
    $port = 3306;
    $dbname = 'plataformacursos';
    $user = 'root';
    $password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $password);
    // Configura o modo de erro do PDO para exceção
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}