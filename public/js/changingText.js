$(function(){

    /**
     * Objet affichant un texte changeant
     * @type {{selector: string, wordList: Array, currentWordIndex: number, init: init, getNextWord: getNextWord, replaceWord: replaceWord, showNextWord: showNextWord, run: run}}
     */
    var changingText = {

        /**
         * Sélecteur de la balise qui contiendra le texte changeant
         */
        selector: '',

        /**
         * Liste des chaines de caractères qui apparaîtront dans la balise
         */
        wordList: [],

        /**
         * Index dans le tableau "wordList" du mot actuellement affiché.
         */
        currentWordIndex: -1,

        /**
         *
         * @param wordlist
         * @param selector
         */
        init: function (wordlist, selector = '.changing-text') {
            this.selector = selector;
            this.wordList = wordlist;
            this.replaceWord();
            changingText.run();
        },

        /**
         * Si le mot actuellement affiché n'est pas le dernier du tableau, retourne le suivant,
         * retourne le premier sinon.
         * @returns string
         */
        getNextWord: function () {
            if (this.currentWordIndex < this.wordList.length - 1) {
                this.currentWordIndex++;
            }
            else {
                this.currentWordIndex = 0;
            }
            return this.wordList[this.currentWordIndex];
        },

        /**
         * Remplace le mot actuellement affiché par le suivant s'il n'est pas le dernier,
         * sinon par le premier.
         */
        replaceWord: function () {
            var currentWord = changingText.getNextWord();
            $(this.selector).animate({opacity: 0}, 500, function () {
                $(changingText.selector).text(currentWord);
            });
            setTimeout(function () {
                $(changingText.selector).animate({opacity: 1}, 1000)
            });
        },

        showNextWord: function () {
            changingText.replaceWord();
        },

        /**
         * Change de mot toutes les 3 secondes.
         */
        run: function () {

            var interval = window.setInterval(changingText.showNextWord, 3000);
        }

    };

    changingText.init(['web', 'php', 'javascipt', 'html5', 'symfony4', 'jquery', 'css3']);

});