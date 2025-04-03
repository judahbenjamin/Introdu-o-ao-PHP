<?php
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

define('UPLOAD_DIR', 'imagens/');
define('PDF_DIR', 'pdfs/');

function validarCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);
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

$camposOK = true;
$erros = [];

// Upload da foto
if (isset($_FILES['txtFoto']) && $_FILES['txtFoto']['error'] == UPLOAD_ERR_OK) {
    $tipos_permitidos = ["image/gif", "image/jpeg", "image/pjpeg", "image/x-png", "image/png", "image/bmp"];
    $arquivo = $_FILES['txtFoto'];
    $nome_arquivo = $arquivo['name'];
    $tipo_arquivo = $arquivo['type'];
    $tamanho_arquivo = $arquivo['size'];
    $tmp_arquivo = $arquivo['tmp_name'];

    if (!in_array($tipo_arquivo, $tipos_permitidos)) {
        $erros[] = "Erro: Tipo de arquivo de imagem não permitido.";
        $camposOK = false;
    }

    if ($tamanho_arquivo > 5000000) { // 5 MB
        $erros[] = "Erro: Tamanho do arquivo de imagem excede o limite permitido (5 MB).";
        $camposOK = false;
    }

    if ($camposOK) {
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }
        $nome_unico = uniqid() . '_' . basename($nome_arquivo);
        $destino = UPLOAD_DIR . $nome_unico;
        if (move_uploaded_file($tmp_arquivo, $destino)) {
            $upload_mensagem = "Arquivo de imagem enviado com sucesso para " . htmlspecialchars($destino);
        } else {
            $erros[] = "Erro: Falha ao mover o arquivo de imagem para o destino.";
            $camposOK = false;
        }
    }
} elseif (isset($_FILES['txtFoto']) && $_FILES['txtFoto']['error'] != UPLOAD_ERR_NO_FILE) {
    $erros[] = "Erro no upload da imagem: " . $_FILES['txtFoto']['error'];
    $camposOK = false;
}

// Upload do PDF
if (isset($_FILES['txtPDF']) && $_FILES['txtPDF']['error'] == UPLOAD_ERR_OK) {
    $tipos_pdf_permitidos = ["application/pdf"];
    $arquivoPDF = $_FILES['txtPDF'];
    $nome_arquivo_pdf = $arquivoPDF['name'];
    $tipo_arquivo_pdf = $arquivoPDF['type'];
    $tamanho_arquivo_pdf = $arquivoPDF['size'];
    $tmp_arquivo_pdf = $arquivoPDF['tmp_name'];

    if (!in_array($tipo_arquivo_pdf, $tipos_pdf_permitidos)) {
        $erros[] = "Erro: Tipo de arquivo PDF não permitido.";
        $camposOK = false;
    }

    if ($tamanho_arquivo_pdf > 5000000) { // 5 MB
        $erros[] = "Erro: Tamanho do arquivo PDF excede o limite permitido (5 MB).";
        $camposOK = false;
    }

    if ($camposOK) {
        if (!is_dir(PDF_DIR)) {
            mkdir(PDF_DIR, 0755, true);
        }
        $nome_unico_pdf = uniqid() . '_' . basename($nome_arquivo_pdf);
        $destinoPDF = PDF_DIR . $nome_unico_pdf;
        if (move_uploaded_file($tmp_arquivo_pdf, $destinoPDF)) {
            $upload_pdf_mensagem = "PDF enviado com sucesso para " . htmlspecialchars($destinoPDF);
        } else {
            $erros[] = "Erro: Falha ao mover o arquivo PDF para o destino.";
            $camposOK = false;
        }
    }
} elseif (isset($_FILES['txtPDF']) && $_FILES['txtPDF']['error'] != UPLOAD_ERR_NO_FILE) {
    $erros[] = "Erro no upload do PDF: " . $_FILES['txtPDF']['error'];
    $camposOK = false;
}

$nome = isset($_POST["txtNome"]) ? htmlspecialchars($_POST["txtNome"]) : "";
$ender = isset($_POST["txtEndereco"]) ? htmlspecialchars($_POST["txtEndereco"]) : "";
$cpf = isset($_POST["txtCPF"]) ? $_POST["txtCPF"] : "";
$estado = isset($_POST["listEstados"]) ? htmlspecialchars($_POST["listEstados"]) : "";
$dtNasc = isset($_POST["txtData"]) ? $_POST["txtData"] : "";
$sexo = isset($_POST["sexo"]) ? htmlspecialchars($_POST["sexo"]) : "";
$cinema = isset($_POST["checkCinema"]) ? true : false;
$musica = isset($_POST["checkMusica"]) ? true : false;
$informatica = isset($_POST["checkInformatica"]) ? true : false;
$login = isset($_POST["txtLogin"]) ? htmlspecialchars($_POST["txtLogin"]) : "";
$senha1 = isset($_POST["txtSenha1"]) ? $_POST["txtSenha1"] : "";
$senha2 = isset($_POST["txtSenha2"]) ? $_POST["txtSenha2"] : "";

