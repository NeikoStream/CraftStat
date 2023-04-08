<?php 
require_once('src/fonction/connexionBD.php');
require_once('src/fonction/fonction.php');

$serverName = "les marmottes";

//Récupérer la table stat avec toutes les infos

$query = "SELECT player_name,TOTAL_WORLD_TIME,DEATHS,JSON_EXTRACT(mine_block, '$.DIAMOND_ORE') AS diamant FROM stats";
$params = [];
//fonction de recherche
if(!empty($_GET['q'])){
    $query .= " WHERE player_name LIKE :pseudo";
    $params['pseudo'] = '%' . $_GET['q'] . '%';
}

$players = $linkpdo->prepare($query);

$players -> execute($params);
$players = $players -> fetchALL();


//Podium Temps
$toptime = $linkpdo->prepare("SELECT player_name, TOTAL_WORLD_TIME FROM stats ORDER BY TOTAL_WORLD_TIME DESC LIMIT 3");
$toptime -> execute();
$toptime = $toptime -> fetchALL();
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
<body >
    <div class="container text-center">
        <div class="row">
            <div class="col">
                <h1 class=""><?=$serverName?></h1>
            </div>
            <div class="col">
                <form action="" class="p-2">
                        <div class="form-group hstack">
                            
                            <input type="text" class="form-control" name="q" placeholder="Rechercher un pseudo" value="<?=htmlentities($_GET['q'] ?? null) ?>">
                            <button class="btn btn-primary mx-2">Rechercher</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

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
                
                <td><img src="https://minotar.net/avatar/<?=$player['player_name']?>/32.png" alt="image du joueur"> <?=$player['player_name']?></td>
                <td><?=TickToTime($player['TOTAL_WORLD_TIME'])?></td>
                <td><?=$player['DEATHS']?></td>
                <td><?=$player['diamant']?></td>
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

    <!-- Partie podium -->
    <div class="container text-center">
        <div class="row">
            <div class="col">
                <h1>Top <img src="src/img/time.png" class="icon" alt="image d'une horloge"></h1>
                <div class="podium">
                    <div class="left vstack">
                        <div class="pt-2">
                            <img src="https://minotar.net/avatar/<?=$toptime[0]['player_name'] ?? "Notch"?>/32.png" alt="image du joueur">
                            <b><?=$toptime[0]['player_name'] ?? "Personne"?></b> 
                            <b><?=TickToTimeShort($toptime[0]['TOTAL_WORLD_TIME'])?></b> 
                        </div>
                    </div>

                    <div class="top">
                        <div class="pt-2">
                            <img src="https://minotar.net/avatar/<?=$toptime[1]['player_name'] ?? "Notch"?>/32.png" alt="image du joueur">
                            <b><?=$toptime[1]['player_name'] ?? "Personne"?></b> 
                            <br>
                            <b><?=TickToTimeShort($toptime[1]['TOTAL_WORLD_TIME'] ?? 0)?></b> 
                        </div>
                    </div>

                    <div class="right">
                        <div class="pt-2">
                            <img src="https://minotar.net/avatar/<?=$toptime[2]['player_name'] ?? "Notch"?>/32.png" alt="image du joueur">
                            <b><?=$toptime[2]['player_name'] ?? "Personne"?></b> 
                            <br>
                            <b><?=TickToTimeShort($toptime[2]['TOTAL_WORLD_TIME'] ?? 0)?></b> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <h1>Top <img src="src/img/mort.png" class="icon" alt="image d'un crane"></h1>
                <div class="podium">
                    <div class="left">1</div>
                    <div class="top">2</div>
                    <div class="right">3</div>
                </div>
            </div>
            <div class="col">
                <h1>Top <img src="src/img/diamant.png" class="icon" alt="image de diamant du jeu minecraft"></h1>
                <div class="podium">
                    <div class="left">1</div>
                    <div class="top">2</div>
                    <div class="right">3</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>