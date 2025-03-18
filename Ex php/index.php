<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        for ($i=0; $i<100; $i++) {
            if ($i % 2 == 0) {
                echo "<b>$i É PAR</b>";
                echo "<br>";
            }
            else {
                echo "<i>$i É IMPAR</i>";
                echo "<br>";
            }
        };
    ?>
</body>
</html>