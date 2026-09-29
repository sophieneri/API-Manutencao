<?php

header("Content-Type: application/json");

require_once "conexao.php";

$metodo = $_SERVER["REQUEST_METHOD"];

$dados = json_decode(
    file_get_contents("php://input"),
    true
);


/* GET */

if ($metodo == "GET") {

    $sql = "SELECT * FROM chamados";

    $resultado = $conexao->query($sql);

    $chamados = $resultado->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($chamados);
}


/* POST */

if ($metodo == "POST") {

    if (
        $dados["equipamento"] == "" ||
        $dados["setor"] == "" ||
        $dados["descricao"] == "" ||
        $dados["prioridade"] == ""
    ) {

        echo json_encode([
            "erro" => "Preencha todos os campos"
        ]);

    } else {

        if (
            $dados["prioridade"] != "baixa" &&
            $dados["prioridade"] != "media" &&
            $dados["prioridade"] != "alta"
        ) {

            echo json_encode([
                "erro" => "Prioridade inválida"
            ]);

        } else {

            $sql = "INSERT INTO chamados
            (equipamento, setor, descricao, prioridade)
            VALUES
            (:equipamento, :setor, :descricao, :prioridade)";

            $stmt = $conexao->prepare($sql);

            $stmt->execute([
                ":equipamento" => $dados["equipamento"],
                ":setor" => $dados["setor"],
                ":descricao" => $dados["descricao"],
                ":prioridade" => $dados["prioridade"]
            ]);

            echo json_encode([
                "mensagem" => "Chamado cadastrado com sucesso"
            ]);
        }
    }
}


/* PUT */

if ($metodo == "PUT") {

    if (
        $dados["id"] == "" ||
        $dados["equipamento"] == "" ||
        $dados["setor"] == "" ||
        $dados["descricao"] == "" ||
        $dados["prioridade"] == "" ||
        $dados["status"] == ""
    ) {

        echo json_encode([
            "erro" => "Preencha todos os campos"
        ]);

    } else {

        $sql = "UPDATE chamados SET
        equipamento = :equipamento,
        setor = :setor,
        descricao = :descricao,
        prioridade = :prioridade,
        status = :status
        WHERE id = :id";

        $stmt = $conexao->prepare($sql);

        $stmt->execute([
            ":equipamento" => $dados["equipamento"],
            ":setor" => $dados["setor"],
            ":descricao" => $dados["descricao"],
            ":prioridade" => $dados["prioridade"],
            ":status" => $dados["status"],
            ":id" => $dados["id"]
        ]);

        echo json_encode([
            "mensagem" => "Chamado atualizado com sucesso"
        ]);
    }
}


/* DELETE */

if ($metodo == "DELETE") {

    $sql = "DELETE FROM chamados
            WHERE id = :id";

    $stmt = $conexao->prepare($sql);

    $stmt->execute([
        ":id" => $dados["id"]
    ]);

    if ($stmt->rowCount() > 0) {

        echo json_encode([
            "mensagem" => "Chamado excluído com sucesso"
        ]);

    } else {

        echo json_encode([
            "erro" => "Chamado não encontrado"
        ]);
    }
}

?>