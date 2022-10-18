<!--A FAIRE :
	TEST AJOUT GIT ! HELLO GIT :)
	Drag'n'drop:
		https://stackoverflow.com/questions/3172100/html-drag-and-drop-on-mobile-devices
		https://medium.com/@deepakkadarivel/drag-and-drop-dnd-for-mobile-browsers-fc9bcd1ad3c5
		https://github.com/deepakkadarivel/DnDWithTouch/blob/master/index.html
		https://deepakkadarivel.github.io/DnDWithTouch/		
 -->

<!DOCTYPE HTML>
<html lang="fr">
	 
<head>
	<title>Matchmaking</title>
	<meta charset="utf8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link rel="apple-touch-icon" href="images/favicon.png"/>
	<link rel="shortcut icon" href="images/favicon.png"/>
	<link rel='icon' href='images/favicon.png' type='image/x-icon'>

	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/animations.css">
	<link rel="stylesheet" href="css/snackbar.css">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf8_general_ci"/>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=false;" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="manifest" href="pwa/rompwa.webmanifest">

	<?php 
		require "php/functions.php"; 
		$conn = include 'php/connectToDB.php';
		$all_teams = 	recup_table_ENTIERE($conn, "SELECT * FROM tbteam");
        $all_players = 	recup_table_ENTIERE($conn, "SELECT * FROM tbplayer");
		$conn -> close();
	?>
</head>

<body>
				<div id="snackbar">Snackbar text message</div> 
				<script>	function testLogo() {
					//$('#div9').hide()
					var x = document.getElementById("snackbar"); // Get the snackbar DIV
						x.innerHTML = "🤖 Coucou";
						x.style.fontSize = "45px";
						x.style.color = "white";
						x.style.borderRadius = "100px";
						x.className = "show"; // Add the "show" class to DIV
						setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000); // After 3 seconds, remove the show class from DIV
				}</script>
				<div id="textEchange" style="display:none;"> Clic joueur pour échanger 🔁 ou glisser-déposer<br/>Clic image 🐭😼🐻 pour modifer ✏️ 	</div>
	<header>
		<h1 id="team" name="" onclick="changeTeam()" > </h1>
		<img id="logoEquipe2" onclick="testLogo()" class="logo" src="images/logo/LogoHockey6.png" style = "position:absolute ; max-width:100%; height: 100px; left:15px; top: 5px;";/>
	</header>
	<span id="questionPresents">Qui est présent ?</span>
	<span id="forceEquipes">
		<div id="forceEq1" class="forceEquipe" ></div>
		<div id="forceEq3" class="forceEquipe" ></div>
		<div id="forceEq2" class="forceEquipe" ></div>
	</span>
	<br>
	<div id ="containerEquipes" class="containerEquipes">
		<div id="div1" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div3" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div2" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div0" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div9" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
	</div>
	<footer>
		<div class="containerButtonMenu">
			<!--    BOUTONS    🧩🏒⚙️📃🔙➕+⨄⨁ -->
			<button type="button" id="btBack" 	 class="hide" onclick="btBack()">🏒</button>
			<button type="button" id="btChgTeam" class="" onclick="changeTeam()">👨‍👩‍👦‍👦</button>
			<button type="button" id="btRandom"  class="btn" onclick="btRandom()">
				<img class="logo" id="logoEquipe1" src="" />	
			</button>
			<button type="button" id="btEquipes" class="hide" nb=0 onclick="btModifNbEquipes()">👨🏽‍🤝‍👨🏻</button>
			<button type="button" id="btAddTeam" class="" onclick="btAddTeam()">➕</button> 
			
		</div>
	</footer>
	<button class="add-button" style="position: absolute;bottom:0px;right:5px;">Ajouter à l'écran d'accueil</button>

	<script src="scripts/javascript.js"></script>
	<script src="scripts/dragndrop.js"></script>
	<script src="scripts/DragDropTouch.js"></script>
	<script src="scripts/appel_server.js"></script>
	
	<script>
		var all_teams = <?= $all_teams ?>;
		var all_players = <?= $all_players ?>;
			// testTable(all_teams)

		$(document).ready(function () {
			console.log("Hello world - document ready")

			var team = 		Object.values(all_teams	 ).filter(item => item.fav === '1')[0] // récupère le premier résultat
			if (!team) {team = all_teams[0]} // Si l'équipe favorite n'est pas définie, on prend la première équipe de la liste
			var players = 	Object.values(all_players).filter(item => item.team === team.id)
			// console.log("team :");		console.log(team);
			// console.log("players :");	console.log(players);

			loadTeam(team, players);
			console.log("<<<<<<<<<<<< END >>>>>>>>>>>>");




			
		});

	</script>

</body>

</html>