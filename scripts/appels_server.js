/**
 * L'article qui explique tout sur Fetch , en json ET en x-www-form-urlencoded :
 * PHP Form submitting
 * https://gist.github.com/jesperorb/a6c12f7d4418a167ea4b3454d4f8fb61
 * 
 * L'article qui m'a débloqué, où j'ai récupéré formEncode()
 * https://forums.fusetools.com/t/how-do-i-receive-post-data-in-php-sent-from-fuse-javascript-by-fetch/5357/3
 */ 

	
function formEncode(obj) {
	var str = [];
	for(var p in obj)
	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
	return str.join("&");
}

function loadingSpinner(){  
	document.getElementById('loading-spinner-mask').classList.remove('invisible');
}
function unloadingSpinner(){   
	document.getElementById('loading-spinner-mask').classList.add('invisible');
	var e = document.getElementById('resultImportLogo')
	if (e!==null) {e.remove()}
}

function DB_DELETE_player(id, name) {
	console.log("==============> DB_DELETE_player " + id);
	loadingSpinner()

	fetch('php/DELETE_player.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: 'id='+id+"&name="+name,
	})
	.then(response => response.json())
	.then(response => {						// exemple de response :  {"success":true,"result":"Le joueur a été ajouté","id":null}
		console.log("RESULTAT APPEL SERVEUR SUPPRESSION JOUEUR");
		console.log("DB -> " + response.result)
		snackbar_DB(response) // Affichage du pop (snackbar)
		unloadingSpinner()
	}).catch(error => console.log(error));
}

function DB_CREATE_player(force, name, team, player) {
	console.log("==============> DB_CREATE_player");
	loadingSpinner()

	const data = {
		team:parseInt(team),
		name:name,
		absent:0,
		xp:parseInt(force)}
	fetch('php/CREATE_player.php', {
		method: 'POST',
		mode: 'cors',
		// headers: {'Content-Type': 'application/json'},
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: formEncode(data),
	})
	.then(response => response.json())
	.then(response => {						// exemple de response :  {"success":true,"result":"Le joueur a été ajouté","id":null}
		console.log("RESULTAT APPEL SERVEUR AJOUT JOUEUR");
		console.log("DB -> " + response.result)
		snackbar_DB(response) // Affichage du pop (snackbar)

		if (response.success) {
			player.id = response.id; // ajouter l'ID au joueur
		// } else {
		// 	player.remove();  // Empêcher le joueur 
		// 	var p = document.getElementsByName(name);
		// 	console.log(p[p.length-1])
		}
		unloadingSpinner()
	}).catch(error => console.log(error));
}


function DB_CHANGE_player_name(id, newname) {
	console.log("==============> DB_CHANGE_player_name " + newname + '('+id + ')');
    loadingSpinner()

	fetch('php/CHANGE_player_name.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: 'id='+id+'&name='+newname,
	})
	.then(response => response.json())
	.then(response => {					
		console.log("RESULTAT APPEL SERVEUR MODIF NOM JOUEUR");
		console.log("DB -> " + response.result)
		snackbar_DB(response) // Affichage du pop (snackbar)
		unloadingSpinner()
	}).catch(error => console.log(error));
}

function DB_CHANGE_team_name(id, newname) {
	console.log("==============> DB_CHANGE_team_name " + newname + '('+id+')');
	loadingSpinner()

	fetch('php/CHANGE_team_name.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: 'id='+id+'&name='+newname,
	})
	.then(response => response.json())
	.then(response => {
		console.log("RESULTAT APPEL SERVEUR MODIF NOM EQUIPE");
		console.log("DB -> " + response.result)
		snackbar_DB(response) // Affichage du pop (snackbar)
		if (response.success) {
			document.getElementById('btModifNom').innerText = newname;
			document.getElementById('teamlistfav').innerHTML = response.dropdownHTMLfav ; // METTRE A JOUR LA LISTE DEROULANTE
			document.getElementById('teamlistdel').innerHTML = response.dropdownHTMLdel ; // METTRE A JOUR LA LISTE DEROULANTE
			document.getElementById('autoDestruction').setAttribute('teamname',newname);
		}
		unloadingSpinner()
	}).catch(error => console.log(error));
}

function DB_CHANGE_team_favorite(id, name) {
	console.log("==============> DB_CHANGE_team_favorite " + name + '('+id+')');
	loadingSpinner()
	
	fetch('php/CHANGE_team_favorite.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: 'id='+id+'&name='+name,
	})
	.then(response => response.json())
	.then(response => {
		console.log("RESULTAT APPEL SERVEUR MODIF EQUIPE FAVORITE");
		console.log("DB -> " + response.result)
		snackbar_DB(response) // Affichage du pop (snackbar)
		if (response.success) {
			document.getElementById('teamlistfav').innerHTML = response.dropdownHTML ; // METTRE A JOUR LA LISTE DEROULANTE
		}
		unloadingSpinner()
	}).catch(error => console.log(error));
}

