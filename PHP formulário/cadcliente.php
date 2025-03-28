<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cadastro de Clientes</title>
</head>

<body>
    <h1>Os dados informados são:</h1>

    <?php
    // Diretório para salvar as imagens (relativo)
    define('UPLOAD_DIR', 'imagens/');

    // Função para validar CPF
    function validarCPF($cpf) {
        $cpf = preg_replace('/\D/', '', $cpf); // Remove caracteres não numéricos
        if (strlen($cpf) != 11 || preg_match('/^(\d)\1+$/', $cpf)) {
            return false;
        }
        $soma1 = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma1 += intval($cpf[$i]) * (10 - $i);
        }
        $resto1 = ($soma1 % 11);
        $digito1 = ($resto1 < 2) ? 0 : 11 - $resto1;
        if (intval($cpf[9]) != $digito1) {
            return false;
        }
        $soma2 = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma2 += intval($cpf[$i]) * (11 - $i);
        }
        $resto2 = ($soma2 % 11);
        $digito2 = ($resto2 < 2) ? 0 : 11 - $resto2;
        if (intval($cpf[10]) != $digito2) {
            return false;
        }
        return true;
    }

    // Função para validar data (DD/MM/AAAA)
    function validarData($data) {
        $padrao_data = '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20)\d{2}$/';
        if (!preg_match($padrao_data, $data)) {
            return false;
        }
        list($dia, $mes, $ano) = explode('/', $data);
        if (!checkdate($mes, $dia, $ano)) {
            return false;
        }
        return true;
    }

    // Inicializa a variável para controlar se houve erros
    $camposOK = true;
    $erros = []; // Array para armazenar mensagens de erro

    // Obtendo a foto
    $arquivo = $_FILES['txtFoto'];

    // Verificando erro no upload
    if ($arquivo['error'] != 0) {
        $erros[] = "Erro no UPLOAD do arquivo. Código: " . $arquivo['error'];
        $camposOK = false;
    }

    // Verificando o tamanho
    if ($arquivo['size'] == 0) {
        $erros[] = "Erro no arquivo. Tamanho igual a ZERO.";
        $camposOK = false;
    }
    if ($arquivo['size'] > 100000) {
        $erros[] = "Tamanho maior que o permitido (100 kbytes).";
        $camposOK = false;
    }

    // Verificando o tipo de arquivo
    $tipos_permitidos = ["image/gif", "image/jpeg", "image/pjpeg", "image/x-png", "image/png", "image/bmp"];
    if (!in_array($arquivo['type'], $tipos_permitidos)) {
        $erros[] = "Erro no arquivo. Tipo não permitido: " . $arquivo['type'];
        $camposOK = false;
    }

    // Movendo o arquivo para o diretório de upload
    if ($camposOK) {
        // Certifique-se de que o diretório de upload existe
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true); // Cria o diretório se não existir
        }
        $destino = UPLOAD_DIR . basename($arquivo['name']); // Use basename para evitar problemas com caminhos
        if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
            $upload_mensagem = "Arquivo movido com sucesso para " . htmlspecialchars($destino);
        } else {
            $erros[] = "Erro ao copiar o arquivo para o destino.";
            $camposOK = false;
        }
    }

    // Recebe cada campo do formulário e coloca em uma variável.
    $nome = isset($_POST["txtNome"]) ? htmlspecialchars($_POST["txtNome"]) : "";
    $ender = isset($_POST["txtEndereco"]) ? htmlspecialchars($_POST["txtEndereco"]) : "";
    $cpf = isset($_POST["txtCPF"]) ? $_POST["txtCPF"] : ""; // CPF será validado, então escapamos depois
    $estado = isset($_POST["listEstados"]) ? htmlspecialchars($_POST["listEstados"]) : "";
    $dtNasc = isset($_POST["txtData"]) ? $_POST["txtData"] : ""; // Data será validada
    $sexo = isset($_POST["sexo"]) ? htmlspecialchars($_POST["sexo"]) : "";
    $cinema = isset($_POST["checkCinema"]) ? true : false;
    $musica = isset($_POST["checkMusica"]) ? true : false;
    $informatica = isset($_POST["checkInformatica"]) ? true : false;
    $login = isset($_POST["txtLogin"]) ? htmlspecialchars($_POST["txtLogin"]) : "";
    $senha1 = isset($_POST["txtSenha1"]) ? $_POST["txtSenha1"] : ""; // Senhas serão comparadas, escapamos depois se necessário
    $senha2 = isset($_POST["txtSenha2"]) ? $_POST["txtSenha2"] : "";

    // Verificar Campos
    if (empty($nome)) {
        $erros[] = "Informe o NOME.";
        $camposOK = false;
    }
    if (empty($ender)) {
        $erros[] = "Informe o ENDEREÇO.";
        $camposOK = false;
    }

    // Verificar se as SENHAS conferem
    if ($senha1 != $senha2) {
        $erros[] = "As SENHAS não conferem!.";
        $camposOK = false;
    }

    // Validações de CPF e Data
    if (!empty($cpf) && !validarCPF($cpf)) {
        $erros[] = "CPF inválido.";
        $camposOK = false;
    } else {
        $cpf = htmlspecialchars($cpf); // Escapa o CPF se for válido
    }

    if (!empty($dtNasc) && !validarData($dtNasc)) {
        $erros[] = "Data de Nascimento inválida (DD/MM/AAAA).";
        $camposOK = false;
    }

    if (empty($estado)) {
        $erros[] = "Por favor, selecione um estado.";
        $camposOK = false;
    }
    if (empty($login)) {
        $erros[] = "Por favor, preencha o login.";
        $camposOK = false;
    }
    if (empty($senha1)) {
        $erros[] = "Por favor, preencha a senha.";
        $camposOK = false;
    }
    if (empty($senha2)) {
        $erros[] = "Por favor, confirme a senha.";
        $camposOK = false;
    }

    // Mostrar erros ou dados
    if (!$camposOK) {
        echo "<h2>Erros encontrados:</h2>";
        echo "<ul>";
        foreach ($erros as $erro) {
            echo "<li>$erro</li>";
        }
        echo "</ul>";
    } else {
        // Mostrar a foto
        if (isset($destino)) {
            echo "<p>$upload_mensagem</p>";
            echo "<img height=120 width=120 src='" . htmlspecialchars($destino) . "'>";
        }

        // Mostrar os valores em forma de tabela
        echo "<table border='0' cellpadding='5'>";
        echo "<tr><td>NOME:</td><td><b>" . $nome . "</b></td></tr>";
        echo "<tr><td>CPF:</td><td><b>" . $cpf . "</b></td></tr>";
        echo "<tr><td>ENDEREÇO:</td><td><b>" . $ender . "</b></td></tr>";
        echo "<tr><td>ESTADO:</td><td><b>" . $estado . "</b></td></tr>";
        echo "<tr><td>DATA NASC.:</td><td><b>" . htmlspecialchars($dtNasc) . "</b></td></tr>";
        echo "<tr><td>SEXO:</td><td><b>" . $sexo . "</b></td></tr>";
        echo "<tr><td>LOGIN:</td><td><b>" . $login . "</b></td></tr>";
        echo "<tr><td>SENHA:</td><td><b>" . htmlspecialchars($senha1) . "</b></td></tr>";
        echo "<tr><td>ÁREAS DE INTERESSE:</td></tr><b>";
        if ($cinema) {
            echo "Cinema <br>";
        }
        if ($musica) {
            echo "Música <br>";
        }
        if ($informatica) {
            echo "Informática";
        }
        echo "</b></td></tr></table>";
        echo "<p>Cadastro realizado com sucesso!</p>";
    }
    ?>

</body>

</html>