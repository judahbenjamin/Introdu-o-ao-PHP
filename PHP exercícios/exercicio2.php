<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercício 2</title>
</head>
<body>
    <?php 
        $num1 = 10;
        $num2 = 20;

        echo 'Primeiro valor = '.$num1;
        echo '<br>Segundo valor = '.$num2;

        $soma = $num1 + $num2;
        $sub = $num1 - $num2;
        $mult = $num1 * $num2;
        $div = $num1 / $num2;

        echo '<br><b> A soma é ' .$soma;
        echo '<br><b> A subtração é ' .$sub;
        echo '<br><b> A multiplicação é ' .$mult;
        echo '<br><b> A div é ' .$div;
    ?>
</body>
</html>