(function ($) {
    var lastPlace;

    $("#choises li img").draggable({
        revert: true,
        zIndex: 10,
        // snap: mes éléments li dans #choices, s'accroche aux éléments li dans #answers
        snap: "#answers li",
        // snapMode: détermine les bords des éléments d'accrochage auxquels le draggable s'accroche.
        snapMode: "inner",
        // snapTolerance: distance en pixels des bords de l'élément d'accrochage à laquelle l'accrochage doit se produire.
        snapTolerance: 40,
        start: function (event, ui) {
            lastPlace = $(this).parent();
        }
    });

    function drop(emplacement, formQuizImage){
        $(emplacement).droppable({
            drop: function (event, ui) {
                var dropped = ui.draggable;
                // l'emplacement de la case pour déposer l'image
                var droppedOn = this;

                // récupérer l'id de l'élément pris puis déposé
                var id_objet = dropped.attr('id');

                // récupérer l'emplacement de la case
                // var drop = $(this).attr('id');

                $(formQuizImage).val(id_objet);

                // Autorise de déplacer plusieurs fois l'objet:
                if ($(droppedOn).children().length > 0) {
                    $(droppedOn).children().detach().prependTo($(lastPlace));
                }

                // l'objet se mets dans la case:
                $(dropped).detach().css({
                    top: 0,
                    left: 0
                }).prependTo($(droppedOn)); //Ajoute à l'emplacement de la case
            }
        }); 
    }
    for(i=0;i<9;i++){ 
        drop("#answers ul .reponseQuizImage li#"+i, "#form_reponseImage"+i);
    }
  

})(jQuery);

