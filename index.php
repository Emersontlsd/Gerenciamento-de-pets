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
                <h1>AGENDAMENTOS DE SERVIÇOS</h1>
            </div>

            <table class="table table-info table-success table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID AGENDAMENTO</th>
                            <th scope="col">NOME TUTOR</th>
                            <th scope="col">NOME PET</th>
                            <th scope="col">SERVIÇO</th>
                            <th scope="col">DATA DO AGENDAMENTO</th>
                            <th scope="col">HORA DO AGENDAMENTO</th>
                        </tr>
                    </thead>
                <tbody>

                <?php
                        include_once("config.php");

                        // Consulta o banco de dados para recuperar os agendamentos com informações relevantes
                        $sql_agendamentos = "SELECT a.idagendamento, a.data_atend, a.hora_agendamento, a.servico, p.nome AS nome_animal, pr.nome AS nome_proprietario
                                            FROM agendamento a
                                            INNER JOIN pets p ON a.idpet = p.idpet
                                            INNER JOIN proprietario pr ON p.idproprietario = pr.idproprietario";
                        $result_agendamentos = $conn->query($sql_agendamentos);

                        // Verifica se a consulta retornou resultados
                        if ($result_agendamentos->num_rows > 0) {
                            // Exibe os agendamentos em uma tabela
                            while ($row = $result_agendamentos->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["idagendamento"] . "</td>";
                                echo "<td>" . $row["nome_proprietario"] . "</td>";
                                echo "<td>" . $row["nome_animal"] . "</td>";
                                echo "<td>" . $row["servico"] . "</td>";
                                echo "<td>" . $row["data_atend"] . "</td>";
                                echo "<td>" . $row["hora_agendamento"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            // Se não houver agendamentos no banco de dados, exibe uma mensagem indicando isso
                            echo "<tr><td colspan='6'>Nenhum agendamento encontrado.</td></tr>";
                        }

                        // Fecha a conexão com o banco de dados
                        $conn->close();
                ?>
            </tbody>
            </table>

        </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>  
</body>
</html>