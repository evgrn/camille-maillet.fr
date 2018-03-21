$(function(){

    var changingText = {
        selector: '',
        wordList: [],
        currentWordIndex: -1,


        init: function (wordlist, selector = '.changing-text') {
            this.selector = selector;
            this.wordList = wordlist;
            this.replaceWord();
            changingText.run();
        },

        getNextWord: function () {
            if (this.currentWordIndex < this.wordList.length - 1) {
                this.currentWordIndex++;
            }
            else {
                this.currentWordIndex = 0;
            }
            return this.wordList[this.currentWordIndex];
        },

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

        run: function () {

            var interval = window.setInterval(changingText.showNextWord, 3000);
        }

    };

    changingText.init(['web', 'php', 'javascipt', 'html5', 'symfony4', 'jquery', 'css3']);

});