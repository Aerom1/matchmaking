// const { cp } = require('fs');
// const { create } = require('domain');
// const { cp } = require('fs');
// const { cp } = require('fs');
// const { cp } = require('fs');
// const { cp } = require('fs');
// const { cp } = require('fs');

const imgSunride = "img/sunride.png";
const imgViking = "img/viking.png";
const emoForce1 = "🐭";
const emoForce2 = "😼";
const emoForce3 = "🐻";
//🐭😼🐻🦁🐯🐹😺😺🐶

function x(id){return document.getElementById(id)}

function changerEquipe(element) {
    document.getElementById('loading-spinner-mask').classList.remove('invisible');
    teamid = element.getAttribute('teamid')
    window.location.href = 'index.php?teamid='+teamid;
}

 // définit la prochaine équipe qui sera affichée, pour pouvoir afficher son logo sur le bouton de chgmt d'équipe
function getNextTeamId(all_teams, team) {

    var teamActuelleId = team.id
    var newTeamIndex
    var nbEquipes = Object.keys(all_teams).length

    for (i=0 ; i<nbEquipes ; i++){
        // console.log("all_teams[i].id == team.id ?  ->  " + all_teams[i].id + " == " + teamActuelleId)
        if (all_teams[i].id == teamActuelleId) {
            if (i == nbEquipes-1) {
                newTeamIndex = 0
            } else {
                newTeamIndex = i+1
            }
            break;
        }
    }
    var nextTeam = all_teams[newTeamIndex]
    console.info("Prochaine équipe trouvée : " +  nextTeam.name)
    return nextTeam
}

function loadTeam(team, players){
    console.log("load team : " + team.name)

    document.getElementById("logoTeam").setAttribute("src",team.logo); // logo de l'appli (header)
    // document.getElementById("logoEquipeNext").setAttribute("src",nextTeam.logo); // logo du bouton next Team
    // document.getElementById("logoEquipeNext2").setAttribute("src",nextTeam.logo); // logo du bouton next Team
    // document.getElementById("nextteamid").value = nextTeam.id; // affiche l'ID

    var teamActuelle = document.getElementById("team")
        teamActuelle.setAttribute("name", team.id);
        teamActuelle.innerText = team.name;
    
    enleverTousJoueurs_();
    //----------------------------
        loadData(team, players);
    //----------------------------
    majForceEquipes_();
    majNbJoueurs_();
    creerDivPlus_();

    // Ajuster l'affichage 
    $("#containerEquipes").css({"display":"grid"})
    $(".teamContainer").css({"width":"100%"})
    $(".cible").remove();
    $("#containerForceMenuEquipes").hide();

}

