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
	<meta charset="UTF-8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link rel="apple-touch-icon" href="images/favicon.png"/>
	<link rel="shortcut icon" href="images/favicon.png"/>
	<link rel='icon' href='images/favicon.png' type='image/x-icon'>

	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="animations.css">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=false;" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="manifest" href="pwa/rompwa.webmanifest">
	<?php require "functions.php"; ?>

	<?php $AZE = connectToBDD(); ?>

	<script>
		alert(<?= $AZE ?>)
	</script>


</head>

<body>
	<!-- <div id="textEchange" style="display:none;">
		Clic joueur pour échanger 🔁 (ou glisser-déposer)<br>
		Clic image 🐭😼🐻 pour modifer ✏️
		hideAll() changeTeam()
	</div> -->
	<header>
		<h1 id="team" name="" onclick="changeTeam()" > </h1>
		<img id="logoEquipe2" onclick="hideAll()" class="logo" src="" style = "position:absolute ; max-width:100%; height: 80px; left:15px; top: 5px;";/>
		<script>function hideAll(){	$("#div0").hide();}	</script>
	</header>

	<span id="questionPresents">Qui est présent ?</span>
	<span id="forceEquipes">
		<div id="forceEq1" class="forceEquipe" ></div>
		<div id="forceEq3" class="forceEquipe" ></div>
		<div id="forceEq2" class="forceEquipe" ></div>
	</span>
	<br>

	<div class="containerEquipes">
		<!-- <h1>Choix des équipes</h1> -->
		<div id="div1" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div3" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div2" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div0" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
		<div id="div9" class="container" ondragend="dragEnd(event)" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
	</div>
	
	<footer>
		<div class="containerButtonMenu">
			<button type="button" id="btBack" 	 class="hide" onclick="btBack()">🏒</button>
			<button type="button" id="btChgTeam" class="" onclick="changeTeam()">👨‍👩‍👦‍👦</button>
			<button type="button" id="btRandom"  class="btn" onclick="btRandom()">
				<img class="logo" id="logoEquipe" src="" />	
			</button>
			<button type="button" id="btEquipes" class="hide" nb=0 onclick="btModifNbEquipes()">👨🏽‍🤝‍👨🏻</button>
			<button type="button" id="btAddTeam" class="" onclick="btAddTeam()">➕</button>
			<!-- 🧩🏒⚙️📃🔙 -->
			<!-- <button type="button" id="btAjouter" onclick="addPlayer()">➕</button> -->
		</div>
	</footer>
	<button class="add-button" style="position: absolute;bottom:0px;right:5px;">Ajouter à l'écran d'accueil</button>

	<script src="javascript.js"></script>

	<script>
		$(document).ready(function () {
		console.log("Hello world")
		$("#div1").hide();
		$("#div2").hide();
		$("#div3").hide();
		changeTeam();
		console.log("<<<<<<<<<<<< END >>>>>>>>>>>>")
		});
	</script>
	<script src="dragndrop.js"></script>


</body>

</html>