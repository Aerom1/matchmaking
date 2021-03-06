// const { cp } = require('fs');
// const { create } = require('domain');
// const { cp } = require('fs');
// const { cp } = require('fs');

const imgSunride = "images/sunride.png";
const imgViking = "images/viking.png";
const emoForce1 = "π­";
const emoForce2 = "πΌ";
const emoForce3 = "π»";
//πΌπΊπ¦π―π­πΉπ»πΊπΆ


$(document).ready(function () {

    console.log("Hello world")
    
    // localStorage.clear();   console.log("SUPPRESSION LOCALSTORAGE----------");

    $("#div1").hide();
    $("#div2").hide();
    $("#div3").hide();

    changeTeam();
    creerDivPlus_();

    console.log("<<<<<<<<<<<< END >>>>>>>>>>>>")
});

function changeTeam(){

    var team1sun = {
        newName: "SUN",
        logoEquipe: imgSunride,
        newText: "Sun Ride"
    }
    var team2vik = {
        newName: "VIK",
        logoEquipe: imgViking,
        newText: "Viking" //π
    }

    console.log("change team")
    var teamActuelle = document.getElementById("team")

    // <h1 id="team" name="SUN" onClick="changeTeam()">π Sun Ride</h1>
    var newName
    var newText
    var team
    switch(teamActuelle.getAttribute("name")) {
        case "VIK": team = team1sun ; break;
        case "SUN": team = team2vik ; break;
        default: team = team1sun;
    }
    //team.innerHTML = '<h1 id="team" name="VIK" onClick="changeTeam()">π Viking</h1>' ; break;
    teamActuelle.setAttribute("name",team.newName);
    teamActuelle.innerText = team.newText;
    document.getElementById("logoEquipe").setAttribute("src",team.logoEquipe);
    document.getElementById("logoEquipe2").setAttribute("src",team.logoEquipe);

    console.log("New team : " + teamActuelle.getAttribute("name"));

    enleverTousJoueurs_();

    loadData(team.newName);
}

function compteJoueursDeLequipe_(team){
    const items = { ...localStorage };
    // console.log(items)
    var cpt=0
    for (let uid of Object.keys(items)) {
        // console.log(uid.substring(0,3));
        if (uid.substring(0,3)==team) {
            cpt++
        }      
    }
    console.log("compteJoueursDeLequipe_ " + team + " : " + cpt)
    return cpt
}

