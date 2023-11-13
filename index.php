<!--
http://localhost:8080/matchmaking/index.php
http://127.0.0.1:8080/matchmaking/index.php
-->


<?php 
	
	session_start(); // Start the session to store variable between pages

	require_once "php/auth/authCookieSessionValidate.php";
	// SOURCE : https://phppot.com/php/secure-remember-me-for-login-using-php-session-and-cookies/ 

	// if(!$isLoggedIn) { header("Location: ./"); }
	if($isLoggedIn) {
		echo "<script> snackbar('🤪 Connecté','white',2) </script>";
	}


	include 'php/input.php'; // pour la fonction clean_input qui évite les injections sql
	include 'php/updateDropdowns.php';

	$conn = include 'php/connectToDB.php'; // connexion à la bdd mysql

	// Toutes les équipes
	$all_teams = $conn->query("SELECT * FROM tbteam ORDER BY fav DESC") -> fetch_all( MYSQLI_ASSOC );
	
	// Détermine l'équipe à afficher :
	if (isset($_POST["teamid"])) { $id = $_POST["teamid"]; }	// Si le POST contient un ID d'équipe
	if (isset($_GET["teamid"]))  { $id = $_GET["teamid"]; }	// Si le GET contient un ID d'équipe
	if ($id) {
		foreach ($all_teams as $t) { 	// On filtre la liste d'équipe
			if ($t['id'] == $id) { 
				$team = $t; 
			}
		}
	} 
	if (!$team) {
		$team =	 $all_teams[0];			// Si pas de demande d'équipe, ou équipe n'existe plus, on prend l'équipe favorite
	}

	// Récupérer la liste des joueurs de l'équipe
	$stmt = $conn->prepare("SELECT * FROM tbplayer WHERE team = ?");
		$stmt->bind_param('i',clean_input($team["id"]));
		$stmt->execute();
	$result = $stmt->get_result();
	$players = $result -> fetch_all(MYSQLI_ASSOC);
	$conn -> close();

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
	
	<link rel="stylesheet" href="css\style.css">
	<link rel="stylesheet" href="css\header.css">
	<link rel="stylesheet" href="css/footer.css">
	<link rel="stylesheet" href="css\menujoueur.css">
	<link rel="stylesheet" href="css\animations.css">
	<link rel="stylesheet" href="css\snackbar.css">
	<link rel="stylesheet" href="css\zoom.css">
	<link rel="stylesheet" href="css\loading.css">
	<link rel="stylesheet" href="css\menuburger.css">
	<link rel="stylesheet" href="css\menupop.css">
	<link rel="stylesheet" href="css\tabteams.css">
	<!-- <link rel="stylesheet" href="css\rotatingborder.css"> -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia&effect=neon|outline|emboss|shadow-multiple">
	
	<link rel="apple-touch-icon" href="img/favicon.png"/>
	<link rel="shortcut icon" href="img/favicon.png"/>
	<link rel='icon' href='img/favicon.png' type='image/x-icon'>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf8_general_ci"/>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<!-- <meta name="apple-mobile-web-app-capable" content="yes"> -->
	<!-- <meta name="mobile-web-app-capable" content="yes"> -->
	<!-- <link rel="manifest" href="pwa/rompwa.webmanifest"> -->

</head>

<!----------------------++++++---------------------->
<!---------------------- BODY ---------------------->
<!----------------------++++++---------------------->

