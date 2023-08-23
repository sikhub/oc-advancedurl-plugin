+function ($) { "use strict";

    const $advancedToggle = $('.advanced-toggle')
    const $advancedCard = $('.advanced-card')

    $(window).on($advancedToggle, 'click', function(e) {
        e.preventDefault()
        $advancedCard.toggle()
    })

    console.log("test")

}(window.jQuery);