function loadData(team){
    console.log("Load Data pour : " + team)
    var nbPlayers = compteJoueursDeLequipe_(team);

    // if(localStorage.length<3){
    
    if(nbPlayers<3){

        // localStorage.clear();
        console.log("base vide ou presque ("+ localStorage.length +"): crΓ©ation de la base")
        console.log({ ...localStorage })

        var SUN = [
            [3,"Manu"],
            [3,"Yann"],
            [3,"Olivier"],
            [3,"Richard"],
            [3,"Philippe"],
            [2,"StΓ©phane"],
            [2,"Flo"],
            [2,"Laurent"],
            [2,"Romain"],
            [1,"Raphael"],
            [1,"MariLoL"],
            [1,"AurΓ©lie"],
            [1,"CΓ©line"],
            [1,"Laurence"],
            [2,"Mateo"]
        ]

        var VIK = [
            [3,"Fabien"],
            [3,"FranΓ§ois"],
            [3,"Manu"],
            [3,"Yann"],
            [3,"Richard"],
            [2,"StΓ©phane"],
            [2,"Flo"],
            [2,"Romain"],
            [1,"Raphael"],
            [2,"MariLoL"],
            [1,"AurΓ©lie"],
            [1,"CΓ©line"],
            [1,"Laurence"],
            [1,"Laure"],
        ]

        var equipe // joueurs par dΓ©faut dans l'equipe
        switch (team){
            case "SUN": equipe = SUN;break;
            case "VIK": equipe = VIK;break;
        }

        console.log("AJOUT DES JOUEURS DE L'EQUIPE "+ team)

        var date = (new Date()).toLocaleDateString();

        for (let i=0;i<equipe.length;i++) {
            var player={
                "team" : document.getElementById("team").getAttribute("name"),
                "force" :equipe[i][0],
                "absent":false,
                "name" :equipe[i][1]
            }
            var UID = team+"-PLAYER-"+player.name+"-"+date
            console.log("Ajout de: "+JSON.stringify(player) +" | UID:"+UID);
            // AJOUT DU JOUEUR DANS LE DOM :
            createPlayer_(equipe[i][0],equipe[i][1],false,UID)
            // STOCKAGE DU JOUEUR DANS LE COOKIE :
            localStorage.setItem(
                UID,
                JSON.stringify(player)
            );
        }

    } else {

        console.log("Base existante : chargement des donnΓ©es de ("+team+")")
        const items = { ...localStorage };
        // console.log(items)
        var logPlayersCreated=[]
        for (let uid of Object.keys(items)) {
            if (uid.substring(0,3)==team) {
                logPlayersCreated.push(uid + "/" + items[uid])
                var player = JSON.parse(items[uid])
                createPlayer_(player.force,player.name,player.absent,uid)
            }
        }
        // console.log(logPlayersCreated)

        // On met les joueurs inactifs Γ  la fin
        var logPlayersInactifs=[]
        var container = document.getElementById("div0")
        var joueurs = container.children
        console.log("tri de "+joueurs.length + " joueurs selon activitΓ©")
        for (let i = joueurs.length-1; i >= 0; i--) {
            //console.log(joueurs[i].innerText + " doit-il bouger ?")
            if (joueurs[i].classList.contains("inactif")) {
                logPlayersInactifs.push(joueurs[i].innerText + " est inactif: bouge Γ  la fin")
                container.appendChild(joueurs[i])   
            }
        }
        // console.log(logPlayersInactifs)

    }

    const boxes = document.querySelectorAll('.player');

    boxes.forEach(box => {
        box.addEventListener('dragenter', dragEnter)
        box.addEventListener('dragover', dragOver);
        box.addEventListener('dragleave', dragLeave);
    });

}

function btBack(){
    console.log("bouton BACK")
    enleveMenuTousJoueurs_();
    $("#questionPresents").slideDown();
    supprDivPlus_();
    $("#div1").hide();
    $("#div2").hide();
    $("#div3").hide();
    $("#btBack").hide(); 
    $("#btEquipes").hide(); 
    if ($(".selected").length>0) {unselect_($(".selected")[0])}


    reinit_();
    creerDivPlus_();
}

function reinit_() {
    console.log("reinit_")
    // $("#div0").show();
    var dispos = document.getElementById('div0');
    var equipe1 = document.getElementById('div1');
    var equipe2 = document.getElementById('div2');
    var equipe3 = document.getElementById('div3');
    var absents = document.getElementById('div9');
    while (equipe1.children.length) { dispos.appendChild(equipe1.firstChild); }
    while (equipe2.children.length) { dispos.appendChild(equipe2.firstChild); }
    while (equipe3.children.length) { dispos.appendChild(equipe3.firstChild); }
    while (absents.children.length) { dispos.appendChild(absents.firstChild); }
    // majForceEquipes_();
}

function creerDivPlus_(){
    var nouveau = document.createElement("div")
    nouveau.textContent = "+" //β
    nouveau.id = "divPlus"
    nouveau.setAttribute("onclick","clickAddPlayer()");
    document.getElementById('div0').prepend(nouveau);
}
function supprDivPlus_(){
    $("#divPlus").remove()
}

function btModifNbEquipes() {
    // $("#btEquipes").animate({"":""},1000)
    enleveMenuTousJoueurs_();
    var bouton = document.getElementById('btEquipes');
    var nbEquipe = bouton.getAttribute("nb");
    //var txtBtEq = bouton.innerText;
    
    if (nbEquipe==2){
        nbEquipe = 3;
    }else{
        nbEquipe = 2;
    }
    console.log("NB EQUIPE : " + nbEquipe)
    bouton.setAttribute("nb",nbEquipe);
    RANDOM(nbEquipe)
}

