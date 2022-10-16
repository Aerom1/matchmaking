<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1> Problème pas cool </h1>
    Si je récupère un champ -> seule la lettre est "cassée" 😑<br/>
    Si je récupère une ligne -> certaines lignes sont vides 😬<br/>
    Si je récupère une table -> la table est vide 😭<br/>
    <h2> Boutons avec emoji utilisés sur le site </h2>
    <button type="button" id="btBack" 	 onclick="">🏒</button>
    <button type="button" id="btChgTeam" onclick="alert(this.innerText + this.id)">👨‍👩‍👦‍👦</button>
    <button type="button" id="btEquipes" onclick="alert(this.innerText + this.id)">👨🏽‍🤝‍👨🏻</button>
    <button type="button" id="btAddTeam" onclick="alert(this.innerText + this.id)">➕</button>

    <?php


        $db = "dbmatchmaking";
        echo "<h2>Accès à la bdd : ".$db."</h2>";
        $conn = connectToDB($db);
        
        recup_table_PAR_LIGNE($conn, "SELECT * FROM tbteam");
        recup_table_PAR_LIGNE($conn, "SELECT * FROM tbplayer");
        recup_table_ENTIERE($conn, "SELECT * FROM tbteam");
        recup_table_ENTIERE($conn, "SELECT * FROM tbplayer");

        function recup_table_PAR_LIGNE($conn, $req){
            echo "<h4>".$req." <><><><><><> ".__FUNCTION__.":</h4>";
            echo "<style> h4 {color:blue;} table, th, td {  border: 1px solid lightgrey;  border-collapse: collapse; padding-left:10px;padding-right:10px} th {background-color: grey; color:white;} .e {color:red} .player {  font-size: 1.5vw ;   padding-bottom:3px;  padding-left:3px;  border-radius: 5vw;  font-family: fantasy;  overflow: hidden;  flex-grow: 1;  display: flex;  grid-auto-flow: column;  align-items: center;   animation: bounce-in .3s linear;    box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px 0px,               rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,               rgba(0, 0, 0, 0.2) 0px -3px 1px 0px inset;}   #btBack,#btEquipes,#btChgTeam,#btAddTeam {  font-size: 4vh;  width: 20vw;    height: 6vh;    border-radius: 500px;  animation: bounce-in .3s linear;  }  @keyframes bounce-in {    from {        transform: scale(0);        opacity: 0;    }    to {        transform: scale(1);        opacity: 1;     }     0% {    transform: scale(0);    }    50% {    transform: scale(1.1);    }    100% {    transform: scale(1);    }} button:active {animation: zoom 0.3s linear ;}@keyframes zoom {0% {    transform: scale(1, 1);  }50% {    transform: scale(1.2, 1.6);  }  100% {    transform: scale(1, 1);  }  } </style>";
            echo "<table> <tr> <th></th> <th><b>Joueur</b></th> <th><b>json_encode ( fetch_assoc )</b></th></tr>";
            $res = $conn->query($req);
            while ($row = $res->fetch_assoc()) {
                $TOUTE_LA_LIGNE = json_encode($row) ? json_encode($row,JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE) : "<span class='e'> 🛑 json vide<span>" ;
                $nom = $row["name"];
                switch ($row["xp"]) { case 1:$emoji="🐭";break;
                                      case 2:$emoji="😼";break;
                                      case 3:$emoji="🐻";break; };
                echo "<tr><td>ligne ".++$cpt."</td><td class='player'><span>"
                                     .$emoji."</span><span>"
                                     .$nom."</span></td><td>"
                                     .$TOUTE_LA_LIGNE."</td></tr>";
            }
            echo "</table>";
        }

        function recup_table_ENTIERE($conn, $req){
            echo "<h4>".$req." <><><><><><> ".__FUNCTION__.":</h4>";
            $data = $conn->query($req)->fetch_all( MYSQLI_ASSOC );
            echo json_encode($data,JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
        }

        function connectToDB($db) {
            $servername = "localhost"; // 192.168.1.40 localhost
            $username = "root";
            $password = "";
            $conn = new mysqli($servername, $username, $password, $db) or die("Connect failed: %s\n". $conn -> error);
            if ( mysqli_connect_errno() ) {
                die("Connection failed: " . $conn->connect_error);
              }
            $conn->set_charset("utf8");
            return $conn;
        }
    ?>
</body>
</html>