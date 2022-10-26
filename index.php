<!--
http://localhost:8080/matchmaking/index.php
http://127.0.0.1:8080/matchmaking/index.php
-->

<!DOCTYPE HTML>
<html lang="fr">
	 
<head>
	<title>Matchmaking</title>
	<meta charset="utf8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia&effect=neon|outline|emboss|shadow-multiple">
	
	<link rel="apple-touch-icon" href="img/favicon.png"/>
	<link rel="shortcut icon" href="img/favicon.png"/>
	<link rel='icon' href='img/favicon.png' type='image/x-icon'>

	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/footer.css">
	<link rel="stylesheet" href="css/menu.css">
	<link rel="stylesheet" href="css/animations.css">
	<link rel="stylesheet" href="css/snackbar.css">
	<link rel="stylesheet" href="css/zoom.css">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf8_general_ci"/>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="manifest" href="pwa/rompwa.webmanifest">

	<?php 
		$conn = include 'php/connectToDB.php';
		// require "php/functions.php"; 
		// $all_teams = 	recup_table_ENTIERE($conn, "SELECT * FROM tbteam");
        // $all_players = 	recup_table_ENTIERE($conn, "SELECT * FROM tbplayer");
		$all_teams = 	json_encode( $conn->query("SELECT * FROM tbteam ORDER BY fav DESC")  ->fetch_all( MYSQLI_ASSOC ) ,	JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
        $all_players = 	json_encode( $conn->query("SELECT * FROM tbplayer ORDER BY absent ASC, name ASC")->fetch_all( MYSQLI_ASSOC ) ,JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
		$conn -> close();
	?>
	
</head>
<body>
	<!----------------- HEADER --------------->
	<header>
		<h1 id="team" onclick="btEditTeam()" class="font-effect-shadow-multiple"> </h1>
		<img id="logoHeader" onclick="testLogo()" class="logo" src="img/logo/LogoHockey7.png" alt="🏒" style='font-size:60px'/>
		<h2 id="questionPresents">Qui est présent ?</h2>
	</header>
	<div id='lienzoom' onclick="showZoom()">🔎</div>
	<!----------------- EQUIPES --------------->
	<section>
		<div id ="containerEquipes" class="accueil">
			<div id="div0" class="container"></div>
			<div id="div1" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
			<div id="div3" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
			<div id="div2" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
			<div id="div9" class="container"></div>
		</div>
	</section>
	<!----------------- FOOTER --------------->
	<footer>
		<div id="containerButton_MenuAccueil"> 	<!--    BOUTONS    👨‍👩‍👦‍👦🧩🏒⚙️📃🔙➕+⨄⨁ -->
			<button type="button" id="btChgTeam" onclick="changeTeam()" nextteamid="">
				<img class="logo2" id="logoEquipeNext" src="" alt="👨‍👩‍👦‍👦">	</button>
			<button type="button" id="btRandom"  onclick="btRandom()"> <!-- <img id="logoBtRandom1"  src='img/logo/LogoHockey7.png' alt='🏒'/> -->
				<span id="logoBtRandom2">	🎲	</span>				</button>
			<button type="button" id="btAddTeam" onclick="btAddTeam()">
				<span class="logo2">	➕	</span>			</button> 
		</div>

		<div id="containerButton_MenuEquipes" style='display:none;'> 	<!--    BOUTONS    👨‍👩‍👦‍👦🧩🏒⚙️📃🔙➕+⨄⨁👨🏽‍🤝‍👨🏻 -->
			<button type="button" id="btBack" onclick="btBack()">
				<span class="logo2">	🔙	</span>	</button>
			<button type="button" id="btForceEquipes" onclick="btRandom()">
				<span id='icoRandom'>	🎲	</span> <!-- 🦾🥋🥇🏅🏆🎲⚡ -->
				<div id='containerForceMenuEquipes'>
					<div id="forceEq1" class="forceEquipe" ></div>
					<div id="forceEq3" class="forceEquipe" ></div>
					<div id="forceEq2" class="forceEquipe" ></div>
				</div>
			</button>
			<button type="button" id="btNbEquipes" nb=0 onclick="btModifNbEquipes()">
				<span class="logo2">➗</span>			
			</button>
		</div>
		
	</footer>
	<!----------------- ASIDE --------------->
	<aside>
		<div id="snackbar">Snackbar text message</div> 
		<div id="textEchange" style="display:none;"> Clic joueur pour échanger 🔁 ou glisser-déposer<br/>Clic image 🐭😼🐻 pour modifer ✏️ 	</div>
		<div id='zoom'>
			<div id='zoomframe' onclick="hideZoom()"></div>
			<button id='smaller' onclick='retrecir()'>🔍</button>
			<button id='bigger' onclick='grossir()' tailleText='1'>🔎</button>
			<!-- <button class="add-button" >Ajouter <sub>à l'écran d'accueil</sub></button> -->
			<span id='closeZoom' onclick="hideZoom()">X</span>
		</div>
		<input type="file" name="file" enctype="multipart/form-data" accept="image/png, image/gif, image/jpeg" style='display:none;'></input> <!-- Champ caché ! champ input pour choisir une image -> https://gist.github.com/0xPr0xy/4060754-->
	</aside>

	<!--===================================-->

	<script src="scripts/javascript.js"></script>
	<script src="scripts/dragndrop.js"></script>
	<script src="scripts/DragDropTouch.js"></script>
	<script src="scripts/appels_server.js"></script>
	
	<script>
		// =================== // Nombre de caractère maximal autorisé
		var nbcarPlayer = 13;
		var nbcarTeam = 13;
		// =================== // Récupération des données du serveur // testTable(all_teams)
		var all_teams = <?= $all_teams ?>;
		var all_players = <?= $all_players ?>;
		// =================== // 
		const defautTextSize = 20;
			

		$(document).ready(function () {
			console.log("Hello world - document ready")

				snackbar("🤖 Coucou","white",2)
				var team = 		Object.values(all_teams	 ).filter(item => item.fav === '1')[0] // récupère le premier résultat
				if (!team) {team = all_teams[0]} // Si l'équipe favorite n'est pas définie, on prend la première équipe de la liste
				var players = 	Object.values(all_players).filter(item => item.team === team.id)
				var nextTeam =  getNextTeamId(all_teams, team) // on définit dès maintenant l'identifiant de la prochaine équipe, pour pouvoir afficher son logo
				loadTeam(team, players, nextTeam);
				defineTextSize(defautTextSize);

			console.log("<<<<<<<<<<<< END >>>>>>>>>>>>");
		});

		function testLogo(e) {
			snackbar("🤖 Yo l'humain ! Tu pues du cul 💩","white",2)
		}

	</script>

</body>

</html>