if (empty($nome)) {
    $erros[] = "Informe o NOME.";
    $camposOK = false;
}
if (empty($ender)) {
    $erros[] = "Informe o ENDEREÇO.";
    $camposOK = false;
}
if ($senha1 != $senha2) {
    $erros[] = "As SENHAS não conferem!.";
    $camposOK = false;
} else {
    $senhaHash = password_hash($senha1, PASSWORD_DEFAULT);
}
if (!empty($cpf) && !validarCPF($cpf)) {
    $erros[] = "CPF inválido.";
    $camposOK = false;
} else {
    $cpf = htmlspecialchars($cpf);
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

$html = "<div style='font-family: sans-serif; background-color: #f4f4f4; padding: 20px;'>";
$html .= "<h1 style='text-align: center; color: #333;'>Dados do Cadastro</h1>";
$html .= "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
$html .= "<tr><th style='border-bottom: 1px solid #ddd; padding: 10px; text-align: left;'>Nome</th><td style='border-bottom: 1px solid #ddd; padding: 10px;'>" . $nome . "</td></tr>";
$html .= "<tr><th style='border-bottom: 1px solid #ddd; padding: 10px; text-align: left;'>CPF</th><td style='border-bottom: 1px solid #ddd; padding: 10px;'>" . $cpf . "</td></tr>";
$html .= "<tr><th style='border-bottom: 1px solid #ddd; padding: 10px; text-align: left;'>Endereço</th><td style='border-bottom: 1px solid #ddd; padding: 10px;'>" . $ender . "</td></tr>";
$html .= "<tr><th style='border-bottom: 1px solid #ddd; padding: 10px; text-align: left;'>Estado</th><td style='border-bottom: 1px solid #ddd; padding: 10px;'>" . $estado . "</td></tr>";
$html .= "<tr><th style='border-bottom: 1px solid #ddd; padding: 10px; text-align: left;'>Data de Nascimento</th><td style='border-bottom: 1px solid #ddd; padding: 10px;'>" . htmlspecialchars($dtNasc) . "</td></tr>";
$html .= "<tr><th style='border-bottom: 1px solid #ddd; padding: 10px; text-align: left;'>Sexo</th><td style='border-bottom: 1px solid #ddd; padding: 10px;'>" . $sexo . "</td></tr>";
$html .= "<tr><th style='border-bottom: 1px solid #ddd; padding: 10px; text-align: left;'>Login</th><td style='border-bottom: 1px solid #ddd; padding: 10px;'>" . $login . "</td></tr>";
$html .= "</table>";

$html .= "<h2 style='color: #333;'>Interesses</h2>";
$html .= "<ul style='list-style-type: none; padding: 0;'>";
if ($cinema) {
    $html .= "<li style='margin-bottom: 5px;'>Cinema</li>";
}
if ($musica) {
    $html .= "<li style='margin-bottom: 5px;'>Música</li>";
}
if ($informatica) {
    $html .= "<li style='margin-bottom: 5px;'>Informática</li>";
}
$html .= "</ul>";

if (isset($destino)) {
    $tipo_imagem = pathinfo($destino, PATHINFO_EXTENSION);
    $dados_imagem = file_get_contents($destino);
    $base64_imagem = base64_encode($dados_imagem);
    $src_imagem = 'data:image/' . $tipo_imagem . ';base64,' . $base64_imagem;
    $html .= "<img src='" . $src_imagem . "' style='width:150px; margin-top: 20px;'>";
}

if (isset($destinoPDF)) {
    $html .= "<p style='margin-top: 20px;'><strong>Currículo PDF:</strong> <a href='" . htmlspecialchars($destinoPDF) . "' target='_blank'>Visualizar PDF</a></p>";
}

if (!$camposOK) {
    $html .= "<h2 style='color: red;'>Erros Encontrados:</h2>";
    $html .= "<ul style='list-style-type: none; padding: 0;'>";
    foreach ($erros as $erro) {
        $html .= "<li style='color: red; margin-bottom: 5px;'>" . $erro . "</li>";
    }
    $html .= "</ul>";
}
$html .= "</div>";

if ($camposOK) {
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("cadastro_cliente.pdf", array("Attachment" => 0)); // 0 para exibir no navegador, 1 para download
} else {
    echo $html;
}
?>