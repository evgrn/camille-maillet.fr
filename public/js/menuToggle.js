$(function(){

    /**
     * Objet gérant l'affichage et la disparition du menu principal en orientation portrait
     * @type {{toggleSelector: string, menusSelector: string, globalSelector: string, init: init, toggle: toggle, listenClick: listenClick, handleScroll: handleScroll}}
     */
    var menuToggle = {

        /**
         * Sélecteur de l'élement au clic duquel le menu est affiché / caché
         */
        toggleSelector: '',

        /**
         * Sélecteur du menu
         */
        menuSelector: '',

        /**
         * Sélecteur ciblant l'élément toggle et le menu.
         */
        globalSelector: '',

        /**
         *
         * @param toggleSelector
         * @param menuSelector
         */
        init: function(toggleSelector, menuSelector){
            this.toggleSelector = toggleSelector;
            this.menuSelector = menuSelector;
            this.globalSelector = this.toggleSelector + ', ' + this.menuSelector + ' li';
            this.listenClick();
        },

        /**
         * Ajout / suppression dynamique de la classe "mobile-hidden",
         * qui cache les éléments en orientation portrait.
         */
        toggle: function(){
            $(this.menuSelector).toggleClass('mobile-hidden');
        },

        /**
         * Au clic sur l'élément ciblé par le sélecteur "toggleSelector", ajoute / supprime dynamiquement la classe "mobile-hidden" au menu.
         * Si le toggleSelector n'est pas en haut de la fenêtre,
         * scrolle jusqu'à ce qu'il le soit.
         */
        listenClick: function(){
            $(this.globalSelector).on('click', function(){
              menuToggle.toggle();
              menuToggle.handleScroll();
            })
        },

        /**
         * Si l'élément ciblé par le sélecteur "toggleSelector" n'est pas en haut de la fenêtre,
         * scrolle jusqu'à ce qu'il le soit.
         */
        handleScroll: function () {
            if($(this.toggleSelector).offset().top > $(window).scrollTop()){
                $('html, body').animate({scrollTop: $(menuToggle.toggleSelector).offset().top}, 500);
            }

        }

    };

    menuToggle.init('#menu-toggle', '#main-nav');

});