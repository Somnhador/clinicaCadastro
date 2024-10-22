<?php
session_start();
try {
    require 'conexao.php';
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Falha na conexão: " . $e->getMessage());
}

$mensagem = "";

function cadastrarPaciente($conn)
{
    global $mensagem;
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cadastrar'])) {
        $nome = htmlspecialchars($_POST['nome']);
        $data_nascimento = $_POST['data_nascimento'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $endereco = htmlspecialchars($_POST['endereco']);
        $sexo = $_POST['sexo'];


        $stmt = $conn->prepare("SELECT email FROM pacientes WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $mensagem = "<div class='alert alert-danger'>Email já cadastrado!</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO pacientes (nome, data_nascimento, email, telefone, endereco, sexo) VALUES (:nome, :data_nascimento, :email, :telefone, :endereco, :sexo)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':data_nascimento', $data_nascimento);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':endereco', $endereco);
            $stmt->bindParam(':sexo', $sexo);

            if ($stmt->execute()) {
                $mensagem = "<div class='alert alert-success'>Cadastro do paciente realizado com sucesso!</div>";
            } else {
                $mensagem = "<div class='alert alert-danger'>Erro ao cadastrar Paciente!</div>";
            }
        }
    }
}

cadastrarPaciente($conn);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>

    <script src="index.js"></script>

    <title>Clínica - Cadastro</title>
    <link rel="icon" type="image/x-icon" href="saude.png">

    <style>
        #card {
            display: flex;
            flex-direction: column;
            max-width: 70vh;
            width: 100%;

            border: 4px dashed grey;
            border-radius: 10px;
            padding: 20px;

            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        #enviar {
            width: 30vh;
            height: 7vh;
        }
    </style>
</head>

<body background="hosp.png" class="d-flex justify-content-center align-items-center min-vh-100">

    <div class="container-fluid" id="card"><br>

        <?php if ($mensagem): ?>
            <div class="text-center">
                <?php echo $mensagem; ?>
                <span class="countdown"></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="d-flex flex-column justify-content-center align-items-center">
            <h1 style="color: white; margin-bottom: 4vh;">Cadastro de Paciente</h1>
            <div class="input-group mb-3">
                <input name="nome" type="text" class="form-control" placeholder="Nome Completo" aria-label="Nome Completo" inputmode="text" maxlength="254" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1" style="color: white;">Data de Nascimento</span>
                <input name="data_nascimento" type="date" id="birthdate" class="form-control" aria-label="nascimento" aria-describedby="basic-addon1" required>
            </div>

            <div class="input-group mb-3">
                <input name="email" type="email" class="form-control" placeholder="E-mail" aria-label="Email" aria-describedby="basic-addon1" inputmode="email" maxlength="254" required>
            </div>

            <div class="input-group mb-3">
                <input name="telefone" type="tel" class="form-control" placeholder="Telefone" aria-label="Telefone" aria-describedby="basic-addon1" maxlength="11" pattern="[0-9]{10,11}" inputmode="numeric" required>
            </div>

            <div class="input-group mb-3">
                <input name="endereco" type="text" class="form-control" placeholder="Endereço" aria-label="Endereco" aria-describedby="basic-addon1" inputmode="text" maxlength="254" required>
            </div>

            <div class="input-group mb-3">
                <select name="sexo" class="form-select" aria-label="Default select example" required>
                    <option selected>Selecione seu gênero...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>
            <button type="submit" name="cadastrar" class="btn btn-primary" id="enviar">Cadastrar Paciente</button>
        </form>

    </div>
    <script>
        // Obtém a data atual
        const today = new Date();

        // Calcula a data de 18 anos atrás
        const minDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

        // Formata a data no formato adequado para o input date (YYYY-MM-DD)
        const minDateString = minDate.toISOString().split('T')[0];

        // Aplica o valor de min no input date
        document.getElementById('birthdate').setAttribute('max', minDateString);


        // Função para esconder a mensagem após 4 segundos
        if (document.querySelector('.alert')) {
            setTimeout(() => {
                document.querySelector('.alert').style.display = 'none'; // Esconde a mensagem
            }, 4000); // Tempo em milissegundos (4000 ms = 4 segundos)
        }
    </script>
</body>

</html>