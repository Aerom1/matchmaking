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

function deleteFromDatabase(id) {

	console.log("==============> deleteFromDatabase " + id);

	const data = {id:id}

	fetch('php/deleteplayer.php', {
		method: 'POST',
		mode: 'cors',
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: formEncode(data),
	})
	.then(response => response.json())
	.then(response => {
		
		// exemple de response :  {"success":true,"result":"Le joueur a été ajouté","id":null}
		console.log("RESULTAT APPEL SERVEUR SUPPRESSION JOUEUR");

		// Affichage du pop (snackbar)
		var x = document.getElementById("snackbar"); // Get the snackbar DIV
		x.className = "show"; // Add the "show" class to DIV
		x.innerHTML = response.result;
		x.style.color = response.success ? 'white' : 'orange'
		setTimeout(function(){ 
			x.className = x.className.replace("show", ""); 
		}, 3000); // After 3 seconds, remove the show class from DIV

		if (response.success) {
			console.log('Joueur supprimé avec succès !')
		}

	}).catch(error => console.log(error));


}



function createPlayerInDatabase(force, name, team) {

	console.log("==============> envoi data : ");

	const data = {
		team:parseInt(team),
		name:name,
		// team:1,
		// name:"BBBBB",
		absent:0,
		id:54,
		xp:parseInt(force)}
	// console.log(data);
	// console.log("JSON.stringify(data) : " + JSON.stringify(data));

	fetch('php/newplayer.php', {
		method: 'POST',
		mode: 'cors',
		// headers: {'Content-Type': 'application/json'},
		headers: {'Content-type': 'application/x-www-form-urlencoded'},
		body: formEncode(data),
	})
	.then(response => response.json())
	.then(response => {
		
		// exemple de response :  {"success":true,"result":"Le joueur a été ajouté","id":null}
		console.log("RESULTAT APPEL SERVEUR AJOUT JOUEUR");

		// Affichage du pop (snackbar)
		var x = document.getElementById("snackbar"); // Get the snackbar DIV
		x.className = "show"; // Add the "show" class to DIV
		x.innerHTML = response.result;
		x.style.color = response.success ? 'white' : 'orange'
  		setTimeout(function(){ 
			x.className = x.className.replace("show", ""); 
		}, 3000); // After 3 seconds, remove the show class from DIV

		if (response.success) {
			document.getElementsByName(name)[0].id = response.id; // ajouter l'ID au joueur
		}

	}).catch(error => console.log(error));

}