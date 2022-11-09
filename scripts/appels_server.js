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



function DB_DELETE_player(id, name) {
	console.log("==============> DB_DELETE_player " + id);
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
	}).catch(error => console.log(error));
}

function DB_CREATE_player(force, name, team, player) {
	console.log("==============> DB_CREATE_player");
	const data = {
		team:parseInt(team),
		name:name,
		absent:0,
		id:54,
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
	}).catch(error => console.log(error));
}


function DB_CHANGE_player_name(id, newname) {
	console.log("==============> DB_CHANGE_player_name " + newname + '('+id + ')');

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
	}).catch(error => console.log(error));
}

function DB_CHANGE_team_name(id, newname) {
	console.log("==============> DB_CHANGE_team_name " + newname + '('+id+')');

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
	}).catch(error => console.log(error));
}

function DB_CREATE_team(name) {
	console.log("==============> DB_CREATE_team " + name);

	fetch('php/CREATE_team.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: 'name='+name,
	})
	.then(response => response.json())
	.then(response => {
		console.log("RESULTAT APPEL SERVEUR CREATION EQUIPE");
		console.log("DB -> " + response.result)
		// location.reload();
		snackbar_DB(response) // Affichage du pop (snackbar)
	}).catch(error => console.log(error));
}

function DB_DELETE_team(id) {
	console.log("==============> DB_DELETE_team " + id);

	fetch('php/DELETE_team.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: 'id='+id,
	})
	.then(response => response.json())
	.then(response => {
		console.log("RESULTAT APPEL SERVEUR SUPPRESSION EQUIPE");
		console.log("DB -> " + response.result)
		// location.reload();
		snackbar_DB(response) // Affichage du pop (snackbar)
		setTimeout(function() {window.location.href = 'index.php'}, 1000);
	}).catch(error => console.log(error));
}

function DB_CHANGE_team_logo(id, newlogo) {
	console.log("==============> DB_CHANGE_team_logo");

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
	}).catch(error => console.log(error));
}

function DB_CHANGE_player_xp(id, xp) {
	console.log("==============> DB_CHANGE_player_xp xp:" + xp + '(id:'+id+')');

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

// function connectBdd(){
//     var mysql = require('mysql');
//     const pool  = mysql.createPool({
//         connectionLimit : 10,
//         host     : 'fdb32.atspace.me',
//         database : '4081187_matchmaking',
//         user     : '4081187_matchmaking',
//         password : 'M4tchmaking'
//     });
//     // Get all beers
//     app.get('', (req, res) => {
//         pool.getConnection((err, connection) => {
//             if(err) throw err;
//             console.log('connected as id ' + connection.threadId);
//             connection.query('SELECT * from players', (err, rows) => {
//                 connection.release(); // return the connection to pool
//                 if (!err) {
//                     res.send(rows);
//                 } else {
//                     console.log(err);
//                 }
//                 // if(err) throw err
//                 console.log('The data from players table are: \n', rows);
//             });
//         });
//     });
// }


// function save2(){
//     // Get the mysql service
//     var mysql = require('mysql');
//     // Add the credentials to access your database
//     var connection = mysql.createConnection({
//         host     : 'fdb32.atspace.me',
//         database : '4081187_matchmaking',
//         user     : '4081187_matchmaking',
//         password : 'M4tchmaking'
//     });
//     // connect to mysql
//     connection.connect(function(err) {
//         // in case of error
//         if(err){
//             console.log(err.code);
//             console.log(err.fatal);
//         }
//     });
//     // Perform a query
//     $query = 'SELECT * from players LIMIT 10';
//     connection.query($query, function(err, rows, fields) {
//         if(err){
//             console.log("An error ocurred performing the query.");
//             return;
//         }
//         console.log("Query succesfully executed: ", rows);
//     });
//     // Close the connection
//     connection.end(function(){
//         // The connection has been closed
//     });
// }