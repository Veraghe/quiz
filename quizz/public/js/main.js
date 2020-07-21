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

    $("#answers ul li").droppable({
        drop: function (event, ui) {
            var dropped = ui.draggable;
            var droppedOn = this;

            if ($(droppedOn).children().length > 0) {
                $(droppedOn).children().detach().prependTo($(lastPlace));
            }

            $(dropped).detach().css({
                top: 0,
                left: 0
            }).prependTo($(droppedOn));
        }
    });
})(jQuery);