function btRandom(){
    var bouton = document.getElementById('btEquipes');
    var nbEquipe = bouton.getAttribute("nb");   
    $("#btBack").show(); 
    $("#btEquipes").show(); 

    if (nbEquipe==0) {
        // Pas de nombre d'Γ©quipe choisi -> selon nombre de joueurs
        var nbJoueurs = document.getElementsByClassName("player").length - document.getElementsByClassName("inactif").length
        //if(txtBtEq=="π©π»βπ€βπ©πΏπ§π½βπ€βπ§π»"){nbEquipe=3}else{nbEquipe=2}
        if(nbJoueurs>=12){
            nbEquipe=3;
        }else{
            nbEquipe=2
        }
        console.log(nbJoueurs + " JOUEURS : " + nbEquipe + " equipes !")
    }
    RANDOM(nbEquipe);
}

function RANDOM(nbEquipe) {
    console.log("=============dΓ©but random");
    document.getElementById("questionPresents").style.display = "none"
    // $("#btRandom").animate({
    //     height:"10px",
    //     width: "+=100px",
    //     opacity: 0.5
    // }, 1000)

    supprDivPlus_();
    
    enleveMenuTousJoueurs_();
    var dispos = document.getElementById('div0');
    var equipe1 = document.getElementById('div1');
    var equipe2 = document.getElementById('div2');
    var equipe3 = document.getElementById('div3');
    var absents = document.getElementById('div9');
    var txtBtEq = document.getElementById('btEquipes').innerText;
    //console.log("txtBtEq:"+txtBtEq.substring(txtBtEq.length-2))

    
    //parseInt(txtBtEq.substring(txtBtEq.length-2));
    console.log("nbEquipe:"+nbEquipe);
    var ecart = 0, ecartmax = 0, difference = 0;
    do {
        reinit_();
        // supprDivPlus_();
        var forceEq1 = 0, forceEq2 = 0, forceEq3 = 0;
        var bascule = 0;
        while (dispos.children.length) {
            var i = getRandomInt_(dispos.children.length);
            var player = dispos.children[i];
            var force = parseInt(player.getAttribute("force"));
            // console.log(player.innerText+" va bouger...");
            if (player.classList.contains("inactif")) {
                absents.appendChild(player);
                continue;
            }
            //console.log("choix:"+i+" /"+dispos.children.length+' ('+bascule+')')
            // console.log("bascule:"+bascule +" mod:"+(bascule%3))
            switch (bascule%nbEquipe) {
                case 0:
                    movePlayer_(player, force, equipe1);
                    forceEq1 += parseInt(force);
                    break;
                case 1:
                    movePlayer_(player, force, equipe2);
                    forceEq2 += parseInt(force);
                    break;
                case 2:
                    movePlayer_(player, force, equipe3);
                    forceEq3 += parseInt(force);
                    break;
            }
            bascule++;
        }
        ecartmax = Math.round(0.1 * (forceEq1 + forceEq2 + forceEq3) /nbEquipe, 2); //10% de diffΓ©rence de force max
        var max = Math.max(forceEq1,forceEq2,forceEq3);
        var min = 0;
        if (nbEquipe==2) {
            min = Math.min(forceEq1,forceEq2);
        } else {
            min = Math.min(forceEq1,forceEq2,forceEq3);
        }
        difference = max-min;
        var resul 
        if (difference > ecartmax) { resul="--> ON RECOMMENCE";
        } else { resul="--> ON ACCEPTE"; }
        console.log("ecartmax:"+ecartmax+", diff:"+difference+", RΓ©partition: "+forceEq1+" / "+forceEq2+" / "+ forceEq3 + resul);

    } while (difference > ecartmax);
    majForceEquipes_();
   
    // $("#div0").hide();
    $("#div1").show();
    $("#div2").show();
    
    // console.log("NB JOUEURS EQ 3 : "+ equipe3.children.length)
    if(equipe3.children.length){
        $("#div3").show();
    }else{
        $("#div3").hide();
    }

}

function save(){
    const pool  = mysql.createPool({
        connectionLimit : 10,
        host     : 'localhost',
        user     : 'Admin',
        password : '/b%E?Q)0/ossF4GL',
        database : 'id18677336_players'
    });

    // Get all beers
    app.get('', (req, res) => {
        pool.getConnection((err, connection) => {
            if(err) throw err;
            console.log('connected as id ' + connection.threadId);
            connection.query('SELECT * from players', (err, rows) => {
                connection.release(); // return the connection to pool

                if (!err) {
                    res.send(rows);
                } else {
                    console.log(err);
                }

                // if(err) throw err
                console.log('The data from players table are: \n', rows);
            });
        });
    });

}

