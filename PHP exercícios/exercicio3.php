<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercício 3</title>
</head>
<body>
    <?php
        function MediaDoAluno($n1,$n2,$n3){
            $media = ($n1 + $n2 + $n3)/3;

            if ($media > 6 || $media == 6){
                echo 'Média: '.round($media);
                echo '<p style="color:blue;"> APROVADO';
            } 
            else {
                echo 'Média: '.round($media);
                echo '<p style="color:red;"> REPROVADO';
            }
        }

        MediaDoAluno(9.0,4.5,6.0);
    ?>
</body>
</html>