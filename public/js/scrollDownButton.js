$(function(){

    /**
     * Objet permettant de gérer le bouton de scrollDown.
     * @type {{buttonSelector: string, sectionSelector: string, speed: number, init: init, listenClick: listenClick, toggleButton: toggleButton, hideButton: hideButton, showButton: showButton, listenScroll: listenScroll}}
     */
    var scrollDownButton = {

        /**
         * Sélecteur du bouton de scrollDown
         */
        buttonSelector: '',

        /**
         * Sélecteur de la section à cacher par le scroll au  clic sur le bouton de scrollDown
         */
        sectionSelector: '',

        /**
         * Vitesse du scroll
         */
        speed: 750,

        /**
         *
         * @param buttonSelector
         * @param sectionSelector
         * @param speed
         */
        init: function (buttonSelector, sectionSelector, speed) {
            this.buttonSelector = buttonSelector;
            this.sectionSelector = sectionSelector
            this.speed = speed;
            setTimeout(this.toggleButton, 1500);
            this.listenClick();
            this.listenScroll();
        },

        /**
         * Scroll au clic du bouton scrollDown
         */
        listenClick: function () {
            $(this.buttonSelector).on('click', function () {
                $('html, body').animate({scrollTop: $(scrollDownButton.sectionSelector).height()}, scrollDownButton.speed);
            });
        },

        /**
         * Cache le bouton scrollDown si l'offset de la section à cacher est inférieur au dessus de la fenêtre,
         * le montre sinon.
         */
        toggleButton: function () {
            if ($(scrollDownButton.sectionSelector).offset().top < $(window).scrollTop()) {
                scrollDownButton.hideButton();
            }
            else {
                scrollDownButton.showButton();
            }
        },

        /**
         * Cache le bouton scrollDown et désactive ses animations
         */
        hideButton: function () {
            $(this.buttonSelector).css('animation', 'none').hide("fade", 500);
        },

        /**
         * Montre le bouton scrollDown et lui assigne ses animations
         */
        showButton: function () {
            $(this.buttonSelector).show("drop", {direction: "down"}, 500, function () {
                $(scrollDownButton.buttonSelector).css('animation', 'shake 3s infinite ease');
            });
        },

        /**
         * Au scroll de l'utilisateur, cache le bouton scrollDown si l'offset de la section à cacher est inférieur au dessus de la fenêtre,
         * le montre sinon.
         */
        listenScroll: function () {
            $(window).on('scroll', function () {
                scrollDownButton.toggleButton();
            });
        }


    };

    scrollDownButton.init('#intro-scrolldown', '#intro', 750);
});