function save2(){
    // Get the mysql service
var mysql = require('mysql');

// Add the credentials to access your database
var connection = mysql.createConnection({
    host     : 'localhost',
    user     : 'Admin',
    password : '/b%E?Q)0/ossF4GL',
    database : 'id18677336_players'
});

// connect to mysql
connection.connect(function(err) {
    // in case of error
    if(err){
        console.log(err.code);
        console.log(err.fatal);
    }
});

// Perform a query
$query = 'SELECT * from players LIMIT 10';

connection.query($query, function(err, rows, fields) {
    if(err){
        console.log("An error ocurred performing the query.");
        return;
    }

    console.log("Query succesfully executed: ", rows);
});

// Close the connection
connection.end(function(){
    // The connection has been closed
});


}

function clickAddPlayer() {
    enleveMenuTousJoueurs_();
    var name = prompt("Nom du joueur :")
    if (name == "" || name == null) { return }
    var force = prompt("Force du joueur de 1 (faible) Γ  3 (fort)")
    if (force == "" || force == null) { return }
    var intForce = parseInt(force)
    console.log(intForce)
    if ((intForce <= 0) || (intForce > 3) || isNaN(intForce)) {
        alert("La force du joueur doit Γͺtre comprise entre 1 et 3 (vous avez Γ©crit " + force + ")");
        return;
    }
    var team = document.getElementById("team").getAttribute("name");
    var player = {
        "team":team,
        "name":name,
        "absent":false,
        "force":intForce}
    var date = (new Date()).toLocaleDateString();
    var UID = team+"-PLAYER-"+player.name+"-"+date
    console.log("Ajout de: "+JSON.stringify(player) +" | UID:"+UID);
    localStorage.setItem(
        UID,
        JSON.stringify(player)
        );
    createPlayer_(intForce, name, false, UID)
}

function createPlayer_(force, name, inactif, UID) {
    var img, opacity,emo
    switch (force) {
        case 1: emo = emoForce1; break;
        case 2: emo = emoForce2; break;
        case 3: emo = emoForce3; break;
    }

    // var playerId = "player" + name.replace(" ", "") + force
    var nouveau = $("#div0")
        .append($('<div></div>')
            .attr({ id: UID, force: force, 'name':UID, ondragstart: "drag(event)", draggable: "true", onclick: "select(this)" })
            .addClass("player")
            .prepend($('<span ></span>') // .prepend($('<img src=' + img + ' style="filter: opacity(' + ((force - 1) * 20) + '%)" ></img>')
                .addClass("emoforce")
                .attr({onclick: ""})
                .text(emo)
            )
            .append($('<span></span>')
                .text(name)
            )
            // .prepend($('<img src='+img+'></img>') // .prepend($('<img src=' + img + ' style="filter: opacity(' + ((force - 1) * 20) + '%)" ></img>')
            //     .addClass("imgforce")
            //     .addClass("force"+force)
            // )
        );
    if (inactif) {
        document.getElementById(UID).classList.add("inactif")
        //console.log("AJOUT CLASSE INACTIF Γ  : "+name)
    }
    
}


function getRandomInt_(max) {
    return Math.floor(Math.random() * max);
}

function movePlayer_(player, force, equipe) {
    var nbEnfants = equipe.children.length
    if (nbEnfants>0) {
        //console.log("equip1 : "+ nbEnfants + " joueurs. DΓ©but parcours equipe")
        var sortie = false
        for (let j=0 ; j<nbEnfants; j++){
            //console.log(equipe.children[j])
            if (force >= equipe.children[j].getAttribute("force")){
                //console.log("force Child:"+equipe.children[j].getAttribute("force"))
                // console.log(equipe.id + " reΓ§oit "+player.innerText+" ("+force+") avant "+equipe.children[j].innerText+" ("+equipe.children[j].getAttribute("force")+")")
                equipe.insertBefore(player,equipe.children[j]);
                sortie = true;
                break;
            }
        }
        if (!sortie){equipe.appendChild(player);}
    } else {
        // console.log(equipe.id + " vide, ajout de "+player.innerText+" ("+force+")")
        equipe.appendChild(player);
    }
}

