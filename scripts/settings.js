


function x(id){return document.getElementById(id)}

function fermer(btn) {
    document.getElementById('loading-spinner-mask').classList.remove('invisible');
    teamid = btn.getAttribute('teamid')
    document.getElementById("btCloseMenu").classList.add("animer")
    window.location.href = 'index.php?teamid='+teamid;
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById("imgPlaceholder").setAttribute('src', e.target.result)
            document.getElementById("supprLogo").style.display = 'inline-block';
        }
        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

function btAddTeam(nbcarTeam){
    var name = prompt("Nom de la nouvelle équipe ?")
    if (name == "" || name == null) { return }
    if (name.length > nbcarTeam ) {
        snackbar('ℹ️ | Le nom ne doit pas dépasser '+nbcarTeam+' caractères', 'orange')
        return;
    }
    DB_CREATE_team(name, 'settings');
    
}

function btDelTeam(btn, self){
    var name = btn.getAttribute('teamname')
    var id = btn.getAttribute('teamid')
    if (confirm("Confirmer la suppression de l'équipe "+ name +" ?\nCette action est irréversible !")) {
        DB_DELETE_team(id, name, self);
    }
}

function supprLogo_MODIFTEAM(btn){
    var id = btn.getAttribute('teamid')
    if (confirm("Confirmer la suppression du logo ?\nCette action est irréversible !")) {
        CHANGE_team_logo_suppr(id);
    }
}

function changeName_MODIFTEAMDB(e, nbcarTeam){
    // console.log(e);
    id = e.getAttribute('teamId')
    newname = prompt('Nouveau nom ?',e.innerText);
    if (!newname) { return }
    if (newname.length > nbcarTeam ) {
        snackbar('ℹ️ | Le nom ne doit pas dépasser '+nbcarTeam+' caractères', 'orange');
        return;
    }
    e.innerText = newname;
    DB_CHANGE_team_name(id, newname);
}

function selectFavorite(btn) {
    var id =    btn.getAttribute("teamid");
    var name =  btn.innerText;
    DB_CHANGE_team_favorite(id, name);
}

function snackbar_DB (response) {
    var text = response.result;
    var color = response.success ? 'rgb(0,255,0)' : 'orange';
    snackbar(text, color)
}
function snackbar (text, color='white', size = 1) {
    var x = document.getElementById("snackbar"); // Get the snackbar DIV
        x.className = "show"; // Add the "show" class to DIV
        x.innerHTML =text;
        x.style.fontSize = size+"em"
        x.style.color = color;
        setTimeout(function(){  x.className = x.className.replace("show", ""); 	}, 3000); // After 3 seconds, remove the show class from DIV
}
