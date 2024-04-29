<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Home</title>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-color: #e3f2fd;">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Gerenciamento PET</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Início  | </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cadastro-tutor.php"> Cadastro Tutor  | </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cadastro-pets.php"> Cadastro PETS  | </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="agendamento.php">Agendamento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true"></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-center">
        <h1>AGENDAMENTO DE SERVIÇOS</h1>
    </div>

    <form action="?agendamento.php" method="POST">
        <div>
            <label for="servico">Selecione o Serviço:</label>
            <select name="servico" id="servico" class="form-control">
                <option value="banho">Banho</option>
                <option value="tosa">Tosa</option>
                <option value="consulta">Consulta Veterinária</option>
            </select>
            <br>
            <label>Data:</label>
            <input type="date" class="form-control" id="data" name="data">
            <br>
            <!-- Aqui você pode adicionar a lógica para exibir os horários disponíveis -->
            <label for="horario">Selecione o Horário:</label>
            <select name="horario" id="horario" class="form-control">
                <option value="08:00">08:00</option>
                <option value="09:00">09:00</option>
                <option value="10:00">10:00</option>
                <option value="11:00">11:00</option>
                <option value="12:00">12:00</option>
                <option value="14:00">14:00</option>
                <option value="15:00">15:00</option>
                <option value="16:00">16:00</option>
                <option value="17:00">17:00</option>
            </select>
            <br>
            <!-- Aqui você pode adicionar a lógica para exibir os animais cadastrados -->
            <?php
            include_once("config.php");

            // Prepara a consulta SQL para buscar todos os animais e seus tutores
            $sql = "SELECT p.idpet, p.nome AS nome_animal, pr.nome AS nome_tutor 
                    FROM pets p
                    INNER JOIN proprietario pr ON p.idproprietario = pr.idproprietario";

            // Executa a consulta
            $result = $conn->query($sql);

            // Verifica se a consulta retornou resultados
            if ($result->num_rows > 0) {
                // Inicializa a variável $animais como um array vazio
                $animais = array();

                // Percorre os resultados da consulta e os armazena em $animais
                while ($row = $result->fetch_assoc()) {
                    $animais[] = $row;
                }

                // Ordena o array $animais pelo nome do animal em ordem alfabética
                usort($animais, function($a, $b) {
                    return strcmp($a['nome_animal'], $b['nome_animal']);
                });
            } else {
                // Se não houver animais no banco de dados, inicializa $animais como um array vazio
                $animais = array();
            }
            ?>
            <label for="animal">Selecione o Animal:</label>
            <select name="idpet" id="idpet">
                <!-- Aqui você pode popular com os animais cadastrados no sistema -->
                <?php
                foreach ($animais as $animal) {
                    echo "<option value=\"" . $animal['idpet'] . "\">" . $animal['nome_animal'] . " - " . $animal['nome_tutor'] . "</option>";
                }
                ?>
            </select>
            <br><br>
            <button type="submit" class="btn btn-primary" name="agendar">Agendar</button>
        </div>
        <br>
    </form>
</div>

<?php

$mensagem_erro = "";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se a hora, a data e o ID do animal foram selecionados
    if (isset($_POST['horario'], $_POST['data'], $_POST['idpet'], $_POST['servico'])) {
        $hora_selecionada = $_POST['horario'];
        $data_agendamento = $_POST['data'];
        $id_pet = $_POST['idpet'];
        $servico = $_POST['servico'];

        // Consulta o banco de dados para verificar se há um agendamento para a hora e data selecionadas
        $sql_check_availability = "SELECT COUNT(*) AS total FROM agendamento WHERE hora_agendamento = '$hora_selecionada' AND data_atend = '$data_agendamento'";
        $result_check_availability = $conn->query($sql_check_availability);

        if ($result_check_availability) {
            $row = $result_check_availability->fetch_assoc();
            $total_agendamentos = $row['total'];

            if ($total_agendamentos == 0) {
                // A hora está disponível, então você pode processar o agendamento

                // Insere o agendamento no banco de dados
                $sql_insert_agendamento = "INSERT INTO agendamento (data_atend, idpet, hora_agendamento, hora_disponivel, servico) 
                VALUES ('$data_agendamento', '$id_pet', '$hora_selecionada', 0, '$servico')";
                $result_insert_agendamento = $conn->query($sql_insert_agendamento);

                if ($result_insert_agendamento) {
                    // Atualiza o estado de disponibilidade da hora selecionada no banco de dados
                    // para indicar que está ocupada
                    $sql_update_disponibilidade = "UPDATE agendamento SET hora_disponivel = 0 WHERE hora_agendamento = '$hora_selecionada' AND data_atend = '$data_agendamento'";
                    $result_update_disponibilidade = $conn->query($sql_update_disponibilidade);

                    if ($result_update_disponibilidade) {
                        echo "<div class='alert alert-success' role='alert'>Agendamento realizado com sucesso!</div>";
                    } else {
                        // Erro ao atualizar a disponibilidade da hora no banco de dados
                        $mensagem_erro = "Erro ao atualizar a disponibilidade da hora no banco de dados. Por favor, tente novamente mais tarde.";
                        if (!empty($mensagem_erro)) {
                            echo "<div class='alert alert-danger' role='alert'>$mensagem_erro</div>";
                        }
                    }
                } else {
                    // Erro ao inserir o agendamento no banco de dados
                    $mensagem_erro = "Erro ao armazenar o agendamento no banco de dados. Por favor, tente novamente mais tarde.";
                    if (!empty($mensagem_erro)) {
                        echo "<div class='alert alert-danger' role='alert'>$mensagem_erro</div>";
                    }
                }
            } else {
                // A hora está ocupada, exibe uma mensagem de erro
                $mensagem_erro =  "Desculpe, hora indisponível!";
                if (!empty($mensagem_erro)) {
                    echo "<div class='alert alert-danger' role='alert'>$mensagem_erro</div>";
                }
            }
        } else {
            // Erro ao consultar o banco de dados, exibe uma mensagem de erro
            $mensagem_erro = "Ocorreu um erro ao verificar a disponibilidade da hora. Por favor, tente novamente mais tarde.";
            if (!empty($mensagem_erro)) {
                echo "<div class='alert alert-danger' role='alert'>$mensagem_erro</div>";
            }
        }
    } else {
        // A hora, a data ou o ID do animal não foram selecionados, exibe uma mensagem de erro
        $mensagem_erro = "Por favor, preencha todos os campos necessários para o agendamento.";
        if (!empty($mensagem_erro)) {
            echo "<div class='alert alert-danger' role='alert'>$mensagem_erro</div>";
        }
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>  
</body>
</html>