function majForceEquipes_() {
    console.log("majForceEquipes_")
    var forceEq1 = 0, forceEq2 = 0, forceEq3 = 0, forceDispos = 0;
    var joueurs = $(".player")
    for (let i = 0; i < joueurs.length; i++) {
        force = joueurs[i].getAttribute("force");
        nom = joueurs[i].id;
        switch (joueurs[i].parentNode.id) {
            case "div0": forceDispos += parseInt(force); break;
            case "div1": forceEq1 += parseInt(force); break;
            case "div2": forceEq2 += parseInt(force); break;
            case "div3": forceEq3 += parseInt(force); break;
        }
    }

    $("#forceEq1").text(forceEq1);
    $("#forceEq2").text(forceEq2);
    $("#forceEq3").text(forceEq3);

    if (forceEq1==0)  { $("#forceEq1").css({"display":"none"});
        } else        { $("#forceEq1").css({"display":"inline-block"}); }
    if (forceEq2==0)  { $("#forceEq2").css({"display":"none"});
        } else        { $("#forceEq2").css({"display":"inline-block"}); }
    if (forceEq3==0)  { $("#forceEq3").css({"display":"none"});
        } else        { $("#forceEq3").css({"display":"inline-block"}); }
    
    // $("#forceEq2").css({"display":"inline-block"});
    // if (nbEquipe=3){
    //     $("#forceEq3").css({"display":"inline-block"});
    // }else {
    //     $("#forceEq3").css({"display":"none"});
    // }

    // $("#forceEq0").text("(dispo) [" + forceDispos + "]");
}

function unselect_(player){
    $(player).removeClass("selected"); //dΓ©selectionne
    $(".containerOptionsJoueur").slideUp()
}

function clickIcone(clicked) {
    console.log("Click Icone : modif du joueur");

    $("#divOptionsJoueurs").remove();
    console.log(clicked.children)

    if(clicked.classList.contains("menu")){
        console.log(clicked.children)
        console.log("menu existant : suppression")
        clicked.classList.remove("menu")
        $(clicked.children[2]).remove();
        return
    }
    console.log("le menu n'existe pas")
    clicked.classList.add("menu")

    var tree = document.createDocumentFragment();
    var containerBt = document.createElement("div");
    containerBt.setAttribute("id", "divOptionsJoueurs");
    containerBt.style.display = "inline";

    var hr = document.createElement("br")
    // hr.style.margin = "0px"
    containerBt.append(hr);

    var newBt4 = document.createElement("button"); // FORCE 1
    newBt4.setAttribute("id", "emoForce1");
    newBt4.classList.add("btModifForce");
    newBt4.textContent = emoForce1
    containerBt.append(newBt4)

    var newBt2 = document.createElement("button"); // FORCE 2
    newBt2.setAttribute("id", "emoForce2");
    newBt2.classList.add("btModifForce");
    newBt2.textContent = emoForce2
    containerBt.append(newBt2)

    var newBt3 = document.createElement("button"); // FORCE 3
    newBt3.setAttribute("id", "emoForce3");
    newBt3.classList.add("btModifForce");
    newBt3.textContent = emoForce3
    containerBt.append(newBt3)

    var hr = document.createElement("br")
    // hr.style.margin = "0px"
    containerBt.append(hr);

    var newBt1 = document.createElement("button"); // MODIF NOM
    newBt1.textContent = "βοΈ"
    newBt1.classList.add("btModifNom");
    //newBt1.style.backgroundColor = "lightgreen"
    // newBt1.style.border = "0px"
    // newBt1.style.marginLeft = "3px"
    // newBt1.style.fontSize = "1.2rem"
    containerBt.append(newBt1)

    var newBt6 = document.createElement("button");  // SUPPR JOUEUR
    newBt6.textContent = "β"
    newBt6.classList.add("btSupprPlayer");
    containerBt.append(newBt6)

    var newBt5 = document.createElement("button");  // CACHE MENU TRIANGLE
    newBt5.textContent = "πΊ"
    newBt5.classList.add("btCloseMenu");
    containerBt.append(newBt5)


    tree.appendChild(containerBt)
    clicked.appendChild(tree);
}

