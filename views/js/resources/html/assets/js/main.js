(function ($, window) {

    'use strict';

    var $window = $(window);

    function navHeightInit() {

        var $nav = $('nav');

        $nav.css({
            minHeight: $window.height() - $nav.offset().top - $('footer').height()
        });
    }

    function initSearch() {
        var $searchBox = $('.search-area'),
            $guideContent = $('.guide-content'),
            $form = $searchBox.find('form');

        $.ajax({
            url: $form.attr('action'),
            success: function () {
                $searchBox.show();
            }
        });

        $form.on('submit', search);

        function search() {

            $.get($form.attr('action'), $form.serialize(), function (result) {
                var $resultsContainer = $('<div id="search-results" class="search-results"/>');
                $resultsContainer.html($('<h1/>').text('Search results'));
                $.each(result, function (i, e) {
                    var $result = $('<div />');
                    $('<h2/>').append($('<a />', {href: e.href, text: e.name})).appendTo($result);
                    $('<div />').html(e.preview).appendTo($result);
                    $result.appendTo($resultsContainer);
                });
                if (!result.length) {
                    $resultsContainer.append($('<h2/>').text('Nothing to display'));
                }
                $guideContent.html($resultsContainer);
            });
            return false;
        }
    }


    function hideFallbackVideo() {
        $('.hidden-video').hide();
    }

    function internalNavi() {
        return
        var $navi = $('<ul>'),
            $naviBox = $('<div>', {class: 'internal-navi'}).append($navi),
            $optionalExtras = $('.optional-extras h3');

        if (!$optionalExtras.length) {
            return;
        }

        $optionalExtras.each(function () {
            var $link = $('<a>', {
                    href: '#' + this.id,
                    text: this.textContent
                }),
                $li = $('<li>').append($link);
            $navi.append($li);
        });

        $naviBox.prepend($('<h3>', {
            text: 'Optional Extras'
        }));

        $('.guide-content blockquote').after($naviBox);
    }

    function menuToggleInit() {
        $('.menu').on('click', function () {
            $('body').toggleClass('navi-open');
        });
    }

    function isExternal(url) {
        var match = url.match(/^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/);
        if (typeof match[1] === "string" && match[1].length > 0 && match[1].toLowerCase() !== location.protocol) {
            return true;
        }
        if (typeof match[2] === "string" && match[2].length > 0 && match[2].replace(new RegExp(":(" + {
                    "http:": 80,
                    "https:": 443
                }[location.protocol] + ")?$"), "") !== location.host) {
            return true;
        }
        return false;
    }

    //external links should be opened in new window
    function parseLinks() {
        $('a').each(function () {
            if (isExternal(this.getAttribute('href'))) {
                $(this).attr('target', '_blank');
            }
        })
    }

    internalNavi();
    hideFallbackVideo();
    menuToggleInit();
    navHeightInit();
    initSearch();
    parseLinks();

    $window.on('resize orientationchange', navHeightInit);

    $window.on('resize orientationchange', navHeightInit);
    (function () {

        var $activeLi = $('nav li.active');

        $activeLi.find('ul').animate({opacity: 1});

    }());


}(jQuery, window));
