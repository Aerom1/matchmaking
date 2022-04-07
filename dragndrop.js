
function dragEnter(e) {
    e.preventDefault();
    if (e.target.parentNode.classList.contains("player")) {
        e.target.parentNode.classList.add('drag-over');
    } else {
        e.target.classList.add('drag-over');
    }
}

function dragOver(e) {
    e.preventDefault();

    if (e.target.parentNode.classList.contains("player")) {
        e.target.parentNode.classList.add('drag-over');
    } else {
        e.target.classList.add('drag-over');
    }
}

function dragLeave(e) {
    e.target.classList.remove('drag-over');
    e.target.parentNode.classList.remove('drag-over');
}

function allowDrop(ev) {
    ev.preventDefault();
}
function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
    // setTimeout(() => {
    //     ev.target.classList.add('hide');
    // }, 0);
}
function drop(ev) {
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

    unselect(source)

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

    majForceEquipes();
}