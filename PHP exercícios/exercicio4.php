<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercício 4</title>
</head>
<body>
<?php

function Saudacao() {
    date_default_timezone_set('America/Sao_Paulo');

    $hora = date('H');

    if ($hora >= 0 && $hora < 12) {
        echo '<p style="color: red;">BOM DIA!</p>';
    } elseif ($hora >= 12 && $hora < 18) {
        echo '<p style="color: green;">BOA TARDE!</p>';
    } else {
        echo '<p style="color: blue;">BOA NOITE!</p>';
    }
}

Saudacao();

?>
</body>
</html>