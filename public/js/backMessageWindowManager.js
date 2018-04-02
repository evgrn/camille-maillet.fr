$(function(){
    /**
     * Objet permettant d'afficher la fenêtre de lecture d'un message.
     * @type {{messageWindow: string, closeButton: string, init: init, close: close, open: open, listenClick: listenClick, hydrateMessage: hydrateMessage}}
     */
    var backMessageWindowManager = {

        /**
         * Selecteur de la fenêtre dee lecture du message
         */
        messageWindow: '',

        /**
         * Selecteur de l'élément au clic duquel la fenêtre de lecture du message disparaît.
         */
        closeButton: '',

        /**
         *
         * @param trigger
         * @param messageWindow
         * @param closeButton
         */
        init: function(trigger, messageWindow, closeButton){
            this.trigger = trigger;
            this.messageWindow = messageWindow;
            this.closeButton = closeButton;
            $(this.messageWindow).hide();
            this.listenClick();
        },

        /**
         * Disparition de la fenêtre de lecture du message.
         */
        close: function(){
            $(this.messageWindow).hide("drop", {direction: "down"});
        },

        /**
         * Affichage de la fenêtre de lecture du message.
         */
        open: function(){
            $(this.messageWindow).removeClass('hidden').show("drop", {direction: "down"});
        },

        /**
         * Fermeture de la fenêtre de lecture du message au clic de l'élément désigné,
         * Hydratation et affichage de la fenêtre de lecture du message au clic de l'élément désigné.
         */
        listenClick: function(){
            $(this.closeButton).on('click', function(){
                backMessageWindowManager.close();
            });

            $(this.trigger).on('click', function(){
                backMessageWindowManager.hydrateMessage(this);
                backMessageWindowManager.open();
            })
        },

        /**
         * Hydratation de la fenêtre de lecture du message par les valeurs des attributs du paramètre "message".
         * @param message
         */
        hydrateMessage: function(message){

            // Récupération des valeurs des attributs
            var author = $(message).attr('author');
            var email = $(message).attr('email');
            var tel = $(message).attr('tel');
            var object = $(message).attr('object');
            var content = $(message).attr('message');
            var date = $(message).attr('date');

            // Hydratation de la fenêtre de lecture du message.
            $('.message-object').text(object);
            $('.contact-name span').text(author);
            $('.contact-email span').text(email);
            $('.contact-tel span').text(tel);
            $('.message-content span').html(content);
            $('.message-date span').html(date);
        },
    };

    backMessageWindowManager.init('.show-message', '#message-single', '#message-window-close');
});