<body>

	<div id="snackbar">Snackbar text message</div> 
	<!----------------- HEADER --------------->
	<header class="titre">
		<div id="logoHeader">
			<img id="logoTeam" onclick="clicLogo()" src="img/logo/LogoHockey7.png" alt="🏟" />
		</div>
		<h1 id="team" onclick="clicTitre()" class="font-effect-shadow-multiple"> </h1>
		<h2 id="questionPresents">Qui est présent ?</h2>
	</header>
	
	<!-- <div id='lienzoom' onclick="showZoom()">🔎</div> -->

	<div id='zoom' class="menu" >
			<div id='zoomframe' onclick="hideZoom()"></div>
			<span id='closeZoom' onclick="hideZoom()">X</span>
			<button type="button" id='btn_theme' onclick="toggleTheme()">🌗</button>
			<button type="button" id='smaller' onclick='zoom("retrecir")'>🔍</button>
			<button type="button" id='bigger' onclick='zoom("grossir")'>🔎</button>
		</div>

	<!----------------- EQUIPES --------------->
	<article>
		<div id='containerForceMenuEquipes'>
			<div id="forceEq1" class="forceEquipe" ></div>
			<div id="forceEq3" class="forceEquipe" ></div>
			<div id="forceEq2" class="forceEquipe" ></div>
		</div>
	</article>
	
	<section>
		<div id ="containerEquipes" class="accueil">
			<div id="div0" class="equipe"></div>
			<div id="div1" class="equipe" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
			<div id="div3" class="equipe" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
			<div id="div2" class="equipe" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
			<div id="div9" class="equipe"></div>
		</div>
	</section>



	<!-- https://codepen.io/Markshall/pen/ZEQBKpb?editors=1100 -->
	<article id="tabteam_wrapper">
		<section id="tabteam">
		<header>
			<img class="tabteam__create" src="img/svg/plus.svg" onclick="openPageSettings_CreateTeam()">
			<!-- <span id="createTeam" onclick="openPageSettings_CreateTeam()">+</span> -->
			<h1 class="tabteam__title">
				<span class="tabteam__title--top">Equipes</span>
				<span class="tabteam__title--bottom">Choisissez</span>
			</h1>
			<img class="tabteam__close" src="img/svg/close.svg" onclick="closeTabteam()">
		</header>
		<main class="tabteam__profiles">
			<?php echo displayTeams3($all_teams); ?>
		</main>
		<!-- <div class="menu-background" onclick="this.classList.toggle('open')"></div> -->
		</section>
	</article>

	<!----------------- FOOTER ---------------> 
	<footer style="text-align:center;">
		<!-- 👨‍👩‍👦‍👦🧩🏟🏒⚙️📃🔙➕+⨄⨁👨🏽‍🤝‍👨🏻↻ -->

		<!-- https://codepen.io/yuhomyan/pen/WNwGywp -->
		<!-- document.getElementById('menu1').classList.remove('open') -->
		<!-- https://codepen.io/barhatsor/pen/YzwxaQV?editors=1100	 -->

		<div class="menudeco burger-menu" id="btfullscreen" onclick="toggleFullscreen()"></div>

		<input type="checkbox" id="burger-toggle">
		<label for="burger-toggle" id="menu1" class="menuP menudeco burger-menu" onclick="this.classList.toggle('open')">
		<!-- <div id="menu1" class="menuP menudeco burger-menu" onclick="this.classList.toggle('open')"> -->
			<div class="line"></div>
			<div class="line"></div>
			<div class="line"></div>
			<div class="background" onclick="document.getElementById('burger-toggle').classList.toggle('open')"></div>
			<!-- <label for="active" id="btchangeteam" class="sbutton"></label>	 -->
			<div class="sbutton" id="btzoom" onclick='showZoom()'></div>
			<div class="sbutton" id="btchangeteam" onclick="openTabteam()"></div>
			<div class="sbutton" id="btsettings" onclick="openPageSettings()"></div>

			<?php if($isLoggedIn) { ?>
				<div class="sbutton connected" id="btlogin" onclick="logout()"></div>
			<?php } else { ?>
				<div class="sbutton disconnected" id="btlogin" onclick="openPageLogin()"></div>
			<?php } ?>
		</label>
		

		<!-- BOUTONS  🔙🎲➗  -->


		<div id="containerButton_MenuEquipes_container">
			<!-- <div style="position: relative; left: -50%"> -->
				<div id="containerButton_MenuEquipes">
					<!--  🔙 BACK 🔄↺ -->
					<div id="btBack" class="menudeco" onclick="btBack()"></div>
					<!-- 🎲 RANDOM -->
					<div id="btRandom" class="menudeco" onclick="btRandom()" style='background: url("img/svg/shuffle.svg") no-repeat 50%/ 50% #e8e8f3;' >	</div> 
					<!-- ➗ NB ÷ -->
					<div id="btNbEquipes" class="menudeco" nb=0 onclick="btModifNbEquipes()"></div>
					<!-- <span class="logo2"></span>	 -->
				
					<!--  RANDOM 🎲  🦾🥋🥇🏅🏆⚡-->
					<!-- <button type="button" id="btForceEquipes" onclick="btRandom()">
						<span id='icoRandom'>	🎲	</span> 
					</button> -->
				</div>
			<!-- </div> -->
		</div>		
	</footer>
	<!----------------- ASIDE: ZOOM + LOADING SPINNER + TEXT--------------->
	<aside>
		<div id="textEchange" style="display:none;"> Clic joueur pour échanger 🔁 ou glisser-déposer<br/>Clic image 🐭😼🐻 pour modifer ✏️ 	</div>

		<div id="loading-spinner-mask"  class="invisible">
			<div id="spinner6"  class=""></div>
		</div>

	</aside>

	<!--===================================-->
	<!-- 			 SCRIPTS			   -->
	<!--===================================-->

	<script src="scripts/appels_server.js"></script>
	<script src="scripts/javascript.js"></script>
	<script src="scripts/dragndrop.js"></script>
	<script src="scripts/DragDropTouch.js"></script>
	<script src="scripts/burger.js"></script>

	<script>
		const defautTextSize = 20;
		// =================== // Nombre de caractère maximal autorisé
		var nbcarPlayer = <?= $_SESSION['nbcarPlayer'] ?> // 13;
		var nbcarTeam =	  <?= $_SESSION['nbcarTeam']   ?> // 13;
		// =================== // Récupération des données du serveur // testTable(all_teams)
		var all_teams = <?= $all_teams_json ?>;
			
		$(document).ready(function () {
			console.log("Hello world - document ready")

				<?PHP if(!(isset($_POST["teamid"]) or isset($_GET["teamid"]))) {	// Si c'est le premier chargement de la page
					echo "snackbar('🤖 Coucou','white',2);";
				} ?>
				var team = <?= $team_json ?>;
				var players = <?= $players_json ?>;
				loadTeam(team, players);
				defineTextSize(defautTextSize);
				setTheme();
			console.log("<<<<<<<<<<<< END >>>>>>>>>>>>");
		});
		function testpropagation(e){
			alert(2);
			e.stopPropagation();
			e.preventDefault();
			return false;
		}

		function clicTitre(e) {	snackbar("🤖 Détruire humain","white",2)		}
		function clicLogo(e) {	
			// document.getElementById('loading-spinner-mask').classList.remove('invisible');
			// document.getElementById("formChgTeam").submit();
			// snackbar("Equipe suivante !","white",2)		
		}
		function openPageSettings() {
			document.getElementById('loading-spinner-mask').classList.remove('invisible');
			window.open('settings.php','_self');
		}

		function openPageLogin() {
			document.getElementById('loading-spinner-mask').classList.remove('invisible');
			window.open('php/auth/login.php','_self');
		}
		function logout(){
			document.getElementById('loading-spinner-mask').classList.remove('invisible');
			window.open('php/auth/logout.php','_self');
		}

		function openPageSettings_CreateTeam() {
			var name = prompt("Nom de la nouvelle équipe ?")
			if (name == "" || name == null) { return }
			if (name.length > nbcarTeam ) {
				snackbar('ℹ️ | Le nom ne doit pas dépasser '+nbcarTeam+' caractères', 'orange')
			} else {
				DB_CREATE_team(name, 'accueil');				
			}
		}
		function openTabteam(){
			document.getElementById('tabteam_wrapper').classList.add('open');
		}
		function closeTabteam(){
			document.getElementById('tabteam_wrapper').classList.remove('open');
		}

	</script>

