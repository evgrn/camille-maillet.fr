$(function(){

    /**
     * Objet permetant d'effectuer une animation de scroll vers l'ancre correspondant au lien cliqué.
     * @type {{menuSelector: string, speed: number, init: init, scroll: scroll, offsetMargin: offsetMargin}}
     */
    var smoothScroll = {

        /**
         * Menu contenant les liens
         */
        menuSelector: "",

        /**
         * Durée du scroll
         */
        speed: 750,

        /**
         * Stocke le selecteur du menu dans la propriété "menuSelector" et la durée du scroll dans la propriété "speed",
         * active le scroll au clic du lien.
         *
         * @param menuSelector
         * @param speed
         */
        init: function (menuSelector, speed) {
            this.menuSelector = menuSelector;
            this.speed = speed;
            this.scroll();
        },

        /**
         * Lie le scroll vers l'ancre à chaque lien du menu.
         * Ajuste la position du scroll selon l'orientation de la fenêtre.
         */
        scroll: function () {
            var selector = this.menuSelector + ' a';
            $.each($(selector), function () {
                var link = this;
                $(this).parent().on('click', function () {
                    var section = $(link).attr('href');
                    $('html, body').animate({scrollTop: $(section).offset().top - smoothScroll.offsetMargin()}, smoothScroll.speed);
                    return false;

                });


            });
        },

        /**
         * Définit le décalage du scroll par rapport au menu de navigation selon l'orientation de la fenêtre.
         * @returns {number}
         */
        offsetMargin: function(){
            if($(window).height() >= $(window).width() ){
                return 40;
            }
            else{
                return 0;
            }
        }
    };

    smoothScroll.init('#main-nav', 750);
});