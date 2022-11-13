<!--
http://localhost:8080/matchmaking/index.php
http://127.0.0.1:8080/matchmaking/index.php
-->

<?php 
	
	session_start(); // Start the session to store variable between pages
	include 'php/input.php'; // pour la fonction clean_input qui évite les injections sql
	$conn = include 'php/connectToDB.php'; // connexion à la bdd mysql

	// Toutes les équipes
	$all_teams = $conn->query("SELECT * FROM tbteam ORDER BY fav DESC") -> fetch_all( MYSQLI_ASSOC );
	
	// Détermination de l'équipe à afficher :
	if(isset($_POST["teamid"])) {	// Si le POST contient un ID d'équipe
		foreach ($all_teams as $t) { 	// On filtre la liste d'équipe
			if ($t['id'] == $_POST["teamid"]) 
			{ $team = $t; }
		}
	} elseif(isset($_GET["teamid"])) {
		foreach ($all_teams as $t) { 	// On filtre la liste d'équipe
			if ($t['id'] == $_GET["teamid"]) 
			{ $team = $t; }
		}	
	} else {
		$team =	 $all_teams[0];			// Si pas de demande d'équipe, on prend l'équipe favorite
	}

	// Récupérer la liste des joueurs de l'équipe
	$stmt = $conn->prepare("SELECT * FROM tbplayer WHERE team = ?");
	$stmt->bind_param('i',clean_input($team["id"]));
	$stmt->execute();
	$result = $stmt->get_result();
	$players = $result -> fetch_all(MYSQLI_ASSOC);

	// $players =	 $conn->query("SELECT * FROM tbplayer WHERE team = ". $team["id"]) -> fetch_all(MYSQLI_ASSOC) ;
	$conn -> close();
	// echo "<br/>";
	// var_dump($team);
	// echo " SUCCESS !";
	// echo "<br/>" . $t['id'] . "=?=" . $_POST["nextteamid"];
	// echo ("<br/><br/>");
	// $team =
	// $conn->query("SELECT * FROM tbteam WHERE id = " . $_POST['nextteamid']) -> fetch_assoc() ;
	// $conn->query("SELECT * FROM tbteam WHERE fav = 1 LIMIT 1") -> fetch_assoc() ;
	// echo "<script> console.log('nouvelle equipe: ". $team["name"] ."')</script>";
	// $team =		 	 	$conn->query("SELECT * FROM tbteam WHERE fav = 1 LIMIT 1") -> fetch_assoc() ;
	$all_teams_json = 	json_encode( $all_teams, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
	$team_json =		json_encode( $team, 	 JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
	$players_json =		json_encode( $players,	 JSON_UNESCAPED_UNICODE);
	
	// require "php/functions.php"; 
	// $all_teams = 	recup_table_ENTIERE($conn, "SELECT * FROM tbteam");
	// $all_players = 	recup_table_ENTIERE($conn, "SELECT * FROM tbplayer");
	// $all_players_json = json_encode( $conn->query("SELECT * FROM tbplayer ORDER BY absent ASC, name ASC") -> fetch_all( MYSQLI_ASSOC ), JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);

	$_SESSION['team'] = $team;
	$_SESSION['nbcarTeam']   = 13;
	$_SESSION['nbcarPlayer'] = 13;
?>

<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Matchmaking</title>
	<meta charset="utf8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/footer.css">
	<link rel="stylesheet" href="css/menu.css">
	<link rel="stylesheet" href="css/animations.css">
	<link rel="stylesheet" href="css/snackbar.css">
	<link rel="stylesheet" href="css/zoom.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia&effect=neon|outline|emboss|shadow-multiple">

	
	<link rel="apple-touch-icon" href="img/favicon.png"/>
	<link rel="shortcut icon" href="img/favicon.png"/>
	<link rel='icon' href='img/favicon.png' type='image/x-icon'>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf8_general_ci"/>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="manifest" href="pwa/rompwa.webmanifest">
</head>


<body>
	<div id="snackbar">Snackbar text message</div> 
	<!----------------- HEADER --------------->
	<header>
		<div id="logoHeader">
			<img id="logoTeam" onclick="clicLogo()" src="img/logo/LogoHockey7.png" alt="🏒" />
			<form  id='formChgTeam' action="index.php" method="post" >
				<input type="hidden" name="teamid" id="nextteamid" value="" />
				<input type="image" id="logoEquipeNext" name="submit" alt="👨‍👩‍👦‍👦" >
			</form>
		</div>
		<h1 id="team" onclick="clicTitre()" class="font-effect-shadow-multiple"> </h1>
		<h2 id="questionPresents">Qui est présent ?</h2>
	</header>
	<div id='lienzoom' onclick="showZoom()">🔎</div>
	<!----------------- EQUIPES --------------->
	<section>
		<div id ="containerEquipes" class="accueil">
			<div id="div0" class="teamContainer"></div>
			<div id="div1" class="teamContainer" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
			<div id="div3" class="teamContainer" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
			<div id="div2" class="teamContainer" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
			<div id="div9" class="teamContainer"></div>
		</div>
	</section>
	<!----------------- FOOTER ---------------> <!-- 👨‍👩‍👦‍👦🧩🏒⚙️📃🔙➕+⨄⨁👨🏽‍🤝‍👨🏻↻ -->
	<footer>
		<div id="containerButton_MenuAccueil">
			<!-- 🔁 CHANGE TEAM -->
			<button type="button" id="btVide">	(❁´◡`❁)	</button>
			<!-- 🎲 RANDOM -->
			<button type="button" id="btRandom"  onclick="btRandom()"> <span id="logoBtRandom2">	🎲	</span></button> <!-- <img id="logoBtRandom1"  src='img/logo/LogoHockey7.png' alt='🏒'/> -->
			<!-- ⚙️ SETTINGS -->
			<button type="button" id="btSettings" onclick="window.open('settings.php','_self')">⚙️</button>
				<!-- <form action="settings.php" method="post" id='formSettings' >
					<input type="hidden" name="nbcarTeam" value= <!?= $_SESSION['nbcarTeam'] ?> />
					<input type="hidden" name="team" value= <!?= $_SESSION['team']['id'] ?> />
					<input type="submit" name="submit" value="⚙️" id="btSettings" style="width:100%;height:100%;font-size: 3rem;">
				</form> -->
		</div>

		<div id="containerButton_MenuEquipes" style='display:none;'>
			<!-- 🔙 BACK -->
			<button type="button" id="btBack" onclick="btBack()">
				<span class="logo2">	🔙	</span>	</button>
			<!-- 🎲 RANDOM -->
			<button type="button" id="btForceEquipes" onclick="btRandom()">
				<span id='icoRandom'>	🎲	</span> <!-- 🦾🥋🥇🏅🏆🎲⚡ -->
				<div id='containerForceMenuEquipes'>
					<div id="forceEq1" class="forceEquipe" ></div>
					<div id="forceEq3" class="forceEquipe" ></div>
					<div id="forceEq2" class="forceEquipe" ></div>
				</div>
			</button>
			<!-- ➗ NB -->
			<button type="button" id="btNbEquipes" nb=0 onclick="btModifNbEquipes()">
				<span class="logo2">➗</span>			
			</button>
		</div>
		
	</footer>
	<!----------------- ASIDE:ZOOM--------------->
	<aside>
		<div id="textEchange" style="display:none;"> Clic joueur pour échanger 🔁 ou glisser-déposer<br/>Clic image 🐭😼🐻 pour modifer ✏️ 	</div>
		<div id='zoom'>
			<div id='zoomframe' onclick="hideZoom()"></div>
			<span id='closeZoom' onclick="hideZoom()">X</span>
			<button type="button" id='smaller' onclick='zoom("retrecir", document.getElementById("containerEquipes").className)'>🔍</button>
			<button type="button" id='bigger' onclick='zoom("grossir", document.getElementById("containerEquipes").className)'>🔎</button>
			<button type="button" id='fullscreen' onclick="toggleFullscreen()">  ↕️  </button>
			<!-- <button class="add-button" >Ajouter <sub>à l'écran d'accueil</sub></button> -->
		</div>
	</aside>

	<!--===================================-->
	<!-- 			 SCRIPTS			   -->
	<!--===================================-->

	<script src="scripts/javascript.js"></script>
	<script src="scripts/dragndrop.js"></script>
	<script src="scripts/DragDropTouch.js"></script>
	<script src="scripts/appels_server.js"></script>
	
	<script>
		const defautTextSize = 20;
		// =================== // Nombre de caractère maximal autorisé
		var nbcarPlayer = <?= $_SESSION['nbcarPlayer'] ?> // 13;
		var nbcarTeam =	  <?= $_SESSION['nbcarTeam']   ?> // 13;
		// =================== // Récupération des données du serveur // testTable(all_teams)
		var all_teams = <?= $all_teams_json ?>;
		// =================== // 
			
		$(document).ready(function () {
			console.log("Hello world - document ready")

				var team = <?= $team_json ?>;
				var players = <?= $players_json ?>;
				var nextTeam =  getNextTeamId(all_teams, team) // on définit dès maintenant l'identifiant de la prochaine équipe, pour pouvoir afficher son logo
				loadTeam(team, players, nextTeam);
				defineTextSize(defautTextSize);

				<?PHP if(!isset($_POST["teamid"])) {	// Si c'est le premier chargement de la page
					echo "snackbar('🤖 Coucou','white',2);";
				}
				?>
			console.log("<<<<<<<<<<<< END >>>>>>>>>>>>");
		});

		function clicLogo(e) {	
			document.getElementById("formChgTeam").submit()
			snackbar("Equipe suivante !","white",2)		}
		function clicTitre(e) {	snackbar("🤪","white",2)		}

	</script>

</body>

</html>