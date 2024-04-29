<?php
// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o campo de seleção do tutor está vazio
    if (empty($_POST['tutor'])) {
        // Se estiver vazio, exibe uma mensagem de erro
        $errors[] = "Por favor, selecione um tutor.";
    } else {
        // Se não estiver vazio, o valor selecionado está em $_POST['tutor']
        $tutor_id = $_POST['tutor'];

        // Verifica se os campos obrigatórios foram preenchidos
        if (empty($_POST['nomePet']) || empty($_POST['data_nasc_pet']) || empty($_POST['sexo']) || empty($_POST['raca']) || empty($_POST['especie'])) {
            $errors[] = "Por favor, preencha todos os campos.";
        } else {
            // Se os campos estiverem preenchidos, obtém os dados do formulário
            $nome = $_POST['nomePet'];
            $data_nasc = $_POST['data_nasc_pet'];
            $sexo = $_POST['sexo'];
            $raca = $_POST['raca'];
            $especie = $_POST['especie'];

            // Inicia a conexão com o banco de dados (supondo que você já tenha o arquivo config.php incluído)
            include_once('config.php');

            if ($conn->connect_error) {
                die("Erro na conexão com o banco de dados: " . $conn->connect_error);
            }

            // Executa o insert no banco de dados
            $result = mysqli_query($conn, "INSERT INTO pets(nome, data_nasc, sexo, raca, especie, idproprietario) 
                VALUES ('$nome', '$data_nasc', '$sexo', '$raca', '$especie', '$tutor_id')");

            // Verifica se o cadastro foi efetuado com sucesso
            if($result){
                // Emite a mensagem de sucesso
                echo "<script>alert('Dados cadastrados com sucesso!'); </script>";
            } else {
                // Emite a mensagem de erro
                echo "<script>alert('Erro ao cadastrar os dados. " . mysqli_error($conn) . "'); </script>";
            }

            // Fecha a conexão com o banco de dados
           // $conn->close();
        }
    }

    // Se houver erros, exibe as mensagens de erro
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class=\"alert alert-danger\">" . $error . "</div>";
        }
    } else {
        // Se não houver erros, o formulário pode ser enviado com sucesso
        // Continue com o processo de cadastro do pet aqui, se necessário
    }
}
?>

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
        <h1>CADASTRO DE PETS</h1>
    </div>
    <br><br>
    <div class="d-flex justify-content-center">
        <form action="?cadastro-pets.php" method="POST">
            <div class="mb-3">
                <label>Nome do animal</label>
                <input type="text" class="form-control" id="nomePet" name="nomePet">
                <br>
                <label>Data de Nascimento</label>
                <input type="date" class="form-control" id="data_nasc_pet" name="data_nasc_pet">
                <br>
                <label>Sexo</label>
                <input type="radio" name="sexo" value="Masc">Masculino
                <input type="radio" name="sexo" value="Femi">Feminino
                <br><br>
                <label>Raça do animal</label>
                <input type="text" class="form-control" id="raca" name="raca">
                <br>
                <label>Espécie do animal</label>
                <input type="text" class="form-control" id="especie" name="especie">
                <br>

                <?php
                    // Supondo que você esteja recuperando os dados dos tutores do banco de dados

                    include_once("config.php");

                    // Prepara a consulta SQL para buscar todos os tutores
                    $sql = "SELECT idproprietario, nome FROM proprietario";

                    // Executa a consulta
                    $result = $conn->query($sql);

                    // Verifica se a consulta retornou resultados
                    if ($result->num_rows > 0) {
                        // Inicializa a variável $tutores como um array vazio
                        $tutores = array();

                        // Percorre os resultados da consulta e os armazena em $tutores
                        while ($row = $result->fetch_assoc()) {
                            $tutores[] = $row;
                        }

                        // Ordena o array $tutores pelo nome do tutor em ordem alfabética
                        usort($tutores, function($a, $b) {
                            return strcmp($a['nome'], $b['nome']);
                        });

                        // Fecha a conexão com o banco de dados
                        // $conn->close();
                    } else {
                        // Se não houver tutores no banco de dados, inicializa $tutores como um array vazio
                        $tutores = array();
                    }
                ?>

                <label for="tutor">Selecione o Tutor:</label>
                <select name="tutor" id="tutor" class="form-control">
                    <option value="">Selecione o Tutor</option>
                    <?php
                    // Agora que $tutores está ordenado, você pode percorrê-lo e exibir os tutores em ordem alfabética
                    foreach ($tutores as $tutor) {
                        echo "<option value=\"" . $tutor['idproprietario'] . "\">" . $tutor['nome'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" name="cadastrar">Cadastrar</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>