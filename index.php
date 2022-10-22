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
	<link rel="stylesheet" href="css/param.css">

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
	<!------------------------------------------------------------------------------------------------------------------>

<body>
				<div id="snackbar">Snackbar text message</div> 
				<script>	function testLogo(e) {
					//$('#div9').hide() 
					// $('#logoHeader').hide()
					// alert('Result include: <?= include('php/input.php') ?>')
					// alert('<?php echo $_SERVER['REMOTE_ADDR']; ?>')
					// console.log('<?= $_SERVER['REMOTE_ADDR'] ?>')
					snackbar("🤖 Coucou")
					showParams()
					}</script>
				<div id="textEchange" style="display:none;"> Clic joueur pour échanger 🔁 ou glisser-déposer<br/>Clic image 🐭😼🐻 pour modifer ✏️ 	</div>
	
				<!-- Champ caché ! Si création de nouvelle équipe, ce champ input permet de pour parcourir les fichiers pour choisir une image source-> https://gist.github.com/0xPr0xy/4060754-->
				<input type="file" name="file" enctype="multipart/form-data" accept="image/png, image/gif, image/jpeg" style='display:none;'></input>

	<header>
		<h1 id="team" onclick="btEditTeam()" class="font-effect-shadow-multiple"> </h1>
		<img id="logoHeader" onclick="testLogo()" class="logo" src="img/logo/LogoHockey7.png" alt="🏒" style='font-size:60px'/>
		<h2 id="questionPresents" style='margin-top: 0;'>Qui est présent ?</h2>
		<span id="forceEquipes">
			<div id="forceEq1" class="forceEquipe" ></div>
			<div id="forceEq3" class="forceEquipe" ></div>
			<div id="forceEq2" class="forceEquipe" ></div>
		</span>
	</header>
	<div id ="containerEquipes" class="containerEquipes">
		<div id="div1" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div3" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div2" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div0" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div9" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
	</div>
	<footer>
		<div class="containerButtonMenu"> 	<!--    BOUTONS    👨‍👩‍👦‍👦🧩🏒⚙️📃🔙➕+⨄⨁ -->

			<button type="button" id="btRandom"  class="btn" onclick="btRandom()">
				<img id="logoBtRandom"  src='img/logo/LogoHockey7.png' alt='🏒'/>	
			</button>

			<button type="button" id="btChgTeam" class="btn" onclick="changeTeam()" nextteamid="">
				<img class="logo2" id="logoEquipeNext" src="" alt="👨‍👩‍👦‍👦" width="100" height="120">
			</button>
			<button type="button" id="btAddTeam" class="btn" onclick="btAddTeam()">
				<span class="logo2">➕</span>
			</button> 
			
			<button type="button" id="btBack" 	 class="btn hide" onclick="btBack()">
				<span class="logo2">🏒</span>
			</button>
			<button type="button" id="btEquipes" class="btn hide" nb=0 onclick="btModifNbEquipes()">
				<span class="logo2">👨🏽‍🤝‍👨🏻</span>
			</button>
		</div>

		<div id='params'>
			<button id='smaller' onclick='retrecir()'><sub>Aa</sub><b>-</b></button>
			<button id='bigger' onclick='grossir()' tailleText='1'><sub>Aa</sub><b>+</b></button>
			<!-- <button class="add-button" >Ajouter <sub>à l'écran d'accueil</sub></button> -->
			<span id='closeParams' onclick="hideParams()">X</span>
		</div>

	</footer>

	<!------------------------------------------------------------------------------------------------------------------>

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

				var team = 		Object.values(all_teams	 ).filter(item => item.fav === '1')[0] // récupère le premier résultat
				if (!team) {team = all_teams[0]} // Si l'équipe favorite n'est pas définie, on prend la première équipe de la liste
				var players = 	Object.values(all_players).filter(item => item.team === team.id)
				var nextTeam =  getNextTeamId(all_teams, team) // on définit dès maintenant l'identifiant de la prochaine équipe, pour pouvoir afficher son logo
				loadTeam(team, players, nextTeam);
				defineTextSize(defautTextSize);

			console.log("<<<<<<<<<<<< END >>>>>>>>>>>>");
		});

	</script>

</body>

</html>