function loadData(team, players){
    console.log("Load Data pour " + team.name + " (" + players.length + " joueurs)");
    console.log(players)
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

function btEditTeam(){
    enleveMenu_();
    var team = document.getElementById('team').innerText
    // Création du menu et ajout au DOM au niveau du containerEquipes
    var menu = creerMenu(team, 'modifequipe')
    document.getElementById("containerEquipes").appendChild(menu);
    // menu.style.animation="appearfromright 0.5s linear 2";
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
    enleveMenu_();

    $('#containerButton_MenuEquipes').hide();
    $('header.titre').show();
    $('#menu1').show();
    $('#menu2wrapper').show();

    $("#containerForceMenuEquipes").hide();
    // document.getElementById("#containerForceMenuEquipes").setAttribute('display','none');

    // $('#btRandom').show();

    
    // indique aux conteneur de joueurs quelle mise en forme prendre
    document.getElementById('containerEquipes').className = 'accueil';   // "accueil","equipes"
    
    if ($(".selected").length>0) {unselect_($(".selected")[0])}

    reinit_();
    creerDivPlus_();
}

function reinit_() {
    console.log("reinit_")
    $("#divPlus").remove();
    $(".cible").remove();
    $(".teamContainer").css({"width":"100%"})
    var dispos = document.getElementById('div0');
    var equipe1 = document.getElementById('div1');
    var equipe2 = document.getElementById('div2');
    var equipe3 = document.getElementById('div3');
    var absents = document.getElementById('div9');

    while (equipe1.children.length) { dispos.appendChild(equipe1.firstChild); }
    while (equipe2.children.length) { dispos.appendChild(equipe2.firstChild); }
    while (equipe3.children.length) { dispos.appendChild(equipe3.firstChild); }
    while (absents.children.length) { dispos.appendChild(absents.firstChild); }

    // Enlever la possibilité de glisser-déposer dans l'accueil
    for (let i = 0; i < dispos.children.length; i++) {
        dispos.children[i].setAttribute('draggable' , '')
      }
    
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
    // $("#btNbEquipes").animate({"":""},1000)
    enleveMenu_();
    var bouton = document.getElementById('btNbEquipes');
    var nbEquipe = bouton.getAttribute("nb");
    //var txtBtEq = bouton.innerText;
    
    if (nbEquipe==2){
        nbEquipe = 3;
    } else {
        nbEquipe = 2;
    }
    console.log("NB EQUIPE : " + nbEquipe)
    bouton.setAttribute("nb",nbEquipe);
    RANDOM(nbEquipe)
}

function btRandom(){
    console.log("BTRANDOM")
    document.getElementById('loading-spinner-mask').classList.remove('invisible');

    $('header.titre').hide();
    $('#containerButton_MenuEquipes').show();
    $('#menu1').hide();
    $('#menu2wrapper').hide();
    // $('#btRandom').hide();
    
        
    // indique aux conteneur de joueurs quelle mise en forme prendre
    document.getElementById('containerEquipes').className = 'equipes';   // "accueil","equipes"
    
    var nbEquipe = document.getElementById('btNbEquipes').getAttribute("nb");   
    if (nbEquipe==0) {
        // Pas de nombre d'équipe choisi -> selon nombre de joueurs
        var nbJoueurs = document.getElementsByClassName("player").length - document.getElementsByClassName("inactif").length
        if(nbJoueurs>=12){
            nbEquipe=3;
        }else{
            nbEquipe=2
        }
        document.getElementById('btNbEquipes').setAttribute("nb",nbEquipe);
        console.log(nbJoueurs + " JOUEURS : " + nbEquipe + " equipes !")
    }
    RANDOM(nbEquipe);

    document.getElementById('loading-spinner-mask').classList.add('invisible');

}

function changeBtForceDisposition(nbEquipe) {

// // METHODE POUR CENTRER UN ELEMENT EN POSITION ABSOLUTE
// // top: 50%;  /* position the top  edge of the element at the middle of the parent */
// // left: 50%; /* position the left edge of the element at the middle of the parent */
// // transform: translate(-50%, -50%); /* This is a shorthand of
// //                                      translateX(-50%) and translateY(-50%) */

//     if (nbEquipe==3){
//         document.querySelectorAll('.forceEquipe').forEach(e => {  
//             e.style.paddingRight = '25%';  
//             e.style.paddingTop = '0%';  
//         });
//         // $('.forceEquipe').css('padding-right', '5%') 
//         // document.getElementById('icoRandom').style.left = '60%';
//         // document.getElementById('icoRandom').style.top = '1.1vh';
//         // document.getElementById('icoRandom').style.transform = 'none';
//         document.getElementById('containerForceMenuEquipes').style.flexDirection = 'column';
//         document.getElementById('containerForceMenuEquipes').style.width = '100%';
//         document.getElementById('containerForceMenuEquipes').style.height = '100%';
//     } else {
//         document.querySelectorAll('.forceEquipe').forEach(e => {  
//                 e.style.paddingRight = '0%';
//                 e.style.paddingTop = '7%';
//               });
//         // $('.forceEquipe').css('padding', '1vh 0')
//         // document.getElementById('icoRandom').style.right = 'none';
//         // document.getElementById('icoRandom').style.left = '50%';
//         // document.getElementById('icoRandom').style.top = '20%';
//         // document.getElementById('icoRandom').style.transform = 'translate(-50%)';
//         document.getElementById('containerForceMenuEquipes').style.flexDirection = 'row';
//         document.getElementById('containerForceMenuEquipes').style.width = '100%';
//         document.getElementById('containerForceMenuEquipes').style.height = '100%';

//     }
}

function RANDOM(nbEquipe) {
    console.log("=============début random");
    // document.getElementById("questionPresents").style.display = "none"
    // $("#btRandom").animate({
    //     height:"10px",
    //     width: "+=100px",
    //     opacity: 0.5
    // }, 1000)

    $("#divPlus").remove();
    $("#containerForceMenuEquipes").show();
    // document.getElementById("#containerForceMenuEquipes").setAttribute('display','flex');

    enleveMenu_();
    var dispos = document.getElementById('div0');
    var equipe1 = document.getElementById('div1');
    var equipe2 = document.getElementById('div2');
    var equipe3 = document.getElementById('div3');
    var absents = document.getElementById('div9');
    var txtBtEq = document.getElementById('btNbEquipes').innerText;
    //console.log("txtBtEq:"+txtBtEq.substring(txtBtEq.length-2))

    
    //parseInt(txtBtEq.substring(txtBtEq.length-2));
    console.log("nbEquipe:"+nbEquipe);
    var ecart = 0.1 //10% de différence de force max
    var ecartmax = 0, difference = 0; compteur = 0
    do {
        compteur+=1
        reinit_();
        var forceEq1 = forceEq2 = forceEq3 = 0;
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
    
    if(equipe3.children.length){
        $("#div3").show();
        $("#containerEquipes").css({"display":"grid"})
        $(".teamContainer").css({"width":"100%"})
    }else{
        $("#div3").hide();
        $("#containerEquipes").css({"display":"flex"})
        $(".teamContainer").css({"width":"49%"})
    }

    var j = {}
    var players = document.getElementsByClassName('player')
    Array.prototype.forEach.call(players, p => {
        j[p.getAttribute('id')] = p.classList.contains('inactif') ? 1:0
    })

    DB_CHANGE_player_inactifs(j);
    // changeBtForceDisposition(nbEquipe);
}




function creerMenu(name, menu, force=0) {

    try {document.getElementById('divmenu').remove()} catch{} // On enlève un éventuel menu précédent

    var menuContainer = document.createElement("div");  // BOX FOND NOIR
        menuContainer.setAttribute("id", "divmenu");
    var btClose = document.createElement("span")   // BOUTON FERMER
        btClose.innerText = '╳'; //❌ ✖ ╳
        btClose.setAttribute("id", "btCloseMenu");
        btClose.setAttribute("onclick", "this.parentNode.remove()");
        menuContainer.append(btClose);
    var nom = document.createElement("button")       // AFFICHAGE NOM
        nom.innerText = name;
        nom.setAttribute("id", "btModifNom");
        menuContainer.append(nom);
 
    if (menu == 'addplayer' || menu == 'modifplayer') {

        var labelXP = document.createElement('span')
            labelXP.id = 'labelModifXP';
            labelXP.innerText = '💡 Choisissez la force du joueur'
            menuContainer.append(labelXP)

        var frameXP = document.createElement("div");
            frameXP.setAttribute("id", "frameBtXp");
        var xp1 = document.createElement("button");     // FORCE 1
            xp1.setAttribute("id", "emoForce1");
            xp1.classList.add("btChooseXP");
            xp1.textContent = emoForce1
            frameXP.append(xp1)
        var xp2 = document.createElement("button");     // FORCE 2
            xp2.setAttribute("id", "emoForce2");
            xp2.classList.add("btChooseXP");
            xp2.textContent = emoForce2
            frameXP.append(xp2)
        var xp3 = document.createElement("button");     // FORCE 3
            xp3.setAttribute("id", "emoForce3");
            xp3.classList.add("btChooseXP");
            xp3.textContent = emoForce3
            frameXP.append(xp3)

            if (force==1) {xp1.classList.add('forceActuelle')}
            if (force==2) {xp2.classList.add('forceActuelle')}
            if (force==3) {xp3.classList.add('forceActuelle')}
            // if (force==1) {xp1.style.border = "2px solid white"}
            // if (force==2) {xp2.style.border = "2px solid white"}
            // if (force==3) {xp3.style.border = "2px solid white"}

        //     //✅ 🈯️ 💹 ❇️ ✳️ ❎❌    
        menuContainer.appendChild(frameXP)

        if (menu == 'modifplayer') {
            var btsupr = document.createElement("button");                  // SUPPR JOUEUR
                btsupr.textContent = "🛑 Supprimer le joueur" //❌💥🛑🚮
                btsupr.setAttribute("id", "btSupprPlayer")
                btsupr.setAttribute("onclick", "supprPlayer(this)")
                menuContainer.append(btsupr)
        }
    }

    // <><><><><><><><><><><><><><><><><><>   ON-CLICK   <><><><><><><><><><><><><><><><><><>

    switch (menu) {                                                
        case 'addplayer':
            // Attribution du clic pour le changement de nom :
            nom.setAttribute("onclick", "changeName_DISPLAYONLY(this)");
            // Attribution du clic pour chaque emoji de force(xp) :
            xp1.setAttribute("onclick", "clickBtMenuAddPlayer(this)")
            xp2.setAttribute("onclick", "clickBtMenuAddPlayer(this)")
            xp3.setAttribute("onclick", "clickBtMenuAddPlayer(this)")
            break;
        case 'modifplayer':
            // Attribution du clic pour le changement de nom :
            nom.setAttribute("onclick", "changeName_MODIFPLAYERDB(this)");
            // Attribution du clic pour chaque emoji de force(xp) :
            xp1.setAttribute("onclick", "changeXP_MODIFPLAYER(this)")
            xp2.setAttribute("onclick", "changeXP_MODIFPLAYER(this)")
            xp3.setAttribute("onclick", "changeXP_MODIFPLAYER(this)")
            break;
    }

    var menu = document.createDocumentFragment();
        menu.appendChild(menuContainer)

    return menu;
}

function clickAddPlayer() {
    enleveMenu_();
    var name = prompt("Nom du nouveau joueur ?")
    if (name == "" || name == null) { return }
    if (name.length > nbcarPlayer) {
        snackbar('ℹ️ | Le nom ne doit pas dépasser '+nbcarPlayer+' caractères', 'orange')
        return
    }
    
    // On vérifie qu'un joueur ne porte pas déjà ce nom
    var players = document.getElementsByClassName('player')
    for (const player of players) {
        if (player.getAttribute('name') == name) {
            // Affichage du pop (snackbar)
            var x = document.getElementById("snackbar"); // Get the snackbar DIV
                x.className = "show"; // Add the "show" class to DIV
                x.innerHTML = name + " existe déjà !";
                x.style.borderRadius = '100px'
                x.style.color = 'orange'
                setTimeout(function(){ 
                        x.className = x.className.replace("show", ""); 
                    }, 3000); // After 3 seconds, remove the show class from DIV
            return;
        }
    }
    // Création du menu et ajout au DOM au niveau du containerEquipes
    var menu = creerMenu(name, 'addplayer')
    document.getElementById("containerEquipes").appendChild(menu);

}


function changeName_MODIFPLAYERDB(e) {
    console.log(e)
    newname = prompt('Nouveau nom ?',e.innerText)
    if (!newname) { return }
    if (newname.length > nbcarPlayer) {
        snackbar('ℹ️ | Le nom ne doit pas dépasser '+nbcarPlayer+' caractères', 'orange')
        return
    }
    e.innerText = newname
    id = e.parentNode.parentNode.id
    DB_CHANGE_player_name(id, newname)
    e.parentNode.parentNode.name = newname // Nom affiché dans le menu
    console.log(e.parentNode.parentNode.children[1])
    e.parentNode.parentNode.children[1].innerText = newname // Nom affiché dans la tuile (écran principale)
}

function changeName_DISPLAYONLY(e) {
    newname = prompt('Nouveau nom ?',e.innerText)
    if (!newname) { return }
    if (newname.length > nbcarPlayer) {
        snackbar('ℹ️ | Le nom ne doit pas dépasser '+nbcarPlayer+' caractères', 'orange')
        return
    }
    e.innerText =newname
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
        var name = document.getElementById("btModifNom").textContent;
        
        var player = createPlayer_(force, name, 0)
        DB_CREATE_player(force, name, team, player)
			
    }
    // Suppression du menu
    menu.remove()
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
        p.setAttribute('id' , id)
        p.setAttribute('force' , force)
        p.setAttribute('name' , name)
        p.classList.add("player");
        if (absent) { p.classList.add("inactif"); }
    var x = document.createElement('span')
        x.innerText = emo
        x.classList.add('emoforce')
        x.addEventListener('click' , function(){clickPlayerIcon(p)})
    var n = document.createElement('span')
        n.classList.add('playerName')
        n.innerText = name.substring(0,nbcarPlayer)
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
        if (p.classList.contains("inactif")) {
            p.classList.remove('inactif')
            p.parentNode.insertBefore(p, p.parentNode.children[1]); // J'ajoute après le bouton '+'
            
        } else {
            p.classList.add('inactif')
            p.parentNode.appendChild(p);
            
        }
        p.classList.add('vu')
        majNbJoueurs_();
        console.log(p.getAttribute('name') + " change statut absent à: "+p.classList.contains("inactif"))
    } else {
        // SI ECRAN DES EQUIPES : le joueur est sélectionné pour un échange
        ecranEquipe_SELECTPLAYER(p)
    }
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
    player.setAttribute('draggable' , "true")
    player.setAttribute('ondragstart', 'drag(event)')
}