function DB_CREATE_team(name, nbcarTeam) {
	console.log("==============> DB_CREATE_team " + name);
	loadingSpinner()

	fetch('php/CREATE_team.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: 'name='+name,
	})
	.then(response => response.json())
	.then(response => {
		console.log("RESULTAT APPEL SERVEUR CREATION EQUIPE");
		console.log("DB -> " + response.result);
		// location.reload();
		snackbar_DB(response) // Affichage du pop (snackbar)
		if (response.success) {
			document.getElementById('formTeamId').value = response.id;
			document.getElementById('supprLogo').setAttribute('teamid', response.id);
			document.getElementById('closeTourne').setAttribute('teamid', response.id);
			document.getElementById('btModifNom').innerText = name;
			document.getElementById('btModifNom').setAttribute('teamid', response.id);
			document.getElementById('imgPlaceholder').setAttribute('src','');
			document.getElementById('teamlistfav').innerHTML = response.dropdownHTMLfav ; // METTRE A JOUR LA LISTE DEROULANTE
			document.getElementById('teamlistdel').innerHTML = response.dropdownHTMLdel ; // METTRE A JOUR LA LISTE DEROULANTE
			document.getElementById('autoDestruction').setAttribute('teamid',response.id);
			document.getElementById('autoDestruction').setAttribute('teamname',name);
		}
		unloadingSpinner()
	}).catch(error => console.log(error));
}

function DB_DELETE_team(id, name, self) {
	console.log("==============> DB_DELETE_team " + id + ' (self:'+self+')');
	loadingSpinner()

	fetch('php/DELETE_team.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: 'id='+id+'&name='+name,
	})
	.then(response => response.json())
	.then(response => {
		console.log("RESULTAT APPEL SERVEUR SUPPRESSION EQUIPE");
		console.log("DB -> " + response.result)
		// location.reload();
		snackbar_DB(response) // Affichage du pop (snackbar)
		if (response.success) {
			document.getElementById('teamlistfav').innerHTML = response.dropdownHTMLfav ; // METTRE A JOUR LA LISTE DEROULANTE
			document.getElementById('teamlistdel').innerHTML = response.dropdownHTMLdel ; // METTRE A JOUR LA LISTE DEROULANTE
		}
		if (self) {
			setTimeout(function() {window.location.href = 'index.php'}, 500);
		} else {
			unloadingSpinner()
		}
	}).catch(error => console.log(error));
}

function DB_CHANGE_team_logo(id, newlogo) {
	console.log("==============> DB_CHANGE_team_logo");
	loadingSpinner()

	fetch('php/CHANGE_team_logo.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: 'id='+id+'&logo='+newlogo,
	})
	.then(response => response.json())
	.then(response => {
		console.log("RESULTAT APPEL SERVEUR MODIF LOGO EQUIPE");
		console.log("DB -> " + response.result)
		snackbar_DB(response) // Affichage du pop (snackbar)
		unloadingSpinner()
	}).catch(error => console.log(error));
}

function CHANGE_team_logo_suppr(id) {
	console.log("==============> CHANGE_team_logo_suppr");
	loadingSpinner()

	fetch('php/CHANGE_team_logo_suppr.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: 'id='+id
	})
	.then(response => response.json())
	.then(response => {
		console.log("RESULTAT APPEL SERVEUR SUPPR LOGO EQUIPE");
		console.log("DB -> " + response.result)
		snackbar_DB(response) // Affichage du pop (snackbar)
		if (response.success) {
			document.getElementById('imgPlaceholder').setAttribute('src','');
			document.getElementById("supprLogo").style.display = 'none';
		}
		unloadingSpinner()
	}).catch(error => console.log(error));
}

function DB_CHANGE_player_xp(id, xp) {
	console.log("==============> DB_CHANGE_player_xp xp:" + xp + '(id:'+id+')');
	loadingSpinner()

	fetch('php/CHANGE_player_xp.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: 'id='+id+'&xp='+xp,
	})
	.then(response => response.json())
	.then(response => {
		console.log("RESULTAT APPEL SERVEUR MODIF NOM JOUEUR");
		console.log("DB -> " + response.result)
		snackbar_DB(response) // Affichage du pop (snackbar)
		unloadingSpinner()
	}).catch(error => console.log(error));
}

function DB_CHANGE_player_inactifs(players) {
	console.log("==============> DB_CHANGE_player_inactifs");
	
	fetch('php/CHANGE_player_inactifs.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: formEncode(players),
	})
	.then(response => response.json())
	.then(response => {
		console.log("RESULTAT APPEL SERVEUR MODIF NOM JOUEUR");
		console.log("DB -> " + response.result)
		// snackbar_DB(response) // Affichage du pop (snackbar)
	}).catch(error => console.log(error));
}
