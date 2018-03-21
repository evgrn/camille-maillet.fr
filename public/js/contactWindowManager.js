$(function(){

    contactWindowManager = {

        windowSelector: '',

        formSelector: '',

        triggerSelector: '',

        loaderSelector: '',

        messageBoxSelector: '',

        isWindowActive: '',
        menu: '',

        menuItems: '',

        backButton: '',

        centerPage: '',

        mustsNotScroll: '',

        mustsDisappear: '',

        messageSent: false,

        successMessage: 'Message envoyé',

        errorMessage: 'Message non envoyé, veuillez réessayer',

        init: function(leftWindow, triggerSelector, formSelector, loaderSelector, messageBoxSelector, centerPage, menu, backButton, mustsNotScroll, mustsDisappear){
            this.windowSelector = leftWindow;
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
        },

        isScrollTopOk: function(){
            return ($(window).scrollTop() > $(window).height());
        },

        adaptScrollTop: function(){
            if (!this.isScrollTopOk()) {
                $('html, body').animate({scrollTop: $(window).height()}, 300);
                return  500;
            }
            else {
                return  0;
            }
        },

        handleShowing: function () {
            if(!this.messageSent){
                var timeout = this.adaptScrollTop();
                if (!this.isWindowActive) {
                    setTimeout(function () {
                        contactWindowManager.isWindowActive = true;
                        contactWindowManager.stopScrollElsewhere();
                        contactWindowManager.showBackButton();
                        contactWindowManager.showWindow();
                        contactWindowManager.listenFormSubmit();
                        contactWindowManager.hideToHideElement();
                    }, timeout)
                }
            }


        },

        lockForm: function(){
            this.messageSent = true;
            $(this.triggerSelector).removeClass('blink').addClass('locked');
        },


        showBackButton: function () {
            $(this.menuItems).not(this.backButton).hide("fade", 500);
            $(this.backButton).show("fade", 500).addClass("active");
        },

        showWindow: function(){
            $(this.centerPage).animate({"right": "-100%"}, 500);
            $(this.windowSelector).animate({"right": "0"}, 500);
        },

        handleHiding: function () {
            if (this.isWindowActive) {
                this.isWindowActive = false;
                this.hideBackButton();
                this.hideWindow();
                this.showToHideElement();
                this.resetScrollElsewhere();
            }
        },

        isPortrait: function(){
            return ($(window).width() < $(window).height());
        },

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

        resetScrollElsewhere: function(){
            // Menu fixé
            $(this.menu).attr('style', '');

            // Reste de l'app fixé
            // Reste de l'app fixé

            var scrollTop =  $(this.mustNotScroll).offset().top * -1;
            $(this.mustNotScroll).attr('style', '');
            $('html, body').scrollTop(scrollTop);

        },

        hideBackButton: function () {
            $(this.backButton).removeClass("active").hide("fade", 500);
            $(this.menuItems).not(this.backButton).show("fade", 500);
        },

        hideWindow: function () {
            $(this.centerPage).animate({"right": "0"}, 500);
            $(this.windowSelector).animate({"right": "100%"}, 500);
        },

        listenClicks: function(){
            $(this.triggerSelector).on('click', function(){
                contactWindowManager.handleShowing();
            });
            $(this.backButton).on('click', function(){
                contactWindowManager.handleHiding();
            });
        },

        showLoader: function(){
            $(this.loaderSelector).fadeIn(500);
        },

        hideLoader: function(){
            $(this.loaderSelector).fadeOut(500);
        },

        showMessage: function(status){
            $(this.messageBoxSelector).removeAttr('class');
            if(status === 'success'){
                $(this.messageBoxSelector).text(this.successMessage).addClass('success').addClass('active');
            }else{
                $(this.messageBoxSelector).text(this.errorMessage).addClass('error').addClass('active');
            }
        },

        hideToHideElement: function(){
                    $(contactWindowManager.mustsDisappear).fadeOut(500);
        },

        showToHideElement: function(){
            $(contactWindowManager.mustsDisappear).fadeIn(500);
        },

        listenFormSubmit: function(){
            $(this.formSelector).on('submit', function(e) {
                e.preventDefault();
                contactWindowManager.showLoader();
                var formSerialize = $(this).serialize();

                $.ajax({
                    url: '',
                    type: "post",
                    async: true,
                    data: formSerialize,
                    dataType: 'json',
                    success: function(data) {
                        contactWindowManager.hideLoader();
                        contactWindowManager.handleHiding();
                        contactWindowManager.lockForm();
                        contactWindowManager.showMessage('success');
                        console.log(data.success)
                    },
                    error: function(){
                        contactWindowManager.hideLoader();
                        contactWindowManager.showMessage('error');
                    }
                });
            });
        }

    };

    contactWindowManager.init('#left-page', '#show-left-page', '#contact-form', '#sending-loader', '#message-notification', '#main-content', '#global-nav', '#back', 'body', 'footer');

});