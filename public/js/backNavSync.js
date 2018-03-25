$(function(){

    /**
     * Objet permettant d'ajouter la classe "active" à l'élément du menu de navigation dont l'attribut "page" correspond à l'id de la page courante.
     * @type {{navItems: string, pageName: string, init: init, sync: sync}}
     */
    var backNavSync = {

        /**
         * Sélecteur des éléments du menu de navigation
         */
        navItems: '',

        /**
         * Id de la page
         */
        pageName: '',

        /**
         *
         * @param navItems
         * @param pageContainer
         */
        init: function(navItems, pageContainer){
            this.navItems = navItems;
            this.pageName = $(pageContainer).attr('id');
            this.sync();
        },

        /**
         * Ajoute la classe "active" à l'élément du menu de navigation dont l'attribut "page" correspond à l'id de la page courante
         * et la retire à tous les autres.
         */
        sync: function(){
            $.each($(this.navItems), function(){
                if($(this).attr('page') === backNavSync.pageName){
                    $(this).addClass('active');
                }
                else{
                    $(this).removeClass('active');
                }
            })
        }

    };

    backNavSync.init('#main-nav ul.nav li', '.page-container');

});