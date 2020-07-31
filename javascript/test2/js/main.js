const draggableElements = document.querySelectorAll('.draggable');
const droppableElements = document.querySelectorAll('.droppable');

draggableElements.forEach(elem => {
    // L'événement dragstart se déclenche lorsque l'élément ciblé commence à être déplacé.
    elem.addEventListener("dragstart",dragStart);
    elem.addEventListener("drag",drag);
    // permet de signaler à l'objet déplacé que son déplacement est terminé
    elem.addEventListener("dragend",dragEnd);
});

droppableElements.forEach(elem =>{
    // se déclenche lorsqu'un élément en cours de déplacement entre dans la zone de drop.
    elem.addEventListener("dragenter",dragEnter);
    // se déclenche lorsqu'un élément en cours de déplacement se déplace dans la zone de drop.
    elem.addEventListener("dragover",dragOver);
    // se déclenche lorsqu'un élément en cours de déplacement quitte la zone de drop.
    elem.addEventListener("dragleave",dragLeave);
    // se déclenche lorsqu'un élément en cours de déplacement est déposé dans la zone de drop.
    elem.addEventListener("drop",drop);
});

// Drag & Drop Functions
function dragStart(event){
    // console.log('start');
    event.dataTransfer.setData("text", event.target.id);
}
function drag(){

}
function dragEnd(){

}
function dragEnter(event){
    if(!event.target.classList.contains("dropped")){
       event.target.classList.add("droppable-hover"); 
    }
    
}
function dragOver(event){
    if(!event.target.classList.contains("dropped")){
        // preventDefault() : « annulation d'une action par défaut ».
        event.preventDefault();
    }
}
function dragLeave(event){
    if(!event.target.classList.contains("dropped")){
        event.target.classList.remove("droppable-hover");
    }
}
function drop(event){
    event.preventDefault();
    event.target.classList.remove("droppable-hover");
    const draggableElementData = event.dataTransfer.getData("text");
    const droppableElementData = event.target.getAttribute("data-draggable-id");
    if(draggableElementData === droppableElementData){
        event.target.classList.add("dropped");
        const draggableElement = document.getElementById(draggableElementData);
        event.target.style.backgroundColor = draggableElement.style.color;
        event.target.style.backgroundColor = window.getComputedStyle(draggableElement).color;
        draggableElement.classList.add("dragged");
        draggableElement.setAttribute("draggable","false");
        event.target.inserAdjacentHTML("afterbegin",`<div class="draggable" id="${draggableElementData}"></div>`);
    }
}