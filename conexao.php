<?php

$host = "192.168.10.40";
$usuario = "postgres";
$senha = "nomatterwhat";
$banco = "manutencao";

try {

    $conexao = new PDO(
        "pgsql:host=$host;dbname=$banco",
        $usuario,
        $senha
    );

    $conexao->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $erro) {

    echo json_encode([
        "erro" => "Erro ao conectar com o banco"
    ]);

}

?>