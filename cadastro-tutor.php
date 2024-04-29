<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Home</title>
</head>
<?php
        if(isset($_POST['cadastrar'])){

        include_once('config.php');

        if ($conn->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }

        // Obtém os dados do formulário
        $nome = $_POST['nomeTutor'];
        $cpf = $_POST['cpf'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];
        $email = $_POST['email'];
        $sexo = $_POST['sexo'];

        // executa o insert no banco de dados
        $result = mysqli_query($conn, "INSERT INTO proprietario(nome, cpf, telefone, endereco, email, sexo) 
        VALUES ('$nome', '$cpf', '$telefone', '$endereco', '$email', '$sexo')");
        

        // verificando se o cadastro foi efetuado
        if($result){
            // sim, emite a mensagem de sucesso
            echo "<script>alert('Dados cadastrados com sucesso!'); </script>";
        }else {
            // não, emite a mensagem de erro
            echo " <script>alert ('Erro ao cadastrar os dados. " . mysqli_error($conn) . " '); </script>";
        }

        // header('Location: index.php');

    }

?>

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
            <h1>CADASTRO DE TUTOR</h1>
            
        </div>
        <br><br>
        <div class="d-flex justify-content-center">
        
            <form action="?cadastro-tutor.php" method="POST">
                <div class="mb-3">

                    <label>Nome:</label>
                    <input type="text" class="form-control" id="nomeTutor" name="nomeTutor">
                    <br>
                    <label>CPF:</label>
                    <input type="number" class="form-control" id="cpf" name="cpf">
                    <br>
                    <label>Telefone:</label>
                    <input type="number" class="form-control" id="telefone" name="telefone">
                    <br>
                    <label>Endereço:</label>
                    <input type="text" class="form-control" id="endereco" name="endereco">
                    <br>
                    <label>E-mail:</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <br>
                    <label>Sexo:</label>
                    <input type="radio" name="sexo" value="Masc">Masculino
                    <input type="radio" name="sexo" value="Femi">Feminino
                    <br><br>

                </div>
                
                <button type="submit" class="btn btn-primary" name="cadastrar">Cadastrar</button>
            </form>

        </div>

    </div>
    
    

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>  
</body>
</html>