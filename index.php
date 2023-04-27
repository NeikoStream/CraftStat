<?php
require_once 'config.php';
require_once 'src/fonction/fonction.php';

try {
    $linkpdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

//Récupérer la table stat avec toutes les infos

$query = "SELECT player_name, TOTAL_WORLD_TIME, DEATHS, (IFNULL(JSON_EXTRACT(mine_block, '$.DIAMOND_ORE'),0) + IFNULL(JSON_EXTRACT(mine_block, '$.DEEPSLATE_DIAMOND_ORE'),0)) as diamants FROM stats";
$params = [];
//fonction de recherche
if (!empty($_GET['q'])) {
    $query .= " WHERE player_name LIKE :pseudo";
    $params['pseudo'] = '%' . $_GET['q'] . '%';
}

$query .= " ORDER BY TOTAL_WORLD_TIME DESC";
$players = $linkpdo->prepare($query);

$players->execute($params);
$players = $players->fetchALL();

//Podium Temps
$toptime = $linkpdo->prepare("SELECT player_name, TOTAL_WORLD_TIME FROM stats ORDER BY TOTAL_WORLD_TIME DESC LIMIT 3");
$toptime->execute();
$toptime = $toptime->fetchALL();

//Podium Morts
$topDead = $linkpdo->prepare("SELECT player_name, DEATHS FROM stats ORDER BY DEATHS DESC LIMIT 3");
$topDead->execute();
$topDead = $topDead->fetchALL();

//Podium Diamants
$topDiamant = $linkpdo->prepare("SELECT player_name, (IFNULL(JSON_EXTRACT(mine_block, '$.DIAMOND_ORE'),0) + IFNULL(JSON_EXTRACT(mine_block, '$.DEEPSLATE_DIAMOND_ORE'),0)) as diamants FROM stats ORDER BY diamants DESC LIMIT 3");
$topDiamant->execute();
$topDiamant = $topDiamant->fetchALL();
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
               <?php
$api = json_decode(file_get_contents("https://api.mcstatus.io/v2/status/java/" . $ip . ":" . $port));
$online = $api->players->online;
if ($online !== 0) {
    for ($i = 0; $i < $online; $i++) {
        $playersOnline[$i] = $api->players->list[$i]->name_clean;
    }

}

?>

                    <h1><?=$online?> en ligne</h1>
                    <h2></h2>
            </div>
            <div class="col">
                <h1 class=""><?=$serverName?></h1>
            </div>
            <div class="col">
                <form action="" class="p-2">
                        <div class="form-group hstack">
                            <input type="text" class="form-control" name="q" placeholder="Rechercher un pseudo" value="<?php if (isset($_GET['q'])) {echo htmlentities($_GET['q']);}?>">
                            <button class="btn btn-primary mx-2">🔎</button>
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
foreach ($players as $player):
?>

            <tr class="<?php
if ($online !== 0) {
    if (in_array($player['player_name'], $playersOnline)) {
        echo "EnLigne";
    }
}?>

            ">
                <td>
                    <img src="https://minotar.net/avatar/<?=$player['player_name']?>/32.png" alt="image du joueur"> <?=$player['player_name']?>
                    <?php if ($online !== 0) {
    if (in_array($player['player_name'], $playersOnline)) {?>
                        <img src="src/img/online.png" alt="enLigne" height="30px" class="ps-2">
                    <?php }
}?>
                </td>
                <td><?=TickToTime($player['TOTAL_WORLD_TIME'])?></td>
                <td><?=$player['DEATHS']?></td>
                <td><?=$player['diamants']?></td>
            </tr>
            <?php endforeach?>
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
                    <div class="left vstack">
                        <div class="pt-2">
                            <img src="https://minotar.net/avatar/<?=$topDead[0]['player_name'] ?? "Notch"?>/32.png" alt="image du joueur">
                            <b><?=$topDead[0]['player_name'] ?? "Personne"?></b>
                            <b><?=$topDead[0]['DEATHS']?>☠</b>
                        </div>
                    </div>

                    <div class="top">
                        <div class="pt-2">
                            <img src="https://minotar.net/avatar/<?=$topDead[1]['player_name'] ?? "Notch"?>/32.png" alt="image du joueur">
                            <b><?=$topDead[1]['player_name'] ?? "Personne"?></b>
                            <br>
                            <b><?=$topDead[1]['DEATHS'] ?? 0?>☠</b>
                        </div>
                    </div>

                    <div class="right">
                        <div class="pt-2">
                            <img src="https://minotar.net/avatar/<?=$topDead[2]['player_name'] ?? "Notch"?>/32.png" alt="image du joueur">
                            <b><?=$topDead[2]['player_name'] ?? "Personne"?></b>
                            <br>
                            <b><?=$topDead[2]['DEATHS'] ?? 0?>☠</b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <h1>Top <img src="src/img/diamant.png" class="icon" alt="image de diamant du jeu minecraft"></h1>
                <div class="podium">
                    <div class="left vstack">
                        <div class="pt-2">
                            <img src="https://minotar.net/avatar/<?=$topDiamant[0]['player_name'] ?? "Notch"?>/32.png" alt="image du joueur">
                            <b><?=$topDiamant[0]['player_name'] ?? "Personne"?></b>
                            <b><?=($topDiamant[0]['diamants'])?>💎</b>
                        </div>
                    </div>

                    <div class="top">
                        <div class="pt-2">
                            <img src="https://minotar.net/avatar/<?=$topDiamant[1]['player_name'] ?? "Notch"?>/32.png" alt="image du joueur">
                            <b><?=$topDiamant[1]['player_name'] ?? "Personne"?></b>
                            <br>
                            <b><?=($topDiamant[1]['diamants']) ?? 0?>💎</b>
                        </div>
                    </div>

                    <div class="right">
                        <div class="pt-2">
                            <img src="https://minotar.net/avatar/<?=$topDiamant[2]['player_name'] ?? "Notch"?>/32.png" alt="image du joueur">
                            <b><?=$topDiamant[2]['player_name'] ?? "Personne"?></b>
                            <br>
                            <b><?=($topDiamant[2]['diamants']) ?? 0?>💎</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3 ">
        <div class="row justify-content-center">
            <a href="http://57.128.22.208:40008/"><img src="src/img/carte.png" id="carte" alt="image d'une carte"></a>
        </div>
    </div>

</body>
</html>