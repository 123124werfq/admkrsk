$(document).ready(function() {

    var compactWidth = 1040;
    var mobileWidth = 768;

    $wnd = $(window);
    $body = $('body');


    // ACCESSABILITY
    function getCookie(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    };

    function setCookie(name, value, options) {
        options = options || {};

        var expires = options.expires;

        if (typeof expires == "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires * 1000);
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }

        value = encodeURIComponent(value);

        var updatedCookie = name + "=" + value;

        for (var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];
            if (propValue !== true) {
                updatedCookie += "=" + propValue;
            }
        }

        document.cookie = updatedCookie;
    };
    window.__switchAccessability = function(state) {

        state = typeof(state) == 'undefined' ? !!$('link[rel="stylesheet accessability"]').get(0).disabled : state;

        $('link[rel="stylesheet main"]').eq(0).get(0).disabled = (state ? true : false);
        $('link[rel="stylesheet main"]').eq(1).get(0).disabled = (state ? true : false);
        $('link[rel="stylesheet accessability"]').get(0).disabled = (state ? false : true);
        if (state) {
            $('html').addClass('accessability-mode');
        } else {
            $('html').removeClass('accessability-mode');
        }
        if ($('.main-slider').length) {
            $('.main-slider')[0].slick.refresh();
        }

        setCookie('accessabilityMode', state, { path: '/', expires: 3600 * 24 });
    };

    if (typeof(getCookie('accessabilityMode')) !== 'undefined') {
        window.__switchAccessability(getCookie('accessabilityMode') == 'true');
    }

    $body.on('click', '.link-accessability',function(e){
        e.preventDefault();
        window.__switchAccessability();
        return false;
    });

    $('.faceTuneBlock').each(function(){
        var owner = this;

        $('.settingsBlock',this).on('click','.variantList .item a', function(e){
            e.preventDefault();
            $(this).parentsUntil('.faceTuneBlock').last().parent().find('[data-key="'+this.getAttribute('data-key')+'"]').parent().removeClass('item-active');
            $(this.parentNode).addClass('item-active');

            $('html').attr('data-'+$(this).data('key'),$(this).data('value'));

            setCookie('accessabilityMode-'+$(this).data('key'),$(this).data('value'),{path:'/',expires:3600*24});

            return false;
        })

        $(this).find('[data-key]').each(function() {
            var $panelControl = $(this);
            var keyName = $panelControl.data('key');
            var keyValue = $panelControl.data('value');

            if (typeof(getCookie('accessabilityMode-'+keyName)) !== 'undefined') {
                var cookieValue = getCookie('accessabilityMode-'+keyName);
                $panelControl.filter('[data-value="'+ cookieValue +'"]').click();
            }
        });
    });

    $body.on('click', '.sitemap_menu-item__submenu .sitemap_header', function(e) {
        if ($('.accessability-mode').length) {
            e.preventDefault();
            e.stopPropagation();

            $(this).closest('.sitemap_menu-item').toggleClass('active').siblings().removeClass('active');

            return false;
        }
    });
    $body.on('click', function() {
        if ($('.accessability-mode').length) {
            $('.sitemap_menu-item').removeClass('active');
        }
    });
    // /ACCESSABILITY

    // TABLE TOP SCROLL
    /*$('.table-responsive').doubleScroll({
        resetOnWindowResize: true
    });*/
    // /TABLE TOP SCROLL

    // HEADER SCROLL
    $wnd.on('scroll', function() {
        if ($wnd.scrollTop() >= $('.gosbar').outerHeight() + $('.header').outerHeight()) {
            $body.addClass('page-scroll');
            setTimeout(function() {
                $body.addClass('page-smooth');
            }, 200);
        } else {
            $body.removeClass('page-scroll page-smooth');
        }
    });

    // Initial state
    var checkScrollUp = function() {
        var scrollPos = 0;
        // adding scroll event
        window.addEventListener('scroll', function() {
            // detects new state and compares it with the new one
            if ((document.body.getBoundingClientRect()).top > scrollPos) {
                $body.addClass('page-scroll-up');
            } else {
                $body.removeClass('page-scroll-up');
            }
            // saves the new position for iteration.
            scrollPos = (document.body.getBoundingClientRect()).top;
        });
    }
    checkScrollUp();
    // /HEADER SCROLL


    //ACCARDEON
    // $body.on('click', '.sidemenu li a', function(e) {
    //     if ($wnd.outerWidth() >= mobileWidth) {
    //         var $this = $(this);
    //         var $menu = $this.siblings('ul');
    //         var $menuItem = $this.parent();

    //         if ($menu.length) {
    //             $menu.slideToggle(200);
    //             $menuItem.toggleClass('active');

    //             return false;
    //         }
    //     }
    // });

    //sidemenu
    // $('.sidemenu a.selected').each(function() {
    //     console.log($(this).closest('li').hasClass('selected'))
    //     if (!$(this).closest('li').hasClass('selected')) {
    //         console.log(222)
    //         $(this).closest('li').addClass('selected');
    //     }
    // });

    $('.sidemenu li.selected').each(function() {
        $(this).addClass('active').parents('li').addClass('active');
    });

    if ($('.sidemenu a.selected').length) {
        $('.sidemenu a.selected').last().clone().removeClass('active selected').insertBefore('.sidemenu > ul > li:first').wrap('<li class="current"></li>');
    } else {
        $('<li class="current"><a href="#">Выберите Раздел</a></li>').insertBefore('.sidemenu > ul > li:first');
    }

    $body.on('click', '.sidemenu .current', function(e) {
        e.preventDefault();
        e.stopPropagation();

        $(this).closest('.sidemenu').toggleClass('sidemenu__active');
    });
    $body.on('click', function() {
        $('.sidemenu').removeClass('sidemenu__active');
    });
    //ACCARDEON


    // Dropdown
    $body.on('click', '.dropdown-toggle',function(e) {
        console.log(1234);
        e.stopPropagation();
        $(this).closest('.dropdown').toggleClass('active');
    });
    $body.on('click', function() {
        $('.dropdown').removeClass('active');
    });
    // /Dropdown


    // Sitemap
    $body.on('click', '.sitemap-toggle', function() {
        $body.toggleClass('sitemap-active page-locked');
        $(this).children('.hidden').removeClass('hidden').siblings().addClass('hidden');
    });
    $body.on('click', '.sitemap_header-wrap', function(e) {
        if ($(this).closest('.sitemap_menu-item__submenu').length) {
            $(this).closest('.sitemap_menu-item').toggleClass('active');
        }
    });
    $body.on('click', '.sitemap_header', function(e) {
        e.stopPropagation();
    });
    // /Sitemap


    // Header menu hover
    // $('.header-menu_list').hover(function() {
    //  $body.addClass('page-menu-hover');
    // }, function() {
    //  $body.removeClass('page-menu-hover');
    // });
    // /Header menu hover


    // Slide hover
    function slideHoverClicked(el) {
        var $this = $(el);
        var $line = $this.closest('.slide-hover').find('.slide-hover-line');

        $line.addClass('active');
        $line.css({
            left: $this.position().left + parseInt($this.css('margin-left').replace('px', '')) + 'px',
            width: $this.width()
        });
    }
     $body.on('click', '.slide-hover-item', function() {
        slideHoverClicked(this);
    });
    if ($('.slide-hover-item.tab-control__active').length) {
        setTimeout(function() {
            $('.slide-hover-item.tab-control__active').each(function() {
                slideHoverClicked(this);
            });
        }, 200);
    }
    // /Slide hover


    // TABS
    $body.on('click', '.tab-control', function(e) {
        e.preventDefault();

        var $this = $(this);
        var $tabControls = $this.closest('.tab-controls');

        if (!$tabControls.hasClass('tab-controls__filter')) {
            var $target = $($this.data('href'));

            $tabControls.find('.tab-control.active').removeClass('tab-control__active');
            $this.addClass('tab-control__active');
            $target.addClass('active').siblings().removeClass('active');
        } else {
            var filterValue = $this.data('href');
            var $tabContent = $this.closest('.tab-container').find('.tab-content');
            if (filterValue == 0) {
                $tabContent.find('[data-filter-type]').removeClass('hidden')
            } else {
                $tabContent.find('[data-filter-type]').addClass('hidden');
                $tabContent.find('[data-filter-type="'+ filterValue +'"]').removeClass('hidden');
            }
        }
    });

    $body.on('click', '.tab-controls__responsive .tab-control', function() {
        $(this).closest('.tab-controls__responsive').toggleClass('active');
        $(this).addClass('tab-control__active').siblings().removeClass('tab-control__active');
    });
    // /TABS

    // SHOW HIDDEN
    $body.on('click', '.show-hidden', function(e) {
        e.preventDefault();

        var $this = $(this);
        var $target = $($this.data('show-target'));
        if ($this.hasClass('show-hidden-visible')) {
            $target.removeClass('hidden');
        } else {
            $this.addClass('hidden');
            $target.children().unwrap();
        }
    });

    $body.on('click', '.hide-hidden', function(e) {
        e.preventDefault();

        var $this = $(this);
        var $target = $($this.data('show-target'));
        $target.addClass('hidden');
    });
    // /SHOW HIDDEN


    // Hover svg
    $('.directions_content').hover(function(){
        $(this).closest('.directions_item').find('.directions_img').toggleClass('svg-active');
    });

    $('.situations_content').hover(function(){
        $(this).siblings('.situations_img').toggleClass('svg-active');
    });
    // / Hover svg


    // CONTENT SLIDER
    $('.content-slider').slick({
        infinite: true,
        slidesToShow: 1,
        dots: true,
        arrows: false,
        speed: 600,
        autoplay: true,
        autoplaySpeed: 3000,
        pauseOnHover: false
    });
    // /CONTENT SLIDER


    // CUSTOM VIDEO
    // $('.custom-video').on('click', function(e){
    //     e.preventDefault();
    //     var $cover = $(this).find('.custom-video_poster');
    //     $cover.next()[0].src += "&autoplay=1";
    //     $(this).addClass('custom-video__active');
    // });
    // /CUSTOM VIDEO


    // SORT
    function resortValues($el) {
        $el.find('.sortable-item').each(function(index ) {
            $(this).find('input').val(index);
            $(this).find('.sort-control_value').text(index+1);
        });
    }

    $('.sortable').sortable({
        placeholder: 'ui-state-highlight',
        stop: function() {
            resortValues($(this));
        }
    });
    $('.sortable').disableSelection();

    $body.on('click', '.sort-control_up', function() {
        $elemBox = $(this).closest('.sortable-item');
        $prevElem = $elemBox.prev();
        $elemBox.insertBefore($prevElem);
        resortValues($(this).closest('.sortable'));
    });
    $body.on('click', '.sort-control_down', function() {
        $elemBox = $(this).closest('.sortable-item');
        $prevElem = $elemBox.next();
        $elemBox.insertAfter($prevElem);
        resortValues($(this).closest('.sortable'));
    });
    // /SORT


    // GID SLIDER
    // $('.gid-slider').slick({
    //     infinite: true,
    //     slidesToShow: 1,
    //     dots: true,
    //     arrows: false,
    //     fade: true,
    //     cssEase: 'linear',
    //     speed: 600,
    //     autoplay: true,
    //     autoplaySpeed: 5000,
    //     pauseOnHover: false
    // });
    // // /GID SLIDER


    // MAIN SLIDER
    $('.main-slider').slick({
        infinite: true,
        slidesToShow: 1,
        dots: true,
        arrows: false,
        fade: true,
        cssEase: 'linear',
        speed: 600,
        autoplay: true,
        autoplaySpeed: 8000,
        pauseOnHover: false
    });
    // /MAIN SLIDER


    // MAIN COUNTDOWN
    // if ($('.main-countdown').length) {
    //     var $counter = $('.main-countdown');
    //     var counterDate = $counter.data('date');

    //     $('.main-countdown').countdown(counterDate).on('update.countdown', function(event) {
    //         var $this = $(this).html(event.strftime(''
    //             + '<span class="countdown-item"><span class="countdown-value">%D</span><span class="countdown-label">дней</span></span>'
    //             + '<span class="countdown-item"><span class="countdown-value">%H</span><span class="countdown-label">часов</span></span>'
    //             + '<span class="countdown-item"><span class="countdown-value">%M</span><span class="countdown-label">минут</span></span>'
    //             + '<span class="countdown-item"><span class="countdown-value">%S</span><span class="countdown-label">секунд</span></span>'));
    //     });
    // }
    // /MAIN COUNTDOWN


    // DIAGRAM
    function interpolateColor(color1, color2, factor) {
        if (arguments.length < 3) {
            factor = 0.5;
        }
        var result = color1.slice();
        for (var i = 0; i < 3; i++) {
            result[i] = Math.round(result[i] + factor * (color2[i] - color1[i]));
        }
        return result;
    };

    function interpolateColors(color1, color2, steps) {
        var stepFactor = 1 / (steps - 1);
        var interpolatedColorArray = [];

        color1 = color1.match(/\d+/g).map(Number);
        color2 = color2.match(/\d+/g).map(Number);

        for(var i = 0; i < steps; i++) {
            interpolatedColorArray.push(interpolateColor(color1, color2, stepFactor * i));
        }

        return interpolatedColorArray;
    }

    window.drawCharts = function() {
        $('.chart').each(function() {
            var $chart = $(this);
            var $chartLabel = $chart.siblings('.chart-labels');
            var chartLabels = JSON.parse("[" + $chartLabel.text() + "]");
            var chartValues = $chart.data('values').toString().split(',');

            var colorArr = [];
            var startColor = $chart.is('[data-color-dark]') ? 'rgb(59,66,86)' : 'rgb(210,174,110)';
            var endColor = $chart.is('[data-color-dark]') ? 'rgb(244,247,251)' : 'rgb(226,210,180)';

            colorArr = interpolateColors(startColor, endColor, chartValues.length);
            for (var i = 0; i < colorArr.length; i++) {
                colorArr[i] = 'rgb('+colorArr[i].join()+')';
            }

            var chartColors = {
                bar: colorArr,
                ticks: $chart.is('[data-color-dark]') ? 'rgb(59,66,86)' : 'rgb(221,225,230)',
                datalabels: $chart.is('[data-color-dark]') ? '#848e99' : '#fff'
            }

            var chartData = {
                labels: chartLabels,
                datasets: [
                    {
                        backgroundColor: chartColors.bar,
                        strokeColor: chartColors.bar,
                        data: chartValues,
                        borderWidth: 0,
                        datalabels: {
                            align: 'end',
                            anchor: 'end',
                            color: chartColors.datalabels,
                            font: {
                                size: '16'
                            }
                        }
                    }
                ]
            };

            switch($chart.data('chart-type')) {
                case 'bar-v':
                    var ctx = $chart[0].getContext("2d");
                    window.myBar = new Chart(ctx, {
                        type: 'bar',
                        data: chartData,
                        showTooltips: false,
                        options: {
                            animation: false,
                            responsive: true,
                            layout: {
                                padding: {
                                    top: 25,
                                    bottom: 0,
                                    left: 0,
                                    right: 0
                                }
                            },
                            legend: {
                                display: false
                            },
                            tooltips: {
                                enabled: false
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        display: false,
                                        drawBorder: false,
                                        offsetGridLines: false
                                    },
                                    ticks: {
                                        display: true,
                                        fontColor: chartColors.ticks,
                                        autoSkip: false
                                    }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        display: false
                                    }
                                }]
                            },
                            plugins: {
                                datalabels: {
                                    formatter: function(value, context) {
                                        return value + '%';
                                    }
                                }
                            }
                        }
                    });
                    break

                case 'bar-h':
                    var ctx = $chart[0].getContext("2d");
                    $chart.parent().height(chartValues.length*60);
                    window.myBar = new Chart(ctx, {
                        type: 'horizontalBar',
                        data: chartData,
                        showTooltips: false,
                        options: {
                            animation: false,
                            // responsive: true,
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    top: 0,
                                    bottom: 0,
                                    left: 0,
                                    right: 15
                                }
                            },
                            legend: {
                                display: false
                            },
                            tooltips: {
                                enabled: false
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        display: false
                                    }
                                }],
                                yAxes: [{
                                    // barThickness: 40,
                                    gridLines: {
                                        display: false,
                                        drawBorder: false,
                                        offsetGridLines: false
                                    },
                                    ticks: {
                                        display: true,
                                        fontColor: chartColors.ticks
                                    }
                                }]
                            },
                            plugins: {
                                datalabels: {
                                    formatter: function(value, context) {
                                        return value + '%';
                                    }
                                }
                            }
                        }
                    });
                    break

                case 'pie':
                    var ctx = $chart[0].getContext("2d");
                    $chart.parent().height(chartValues.length*40 + 250);
                    window.myBar = new Chart(ctx, {
                        type: 'pie',
                        data: chartData,
                        showTooltips: false,
                        options: {
                            animation: false,
                            // responsive: true,
                            maintainAspectRatio: false,
                            onResize: function(chart) {
                                if ($wnd.outerWidth() < mobileWidth) {
                                    chart.options.legend.position = 'top';
                                } else {
                                    chart.options.legend.position = 'right';
                                }
                            },
                            layout: {
                                padding: {
                                    top: 0,
                                    bottom: 0,
                                    left: 0,
                                    right: 0
                                }
                            },
                            legend: {
                                display: true,
                                position: $wnd.outerWidth() < mobileWidth ? 'top' : 'right',
                                labels: {
                                    boxWidth: 24,
                                    fontSize: 14,
                                    padding: 30
                                }
                            },
                            tooltips: {
                                enabled: false
                            },
                            plugins: {
                                datalabels: {
                                    formatter: function(value, context) {
                                        //return value + '%';
                                        return '';
                                    }
                                }
                            }
                        }
                    });
                    break

                case 'graph':
                    var ctx = $chart[0].getContext("2d");
                    var gradientDark = ctx.createLinearGradient(0, 0, 0, 400);
                    var gradientLight = ctx.createLinearGradient(0, 0, 0, 400);
                    var axeType = $chart.is('[data-y-type]') ? $chart.data('y-type') : '';
                    gradientDark.addColorStop(0, '#f2f3f4');
                    gradientDark.addColorStop(1, '#ffffff');
                    gradientLight.addColorStop(0, 'rgba(210,174,110,0.12)');
                    gradientLight.addColorStop(1, 'rgba(255,255,255,0.12)');
                    window.myBar = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartLabels,
                            datasets: [
                                {
                                    backgroundColor: $chart.is('[data-color-dark]') ? gradientDark : gradientLight,
                                    data: chartValues,
                                    borderWidth: 2,
                                    borderColor: $chart.is('[data-color-dark]') ? '#3b4256' : '#d2ae6e',
                                    pointRadius: 3,
                                }
                            ]
                        },
                        showTooltips: true,
                        options: {
                            animation: false,
                            responsive: true,
                            // maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    top: 0,
                                    bottom: 0,
                                    left: 0,
                                    right: 15
                                }
                            },
                            legend: {
                                display: false
                            },
                            tooltips: {
                                enabled: true,
                                displayColors: false,
                                callbacks: {
                                    label: function(tooltipItem, data) {
                                        return tooltipItem.yLabel + axeType;
                                    }
                                }
                            },
                            elements: {
                                point: {
                                    radius: 0
                                },
                                line: {
                                    tension: 0, // disables bezier curves
                                }
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        display: false,
                                        drawBorder: true,
                                        color: $chart.is('[data-color-dark]') ? '#dde1e6' : '#848e99',
                                    },
                                    ticks: {
                                        display: true,
                                        fontColor: $chart.is('[data-color-dark]') ? '#3b4256' : '#f4f7fb',
                                        fontSize: 14,
                                        padding: 20
                                    }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        display: false,
                                        drawBorder: false,
                                        // offsetGridLines: false
                                    },
                                    ticks: {
                                        display: true,
                                        fontColor: $chart.is('[data-color-dark]') ? '#848e99' : '#f4f7fb',
                                        fontSize: 14,
                                        padding: 16,
                                        callback: function(value, index, values) {
                                            return value + axeType;
                                        }
                                    }
                                }]
                            },
                            plugins: {
                                datalabels: {
                                    formatter: function(value, context) {
                                        return '';
                                    }
                                }
                            }
                        }
                    });
                    break
            }
        });
    }

    drawCharts();
    // /DIAGRAM


    // AUDIO PLAYER
    $('audio').each(function() {
        new Plyr(this, {
            controls: [
                'play', // Play/pause playback
                'progress', // The progress bar and scrubber for playback and buffering
                'current-time', // The current time of playback
                'duration', // The full duration of the media
                'mute', // Toggle mute
                'captions', // Toggle captions
            ]
        });
    });
    // /AUDIO PLAYER


    // TOOLTIP
    if ($('.tooltip').length) {
        $('.tooltip').tipso();
    }
    // /TOOLTIP


    // SELECT
    $('.custom-select select').selectmenu();
    // /SELECT


    // LIMIT TEXTAREA
    function initFieldLimit() {
        $('textarea[maxlength]').keyup(function() {
            var $this = $(this);
            var limit = parseInt($this.attr('maxlength'));
            var $limitElement = $this.closest('.form-control-holder').find('.form-limit');
            var difference;

            if (limit - $this.val().length >= 0) {
                difference = limit - $this.val().length;
                $limitElement.html('Осталось '+ difference +' символов');
            } else {
                difference = Math.abs(limit - $this.val().length);
                $limitElement.html('Лимит превышен на '+ difference +' символов');
            }
        });
    }

    $('textarea[maxlength]').each(function() {
        $(this).wrap('<div class="form-control-holder">');
        $(this).after('<div class="form-limit">Осталось '+ $(this).attr('maxlength') +' символов</div>');
        initFieldLimit();
        $(this).keyup();
    });
    // /LIMIT TEXTAREA


    // DATEPICKER
    $('.datepicker').each(function() {
        $dateInput = $(this);
        $dateInput.dateRangePicker({
            container: $dateInput.parent(),
            language: 'ru',
            startOfWeek: 'monday',
            separator : ' - ',
            format: 'DD.MM.YYYY',
            autoClose: true,
        });
    });
    $('.datepicker-ajax').each(function() {
        $dateInput = $(this);
        $dateInput.dateRangePicker({
            container: $dateInput.parent(),
            language: 'ru',
            startOfWeek: 'monday',
            separator : ' - ',
            format: 'DD.MM.YYYY',
            autoClose: true,
        }).on('datepicker-change', function(event,obj) {
            /* Выполняется когда выбран диапазон, можно запустить аякс здесь */
            if ($dateInput.val() != '') {
                $dateInput.addClass('datepicker__filled');
            } else {
                $dateInput.removeClass('datepicker__filled');
            }

            // obj will be something like this:
            // {
            //      date1: (Date object of the earlier date),
            //      date2: (Date object of the later date),
            //      value: "2013-06-05 to 2013-06-07"
            // }
        });
    });

    $body.on('change', '.datepicker-ajax', function() {
        var re = /\s*-\s*/
        var dateArr =  $(this).val().split(re);

        console.log(dateArr)

        $(this).data('dateRangePicker').setDateRange(dateArr[0], dateArr[1]);
    });

    $body.on('click', '.form-control-reset', function() {
        $(this).prev('input').val('').removeClass('datepicker__filled');
    });
    // /DATEPICKER


    // BREADCRUMBS
    $('.breadcrumbs .my-breadcrumbNode').last().addClass('hidden');
    // /BREADCRUMBS


    // FILE UPLOAD
    function errorHandler(evt) {
        switch(evt.target.error.code) {
            case evt.target.error.NOT_FOUND_ERR:
                alert('File Not Found!');
                break;
            case evt.target.error.NOT_READABLE_ERR:
                alert('File is not readable');
                break;
            case evt.target.error.ABORT_ERR:
                break; // noop
            default:
                alert('An error occurred reading this file.');
        };
    }

    function readerLoadStart(files, i) {
        var f = files[i];
        var fileExtension = f.name.split('.').pop();

        $('.fileupload_list').append('<div id="file'+ fileCount +'" class="fileupload_item"><div class="fileupload_iten-delete">close</div>' +
            '<div class="fileupload_preview"><div class="fileupload_preview-type">'+ fileExtension +'</div></div>'+
            '<div class="fileupload_item-content"><p class="fileupload_item-name">'+ f.name +'</p>'+
            '<div class="fileupload_item-status"><span class="fileupload_item-size">'+ Math.floor(f.size/1024) +' kb</span>'+
            '<div class="fileupload_item-progress"><div class="fileupload_progress-bar"></div></div>'+
            '<div class="fileupload_item-progress-value">0%</div>'+
            '</div></div></div>');
    }

    function readerLoading(e) {
        // evt is an ProgressEvent.
        if (e.lengthComputable) {
            var percentLoaded = Math.round((e.loaded / e.total) * 100);
            // Increase the progress bar length.
            if (percentLoaded < 100) {
                $('#file'+fileCount).find('.fileupload_progress-bar').css('width', percentLoaded + '%');
                $('#file'+fileCount).find('.fileupload_item-progress-value').text(percentLoaded + '%');
            }
        }
    }

    function readerLoaded(e, files, i) {
        var bin = e.target.result;
        // do sth with text

        if (files[i].type.match('image.*')) {
            $('#file'+fileCount).find('.fileupload_preview').remove();
            $('#file'+fileCount).prepend('<img class="fileupload_thumb" src="'+ bin +'">');
        }

        $('#file'+fileCount).find('.fileupload_progress-bar').css('width', 100 + '%');
        $('#file'+fileCount).find('.fileupload_item-progress-value').text(100 + '%');

        fileCount++;

        // If there's a file left to load
        if (i < files.length - 1) {
            // Load the next file
            setupReader(files, i+1);
        }
    }

    function setupReader(files, i) {
        var file = files[i];
        var reader = new FileReader();
        reader.onloadstart = function(e){
            readerLoadStart(files, i);
        };
        reader.onprogress = function(e){
            readerLoading(e);
        };
        reader.onload = function(e){
            readerLoaded(e, files, i);
        };
        reader.readAsDataURL(file);
    }

    function handleFileSelect(evt) {
        evt.stopPropagation();
        evt.preventDefault();
        dropZone.removeClass('active');
        $('.fileupload_list').removeClass('hidden');
        var files;

        // файл добавлен через инпут или перекинут из ОС
        if (evt.target.files) {
            files = evt.target.files; // FileList object.
        } else {
            files = evt.dataTransfer.files; // FileList object.
        }

        setupReader(files, 0);
    }

    function handleDragOver(evt) {
        evt.stopPropagation();
        evt.preventDefault();

        evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
    }

    // Setup the dnd listeners.
    var dropZone = $('.fileupload_dropzone');
    var fileCount = 0;

    if (dropZone.length) {
        dropZone.on('dragover', handleDragOver, false);
        dropZone.on('dragenter', function() {
            dropZone.addClass('active');
        });
        dropZone.on('dragleave', function() {
            dropZone.removeClass('active');
        });
        dropZone[0].addEventListener('drop', handleFileSelect, false);
        $('.fileupload_control')[0].addEventListener('change', handleFileSelect, false);

        // delete uploaded file
        $body.on('click', '.fileupload_iten-delete', function() {

        });
    }
    // /FILE UPLOAD
});