<!-- !				! -->
<!-- !		FIN		! -->
<!-- !______________! -->

<!-- <form  id='formChgTeam' action="index.php" method="post" >
<input type="hidden" name="teamid" id="nextteamid" value="" />
<input type="hidden" id="logoEquipeNext" name="submit" alt="👨‍👩‍👦‍👦" >
</form> -->
<!-- <div id='lienzoom' class="menu" onclick="this.classList.toggle('open')">🔎</div> -->

<!-- <div class="sbutton" id="btzoomplus" onclick='zoom(event, "retrecir", document.getElementById("containerEquipes").className)'></div> -->
<!-- <div class="sbutton" id="btzoommoins" onclick='zoom(event, "grossir",  document.getElementById("containerEquipes").className)'></div> -->
<!-- <div class="sbutton" id="btchangeteam"></div> -->
<!-- <label for="btchangeteam">Changer d'équipe</label> -->

<!-- <div id="containerButton_MenuAccueil"></div> -->

<!-- gris foncé : #e8e8f3 --> 
<!-- <img id="logoBtRandom" src="img/svg/gear-solid.svg">  -->
<!-- <span id="logoBtRandom2">	🎲	</span> -->
<!-- <img id="logoBtRandom1"  src='img/logo/LogoHockey7.png' alt='🏟'/> -->
<!--  CHANGE TEAM  🔁🗘↻ -->
<!-- <button type="button" id="btVide" onclick="document.getElementById('containerListeEquipes').classList.toggle('invisible')" nextteamid="">
<img class="logo2" id="logoEquipeNext2" src="<!?= $_SESSION['team']['logo'] ?>" alt="👨‍👩‍👦‍👦" max-width="100" max-height="120">
</button>
<div id='containerListeEquipes' class="invisible" style="position:absolute; bottom:10vh; ">
<!?php echo displayTeams1($all_teams); ?>
</div> -->
<!-- <button type="button" id="btChgTeam" class="btn" onclick="changeTeam()" nextteamid="">
</button> -->
<!-- ⚙️ SETTINGS -->
<!-- <button type="button" id="btSettings" onclick="openPageSettings()">⚙️</button> -->

<!-- <button class="add-button" >Ajouter <sub>à l'écran d'accueil</sub></button> -->

<!-- // var nextTeam =  getNextTeamId(all_teams, team) // on définit dès maintenant l'identifiant de la prochaine équipe, pour pouvoir afficher son logo -->

</div>

</body>

</html>