function clickBtForce(clicked) {
    var emoForce = window.event.target.textContent;
    console.log("Click Bouton force: "+emoForce);
    var emo, force
    switch (emoForce) {
        case emoForce1: force = 1; break;
        case emoForce2: force = 2; break;
        case emoForce3: force = 3; break;
    }
    // STOCKAGE DANS LE DOM :
    var player = clicked;
    player.setAttribute("force",force);
    player.firstChild.innerText = ""+emoForce;
    // STOCKAGE DANS EN LOCAL :
    localStorage.setItem(
        player.getAttribute("name"),
        JSON.stringify({
            "team":document.getElementById("team").getAttribute("name"),
            "force":force,
            "absent": player.classList.contains("inactif"),
            "name":player.children[1].innerText
        })
        );
    console.log(player.children[1].innerText+" a une nouvelle force: "+player.getAttribute("Force") + ' ' +emoForce)
    // Suppression du menu
    $(clicked.children[2]).remove();
    clicked.classList.remove("menu")
}

function clickBtNom(clicked) {
    var player = clicked
    var nouveauNom = prompt("Nouveau nom ?",player.children[1].innerText)
    if (nouveauNom == "" || nouveauNom == null) { 
        $(clicked.children[2]).remove();
        clicked.classList.remove("menu")
        return }
    console.log("changment de nom: "+player.innerText+" -> "+nouveauNom)
    // STOCKAGE DANS LE DOM :
    player.children[1].innerText = nouveauNom
    // STOCKAGE DANS LE COOKIE :
    localStorage.setItem(
        player.getAttribute("name"),
        JSON.stringify({
            "team":document.getElementById("team").getAttribute("name"),
            "force":parseInt(player.getAttribute("force")),
            "absent": player.classList.contains("inactif"),
            "name":nouveauNom
        })
    );
    // Suppression du menu
    $(clicked.children[2]).remove();
    clicked.classList.remove("menu")
}

function clickSwitchInactif(clicked) {       
    // ACTION DANS LE DOM :
    $(clicked).toggleClass("inactif");
    console.log(clicked.innerText+ " change statut absent Γ : "+clicked.classList.contains("inactif"))
    // STOCKAGE DANS LE COOKIE :
    var playerJson = {
        "team":document.getElementById("team").getAttribute("name"),
        "force": parseInt(clicked.getAttribute("force")),
        "absent":clicked.classList.contains("inactif"),
        "name":clicked.children[1].innerText,
    }
    localStorage.setItem(
        clicked.getAttribute("name"),
        JSON.stringify(playerJson)
    );
    clicked.parentNode.appendChild(clicked);
}

function select(clicked) {

    console.log(clicked);
    console.log("^-------------------------^");

    // CLIQUE DE L'ICONE DE FORCE - affichage du menu
    if(window.event.target.classList.contains("emoforce")) {
        clickIcone(clicked);
        return;
    }

    // CLIQUE DU BOUTON FORCE - modif force
    if(window.event.target.classList.contains("btModifForce")) {
        clickBtForce(clicked);
        majForceEquipes_()
        return;
    }
    // CLIQUE DU BOUTON NOM - modif nom
    if(window.event.target.classList.contains("btModifNom")) {
        clickBtNom(clicked);
        return;
    }
    // CLIQUE DU BOUTON CLOSE - ferme le menu
    if(window.event.target.classList.contains("btCloseMenu")) {
        $(clicked.children[2]).remove();
        return;
    }
    // CLIQUE DU BOUTON SUPPR - supprime le joueur
    if(window.event.target.classList.contains("btSupprPlayer")) {
        if (clicked.parentNode.id == "div0") {
            if (confirm("Supprimer dΓ©finitivement ?")) {    
                $(clicked).remove();
                localStorage.removeItem(clicked.id)
            }
            
        } else {
            $(clicked.children[2]).remove();
            clicked.classList.remove("menu")
            document.getElementById("div9").appendChild(clicked);
            clicked.classList.add("inactif");
            majForceEquipes_();
        }
        return;
    }

    enleveMenuTousJoueurs_();

    // gΓ¨re les prΓ©sents/absents dans la div joueursDispos (div0)
    if (clicked.parentNode.id=="div0"){
        clickSwitchInactif(clicked);
        return;
    } 
    
    clickAfficheMenuOLD(clicked);
 
}

