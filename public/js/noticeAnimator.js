$(function(){

    /**
     * Objet permettant d'animer l'apparition et la disparition des notices.
     * @type {{noticeSelector: string, timeout: string, init: init, run: run}}
     */
    var noticeAnimator = {

        /**
         * Sélecteur des notices
         */
        noticeSelector: '',

        /**
         * Durée d'affichage des notices
         */
        timeout: '',

        /**
         *
         * @param noticeSelector
         * @param timeout
         */
        init: function(noticeSelector, timeout){
            this.noticeSelector = noticeSelector;
            this.timeout = timeout;
            this.run();
        },

        /**
         * Affichage des notices pendant la durée définie, puis disparition.
         */
        run: function(){
            $(this.noticeSelector).removeClass('hidden').show("drop", {direction: "right"});
            setTimeout(function(){
                $(noticeAnimator.noticeSelector).removeClass('hidden').hide("drop", {direction: "right"});
            }, this.timeout);
        }

    };

    noticeAnimator.init('.notice', 2000);
});