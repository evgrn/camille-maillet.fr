$(function(){

    contactWindowManager = {

        /**
         * Sélecteur de la fenêtre des réalisations
         */
        windowSelector: '',

        /**
         * Sélecteur du formulaire de contact
         */
        formSelector: '',

        /**
         * Sélecteur de l'élément dont le clic affiche la page de contact
         */
        triggerSelector: '',

        /**
         * Sélecteur du loader
         */
        loaderSelector: '',

        /**
         * Sélecteur de la boîte à messages
         */
        messageBoxSelector: '',

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
         * Sélecteur de l'élément dont le clic cache la page de contact et affiche la page centrale.
         */
        backButton: '',

        /**
         * Sélecteur des éléments dont le scroll sera vérouillé lors de l'affichage de la fenêtre de contact.
         */
        mustsNotScroll: '',

        /**
         * Sélecteur des éléments qui seront cachés lors de l'affichage de la fenêtre de contact.
         */
        mustsDisappear: '',

        /**
         * Si la fenêtre est affichée, vaut true, sinon false.
         */
        isWindowActive: '',

        /**
         * Si le message a été envoyé, vaut true, sinon false.
         */
        messageSent: false,

        /**
         * Message affiché dans la boîte à messages en cas de succès de soumission du formulaire.
         */
        successMessage: 'Message envoyé',

        /**
         * Message affiché dans la boîte à messages en cas d'échec de soumission du formulaire.
         */
        errorMessage: 'Message non envoyé, veuillez réessayer',

        /**
         *
         * @param windowSelector
         * @param triggerSelector
         * @param formSelector
         * @param loaderSelector
         * @param messageBoxSelector
         * @param centerPage
         * @param menu
         * @param backButton
         * @param mustsNotScroll
         * @param mustsDisappear
         */
        init: function(windowSelector, triggerSelector, formSelector, loaderSelector, messageBoxSelector, centerPage, menu, backButton, mustsNotScroll, mustsDisappear){
            this.windowSelector = windowSelector;
            this.triggerSelector = triggerSelector;
            this.formSelector = formSelector;
            this.loaderSelector = loaderSelector;
            this.messageBoxSelector = messageBoxSelector;
            this.menu = menu;
            this.menuItems = menu + ' li';
            this.backButton = backButton;
            this.centerPage = centerPage;
            this.mustNotScroll = mustsNotScroll;
            this.mustsDisappear= mustsDisappear;
            this.listenClicks();
            this.form.listenSubmit();
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
         * Afiche la fenêtre de contact, empêche le scroll sur les éléments désignés,
         * affiche le bouton de retour à la fenêtre centrale et cache les autres éléments du menu de navigation.
         *
         */
        handleShowing: function () {
            if(!this.messageSent){
                var timeout = this.adaptScrollTop();
                if (!this.isWindowActive) {
                    setTimeout(function () {
                        contactWindowManager.isWindowActive = true;
                        contactWindowManager.stopScrollElsewhere();
                        contactWindowManager.showBackButton();
                        contactWindowManager.showWindow();
                        contactWindowManager.hideToHideElement();
                    }, timeout)
                }
            }

        },

        /**
         * Cache les éléments désignés
         */
        hideToHideElement: function(){
            $(contactWindowManager.mustsDisappear).fadeOut(500);
        },

        /**
         * Affiche les éléments désignés.
         */
        showToHideElement: function(){
            $(contactWindowManager.mustsDisappear).fadeIn(500);
        },


        /**
         * Affiche le bouton de retour à la fenêtre centrale et cache les autres éléments du menu de navigation.
         */
        showBackButton: function () {
            $(this.menuItems).not(this.backButton).hide("fade", 500);
            $(this.backButton).show("fade", 500).addClass("active");
        },

        /**
         * Décale la fenêtre centrale à 100% à droite et la page de contact au centre.
         */
        showWindow: function(){
            $(this.centerPage).animate({"right": "-100%"}, 500);
            $(this.windowSelector).animate({"right": "0"}, 500);
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
            $(this.mustNotScroll).css('overflow-y', 'hidden');
            var offsetTop = '-' + $(window).scrollTop() + 'px';
            $(this.mustNotScroll).css('position', 'fixed');
            $(this.mustNotScroll).css('top', offsetTop);
        },

        /**
         * Supprime la position fixée des éléments bloqués et scroll la page
         * jusqu'à la position qu'ils avaient avant d'être fixés.
         */
        resetScrollElsewhere: function(){
            $(this.menu).attr('style', '');

            var scrollTop =  $(this.mustNotScroll).offset().top * -1;
            $(this.mustNotScroll).attr('style', '');
            $('html, body').scrollTop(scrollTop);

        },


        /**
         * Cache la fenêtre de contact, autorise le scroll sur les éléments désignés,
         * cache le bouton de retour à la fenêtre centrale et affiche les autres éléments du menu de navigation.
         */
        handleHiding: function () {
            if (this.isWindowActive) {
                this.isWindowActive = false;
                this.hideBackButton();
                this.hideWindow();
                this.showToHideElement();
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
         * Place la page centrale au centre et décale la fenêtre de contact à 100% à gauche.
         */
        hideWindow: function () {
            $(this.centerPage).animate({"right": "0"}, 500);
            $(this.windowSelector).animate({"right": "100%"}, 500);
        },

        /**
         * Assigne l'affichage de la page de contact au clic sur l'élément déclancheur désigné
         * et sa disparition au click sur l'élément de retour désigné.
         */
        listenClicks: function(){
            $(this.triggerSelector).on('click', function(){
                contactWindowManager.handleShowing();
            });
            $(this.backButton).on('click', function(){
                contactWindowManager.handleHiding();
            });
        },

        /**
         * Affiche le message correspondant au paramètre "status" dans la boîte à messages.
         * @param status
         */
        showMessage: function(status){
            $(this.messageBoxSelector).removeAttr('class');
            if(status === 'success'){
                $(this.messageBoxSelector).text(this.successMessage).addClass('success').addClass('active');
            }else{
                $(this.messageBoxSelector).text(this.errorMessage).addClass('error').addClass('active');
            }
        },


        form: {

            /**
             * A la soumission du formulaire de contact, empêche la soumission directe,
             * affiche le loader et envoie les données au contrôleur pour qu'ils soient stockés en BDD
             * et qu'une notification soit envoyée par mail à l'admnistrateur.
             * En cas de succès, cache le le loader et la page de contact, vérouille le formulaire et affiche le message de succès,
             * En cas d'échec, cache le loader et affiche le message d'erreur.
             */
            listenSubmit: function(){
                $(contactWindowManager.formSelector).on('submit', function(e) {
                    e.preventDefault();
                    contactWindowManager.form.showLoader();
                    var formSerialize = $(this).serialize();

                    $.ajax({
                        url: '',
                        type: "post",
                        async: true,
                        data: formSerialize,
                        dataType: 'json',
                        success: function(data) {
                            contactWindowManager.form.hideLoader();
                            contactWindowManager.handleHiding();
                            contactWindowManager.form.lock();
                            contactWindowManager.showMessage('success');
                        },
                        error: function(){
                            contactWindowManager.form.hideLoader();
                            contactWindowManager.showMessage('error');
                        }
                    });
                });
            },

            /**
             * Si le formulaire a déjà été soumis, empêche un second accès à celui-ci.
             */
            lock: function(){
                this.messageSent = true;
                $(this.triggerSelector).removeClass('blink').addClass('locked');
            },

            /**
             * Affichage du loader de soumission du formulaire de contact
             */
            showLoader: function(){
                $(contactWindowManager.loaderSelector).fadeIn(500);
            },

            /**
             * Disparition du loader de soumission du formulaire de contact
             */
            hideLoader: function(){
                $(contactWindowManager.loaderSelector).fadeOut(500);
            }

        }

    };

    contactWindowManager.init('#left-page', '#show-left-page', 'form', '#sending-loader', '#message-notification', '#main-content', '#global-nav', '#back', 'body', 'footer');

});