function enleverTousJoueurs_(){
    console.log("Suppression de TOUS les joueurs")
    var players = document.getElementsByClassName("player")
    while (players.length>0) {
        // console.log("suppression de "+players[0].innerText)
        $(players[0]).remove();
    }
}

function enleveMenuTousJoueurs_(){
    var players = document.getElementsByClassName("player")
    for (let i=0 ; i<players.length ; i++) {
        $(players[i].children[2]).remove();
        players[i].classList.remove("menu")
    }
}

function clickAfficheMenuOLD(clicked){
    
    // gΓ¨re la sΓ©lection dans les Γ©quipes
    var selection = $(".selected")
    // VΓ©rifique que le joueur cliquΓ© n'est pas le mΓͺme que le joueur sΓ©lectionnΓ©
    if (clicked !== selection[0]) { 
        console.log("SΓ©lection");
        $(".containerOptionsJoueur").slideDown()
        if (selection.length == 0) {
            // S'il n'y avait pas de joueur sΓ©lectionnΓ©, on sΓ©lectionne (classe selected)
            $(clicked).toggleClass("selected")
        } else {
            // S'il y a dΓ©jΓ  un joueur sΓ©lectionnΓ©, on Γ©change les deux
            var cliquedContainer = clicked.parentNode;
            var selectedContainer = selection[0].parentNode;
            var destination = selection[0].nextSibling
            cliquedContainer.insertBefore(selection[0], clicked);
            selectedContainer.insertBefore(clicked, destination);
            console.log("inverse "+selection[0].innerText+" avec "+clicked.innerText)
            unselect_(selection[0]); // DΓ©sΓ©lectionne
            majForceEquipes_()
        }
    } else { 
        // Pour dΓ©sΓ©lectionner :
        console.log("Annule la sΓ©lection");
        //$(clicked).toggleClass("selected");
        unselect_(clicked);
    }
}

function oldBtModifForce(){
    var player = document.getElementsByClassName("selected")[0]
    console.log("ancienne force: "+player.getAttribute("Force") + player.firstChild.innerText)
    var force = prompt("Force du joueur de 1 (faible) Γ  3 (fort)")
    if (force == "" || force == null) { return }
    var intForce = parseInt(force)
    if ((intForce < 1) || (intForce > 3)) {
        alert("La force du joueur doit Γͺtre comprise entre 1 et 3 (vous avez Γ©crit " + force + ")");
        return;
    }
    var emo, img
    switch (intForce) {
        case 1: emo = emoForce1; break;
        case 2: emo = emoForce2; break;
        case 3: emo = emoForce3; break;
    }

    // STOCKAGE DANS LE DOM :
    player.setAttribute("force",intForce);
    player.firstChild.innerText = ""+emo;
    var team = document.getElementById("team").getAttribute("name");
    // STOCKAGE DANS EN LOCAL :
    localStorage.setItem(
        player.getAttribute("name"),
        JSON.stringify({
            "team":team,
            "force":intForce,
            "absent": player.classList.contains("inactif"),
            "name":player.children[1].innerText
        })
        );

    unselect_(player);
    console.log(player.innerText+" a une nouvelle force: "+player.getAttribute("Force") + ""+emo)
}

function oldBtModifNom(){
    var player = document.getElementsByClassName("selected")[0]
    var team = document.getElementById("team").getAttribute("name");
    var nouveauNom = prompt("Nouveau nom ?",player.children[1].innerText)
    if (nouveauNom == "" || nouveauNom == null) { return }
    console.log("changment de nom: "+player.innerText+" -> "+nouveauNom)
    // STOCKAGE DANS LE DOM :
    player.children[1].innerText = nouveauNom
    // STOCKAGE DANS LE COOKIE :

    localStorage.setItem(
        player.getAttribute("name"),
        JSON.stringify({
            "team":team,
            "force":parseInt(player.getAttribute("force")),
            "absent": player.classList.contains("inactif"),
            "name":nouveauNom
        })
        );
}