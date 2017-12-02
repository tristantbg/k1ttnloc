/* globals $:false */
var width = $(window).width(),
    height = $(window).height(),
    $body,
    headerHeight,
    contentHeight,
    requestId,
    isMobile = false,
    target,
    iScroll = [],
    $mouseNav,
    players,
    lastTarget = false,
    $root = '/';
$(function() {
    var app = {
        init: function() {
            console.log('Design by VLF', 'www.vlf-studio.com');
            console.log('Code by Tristan Bagot', 'www.tristanbagot.com');
            $(window).resize(function(event) {
                app.sizeSet();
            });
            $(document).ready(function($) {
                $body = $('body');
                $container = $('#container');
                $header = $('header');
                app.sizeSet();
                app.smoothState('#main', $container);
                app.interact();
                app.ajaxLoading();
                window.viewportUnitsBuggyfill.init();
                $(document).keyup(function(e) {
                    //esc
                    if (e.keyCode === 27) app.goBack();
                    if ($slider && e.keyCode === 39) $slider.flickity('next');
                    if ($slider && e.keyCode === 37) $slider.flickity('previous');
                });
                $body.on('click touchend', '[event-target="menu"]', function(event) {
                    event.preventDefault();
                    $(this).toggleClass('open');
                    $("#mobile-menu").toggleClass('visible');
                });
                $(window).load(function() {
                    $(".loader").hide();
                });
            });
        },
        interact: function() {
            app.loadSlider();
            app.searchBar();
            app.mouseNav();
            app.videoHover();
            //app.contentShrink();
            app.iScroll.init();
        },
        sizeSet: function() {
            width = $(window).width();
            height = $(window).height();
            headerHeight = $("header").height();
            if (width <= 1024) isMobile = true;
            if (isMobile) {
                app.iScroll.disable();
                contentHeight = height - headerHeight - 20;
                if (width > 1024) {
                    //location.reload();
                    isMobile = false;
                    app.iScroll.enable();
                }
            } else {
                contentHeight = height - headerHeight;
            }
        },
        iScroll: {
            init: function() {
                iScroll = [];
                var scrollers = document.querySelectorAll('[data-scroll]');
                for (var i = scrollers.length - 1; i >= 0; i--) {
                    iScroll[i] = new IScroll(scrollers[i], {
                        mouseWheel: true,
                        scrollbars: false,
                        preventDefault: false
                    });
                }
            },
            enable: function() {
                for (var i = iScroll.length - 1; i >= 0; i--) {
                    iScroll[i].enable();
                }
            },
            disable: function() {
                for (var i = iScroll.length - 1; i >= 0; i--) {
                    iScroll[i].disable();
                }
            }
        },
        searchBar: function() {
            var $form = $('#search');
            if ($form.length > 0) {
                app.fillWithBlocks();
                var searchUrl = $form.attr('action');
                $form.keydown(function(event) {
                    if (event.keyCode == 13) {
                        event.preventDefault();
                        return false;
                    }
                });
                $form.find('input').keyup(debounce(function(event) {
                    doSearch(searchUrl, $(this).val(), "#projects", function() {
                        app.videoHover();
                        app.fillWithBlocks();
                    });
                }, 400));
            }
        },
        plyr: function(loop) {
            players = plyr.setup('.js-player', {
                loop: false,
                controls: ['controls', 'progress'],
                iconUrl: $root + "/assets/css/plyr/plyr.svg"
            });
            for (var i = players.length - 1; i >= 0; i--) {
                players[i].on('play', function(event) {
                    $slider.removeClass('play').addClass('pause');
                });
                players[i].on('pause', function(event) {
                    $slider.removeClass('pause').addClass('play');
                });
                players[i].on('waiting', function(event) {
                    $slider.addClass('loading');
                });
                players[i].on('canplay', function(event) {
                    $slider.removeClass('loading');
                });
                players[i].on('ready', function(event) {
                    $(".plyr__controls").hover(function() {
                        $mouseNav.css('visibility', 'hidden');
                    }, function() {
                        $mouseNav.css('visibility', 'visible');
                    });
                });
            }
        },
        videoHover: function() {
            if (!isMobile) {
                var videos = document.querySelectorAll('[data-target="project"].video:not(.play-hover) .project-video:not(.loaded)');
                for (var i = videos.length - 1; i >= 0; i--) {
                    var video = document.createElement('video');
                    video.preload = "none";
                    video.muted = "true";
                    video.loop = "true";
                    var webmSrc = videos[i].getAttribute("data-webm");
                    var mp4Src = videos[i].getAttribute("data-mp4");
                    if (webmSrc) addSourceToVideo(video, webmSrc, "video/webm");
                    if (mp4Src) addSourceToVideo(video, mp4Src, "video/mp4");
                    videos[i].appendChild(video);
                    videos[i].classList.add("loaded");
                    video.addEventListener('loadeddata', function() {
                        $(this).parent().parent().addClass('play-hover').hover(app.hoverVideo, app.hideVideo);
                    }, true);
                    video.setAttribute("preload", "buffer");
                }
            }
        },
        hoverVideo: function(e) {
            var video = $('video', this).get(0);
            var isPlaying = video.currentTime > 0 && !video.paused && !video.ended && video.readyState > 2;
            if (!isPlaying) {
                video.play();
            }
        },
        hideVideo: function(e) {
            var video = $('video', this).get(0);
            var isPlaying = video.currentTime > 0 && !video.paused && !video.ended && video.readyState > 2;
            if (isPlaying) {
                video.pause();
                video.currentTime = 0;
            }
        },
        loadSlider: function() {
            $mouseNav = $('#mouse-nav');
            $slider = false;
            $slider = $('.slider').flickity({
                cellSelector: '.slide',
                imagesLoaded: true,
                lazyLoad: 2,
                setGallerySize: false,
                accessibility: false,
                wrapAround: true,
                prevNextButtons: !isMobile,
                pageDots: false,
                draggable: isMobile,
                dragThreshold: 20
            });
            app.mouseNav();
            if ($slider.length > 0) {
                var vids = document.querySelectorAll(".slider video");
                var hls = [];
                for (var i = vids.length - 1; i >= 0; i--) {
                    vids[i].controls = false;
                    if (!isMobile && vids[i].getAttribute("data-stream") && Hls.isSupported()) {
                        hls[i] = new Hls({
                            minAutoBitrate: 1700000
                        });
                        hls[i].loadSource(vids[i].getAttribute("data-stream"));
                        hls[i].attachMedia(vids[i]);
                        vids[i].setAttribute('poster', '');
                    }
                }
                app.plyr();
                $slider.flkty = $slider.data('flickity');
                $slider.count = $slider.flkty.slides.length;
                if ($slider.flkty) {
                    $slider.attr("data-media", $slider.flkty.selectedElement.getAttribute("data-media"));
                    $slider.on('select.flickity', function() {
                        $('#slide-number').html(($slider.flkty.selectedIndex + 1) + '/' + $slider.count);
                        $slider.attr("data-media", $slider.flkty.selectedElement.getAttribute("data-media"));
                    });
                    $slider.on('staticClick.flickity', function(event, pointer, cellElement, cellIndex) {
                        if (!cellElement || !isMobile) {
                            return;
                        } else {
                            $slider.flickity('next');
                        }
                    });
                    if (vids.length > 0) {
                        $slider.on('select.flickity', function() {
                            $.each(vids, function() {
                                this.pause();
                            });
                            $slider.removeClass('play pause');
                            // For lazysizes
                            // var adjCellElems = $slider.flickity('getAdjacentCellElements', 2);
                            // $(adjCellElems).find('.lazyimg:not(".lazyloaded")').addClass('lazyload');
                        });
                        if ($slider.flkty.selectedElement.getAttribute("data-media") == "video") {
                            if (typeof hls[0] !== "undefined") {
                                hls[0].on(Hls.Events.MANIFEST_PARSED, function() {
                                    vids[0].play();
                                });
                            } else  {
                                vids[0].play();
                            }
                        }
                    } else if ($slider.count < 2) {
                        $mouseNav.hide();
                        $slider.css('cursor', 'auto');
                    }
                }
            }
        },
        mouseNav: function() {
            $slider.unbind('mousemove').mousemove(function(event) {
                var x = event.pageX;
                var y = event.pageY;
                if (x < width / 2) {
                    $slider.removeClass('right').addClass('left');
                } else {
                    $slider.removeClass('left').addClass('right');
                }
                if (this.getAttribute("data-media") === "video" && $slider.count > 1) {
                    if (x < 0.15 * width || x > 0.85 * width) {
                        $slider.addClass('nav-hover');
                    } else {
                        $slider.removeClass('nav-hover');
                    }
                }
                $mouseNav.css({
                    top: y - $(this).offset().top,
                    left: x
                });
            });
        },
        contentShrink: function() {
            $(window).unbind('scroll');
            requestId = null;
            if (typeof document.getElementById("project-content") !== "undefined") {
                $("[event-target='more']").click(function(event) {
                    var position = contentHeight / 2;
                    if (contentHeight / 2 < 300) position = contentHeight - 300;
                    jump(position, {
                        duration: 800
                    });
                });
                // Fallback no requestAnimationFrame 
                // $(window).scroll(function(event) {
                //     var currentScroll = $(window).scrollTop();
                //     var size = contentHeight - currentScroll;
                //     if (size > contentHeight) size = contentHeight;
                //     if (isMobile) {
                //         if (size < contentHeight / 2.5) size = contentHeight / 2.5;
                //     } else {
                //         if (size < contentHeight / 2) size = contentHeight / 2;
                //     }
                //     $("#project-content").height(size);
                // });
                requestId = null;
                shrinkUpdate = function() {
                    var currentScroll = $(window).scrollTop();
                    var size = contentHeight - currentScroll;
                    if (size > contentHeight) size = contentHeight;
                    if (isMobile) {
                        if (size < contentHeight / 2.5) size = contentHeight / 2.5;
                    } else {
                        if (size < contentHeight / 2) size = contentHeight / 2;
                    }
                    $("#project-content").height(size);
                };
                $(window).scroll(function(event) {
                    requestId = window.requestAnimationFrame(shrinkUpdate);
                });
            }
        },
        fillWithBlocks: function() {
            if (!isMobile) {
                var sGrid = $('.simple-grid');
                if (sGrid.length > 0) {
                    var sprojectNum = sGrid.children().length / 4;
                    while (!isInt(sprojectNum)) {
                        sGrid.append('<div class="project-item blank"></div>');
                        sprojectNum = sGrid.children().length / 4;
                    }
                }
                var fGrid = $('.featured-grid');
                if (fGrid.length > 0) {
                    var fprojectNum = fGrid.children().slice(6).length / 4;
                    while (!isInt(fprojectNum)) {
                        fGrid.append('<div class="project-item blank"></div>');
                        fprojectNum = fGrid.children().slice(6).length / 4;
                    }
                }
            }
        },
        goBack: function() {
            if (window.history && history.length > 0 && !$body.hasClass('projects')) {
                window.history.go(-1);
            } else {
                $('#site-title a').click();
            }
        },
        ajaxLoading: function() {
            app.fillWithBlocks();
            if (document.getElementById("pagination")) {
                var infScroll = new InfiniteScroll('#page-content #projects', {
                    path: "#pagination .next",
                    append: '.project-item:not(.blank)',
                    history: false,
                    historyTitle: false,
                    hideNav: "#pagination",
                    status: '.ajax-loading',
                });
                infScroll.on('append', function(response, path, items) {
                    // var $items = $(items);
                    app.videoHover();
                });
                infScroll.on('last', app.fillWithBlocks);
            }
        },
        smoothState: function(container, $target) {
            var options = {
                    debug: true,
                    scroll: false,
                    anchors: '[data-target]',
                    loadingClass: 'is-loading',
                    prefetch: true,
                    cacheLength: 4,
                    onAction: function($currentTarget, $container) {
                        lastTarget = target;
                        target = $currentTarget.data('target');
                        if (target === "back") app.goBack();
                        
                        // console.log(lastTarget);
                    },
                    onBefore: function(request, $container) {
                        popstate = request.url.replace(/\/$/, '').replace(window.location.origin + $root, '');
                        // console.log(popstate);
                    },
                    onStart: {
                        duration: 0, // Duration of our animation
                        render: function($container) {
                            $body.addClass('is-loading');
                            $(window).scrollTop(0);
                        }
                    },
                    onReady: {
                        duration: 0,
                        render: function($container, $newContent) {
                            // Inject the new content
                            app.sizeSet();
                            $body.attr('class', $newContent.find("#page-content").attr("class"));
                            $container.html($newContent);
                        }
                    },
                    onAfter: function($container, $newContent) {
                        app.interact();
                        app.ajaxLoading();
                        setTimeout(function() {
                            $body.removeClass('is-loading');
                            // Clear cache for random content
                            smoothState.clear();
                        }, 200);
                    }
                },
                smoothState = $(container).smoothState(options).data('smoothState');
        }
    };
    app.init();
});