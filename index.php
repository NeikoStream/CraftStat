<?php 
require_once('src/fonction/connexionBD.php');
require_once('src/fonction/fonction.php');
//Récupérer la table stat avec toutes les infos
$players = $linkpdo->prepare('SELECT * FROM stats');
$players -> execute();
$players = $players -> fetchALL();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="src/style/style.css">
    <title>CraftStat</title>
</head>
<body>
    <table class="table table-striped table-bordered ">
        <thead class="table-dark">
            <tr>
                <th>Utilisateur <img src="src/img/head.png" class="icon" alt="image de la tête de steeve"></th>
                <th>Temps de jeu <img src="src/img/time.png" class="icon" alt="image d'une horloge"></th>
                <th>Morts <img src="src/img/mort.png" class="icon" alt="image d'un crane"></th>
                <th>Diamant <img src="src/img/diamant.png" class="icon" alt="image de diamant du jeu minecraft"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($players as $player):
            ?>
            <tr>
                <td><?=$player['player_name']?></td>
                <td><?=TickToTime($player['TOTAL_WORLD_TIME'])?></td>
                <td><?=$player['DEATHS']?></td>
                <td><?=$player['player_name']?></td>
            </tr>
            <?php endforeach ?>
            <tr>
                <td>Steve</td>
                <td>999 jours</td>
                <td>0</td>
                <td>99999</td>
            </tr>
            <tr>
                <td>Steve</td>
                <td>999 jours</td>
                <td>0</td>
                <td>99999</td>
            </tr>
        </tbody>
    </table>
</body>
</html>