function unselect_(player){
    $(player).removeClass("selected"); //déselectionne
    $("#textEchange").slideUp()
    $("#cible").remove();
    $(".player").css("border","none")
}


function clickPlayerIcon(clicked) {
    console.log("Click Icone : modif du joueur");
    var name = clicked.getAttribute('name')
    var force = clicked.getAttribute('force')
    // Création du menu et ajout DOM au niveau du joueur
    var menu = creerMenu(name, 'modifplayer', force);
    clicked.appendChild(menu);
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
                DB_DELETE_player(player_id, player_name)
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
    DB_CHANGE_player_xp(id, force)

    console.log(player.children[1].innerText+" a une nouvelle force: "+player.getAttribute("Force") + ' ' +emoForce)
    
    // Suppression du menu
    menu.remove()
    majForceEquipes_()
}


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
    $("#divPlus").remove();
}

function enleveMenu_(){
    try{document.getElementById('divmenu').remove();} catch {}

    // var players = document.getElementsByClassName("player")
    // for (let i=0 ; i<players.length ; i++) {
    //     $(players[i].children[2]).remove();
    //     // players[i].classList.remove("menu")
    // }
}


function getRandomInt_(max) {
    return Math.floor(Math.random() * max);
}

function majNbJoueurs_() {
    //mise à jour du nombre de joueurs sur l'interface principale
    if (document.getElementsByClassName("player").length > 0) {
        document.getElementById("questionPresents").innerText = "Présents: "+(document.getElementsByClassName("player").length - document.getElementsByClassName("inactif").length) +" / "+document.getElementsByClassName("player").length
    } else {
        document.getElementById("questionPresents").innerText = "Ajoutez un joueur"
    }
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
    
    iconeForce1 = iconeForce2 = iconeForce3 = ''
    // if((forceEq1 > forceEq2) & (forceEq1>forceEq3)) {iconeForce1='🦾'}
    // if((forceEq2 > forceEq1) & (forceEq2>forceEq3)) {iconeForce2='🦾'}
    // if((forceEq3 > forceEq1) & (forceEq3>forceEq2)) {iconeForce3='🦾'}

    var nbPlayer1 = $("#div1")[0].childElementCount
    var nbPlayer2 = $("#div2")[0].childElementCount
    var nbPlayer3 = $("#div3")[0].childElementCount

    $("#forceEq1").html('💪 ' + forceEq1 + ' <sup>(' + nbPlayer1 + ')</sup>'); // jaune  ⚡
    $("#forceEq2").html('💪 ' + forceEq2 + ' <sup>(' + nbPlayer2 + ')</sup>'); // orange ⚡
    $("#forceEq3").html('💪 ' + forceEq3 + ' <sup>(' + nbPlayer3 + ')</sup>'); // noir ⚡

    // if (forceEq1==0)  { document.getElementById("forceEq1").setAttribute("display","none");
    //     } else        { document.getElementById("forceEq1").setAttribute("display","unset"); }
    // // if (forceEq1==0)  { $("#forceEq1").css({"display":"none"});
    // //     } else        { $("#forceEq1").css({"display":"unset"}); }
    // if (forceEq2==0)  { $("#forceEq2").css({"display":"none"});
    //     } else        { $("#forceEq2").css({"display":"unset"}); }
    if (forceEq3==0)  { $("#forceEq3").css({"display":"none"});
        } else        { $("#forceEq3").css({"display":"unset"}); }
    // if (forceEq1==0)  { $("#btForceEquipes").css({"display":"none"});
    //     } else        { $("#btForceEquipes").css({"display":"block"}); }
    // if (forceEq1==0)  { document.getElementById("btForceEquipes").setAttribute("display","none");
    //     } else        { document.getElementById("btForceEquipes").setAttribute("display","unset"); }
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

function showZoom(){
    var p = document.getElementById("zoom"); // Get the DIV
        p.className = "show"; // Add the "show" class to DIV
        setTimeout(function(){  p.className = p.className.replace("show", ""); 	}, 20000); // After 3 seconds, remove the show class from DIV
}

function hideZoom(){
    var p = document.getElementById("zoom"); // Get the DIV
        p.className = ""; // remove the "show" class to DIV
    // p.style.animation="fadeoutzoom 2s linear 2";
    // window.setTimeout(
    //     function removethis()
    //     {
    //         p.className = ""; // remove the "show" class to DIV
    //     }, 2000);       
}

function defineTextSize(defaultSize) {

    var tailleText_accueil = localStorage.getItem('tailleText_accueil');
    console.log('tailleText_accueil récupérée de localstorage :' + tailleText_accueil);
    if (tailleText_accueil === null) {
        tailleText_accueil = defaultSize;
        localStorage.setItem('tailleText_accueil', tailleText_accueil);
        console.log('------------tailleText_accueil localstorage créée :' + tailleText_accueil);
    }
    document.documentElement.style.setProperty('--font-size-player-accueil', tailleText_accueil + 'px');


    var tailleText_equipes = localStorage.getItem('tailleText_equipes');
    console.log('tailleText_equipes récupérée de localstorage :' + tailleText_equipes);
    if (tailleText_equipes === null) {
        tailleText_equipes = defaultSize;
        localStorage.setItem('tailleText_equipes', tailleText_equipes);
        console.log('------------tailleText_equipes localstorage créée :' + tailleText_equipes);
    }
    document.documentElement.style.setProperty('--font-size-player-equipes', tailleText_equipes + 'px');
}

function zoom(action, ecran){
    
    var ecran = document.getElementById("containerEquipes").className
    var property, localStorageItem // nom de la propriété dans le local storage, et de la propriété (var) dans le css
    if (ecran == "accueil") {
        property = '--font-size-player-accueil'
        localStorageItem = 'tailleText_accueil'
    } else {
        property = '--font-size-player-equipes'
        localStorageItem = 'tailleText_equipes'
    }
    // On récupère la taille actuelle d'un joueur au pif (le premier)
    var el = document.getElementsByClassName('player')[0];
    var style = window.getComputedStyle(el, null).getPropertyValue('font-size');
    var fontSize = parseFloat(style);     // now you have a proper float for the font size (yes, it can be a float, not just an integer)
        fontSize = (action == 'grossir' ? Math.round(fontSize + 3) : Math.abs(Math.round(fontSize - 3)));
        fontSizePC = fontSize + 'px'
    document.documentElement.style.setProperty(property, fontSizePC);
    localStorage.setItem(localStorageItem, fontSize);

    console.log("------------ action:" + action + " ecran:" + ecran + " size:" + fontSize)
}

function toggleTheme(){
    document.querySelector("html").classList.toggle('dark-theme')
    console.log("------------ theme:" + document.querySelector("html").classList.toString())
    localStorage.setItem('theme', document.querySelector("html").classList.toString());
}

function setTheme(){
    var theme = localStorage.getItem('theme');
    console.log('theme récupéré de localstorage :' + theme);
    if (theme === null) {
        theme = "";
        localStorage.setItem('theme', theme);
        console.log('------------theme localstorage créé');
    }
    document.querySelector("html").classList.remove('dark-theme')
    if (theme!=='') { document.querySelector("html").classList.add(theme) }
}

function toggleFullscreen(){

    // document.exitFullscreen();
    // document.documentElement.requestFullscreen();
    if (!document.fullscreenElement &&    // alternative standard method
    !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
     if (document.documentElement.requestFullscreen) {
       document.documentElement.requestFullscreen();
     } else if (document.documentElement.mozRequestFullScreen) {
       document.documentElement.mozRequestFullScreen();
     } else if (document.documentElement.webkitRequestFullscreen) {
       document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
     }
   } else {
      if (document.cancelFullScreen) {
         document.cancelFullScreen();
      } else if (document.mozCancelFullScreen) {
         document.mozCancelFullScreen();
      } else if (document.webkitCancelFullScreen) {
        document.webkitCancelFullScreen();
      }
   }
 }

 
function testTable(table){
    console.log("table :");	console.log(table);
    console.log("keys :");			console.log(Object.keys(table));
    console.log("values :");		console.log(Object.values(table));
    console.log("entries :");		console.log(Object.entries(table));
}

