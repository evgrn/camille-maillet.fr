$(function(){


    var windowManager;

    var stick;

    stick = {
        stickyElement: '',
        stickyElementOffset: '',

        init: function (stickyElement) {
            this.stickyElement = stickyElement;
            this.stickyElementOffset = $(stickyElement).offset().top;
            this.handleStickyness();
            this.listenScroll();
        },

        stick: function () {
            $(this.stickyElement).addClass('fixed-top');
        },

        unstick: function () {
            $(this.stickyElement).removeClass('fixed-top');
        },

        isSticky: function () {
            if (this.stickyElementOffset - $(window).scrollTop() <= 0) {
                return true;
            }
            return false;
        },

        handleStickyness: function () {
            if (this.isSticky()) {
                this.stick();
            } else {
                this.unstick();
            }
        },

        listenScroll: function () {
            $(window).on('scroll', function () {
                stick.handleStickyness();
            })
        }


    };

    var navSync = {
        menuSelector: '',
        pastElements: [],

        init: function (menuSelector) {
            this.menuSelector = menuSelector;
            this.listenScroll();
            this.manageActivations();
        },

        check: function () {
            var lastElement = this.pastElements[this.pastElements.length - 1];
            var menuItemSelector = 'a[href="#' + lastElement + '"]';
            $(menuItemSelector).parent().addClass('active');
            $('a').not(menuItemSelector).parent().removeClass('active');
        },


        manageActivations: function () {
            if ($(navSync.pastElements).length > 0) {
                $.map(navSync.pastElements, function (e, i) {

                    var link = "#" + navSync.pastElements[i];
                    if ($(link).offset().top > $(window).scrollTop() + $(window).height() / 2) {
                        navSync.pastElements.splice(i, 1);


                    }
                });
            }
            $('section').each(function () {
                var elementId = $(this).attr('id');
                if ($(this).offset().top <= $(window).scrollTop() + 100) {
                    if ($.inArray(elementId, navSync.pastElements) === -1) {
                        navSync.pastElements.push(elementId);
                    }
                }


                navSync.check();
            })
        },

        listenScroll: function () {
            $(window).on('scroll', function () {
                navSync.manageActivations();
            });
        }

    };


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


    var smoothScroll = {
        menuSelector: "",
        speed: 750,

        init: function (menuSelector, speed) {
            this.menuSelector = menuSelector;
            this.speed = speed;
            this.scroll();
        },

        scroll: function (menuItem) {
            var selector = this.menuSelector + ' a';
            $.each($(selector), function () {
                var link = this;
                $(this).parent().on('click', function () {
                    var section = $(link).attr('href');
                    $('html, body').animate({scrollTop: $(section).offset().top}, smoothScroll.speed);
                    return false;

                });


            });
        }
    };




    var scrollDownButton = {
        buttonSelector: '',
        sectionSelector: '',
        speed: 750,

        init: function (buttonSelector, sectionSelector, speed) {
            this.buttonSelector = buttonSelector;
            this.sectionSelector = sectionSelector
            this.speed = speed;
            setTimeout(this.toggleButton, 1500);
            this.listenClick();
            this.listenScroll();
        },

        listenClick: function () {
            $(this.buttonSelector).on('click', function () {
                $('html, body').animate({scrollTop: $(scrollDownButton.sectionSelector).height()}, scrollDownButton.speed);
            });
        },

        toggleButton: function () {
            if ($(scrollDownButton.sectionSelector).offset().top < $(window).scrollTop()) {
                scrollDownButton.hideButton();
            }
            else {
                scrollDownButton.showButton();
            }
        },

        hideButton: function () {
            $(this.buttonSelector).css('animation', 'none').hide("fade", 500);
        },

        showButton: function () {
            $(this.buttonSelector).show("drop", {direction: "down"}, 500, function () {
                $(scrollDownButton.buttonSelector).css('animation', 'shake 3s infinite ease');
            });
        },

        listenScroll: function () {
            $(window).on('scroll', function () {
                scrollDownButton.toggleButton();
            });
        }


    };

    var relativeFixedHeight = {
        childSelector: '',
        parentHeight: '',

        init: function (childSelector, parentHeight) {
            this.childSelector = childSelector;
            this.parentHeight = parentHeight;
            this.defineHeight();
            this.listenResize();
        },

        defineHeight: function () {
            $(this.childSelector).css('height', this.parentHeight);
        },

        listenResize: function () {
            $(window).on('resize', function () {
                relativeFixedHeight.defineHeight();
            });
        }
    };


    windowManager = {

        windows: [],

        menuItems: '',

        backButton: '',

        centerPage: '',

        requiredScrollTop:  0,

        mustsNotScroll: '',

        init: function (leftSelector = null, leftTriggerSelector = null,  rightSelector = null, rightTriggerSelector = null,  centerPage, menu, backButton, requiredScrollTop = 0, mustsNotScroll = '') {
            if(leftSelector != null){
                var left = {};
                left.side = 'left';
                left.selector = leftSelector;
                left.triggerSelector = leftTriggerSelector;
                this.windows.push(left);
            }
            if(rightSelector != null){
                var right = {};
                right.side = 'right';
                right.triggerSelector = rightTriggerSelector;
                right.selector = rightSelector;
                this.windows.push(right);
            }

            this.menuItems = menu + ' li';
            this.backButton = backButton;
            this.centerPage = centerPage;
            this.requiredScrollTop = requiredScrollTop;
            this.mustsNotScroll = mustsNotScroll;
            this.listenEvents();

        },

        isScrollTopOk: function (side) {
            return ($(window).scrollTop() > this.requiredScrollTop);
        },

        adaptScrollTop: function (side) {
            if (!this.isScrollTopOk(side)) {
                $('html, body').animate({scrollTop: this.requiredScrollTop}, 300);
                return  500;
            }
            else {
                return  0;
            }
        },

        handleShowing: function (side) {
            side.timeout = this.adaptScrollTop(side);
            if (!side.isActive) {
                setTimeout(function () {
                    side.isActive = true;
                    windowManager.showBackButton();
                    windowManager.showWindow(side);
                    $(windowManager.mustsNotScroll).css('overflow-y', 'hidden');
                }, side.timeout)
            }

        },

        showBackButton: function () {

            $(this.menuItems).not(this.backButton).hide("fade", 500);
            $(this.backButton).show("fade", 500).addClass("active");
        },

        showWindow: function (side) {
            if (side.side === 'right') {
                $(this.centerPage).animate({"right": "+100%"}, 500);
                $(side.selector).animate({"left": "0"}, 500);
            }
            else {
                $(this.centerPage).animate({"right": "-100%"}, 500);
                $(side.selector).animate({"right": "0"}, 500);
            }
        },

        handleHiding: function (side) {
            if (side.isActive) {
                side.isActive = false;
                this.hideBackButton();
                this.hideWindow(side);
                $(windowManager.mustsNotScroll).css('overflow-y', 'scroll');
            }
        },

        hideBackButton: function () {
            $(this.backButton).removeClass("active").hide("fade", 500);
            $(this.menuItems).not(this.backButton).show("fade", 500);

        },

        hideWindow: function (side) {
            if (side.side === 'right') {
                $(this.centerPage).animate({"right": "0"}, 500);
                $(side.selector).animate({"left": "100%"}, 500);
            }
            else {
                $(this.centerPage).animate({"right": "0"}, 500);
                $(side.selector).animate({"right": "100%"}, 500);
            }
        },

        listenEvents: function(){
            $.each(this.windows, function(){
                var that = this;
                $(this.triggerSelector).on('click', function(){
                    windowManager.handleShowing(that);
                });
                $(windowManager.backButton).on('click', function(){
                    windowManager.handleHiding(that);
                });
            });
        }

    };


    stick.init('#global-nav');
    navSync.init('#main-nav');
    changingText.init(['web', 'php', 'javascipt', 'html5', 'symfony4', 'jquery', 'css3']);
    smoothScroll.init('#main-nav', 750);
    scrollDownButton.init('#intro-scrolldown', '#intro', 750);
    relativeFixedHeight.init('#main-nav', $(window).height() - $("#menu-header").height() - 55);
    windowManager.init('#left-page', '#show-left-page', '#right-page', '.production-preview', '#main-content', '#main-nav', '#back', $(window).height(), 'body');


});

