<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cadastro de Clientes</title>
</head>

<body>
    <h1>Os dados informados são:</h1>

    <?php


    //Obtendo a foto
    $arquivo = $_FILES['txtFoto'];
    //Verificando erro no upload
    if ($arquivo['error'] != 0) {
        echo "Erro no UPLOAD do arquivo. <br>";
        $camposOK = false;
    }

    //Verificando o tamanho
    if ($arquivo['size'] == 0) {
        echo "Erro no arquivo. Tamanho igual a ZERO. <br>";
        $camposOK = false;
    }
    if ($arquivo['size'] > 100000) {
        echo "Tamanho maior que o permitido (100 kbytes). <br>";
        $camposOK = false;
    }

    //Verificando o tipo de arquivo
    if (
        ($arquivo['type'] != "image/gif") &&
        ($arquivo['type'] != "image/jpeg") &&
        ($arquivo['type'] != "image/pjpeg") &&
        ($arquivo['type'] != "image/x-png") &&
        ($arquivo['type'] != "image/png") &&
        ($arquivo['type'] != "image/bmp")
    ) {
        echo "Erro no arquivo. Tipo não permitido. <br>";
        $camposOK = false;
    }

    //Movendo o arquivo para algum diretório válido dentro do www
    $destino = "/var/www/ProgWeb/imagens/";
    $destino = $destino . $arquivo['name'];
    $res=move_uploaded_file($arquivo['tmp_name'],$destino);
    if ($res == false) {
        echo "Erro ao copiar o arquivo para o destino. <br>";
        $camposOK = false;
    }
    // Mostrando a foto. Coloque o caminho onde está a foto
    echo "<img height=120 width=120 src='imagens/" . $arquivo['name' ] . "'>";


    //Recebe cada campo do formulário
    //e coloca em uma variável.
    $nome = $_POST["txtNome"];
    $ender = $_POST["txtEndereco"];
    $cpf = $_POST["txtCPF"];
    $estado = $_POST["listEstados"];
    $dtNasc = $_POST["txtData"];
    $sexo = $_POST["sexo"];
    $cinema = $_POST["checkCinema"];
    $musica = $_POST["checkMusica"];
    $info = $_POST["checkInfo"];
    $login = $_POST["txtLogin"];
    $senha1 = $_POST["txtSenha1"];
    $senha2 = $_POST["txtSenha2"];

    //Verificar Campos
    $camposOK = true; // Determina se ocorreu erro
    if ($nome == "") {
        echo "Informe o NOME. <br>";
        $camposOK = false;
    }
    if ($ender == "") {
        echo "Informe o ENDEREÇO. <br>";
        $camposOK = false;
    }
    //Verificar se as SENHAS conferem
    if ($senha1 != $senha2) {
        echo "As SENHAS não conferem!. <br>";
        $camposOK = false;
    }
    //*** Acrescentar as validações de CPF e DATA
    
    //Mostrando os valores em forma de tabela
    //Cada campo é uma linha <tr> da tabela
    if ($camposOK) {
        echo "<table border='0' cellpadding='5'>";
        echo "<tr><td>NOME:</td><td><b> $nome </b></td></tr>";
        echo "<tr><td>CPF:</td><td><b> $cpf </b></td></tr>";
        echo "<tr><td>ENDEREÇO:</td><td><b> $ender </b></td></tr>";
        echo "<tr><td>ESTADO:</td><td><b> $estado </b></td></tr>";
        echo "<tr><td>DATA NASC.:</td><td><b> $dtNasc </b></td></tr>";
        echo "<tr><td>SEXO:</td><td><b> $sexo </b></td></tr>";
        echo "<tr><td>LOGIN:</td><td><b> $login </b></td></tr>";
        echo "<tr><td>SENHA:</td><td><b> $senha1 </b></td></tr>";

        //Campos do tipo CheckBox retornam
        //Verdadeiro (true) se foi marcado
        echo "<tr><td>ÁREAS DE INTERESSE:</td></tr><b>";
        if ($cinema == true) {
            echo "Cinema <br>";
        }
        if ($musica) {
            echo "Música <br>";
        }
        if ($info) {
            echo "Informática";
        }
        echo "</b></td></tr></table>";
    } // Fim IF camposOK
    ?>

</body>

</html>