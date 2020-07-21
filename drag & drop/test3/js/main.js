// Le plugin Draggable
$(function(){

    $('#element').draggable(); // appel du plugin

    // ------------------------------------
    // Limiter le déplacement
        // le bloc ayant pour classe la valeur "drag1" ne pourra être déplacé qu'au niveau horizontal
    $('.drag1').draggable({
        axis : 'x'
    });

        // le bloc ayant pour classe la valeur "drag2" ne pourra être déplacé qu'au niveau vertical
    $('.drag2').draggable({
        axis : 'y'
    });

        // Il prend pour valeur la classe ou l'identifiant de l'élément qui jouera le rôle d'une zone
    $('#drag').draggable({
        containment : '#limitation',
        // Définir un magnétisme
        snap : '.draggable', // chaque élément ayant la classe "draggable" exercera une attraction
        grid : [20 , 20],
        revert : true // Retour à l'envoyeur
    });


// Le plugin Droppable

    $('#drop').droppable({
        accept : '#drag', // je n'accepte que le bloc ayant "drag" pour id
        drop : function(){
            alert('Action terminée !'); // cette alerte s'exécutera une fois le bloc déposé
        }
    
    });
    // ------------------------------------
    // Limiter les éléments acceptés
    $('#not-drag').draggable();
    // ------------------------------------
    // Retour à l'envoyeur, 2ème édition
    $('#valid').draggable({
        revert : 'valid' // sera renvoyé à sa place s'il est déposé dans #drop
    });
    
    $('#invalid').draggable({
        revert : 'invalid' // sera renvoyé à sa place s'il n'est pas déposé dans #drop
    });





$("#winston").draggable();
$("#dropzone").droppable({
    drop: function(event, ui) {
        $(this).css('background', 'rgb(0,200,0)');
    },
    over: function(event, ui) {
        $(this).css('background', 'orange');
    },
    out: function(event, ui) {
        $(this).css('background', 'cyan');
    }
});

});