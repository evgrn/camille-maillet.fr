$(function(){

    /**
     * Objet permettant de gérer la partie "réalisations" du site.
     * @type {{windowSelector: string, mosaicTarget: string, isWindowActive: string, menuItems: string, backButton: string, centerPage: string, mustsNotScroll: string, loader: string, init: productionWindowManager.init, isScrollTopOk: productionWindowManager.isScrollTopOk, adaptScrollTop: productionWindowManager.adaptScrollTop, handleShowing: productionWindowManager.handleShowing, showBackButton: productionWindowManager.showBackButton, showWindow: productionWindowManager.showWindow, stopScrollElsewhere: productionWindowManager.stopScrollElsewhere, resetScrollElsewhere: productionWindowManager.resetScrollElsewhere, handleHiding: productionWindowManager.handleHiding, hideBackButton: productionWindowManager.hideBackButton, hideWindow: productionWindowManager.hideWindow, listenClosing: productionWindowManager.listenClosing, productions: {hydrateSingleProductionPage: productionWindowManager.productions.hydrateSingleProductionPage, showCategory: productionWindowManager.productions.showCategory, generateMenuElement: productionWindowManager.productions.generateMenuElement, generateMenu: productionWindowManager.productions.generateMenu, initProductionsMenu: productionWindowManager.productions.initProductionsMenu, generateMosaicElement: productionWindowManager.productions.generateMosaicElement, showLoader: productionWindowManager.productions.showLoader, hideLoader: productionWindowManager.productions.hideLoader, initTrigger: productionWindowManager.productions.initTrigger}}}
     */
    productionWindowManager = {

        /**
         * Sélecteur de la fenêtre des réalisations
         */
        windowSelector: '',

        /**
         * Sélecteur de la mosaïque des productions
         */
        mosaicTarget: '',

        /**
         * Sélecteur de la page centrale
         */
        centerPage: '',

        /**
         * Sélecteur du menu
         */
        menu: '',

        /**
         * Sélecteur des éléments du menu
         */
        menuItems: '',

        /**
         * Sélecteur des éléments du sous-menu comportant les catégories de réalisation
         */
        submenu: '',

        /**
         * Si la fenêtre est affichée, vaut true, sinon false.
         */
        isWindowActive: '',

        /**
         * Sélecteur du bouton permettant de revenir à la page centrale
         */
        backButton: '',

        /**
         * Sélecteur des éléments dont le scroll sera vérouillé lors de l'affichage de la fenêtre de production.
         */
        mustsNotScroll: '',

        /**
         * Loader s'affichant lors du chargement de la mosaïque
         */
        loader: '',

        /**
         *
         * @param rightWindow
         * @param mosaicTarget
         * @param centerPage
         * @param menu
         * @param submenu
         * @param backButton
         * @param mustsNotScroll
         * @param loader
         */
        init: function(rightWindow, mosaicTarget, centerPage, menu, submenu, backButton, mustsNotScroll, loader){
            this.windowSelector = rightWindow;
            this.mosaicTarget = mosaicTarget;
            this.menu = menu;
            this.menuItems = menu + ' li';
            this.submenu = submenu;
            this.submenuItems = submenu + ' li';
            this.backButton = backButton;
            this.centerPage = centerPage;
            this.mustsNotScroll = mustsNotScroll;
            this.loader = loader;
            this.productions.showLoader();
            this.productions.initProductionsMenu();
            this.productions.showCategory(0);
            this.listenClosing();
        },

        /**
         * Retourne true si la section d'introduction est cachée, et donc le menu fixé, sinon retourne false.
         * @returns {boolean}
         */
        isScrollTopOk: function(){
            return ($(window).scrollTop() > $(window).height());
        },

        /**
         * Si le menu n'est pas fixé, scrolle jusqu'à ce qu'il le soit et retourne 500, sinon retourne 0.
         * @returns {number}
         */
        adaptScrollTop: function(){
            if (!this.isScrollTopOk()) {
                $('html, body').animate({scrollTop: $(window).height()}, 300);
                return  500;
            }
            else {
                return  0;
            }
        },

        /**
         * Afiche la fenêtre de réalisation, empêche le scroll sur les éléments désignés,
         * affiche le bouton de retour à la fenêtre centrale et cache les autres éléments du menu de navigation.
         *
         */
        handleShowing: function () {
            var timeout = this.adaptScrollTop();
            if (!this.isWindowActive) {
                setTimeout(function () {
                    productionWindowManager.isWindowActive = true;
                    productionWindowManager.stopScrollElsewhere();
                    productionWindowManager.showBackButton();
                    productionWindowManager.showWindow();
                }, timeout)
            }
        },

        /**
         * Affiche le bouton de retour à la fenêtre centrale et cache les autres éléments du menu de navigation.
         */
        showBackButton: function () {
            $(this.menuItems).not(this.backButton).hide("fade", 500);
            $(this.backButton).show("fade", 500).addClass("active");
        },

        /**
         * Décale la fenêtre centrale à 100% à gauche et la page de production au centre.
         */
        showWindow: function(){
            $(this.centerPage).animate({"right": "+100%"}, 500);
            $(this.windowSelector).animate({"left": "0"}, 500);
        },

        /**
         * Fixe le menu de navigation et empêche le scroll des éléments qui ne doivent pas êtres scrollables en les fixant.
         */
        stopScrollElsewhere: function(){
            // Menu fixé
            $(this.menu).css('position', 'fixed');
            $(this.menu).css('overflow', 'hidden');
            $(this.menu).css('top', '0');

            // Reste de l'app fixé
            $(this.mustsNotScroll).css('overflow-y', 'hidden');
            var offsetTop = '-' + $(window).scrollTop() + 'px';
            $(this.mustsNotScroll).css('position', 'fixed');
            $(this.mustsNotScroll).css('top', offsetTop);
        },

        /**
         * Supprime la position fixée des éléments bloqués et scroll la page
         * jusqu'à la position qu'ils avaient avant d'être fixés.
         */
        resetScrollElsewhere: function(){
            $(this.menu).attr('style', '');

            var scrollTop =  $(this.mustsNotScroll).offset().top * -1;
            $(this.mustsNotScroll).attr('style', '');
            $('html, body').scrollTop(scrollTop);

        },

        /**
         * Cache la fenêtre de production, autorise le scroll sur les éléments désignés,
         * cache le bouton de retour à la fenêtre centrale et affiche les autres éléments du menu de navigation.
         */
        handleHiding: function () {
            if (this.isWindowActive) {
                this.isWindowActive = false;
                this.hideBackButton();
                this.hideWindow();
                this.resetScrollElsewhere();
            }
        },

        /**
         * Cache le bouton de retour à la page centrale et affiche les autres éléments du menu de navigation.
         */
        hideBackButton: function () {
            $(this.backButton).removeClass("active").hide("fade", 500);
            $(this.menuItems).not(this.backButton).show("fade", 500);

        },

        /**
         * Place la page centrale au centre et décale la fenêtre de réalisation à 100% à droite.
         */
        hideWindow: function () {
            $(this.centerPage).animate({"right": "0"}, 500);
            $(this.windowSelector).animate({"left": "100%"}, 500);

        },

        /**
         * Assigne l'affichage de la page centrale au clic sur le bouton de retour à la page centrale.
         */
        listenClosing: function(){
            $(this.backButton).on('click', function(){
                productionWindowManager.handleHiding();
            });
        },


        productions: {


            /**
             * Récupération des catégories de réalisation et création du sous-menu correspondant
             */
            initProductionsMenu: function(){
                $.ajax({
                    url: '/productions/categories',
                    type: "post",
                    async: true,
                    data: '',
                    dataType: 'json',
                    success: function(data) {
                        productionWindowManager.productions.generateMenu(data);
                    },
                    error: function(){
                        console.log('Erreur lors du chargement des catégories de réalisation');
                    }
                });
            },

            /**
             * Génération du sous-menu des catégories de réalisation
             * @param data
             */
            generateMenu: function(data){
                $(productionWindowManager.submenu).append(productionWindowManager.productions.generateMenuElement("Tout", 0));
                $.map( data, function( category ) {
                    $(productionWindowManager.submenu).append(productionWindowManager.productions.generateMenuElement(category['name'], category['id']));
                });
            },

            /**
             * Génération d'un élément du sous-menu des catégories de réalisation
             * @param name
             * @param id
             * @returns {HTMLLIElement}
             */
            generateMenuElement: function(name, id){
                var element = document.createElement('li');
                element.setAttribute('categoryId', id);
                if(id === 0){
                    element.setAttribute('class', 'active');
                }
                $(element).text(name);

                $(element).on('click', function(){
                    productionWindowManager.productions.showLoader();
                    $(productionWindowManager.submenuItems).not(this).removeClass('active');
                    $(this).addClass('active');
                    productionWindowManager.productions.showCategory($(this).attr('categoryId'));
                });

                return element;
            },

            /**
             * Génèration puis affichage des réalisations de la catégorie associée à l'id entré en paramètre dans la mosaïque
             * @param id
             */
            showCategory: function(id){
                $.ajax({
                    url: '/productions/' + id,
                    type: "post",
                    async: true,
                    data: '',
                    dataType: 'json',
                    success: function(data) {

                        // Vidange de la mosaïque
                        $(productionWindowManager.mosaicTarget).empty();
                        // Génération puis affichage des réalisations
                        $.map( data['productions'], function( production ) {
                            $(productionWindowManager.mosaicTarget).append(productionWindowManager.productions.generateMosaicElement(production, data['imagesDirectory']));
                        });
                        // Disparition du loader
                        productionWindowManager.productions.hideLoader();

                    },
                    error: function(){
                        console.log('Erreur de chargement des aperçus de réalisations');
                    }
                });
            },

            /**
             * Génération d'un élément de la mosaïque.
             * @param production
             * @param imagesDirectory
             * @returns {HTMLDivElement}
             */
            generateMosaicElement: function(production, imagesDirectory){

                var element = document.createElement('div');
                element.setAttribute('name', production['name']);
                element.setAttribute('url', production['url']);
                element.setAttribute('github', production['github']);
                element.setAttribute('image', imagesDirectory + '/' + production['image']);
                element.setAttribute('description', production['description']);
                element.setAttribute('category', production['category']);
                element.setAttribute('technologies', production['technologies']);
                element.setAttribute('preview', imagesDirectory + '/' + production['preview']);
                element.setAttribute('class', 'col-sm-4 col-xs-6 production-preview');
                $(element).html('<img src="' + imagesDirectory + '/' + production['thumbnail'] + '" alt="' + production['name'] + '">' );

                this.initTrigger(element);

                return element;
            },

            /**
             * Au clic de l'élément de la mosaïque, hydratation par les données correspondantes puis affichage de la page de production.
             * @param mosaicElement
             */
            initTrigger: function(mosaicElement){
                $(mosaicElement).on('click', function() {
                    productionWindowManager.productions.hydrateSingleProductionPage(this);
                    productionWindowManager.handleShowing();
                });
            },

            /**
             * Récupération des données stockées dans les attributs de l'élément "productionPreview" entré en paramètre
             * et hydrate la page de production.
             * @param productionPreview
             */
            hydrateSingleProductionPage: function(productionPreview){

                /*### Récupération des valeurs des attributs ### */

                // Création d'un lien à partir de l'attribut "url"
                var url = $(productionPreview).attr('url');
                var link = '<a href="' + url + '" target="_blank">' + url + '</a>';

                // Création d'un lien à partir de l'attribut "github" s'il ne vaut pas "null"
                // Remplacement par le texte "Non disponible" sinon
                var gitHub = $(productionPreview).attr('github');

                if(gitHub != 'null'){
                    gitHub = '<a href="' + gitHub + '" target="_blank">' + gitHub + '</a>';
                }
                else{
                    gitHub = "Non disponible";
                }

                // Récupération directe des autres valeurs
                var name = $(productionPreview).attr('name');
                var image = $(productionPreview).attr('image');
                var preview = $(productionPreview).attr('preview');
                var description = $(productionPreview).attr('description');
                var category = $(productionPreview).attr('category');
                var technologies = $(productionPreview).attr('technologies');

                // Hydratation de la page de production
                $('#production-single-header-image-container').html(' <img id="production-single-image" src="' + image + '" alt="' + name + '" />');
                $('#production-single-title').text(name);
                $('#production-single-preview').html('<img id="production-single-preview-image" src="' + preview + '" alt=" Aperçu du site ' + name + '" />');
                $('#production-single-category span').text(category);
                $('#production-single-technologies span').text(technologies);
                $('#production-single-url span').html(link);
                $('#production-single-github span').html(gitHub);
                $('#production-single-description-content-text').html(description);
                $('#production-single-link').html('<a href="' + url + '" class="btn btn-primary pull-right" target="_blank">Visiter</a>');
            },

            /**
             * Affichage du loader de la mosaïque
             */
            showLoader: function(){
                $(productionWindowManager.loader).fadeIn(300);
            },

            /**
             * Disparition du loader de la mosaïque
             */
            hideLoader: function(){
                $(productionWindowManager.loader).fadeOut(300);
            }


        }

    };

    productionWindowManager.init('#right-page', '#mosaic', '#main-content', '#global-nav', '#productions-nav', '#back', 'body, .main-content', '#mosaic-loader');

});