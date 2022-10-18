// const { cp } = require('fs');
// const { create } = require('domain');
// const { cp } = require('fs');
// const { cp } = require('fs');
// const { cp } = require('fs');
// const { cp } = require('fs');
// const { cp } = require('fs');

//test new branch

{/* <script src="appel_server.js"></script> */}


const imgSunride = "images/sunride.png";
const imgViking = "images/viking.png";
const emoForce1 = "🐭";
const emoForce2 = "😼";
const emoForce3 = "🐻";
//🐭😼🐻🦁🐯🐹😺😺🐶


// function hideAll(){
//     if ($("#div0").is(":visible")) {
//         $("#div0").hide();
//         $("#div1").hide();
//         $("#div2").hide();
//         $("#div3").hide();
//     } else {
//         $("#div0").show();
//         $("#div1").hide();
//         $("#div2").hide();
//         $("#div3").hide();
//     }
// }


function loadTeam(team, players){
    console.log("load team : " + team.name)

    // document.getElementById("logoEquipe2").setAttribute("src",team.logo); // logo de l'appli (header)
    document.getElementById("logoEquipe1").setAttribute("src",team.logo); // logo du bouton (random)
    var teamActuelle = document.getElementById("team")
        teamActuelle.setAttribute("name", team.id);
        teamActuelle.innerText = team.name;

    $("#divPlus").remove();
    enleverTousJoueurs_();

    loadData(team, players);

    majForceEquipes_();
    majNbJoueurs_();
    creerDivPlus_();

    $("#div0").show();
    $("#div1").hide();
    $("#div2").hide();
    $("#div3").hide();
    $(".containerEquipes").css({"display":"grid"})
    $(".container").css({"width":"100%"})
    $(".cible").remove();
    $("#questionPresents").slideDown();

}

function loadData(team, players){
    console.log("Load Data pour " + team.name + " (" + players.length + " joueurs)");

    var logPlayersCreated=[]
    players.forEach( function(player) {
        logPlayersCreated.push(player.name + " (xp:" + player.xp +")" + (player.absent==1 ? " -> inactif":""))
        createPlayer_(Number(player.xp), player.name, Number(player.absent), Number(player.id))
    })
    console.log(logPlayersCreated)

    // On met les joueurs inactifs à la fin
    var logPlayersInactifs=[]
    var container = document.getElementById("div0")
    var joueurs = container.children
    console.log("tri de "+joueurs.length + " joueurs selon présence")
    for (let i = joueurs.length-1; i >= 0; i--) {
        //console.log(joueurs[i].innerText + " doit-il bouger ?")
        if (joueurs[i].classList.contains("inactif")) {
            logPlayersInactifs.push(joueurs[i].innerText + " est inactif: bouge à la fin")
            container.appendChild(joueurs[i])   
        }
    }
    const boxes = document.querySelectorAll('.player');
    boxes.forEach(box => {
        box.addEventListener('dragenter', dragEnter)
        box.addEventListener('dragover', dragOver);
        box.addEventListener('dragleave', dragLeave);
    });
}


function changeTeam(){
    console.log("changeTeam()")

    $("#div0").show();
    $("#div1").hide();
    $("#div2").hide();
    $("#div3").hide();
    $(".containerEquipes").css({"display":"grid"})
    $(".container").css({"width":"100%"})
    
    var teamActuelleId = document.getElementById("team").getAttribute('name')
    var newTeamIndex
    var nbEquipes = Object.keys(all_teams).length

    for (i=0 ; i<nbEquipes ; i++){
        console.log("all_teams[i].id == team.id ?  ->  " + all_teams[i].id + " == " + teamActuelleId)
        if (all_teams[i].id == teamActuelleId) {
            if (i == nbEquipes-1) {
                newTeamIndex = 0
            } else {
                newTeamIndex = i+1
            }
            console.info("nouvelle équipe trouvée" )
            break;
        }
    }
    var team = all_teams[newTeamIndex]
    var players = Object.values(all_players).filter(item => item.team === team.id)

    loadTeam(team, players)
}


