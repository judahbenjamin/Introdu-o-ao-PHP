<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercício 2</title>
</head>
<body>
    <?php
        $estados_brasil = array("ES" => "Espírito Santo",
                                "RJ" => "Rio de Janeiro",
                                "SP" => "São Paulo",
                                "AM" => "Amazonas",
                                "BA" => "Bahia",
                                "MG" => "Minas Gerais",
                                "AL" => "Alagoas"
                                );
        // echo $estados_brasil["ES"];
        function array_estados ($estados_brasil){
            return $estados_brasil["ES"];
        }
    ?>
</body>
</html>