<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Números Pares e Ímpares</title>
</head>
<body>
    <?php
        for ($i = 0; $i < 100; $i++) {
            if ($i % 2 == 0) {
                echo "<b>$i É par</b><br>";
            } else {
                echo "<i>$i É ímpar</i><br>";
            }
        }
    ?>
</body>
</html>
