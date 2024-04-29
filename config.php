<?php 
    define("HOST", "localhost");  // Nome do host do banco de dados
    define("USER", "root");       // Usuário do banco de dados
    define("PASS", "aula123");           // Senha do usuário
    define("BASE", "gerenciamento_pets");   // Nome do banco de dados

    $conn = new mysqli(HOST, USER, PASS, BASE);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
?>