function btAddTeam(){

    enleveMenuTousJoueurs_();
    var name = prompt("Nom de la nouvelle équipe ?")
    if (name == "" || name == null) { return }
    changeTeam()
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


function btBack(){
    console.log("bouton BACK")
    enleveMenuTousJoueurs_();
    $("#questionPresents").slideDown();
    $("#divPlus").remove();
    $("#btChgTeam").show();
    $("#btAddTeam").show();
    $("#div0").show();
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
    $(".cible").remove();
    $(".containerEquipes").css({"display":"grid"})
    $(".container").css({"width":"100%"})
    var dispos = document.getElementById('div0');
    var equipe1 = document.getElementById('div1');
    var equipe2 = document.getElementById('div2');
    var equipe3 = document.getElementById('div3');
    var absents = document.getElementById('div9');
    while (equipe1.children.length) { dispos.appendChild(equipe1.firstChild); }
    while (equipe2.children.length) { dispos.appendChild(equipe2.firstChild); }
    while (equipe3.children.length) { dispos.appendChild(equipe3.firstChild); }
    while (absents.children.length) { dispos.appendChild(absents.firstChild); }
    majForceEquipes_();
    majNbJoueurs_();
}

function creerDivPlus_(){
    var nouveau = document.createElement("div")
    nouveau.textContent = "➕" //➕🆕
    nouveau.id = "divPlus"
    nouveau.setAttribute("onclick","clickAddPlayer()");
    // nouveau.setAttribute("class","player");
    document.getElementById('div0').prepend(nouveau);
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

    var nbEquipe = document.getElementById('btEquipes').getAttribute("nb");   
    $("#btBack").show(); 
    $("#btEquipes").show(); 
    $("#btChgTeam").hide();
    $("#btAddTeam").hide();

    if (nbEquipe==0) {
        // Pas de nombre d'équipe choisi -> selon nombre de joueurs
        var nbJoueurs = document.getElementsByClassName("player").length - document.getElementsByClassName("inactif").length
        //if(txtBtEq=="👩🏻‍🤝‍👩🏿🧑🏽‍🤝‍🧑🏻"){nbEquipe=3}else{nbEquipe=2}
        if(nbJoueurs>=12){
            nbEquipe=3;
        }else{
            nbEquipe=2
        }
        document.getElementById('btEquipes').setAttribute("nb",nbEquipe);
        console.log(nbJoueurs + " JOUEURS : " + nbEquipe + " equipes !")
    }
    RANDOM(nbEquipe);
}

function RANDOM(nbEquipe) {
    console.log("=============début random");
    document.getElementById("questionPresents").style.display = "none"
    // $("#btRandom").animate({
    //     height:"10px",
    //     width: "+=100px",
    //     opacity: 0.5
    // }, 1000)

    $("#divPlus").remove();

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
    var ecart = 0.1 //10% de différence de force max
    var ecartmax = 0, difference = 0; compteur = 0
    do {
        compteur+=1
        reinit_();
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
        if (compteur % 3 == 0) { 
            ecart *= 1.1 // si on ne trouve pas au bout de 3 essais, on augmente la marge d'erreur de 10% et on réessaie 3 fois...
            console.log("AUGMENTATION DE L'ECART MAX APRES 3 ESSAIS INFRUCTUEUX : " + ecart)
        }
        
        ecartmax = Math.round(ecart * (forceEq1 + forceEq2 + forceEq3) /nbEquipe, 2); //10% de différence de force max
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

        console.log("ecartmax:"+ecartmax+", diff:"+difference+", Répartition: "+forceEq1+" / "+forceEq2+" / "+ forceEq3 + resul);

    } while (difference > ecartmax);
    majForceEquipes_();
   
    $("#div0").hide();
    $("#div1").show();
    $("#div2").show();
    
    // console.log("NB JOUEURS EQ 3 : "+ equipe3.children.length)
    if(equipe3.children.length){
        $("#div3").show();
        $(".containerEquipes").css({"display":"grid"})
        $(".container").css({"width":"100%"})
    }else{
        $("#div3").hide();
        $(".containerEquipes").css({"display":"flex"})
        $(".container").css({"width":"49%"})

    }

    var j = {}
    var players = document.getElementsByClassName('player')
    // players.forEach(p => t[p.getAttribute('id')] = p.classList.contains('inactif'))

    Array.prototype.forEach.call(players, p => {
        j[p.getAttribute('id')] = p.classList.contains('inactif') ? 1:0
    })

    // players.forEach( function(player) {
        // t[p.getAttribute('id')] = p.classList.contains('inactif')
        // logPlayersCreated.push(player.name + " (xp:" + player.xp +")" + (player.absent==1 ? " -> inactif":""))
        // createPlayer_(Number(player.xp), player.name, Number(player.absent), Number(player.id))
    // })

    DB_changePlayersInactifs(j)

}




function creerMenuJoueur(name) {

    var containerBt = document.createElement("div");
        containerBt.setAttribute("id", "divOptionsJoueurs");
        containerBt.setAttribute("class", "divOptionsJoueurs");
        containerBt.style.display = "inline";
        containerBt.style.backgroundColor = "rgba(0,0,0, 0.8)"
        containerBt.style.borderRadius = "20px"
        containerBt.style.border = "2px solid grey"
        containerBt.style.boxShadow = "rgba(0, 0, 0, 0.56) 0px 0px 70px 10px"
        containerBt.style.position = "absolute";
        containerBt.style.top = "0";
        containerBt.style.bottom = "0";
        containerBt.style.left = "0";
        containerBt.style.right = "0";
    var btCloseMenu = document.createElement("span")   // BOUTON FERMER
        btCloseMenu.innerText = '❌';
        btCloseMenu.setAttribute("onclick", "this.parentNode.remove()");
        btCloseMenu.style.right = "5%";
        btCloseMenu.style.top = "5%";
        btCloseMenu.style.position = "absolute";
        btCloseMenu.style.fontSize = "30px";
        containerBt.append(btCloseMenu);
    var nom = document.createElement("span")       // AFFICHAGE NOM
        nom.innerText = name;
        nom.setAttribute("id", "newPlayerName");
        nom.classList.add("btModifNom");
        nom.style.color = "white"
        nom.style.top = "25%";
        nom.style.position = "relative";
        // nom.style.display = "block"
        containerBt.append(nom);
    var frxp = document.createElement("div");
        frxp.style.position = "absolute"
        frxp.style.top = "45%"
        frxp.style.width = "-moz-available"
    var newBt4 = document.createElement("button"); // FORCE 1
        newBt4.setAttribute("id", "emoForce1");
        // newBt4.classList.add("btModifForceAddplayer");
        newBt4.classList.add("btChooseXP");
        newBt4.textContent = emoForce1
        frxp.append(newBt4)
    var newBt2 = document.createElement("button"); // FORCE 2
        newBt2.setAttribute("id", "emoForce2");
        newBt2.classList.add("btChooseXP");
        newBt2.textContent = emoForce2
        frxp.append(newBt2)
    var newBt3 = document.createElement("button"); // FORCE 3
        newBt3.setAttribute("id", "emoForce3");
        newBt3.classList.add("btChooseXP");
        newBt3.textContent = emoForce3
        frxp.append(newBt3)
    var hr = document.createElement("br")
        containerBt.append(hr);
        //✅ 🈯️ 💹 ❇️ ✳️ ❎❌    
    containerBt.appendChild(frxp)

    return containerBt
}

function clickAddPlayer() {
    enleveMenuTousJoueurs_();
    var name = prompt("Nom du nouveau joueur ?")
    if (name == "" || name == null) { return }
    
    // On vérifie qu'un joueur ne porte pas déjà ce nom
    var players = document.getElementsByClassName('player')
    for (const player of players) {
        if (player.getAttribute('name') == name) {
            // Affichage du pop (snackbar)
            var x = document.getElementById("snackbar"); // Get the snackbar DIV
                x.className = "show"; // Add the "show" class to DIV
                x.innerHTML = name + " existe déjà !";
                x.style.color = 'orange'
                setTimeout(function(){ 
                        x.className = x.className.replace("show", ""); 
                    }, 3000); // After 3 seconds, remove the show class from DIV
            return;
        }
    }

    // clicked.classList.add("menu")
    // var pos = clicked.getBoundingClientRect();

    var containerBt = creerMenuJoueur(name)
    var tree = document.createDocumentFragment();
        tree.appendChild(containerBt)
    document.getElementById("containerEquipes").appendChild(tree);
    document.getElementById('newPlayerName').setAttribute("onclick", "changeName_NEWPLAYER(this)");
    var btsXP = document.getElementsByClassName('btChooseXP')
    Array.prototype.forEach.call(btsXP, bt => 
        bt.setAttribute("onclick", "clickBtMenuAddPlayer(this)")
    )
}

function changeName_MODIFPLAYER(e) {
    console.log(e)
    newname = prompt('Nouveau nom ?',e.innerText)
    if (newname) {
        e.innerText = newname
        id = e.parentNode.parentNode.id
        DB_changePlayerNAME(id, newname)
        e.parentNode.parentNode.name = newname
        e.parentNode.parentNode.children[1] = newname
    }

}

function changeName_NEWPLAYER(e) {
    newname = prompt('Nouveau nom ?',e.innerText)
    if (newname) {
        e.innerText =newname
    }
}


function clickBtMenuAddPlayer(clicked){
    console.log(clicked)
    menu = clicked.parentNode.parentNode

    var emoForce = clicked.getAttribute("id")
    switch (emoForce) {
        case 'emoForce1': force = 1; break;
        case 'emoForce2': force = 2; break;
        case 'emoForce3': force = 3; break;
        default: force = 0;
    }

    if(force!==0){
        console.log("force:"+force+" | emoForce:"+emoForce)

        var team = document.getElementById("team").getAttribute("name");
        var name = document.getElementById("newPlayerName").textContent;
        
        var player = createPlayer_(force, name, 0)
        DB_createPlayer(force, name, team, player)
			
    }
    // Suppression du menu
    menu.remove()
    // $(clicked.children[2]).remove();
    // clicked.remove("menu");

    majNbJoueurs_();
}


function createPlayer_(force, name, absent, id) {
    // console.log("Création de mr. " + name + " (force " + force + ") absent : " + absent)
    
    var emo
    switch (force) {
        case 1: emo = emoForce1; break;
        case 2: emo = emoForce2; break;
        case 3: emo = emoForce3; break;
    }

    var p = document.createElement('div')
        // p.addEventListener('click' , function(){clickPlayerName(p)})
        p.setAttribute('draggable' , "true")
        p.setAttribute('ondragstart', 'drag(event)')
        // p.ondragstart = drag(event)
        // p.addEventListener('dragstart', drag(event))
        p.setAttribute('id' , id)
        p.setAttribute('force' , force)
        p.setAttribute('name' , name)
        p.classList.add("player");
        if (absent) { p.classList.add("inactif"); }
    var x = document.createElement('span')
        x.innerText = emo
        x.classList.add('emoforce')
        // x.addEventListener('click' , "clickPlayerIcon(this)")
        x.addEventListener('click' , function(){clickPlayerIcon(p)})
    var n = document.createElement('span')
        n.classList.add('playerName')
        n.innerText = name.substring(0,13)
        n.addEventListener('click' , function(){clickPlayerName(p)})
        
        p.append(x)
        p.append(n)
    
    document.getElementById('div0').appendChild(p)

    return p
}

function clickPlayerName(p){
    // console.log(p)

    if (p.parentNode.id == 'div0') {
        // SI ECRAN D'ACCUEIL : le joueur passe actif/inactif
        p.classList.toggle('inactif')
        console.log(p.getAttribute('name') + " change statut absent à: "+p.classList.contains("inactif"))
        p.parentNode.appendChild(p);
        majNbJoueurs_();
    } else {
        // SI ECRAN DES EQUIPES : le joueur est sélectionné pour un échange
        ecranEquipe_SELECTPLAYER(p)
    }
    // parentNode.toggleClass('inactif')
    // console.log("clickPlayerName")
    // console.log(p)
    // console.log('window.event.target')
    // console.log(window.event.target)
    
    // if (window.event.target.classList.contains('emoforce')) {return}

    // alert("HELLO")
}


function movePlayer_(player, force, equipe) {
    var nbEnfants = equipe.children.length
    if (nbEnfants>0) {
        //console.log("equip1 : "+ nbEnfants + " joueurs. Début parcours equipe")
        var sortie = false
        for (let j=0 ; j<nbEnfants; j++){
            //console.log(equipe.children[j])
            if (force >= equipe.children[j].getAttribute("force")){
                //console.log("force Child:"+equipe.children[j].getAttribute("force"))
                // console.log(equipe.id + " reçoit "+player.innerText+" ("+force+") avant "+equipe.children[j].innerText+" ("+equipe.children[j].getAttribute("force")+")")
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


function unselect_(player){
    $(player).removeClass("selected"); //déselectionne
    $("#textEchange").slideUp()
    $("#cible").remove();
}

// function creerMenuJoueur_CHG(name){

//     var containerBt = document.createElement("div");
//         containerBt.setAttribute("id", "divOptionsJoueurs");
//         containerBt.setAttribute("class", "divOptionsJoueurs");
//         containerBt.style.display = "inline";
//         containerBt.style.backgroundColor = "rgba(0,0,0, 0.8)"
//         containerBt.style.borderRadius = "30px"
//         containerBt.style.border = "2px solid grey"
//         // containerBt.style.padding = "20px"
//         containerBt.style.boxShadow = "rgba(0, 0, 0, 0.56) 0px 22px 70px 4px"
//         containerBt.style.position = "absolute";
//         containerBt.style.top = "0";
//         containerBt.style.bottom = "0";
//         containerBt.style.left = "0";
//         containerBt.style.right = "0";
//     var newBt6 = document.createElement("button");  // SUPPR JOUEUR
//         newBt6.textContent = "🚮" //❌
//         newBt6.classList.add("btSupprPlayer");
//         containerBt.append(newBt6)
//     var nom = document.createElement("span")       // AFFICHAGE NOM
//         nom.innerText = name;
//         nom.classList.add("btModifNom");
//         nom.style.color = "white"
//         containerBt.append(nom);
//     var hr = document.createElement("br")
//         containerBt.append(hr);
//     var hr = document.createElement("br")
//         containerBt.append(hr);
//     var newBt4 = document.createElement("button"); // FORCE 1
//         newBt4.setAttribute("id", "emoForce1");
//         newBt4.classList.add("btModifForce");
//         newBt4.textContent = emoForce1
//         containerBt.append(newBt4)
//     var newBt2 = document.createElement("button"); // FORCE 2
//         newBt2.setAttribute("id", "emoForce2");
//         newBt2.classList.add("btModifForce");
//         newBt2.textContent = emoForce2
//         containerBt.append(newBt2)
//     var newBt3 = document.createElement("button"); // FORCE 3
//         newBt3.setAttribute("id", "emoForce3");
//         newBt3.classList.add("btModifForce");
//         newBt3.textContent = emoForce3
//         containerBt.append(newBt3)
//     var hr = document.createElement("br")
//         containerBt.append(hr);
//         var newBt5 = document.createElement("button");  // CLOSE
//         newBt5.textContent = "✅"        //✅ 🈯️ 💹 ❇️ ✳️ ❎❌
//         newBt5.classList.add("btCloseMenu");
//         containerBt.append(newBt5)

//     return containerBt 
// }

function clickPlayerIcon(clicked) {

    console.log("Click Icone : modif du joueur");
    // console.log(window)
    // $("#divOptionsJoueurs").remove();
    // console.log(clicked)

    // if(clicked.classList.contains("menu")){

    //     console.log("menu existant : suppression")
    //     console.log(clicked.children)
    //     clicked.classList.remove("menu")
    //     $(clicked.children[2]).remove();
        
    // } else {

        // console.log("le menu n'existe pas")
        // clicked.classList.add("menu")
        // var pos = clicked.getBoundingClientRect();

        // var name = clicked.children[1].innerText // + " ✏️"
        var name = clicked.getAttribute('name')
        var containerBt = creerMenuJoueur(name);

        var newBt6 = document.createElement("button");  // SUPPR JOUEUR
            newBt6.textContent = "🛑 Supprimer le joueur" //❌💥🛑🚮
            newBt6.style.top = "65%";
            newBt6.style.position = "sticky";
            // newBt6.style.top = "75%";
            // newBt6.style.position = "relative";
            newBt6.style.color = "white"
            newBt6.style.padding = "15px"
            newBt6.style.fontSize = "1.4rem"
            newBt6.style.borderRadius = "50px"
            newBt6.style.border = "1px solid white"
            newBt6.classList.add("btSupprPlayer");
            newBt6.setAttribute("onclick", "supprPlayer(this)")

            containerBt.append(newBt6)
        var tree = document.createDocumentFragment();
            tree.appendChild(containerBt)
        clicked.appendChild(tree);

        document.getElementById('newPlayerName').setAttribute("onclick", "changeName_MODIFPLAYER(this)");

        var btsXP = document.getElementsByClassName('btChooseXP')
        Array.prototype.forEach.call(btsXP, bt => 
            bt.setAttribute("onclick", "changeXP_MODIFPLAYER(this)")
        )

    // }
}

function supprPlayer(e) {
    // CLIQUE DU BOUTON SUPPR - supprime le joueur
    console.log("BOUTON SUPPR PLAYER")
    var player = e.parentNode.parentNode
    var player_id = player.id
    var player_name = player.getAttribute('name')

        if (player.parentNode.id == "div0") {
            if (confirm("Supprimer définitivement ?")) {    
                $(player).remove();
                DB_deletePlayer(player_id, player_name)
                // localStorage.removeItem(player.id)
                majNbJoueurs_();
            }
        } else {
            $(player.children[2]).remove();
            // player.classList.remove("menu")
            document.getElementById("div9").appendChild(player);
            player.classList.add("inactif");
            majForceEquipes_();
        }
        return;

}


function changeXP_MODIFPLAYER(clicked) {
    // 
    var menu = clicked.parentNode.parentNode
    var player = clicked.parentNode.parentNode.parentNode 
    // console.log(clicked)
    // console.log('menu');console.log(menu)
    // console.log('player');console.log(player)

    var emoForce = window.event.target.textContent;
    console.log("Click Bouton force: "+emoForce);
    var force
    switch (emoForce) {
        case emoForce1: force = 1; break;
        case emoForce2: force = 2; break;
        case emoForce3: force = 3; break;
    }
    id = player.id

    // STOCKAGE DANS LE DOM :
    var player = clicked.parentNode.parentNode.parentNode;
    player.setAttribute("force",force);
    player.firstChild.innerText = ""+emoForce;

    // STOCKAGE DANS LA BDD :
    DB_changePlayerXP(id, force)

    console.log(player.children[1].innerText+" a une nouvelle force: "+player.getAttribute("Force") + ' ' +emoForce)
    
    // Suppression du menu
    menu.remove()
    // $(clicked.children[2]).remove();
    // clicked.classList.remove("menu")
    majForceEquipes_()
}


// function clickSwitchInactif(clicked) {       
//     // ACTION DANS LE DOM :
//     $(clicked).toggleClass("inactif");
//     console.log(clicked.getAttribute('name') + " change statut absent à: "+clicked.classList.contains("inactif"))

//     clicked.parentNode.appendChild(clicked);
//     majNbJoueurs_();
// }'

// function clickBtNom(clicked) {
//     var player = clicked
//     var nouveauNom = prompt("Nouveau nom ?",player.children[1].innerText)
//     if (nouveauNom == "" || nouveauNom == null) { 
//         return }
//     console.log("changment de nom: "+player.children[1].innerText+" -> "+nouveauNom)
//     // STOCKAGE DANS LE DOM :
//     player.children[1].innerText = nouveauNom
//     // STOCKAGE DANS LE COOKIE :
//     localStorage.setItem(
//         player.getAttribute("name"),
//         JSON.stringify({
//             "team":document.getElementById("team").getAttribute("name"),
//             "force":parseInt(player.getAttribute("force")),
//             "absent": player.classList.contains("inactif"),
//             "name":nouveauNom
//         })
//     );
//     // Suppression du menu
//     $(clicked.children[2]).remove();
//     clicked.classList.remove("menu")
// }


// function select(clicked) {

//     console.log("SELECTED");
//     console.log(clicked);
//     console.log(window.event.target);
//     console.log("^-------------------------^");

//     // CLIQUE DE L'ICONE DE FORCE - affichage du menu
//     if(window.event.target.classList.contains("emoforce")) {
//         clickPlayerIcon(clicked);
//         return;
//     }

//     // // CLIQUE DU BOUTON FORCE - modif force
//     // if(window.event.target.classList.contains("btModifForce")) {
//     //     changeXP_MODIFPLAYER(clicked);
//     //     majForceEquipes_()
//     //     return;
//     // }
//     // CLIQUE DU BOUTON NOM - modif nom
//     if(window.event.target.classList.contains("btModifNom")) {
//         clickBtNom(clicked);
//         return;
//     }
//     // CLIQUE DU BOUTON CLOSE - ferme le menu
//     if(window.event.target.classList.contains("btCloseMenu")) {
//         $(clicked.children[2]).remove();
//         return;
//     }
//     // CLIQUE DU BOUTON SUPPR - supprime le joueur
//     if(window.event.target.classList.contains("btSupprPlayer")) {
//         if (clicked.parentNode.id == "div0") {
//             if (confirm("Supprimer définitivement ?")) {    
//                 $(clicked).remove();
//                 deleteFromDatabase(clicked.id)
//                 localStorage.removeItem(clicked.id)
//                 majNbJoueurs_();
//             }
//         } else {
//             $(clicked.children[2]).remove();
//             clicked.classList.remove("menu")
//             document.getElementById("div9").appendChild(clicked);
//             clicked.classList.add("inactif");
//             majForceEquipes_();
//         }
//         return;
//     }
//     enleveMenuTousJoueurs_();

//     // CLIQUE NUL PART - ferme le menu
//     if(window.event.target.classList.contains("divOptionsJoueurs")) {
//         $(clicked.children[2]).remove();
//         return;
//     }

//     // gère les présents/absents dans la div joueursDispos (div0)
//     if (clicked.parentNode.id=="div0"){
//         clickSwitchInactif(clicked);
//         return;
//     } 
//     ecranEquipe_SELECTPLAYER(clicked);
// }

function ecranEquipe_SELECTPLAYER(clicked){
    // console.log(clicked)
        
    // gère la sélection dans les équipes
    var selection = $(".selected")

    // Vérifique que le joueur cliqué n'est pas le même que le joueur sélectionné
    if (clicked !== selection[0]) { 

        console.log("Sélection:"+clicked.getAttribute('name'));
        $("#textEchange").slideDown()

        if (selection.length == 0) {
            // S'il n'y avait pas de joueur sélectionné, on sélectionne (classe selected)
            $(clicked).toggleClass("selected")
            var divOrigine = clicked.parentNode.id
            ajoutCiblesToutesEquipes(divOrigine);
        } else {
            // S'il y a déjà un joueur sélectionné, 
            // Si la seconde selection est aussi un joueur, on échange les deux jouers
            // Si la seconde selection est une cible, ça marche aussi (on échange puis on supprime la cible)

            var cliquedContainer = clicked.parentNode;
            var selectedContainer = selection[0].parentNode;
            var destination = selection[0].nextSibling
            
            console.log('FLAG')
            console.log(cliquedContainer)
            console.log(selectedContainer)
            console.log(destination)


            cliquedContainer.insertBefore(selection[0], clicked);
            selectedContainer.insertBefore(clicked, destination);

            console.log("inverse "+selection[0].getAttribute('name')+" avec "+clicked.getAttribute('name'))

            unselect_(selection[0]); // Désélectionne en enlevant la classe selected
            $(".cible").remove(); // on enlève les cibles des équipes
            majForceEquipes_()
        }
    } else { 
        // Pour désélectionner :
        console.log("Annule la sélection");
        $(".cible").remove(); // on enlève les cibles des équipes
        unselect_(clicked); // déselectionne en enlevant la classe selected
    }
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
        // players[i].classList.remove("menu")
    }
}


function getRandomInt_(max) {
    return Math.floor(Math.random() * max);
}

function majNbJoueurs_() {
    //mise à jour du nombre de joueurs sur l'interface principale
    document.getElementById("questionPresents").innerText = "Présents: "+(document.getElementsByClassName("player").length - document.getElementsByClassName("inactif").length) +" / "+document.getElementsByClassName("player").length
}


function majForceEquipes_() {
    console.log("majForceEquipes_")
    var forceEq1 = 0, forceEq2 = 0, forceEq3 = 0;
    var joueurs = $(".player")
    for (let i = 0; i < joueurs.length; i++) {
        force = joueurs[i].getAttribute("force");
        nom = joueurs[i].id;
        switch (joueurs[i].parentNode.id) {
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
}



function testTable(table){
    console.log("table :");	console.log(table);
    console.log("keys :");			console.log(Object.keys(table));
    console.log("values :");		console.log(Object.values(table));
    console.log("entries :");		console.log(Object.entries(table));
}