function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById("imgPlaceholder").setAttribute('src', e.target.result)
        }
        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

document.getElementById("chooseFile").onchange = function () {
        readURL(this);
        document.getElementById("btn_ImportLogo").classList.remove('d-none')    // Afficher le bouton de validation
}

function fermer() {
    document.getElementById("btCloseMenu").classList.add("animer")
    window.location.href = 'index.php';
}

function btAddTeam(nbcarTeam){
    var name = prompt("Nom de la nouvelle équipe ?")
    if (name == "" || name == null) { return }
    if (name.length > nbcarTeam ) {
        snackbar('ℹ️ Le nom ne doit pas dépasser '+nbcarTeam+' caractères', 'orange')
        return;
    }
    DB_CREATE_team(name);
}

function btDelTeam(id){
    if (confirm("Confirmer la suppression de l'équipe entière ?\nCette action est irréversible !")) {
        DB_DELETE_team(id);
    }
}

function changeName_MODIFTEAMDB(e, nbcarTeam, id){
    // console.log(e);
    newname = prompt('Nouveau nom ?',e.innerText);
    if (!newname) { return }
    if (newname.length > nbcarTeam ) {
        snackbar('ℹ️ Le nom ne doit pas dépasser '+nbcarTeam+' caractères', 'orange');
        return;
    }
    e.innerText = newname;
    DB_CHANGE_team_name(id, newname);
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
