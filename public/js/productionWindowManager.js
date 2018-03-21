$(function(){

    productionWindowManager = {


        windowSelector: '',

        mosaicTarget: '',

        isWindowActive: '',

        menuItems: '',

        backButton: '',

        centerPage: '',

        mustsNotScroll: '',

        init: function(rightWindow, mosaicTarget, centerPage, menu, submenu, backButton, mustsNotScroll){
            this.windowSelector = rightWindow;
            this.mosaicTarget = mosaicTarget;
            this.menu = menu;
            this.menuItems = menu + ' li';
            this.submenu = submenu;
            this.submenuItems = submenu + ' li';
            this.backButton = backButton;
            this.centerPage = centerPage;
            this.mustsNotScroll = mustsNotScroll;
            this.productions.initProductionsMenu();
            this.productions.showCategory(0);
            this.listenClosing();
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

        showBackButton: function () {
            $(this.menuItems).not(this.backButton).hide("fade", 500);
            $(this.backButton).show("fade", 500).addClass("active");
        },

        showWindow: function(){
            $(this.centerPage).animate({"right": "+100%"}, 500);
            $(this.windowSelector).animate({"left": "0"}, 500);
        },

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

        resetScrollElsewhere: function(){
            // Menu fixé
            $(this.menu).attr('style', '');

            // Reste de l'app fixé

            var scrollTop =  $(this.mustsNotScroll).offset().top * -1;
            $(this.mustsNotScroll).attr('style', '');
            $('html, body').scrollTop(scrollTop);

        },

        handleHiding: function () {
            if (this.isWindowActive) {
                this.isWindowActive = false;
                this.hideBackButton();
                this.hideWindow();
                this.resetScrollElsewhere();
            }
        },


        hideBackButton: function () {
            $(this.backButton).removeClass("active").hide("fade", 500);
            $(this.menuItems).not(this.backButton).show("fade", 500);

        },

        hideWindow: function () {
            $(this.centerPage).animate({"right": "0"}, 500);
            $(this.windowSelector).animate({"left": "100%"}, 500);

        },

        listenClosing: function(){
            $(this.backButton).on('click', function(){
                productionWindowManager.handleHiding();
            });
        },

        productions: {

            hydrateSingleProductionPage: function(productionPreview){

                var url = $(productionPreview).attr('url');
                var link = '<a href="' + url + '" target="_blank">' + url + '</a>';

                var gitHub = $(productionPreview).attr('github');

                if(typeof gitHub !== 'undefined'){
                    gitHub = '<a href="' + gitHub + '" target="_blank">' + gitHub + '</a>';
                }
                else{
                    gitHub = "Non disponible";
                }

                var name = $(productionPreview).attr('name');
                var image = $(productionPreview).attr('image');
                var preview = $(productionPreview).attr('preview');
                var description = $(productionPreview).attr('description');
                var category = $(productionPreview).attr('category');
                var technologies = $(productionPreview).attr('technologies');

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

            showCategory: function(id){
                $.ajax({
                    url: '/productions/' + id,
                    type: "post",
                    async: true,
                    data: '',
                    dataType: 'json',
                    success: function(data) {
                        $(productionWindowManager.mosaicTarget).empty();
                        $.map( data['productions'], function( production ) {
                            $(productionWindowManager.mosaicTarget).append(productionWindowManager.productions.generateMosaicElement(production, data['imagesDirectory']));
                        });

                    },
                    error: function(){
                        console.log('error');
                    }
                });
            },

            generateMenuElement: function(name, id){
                var element = document.createElement('li');
                element.setAttribute('categoryId', id);
                if(id === 0){
                    element.setAttribute('class', 'active');
                }
                $(element).text(name);

                $(element).on('click', function(){
                    $(productionWindowManager.submenuItems).not(this).removeClass('active');
                    $(this).addClass('active');
                    productionWindowManager.productions.showCategory($(this).attr('categoryId'));
                });

                return element;
            },

            generateMenu: function(data){
                $(productionWindowManager.submenu).append(productionWindowManager.productions.generateMenuElement("Tout", 0));
                $.map( data, function( category ) {
                    $(productionWindowManager.submenu).append(productionWindowManager.productions.generateMenuElement(category['name'], category['id']));
                });
            },

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
                        console.log('error');
                    }
                });
            },

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

            initTrigger: function(mosaicElement){
                $(mosaicElement).on('click', function() {
                    productionWindowManager.productions.hydrateSingleProductionPage(this);
                    productionWindowManager.handleShowing();
                });
            }


        }

    };

    productionWindowManager.init('#right-page', '#mosaic', '#main-content', '#global-nav', '#productions-nav', '#back', 'body, .main-content');

});