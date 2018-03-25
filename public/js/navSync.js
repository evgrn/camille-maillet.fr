$(function(){

    /**
     * Objet permettant l'ajout / la suppression dynamique de la classe "active" à chaque élément du menu de navigation
     * en fonction du scrollTop.
     * @type {{menuItems: string, init: init, listenScroll: listenScroll, manageActivations: manageActivations}}
     */
    var navSync = {

        /**
         * Sélecteur des éléments du menu de navigation
         */
        menuItems: '',

        /**
         * Stocke le sélecteur des éléments du menu de navigation dans l'attribut "menuItems".
         * @param menu
         */
        init: function(menu){
            this.menuItems = menu + ' li a';
            this.manageActivations();
            this.listenScroll();
        },

        /**
         * Ajoute / supprime dynamiquement la classe "active"
         * aux éléments du menu lors du scroll.
         */
        listenScroll: function(){
            $(document).on('scroll', function(){
                navSync.manageActivations();
            })
        },

        /**
         * Pour chaque élément du menu, si l'offset de la section associée est inférieur au scrollTop + 5px,
         * la classe active lui est associée et retirée aux autres.
         * Si l'offset est supérieur à la veleur précitée, la classe "active" lui est retirée.
         */
        manageActivations: function(){
            var scrollTop = $(window).scrollTop();

            $(this.menuItems).each(function(){
                var currentItem = $(this);
                var linkedElement = $(currentItem.attr("href"));

                if ($(linkedElement).offset().top <= scrollTop + 5 ) {
                    $(navSync.menuItems).parent().removeClass("active");
                    currentItem.parent().addClass("active");
                }
                else{
                    currentItem.removeClass("active");}
            });
        }

    };

    navSync.init('#main-nav');

});