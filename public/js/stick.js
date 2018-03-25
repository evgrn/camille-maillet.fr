$(function(){

    /**
     * Objet permettant de bloquer un élément au haut de la fenêtre lorsqu'il y est.
     */
    var stick = {
        /**
         * Élément à bloquer
         */
        stickyElement: '',

        /**
         * Offset de l'élément
         */
        stickyElementOffset: '',

        /**
         * @param stickyElement Élément à bloqiuer
         *
         * Stocke le selecteur de l'élément à bloquer dans la propriété "stickyElement",
         * initialise son offset, le vérouille si sa position  est plus haute ou au niveau du haut de la fenêtre,
         * initialise l'écoute de la modification de la taille de la fenêtre et du scroll de l'utilisateur.
         *
         */
        init: function (stickyElement) {
            this.stickyElement = stickyElement;
            this.initStickyElementOffset();
            this.handleStickyness();
            this.listenResize();
            this.listenScroll();
        },

        /**
         * Récupère l'offset de l'éménent à bloquer et le stocke dans la propriété "stickyOffset"
         */
        initStickyElementOffset: function(){
            this.stickyElementOffset = $(this.stickyElement).offset().top;
        },

        /**
         * Bloque l'élément en haut de la fenêtre en lui ajoutant la classe 'fixed-top'
         */
        stick: function () {
            $(this.stickyElement).addClass('fixed-top');
        },

        /*
         * Débloque l'élément en haut de la fenêtre en lui enlevant la classe 'fixed-top'
         */
        unstick: function () {
            $(this.stickyElement).removeClass('fixed-top');
        },

        /**
         * @returns {boolean}
         *
         * Vérifie si la position de l'élément est plus haute ou au niveau du haut de la fenêtre.
         * Renvoie true si oui, false sinon.
         */
        isSticky: function () {
            if (this.stickyElementOffset - $(window).scrollTop() <= 0) {
                return true;
            }
            return false;
        },

        /**
         * Si la position de l'élément est plus haute ou au niveau du haut de la fenêtre, vérouille l'élément, sinon le dévérouille.
         */
        handleStickyness: function () {
            if (this.isSticky()) {
                this.stick();
            } else {
                this.unstick();
            }
        },

        /**
         * Vérifie si l'lément doit être vérouillé au scroll de l'utilisateur.
         */
        listenScroll: function () {
            $(window).on('scroll', function () {
                stick.handleStickyness();
            })
        },

        /**
         * Met à jour la propriété "stickyOffset" avec la nouvelle valeur de l'offset de l'éménent à bloquer
         * en cas de changement de taille de la fenêtre.
         */
        listenResize: function(){
            $(window).on('resize', function(){
                if(!stick.isSticky()){
                    stick.initStickyElementOffset();
                }
                else{
                    var hasBeenResized = false;
                    $(this).on('scroll', function(){
                        if(!hasBeenResized){
                            if(!stick.isSticky()){
                                stick.initStickyElementOffset();
                                hasBeenResized = true;
                            }
                        }
                    });
                }

            })
        }


    };

    stick.init('#global-nav');

});