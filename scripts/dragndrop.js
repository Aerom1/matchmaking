
function dragEnter(e) {
    e.preventDefault();

    if (e.target.classList.contains("player")) {
        e.target.classList.add('drag-over');
    } else {
        e.target.parentNode.classList.add('drag-over');
    }
}

function dragLeave(e) {
    e.target.classList.remove('drag-over');
    e.target.parentNode.classList.remove('drag-over');
    $("#cible").remove();

}
function dragOver(e) {
    console.log("=== dragOver ===");
    e.preventDefault();
    if (e.target.classList.contains("player")) {
        e.target.classList.add('drag-over');
    } else {
        e.target.parentNode.classList.add('drag-over');
    }
}

function dragEnd(e) {
    console.log("<@> dragEnd <@>");
    $(".cible").remove();
}

function allowDrop(ev) {
    ev.preventDefault();
}
function drag(ev) {
    console.log("<<< drag >>>");
    ev.dataTransfer.setData("text", ev.target.id);
    // setTimeout(() => {
    //     ev.target.classList.add('hide');
    // }, 0);
    ajoutCiblesToutesEquipes(ev.target.parentNode.id);
}

function ajoutCiblesToutesEquipes(divOrigine){
    nbEquipe = document.getElementById('btNbEquipes').getAttribute('nb')
    if (nbEquipe == 2) { return }
    // Ajoute 3 cibles (une pour chaque équipe)
    for (let i=1;i<=3;i++) {
        if ("div"+i !== divOrigine) {
            var cible = document.createElement("div");
                cible.setAttribute("class", "cible");
                cible.classList.add("player");
                cible.setAttribute("onclick","ecranEquipe_SELECTPLAYER(this)");
            var cibletxt = document.createElement("span");
                cibletxt.innerText = "🎯";
            cible.appendChild(cibletxt)
            document.getElementById("div"+i).appendChild(cible);
        }
    }
}    

function drop(ev) {
    console.log(">> drop <<");
    ev.target.classList.remove('drag-over');
    ev.target.parentNode.classList.remove('drag-over');
    
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    var source = document.getElementById(data);
    var sourceVoisin = source.nextElementSibling;
    var sourceContainer = source.parentNode;
    // source.classList.remove('hide');
    var target = ev.target
    
    if (!target.classList.contains("player")) {
        target = target.parentNode;
        if (!target.classList.contains("player")) {
            target = target.parentNode;
            if (!target.classList.contains("player")) {
                console.log("aucun parent joueur trouvé")
                return
            }
        }
    }
    var targetVoisin = target.nextElementSibling;
    var targetContainer = target.parentNode;
    
    unselect_(source)
    
    if (targetVoisin===null){
        targetContainer.appendChild(source);
    }else{
        targetContainer.insertBefore(source,targetVoisin);
    }
    if (sourceVoisin===null){
        sourceContainer.appendChild(target);
    }else{
        sourceContainer.insertBefore(target,sourceVoisin);
    }
    
    $(".cible").remove();
    majForceEquipes_();
}