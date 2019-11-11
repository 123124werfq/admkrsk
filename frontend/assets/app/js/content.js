(function() {
	var Utils = {
		setCookie: function(name, value, options) {
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
		},
		getCookie: function(name) {
			var matches = document.cookie.match(new RegExp(
				"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
			));
			return matches ? decodeURIComponent(matches[1]) : undefined;
		}
	};

	ODUtils.getItems({
		resource: "root.redirect",
		params: [{
			name: "select",
			value: "from,to"
		}, {
			name: "filter",
			value: "enabled eq '1'"
		}],
		async: true
	}).then(function(items) {
		var link = window.location.pathname.toLowerCase();
		items.forEach(function(item) {
			var l = item.from.toLowerCase();
			if(link == l) {
				window.location.replace(item.to + location.search);
			}
		});
	});

	if(!Utils.getCookie("DeviceChannel") || Utils.getCookie("DeviceChannel") == "-1") {
		Utils.setCookie('DeviceChannel', "2c8005e555f443e4005e", { path: '/', expires: 3600 * 24 });
	}


	$("a.header_logo").attr("href", "/Pages/default_new.aspx");
	$("a.footer-logo").attr("href", "/Pages/default_new.aspx");
})();



$("document").ready(function() {
	var Private = {
		//dateOptions: {day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric'}
		dateOptions: {day: 'numeric', month: 'long', year: 'numeric'},
		datetimeOptions: {year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric' },
		utils: {
			get2digit: function(number) {
				return number < 10 ? "0" + number : number;
			},
			dateForCounter: function(date) {
				var templateDate = "{0}/{1}/{2} {3}:{4}"

				return templateDate
					.replace("{0}", date.getFullYear())
					.replace("{1}", Private.utils.get2digit(date.getMonth() + 1))
					.replace("{2}", Private.utils.get2digit(date.getDate()))
					.replace("{3}", Private.utils.get2digit(date.getHours()))
					.replace("{4}", Private.utils.get2digit(date.getMinutes()))
			}
		}
	}

	// BUTTONS
	$("button").click(function(e) {e.preventDefault()})
	// /BUTTONS

	// MENU
	//$(".header-menu_component .root > li:gt(4)").hide()

	$('.sitemap_component .root').addClass('sitemap_menu').children('li').addClass('sitemap_menu-item')
		.children('a').addClass('sitemap_header').children().addClass('sitemap_header-text').parent().siblings('ul').addClass('sitemap_submenu')
		.children('li').addClass('sitemap_submenu-item').children('a').addClass('sitemap_submenu-link');

	$('.sitemap_submenu').parent().addClass('sitemap_menu-item__submenu');

	$('.sitemap_header').wrap('<span class="sitemap_header-wrap"></span>');
	// /MENU

	// LK
	$.get('/_layouts/15/restful/service.svc/uinfo')
		.then(function(uinfo) {
			var container = $("#lk"),
				gosbar_btn_text = 'Личный кабинет',
				dropdown_menu_items = '<a class="dropdown-menu_item" href="/login/default.aspx?ReturnUrl=%2fPages%2fdefault.aspx">Войти</a>'

			if(uinfo.claimprovidercontext == 'http://www.admkrsk.ru' && uinfo.upn != 'guest') {
				var fullName = uinfo.title ? uinfo.title : uinfo.fullname,
					gosbar_btn_text = fullName.split(' ').slice(0, 2).join(' '),
					dropdown_menu_items =
						  '<a class="dropdown-menu_item" href="/lk/Pages/reception.aspx">Мои обращения</a>'
                        + '<a class="dropdown-menu_item" href="/reception/Pages/request.aspx">Написать обращение</a>'
                        + '<a class="dropdown-menu_item" href="/_layouts/15/closeConnection.aspx?loginasanotheruser=true&Source=%2freception%2fPages%2frequest.aspx%3floginasanotheruser%3dfalse">Выйти</a>';
			}

			$(".gosbar_btn .gosbar_btn-text", container).append(gosbar_btn_text);
			$(".dropdown-menu", container).append(dropdown_menu_items);
		});
	// /LK

	// MAINSLIDER
	if($(".main-slider").length) {
		ODUtils.getItems({
			resource: "root.mainslider",
			params: [{
				name: "select",
				value: "Title,Text,Link,Rank,Timer,TimerTitle,Image,isDark,ColorField,background"
			}, {
				name: "orderby",
				value: "Rank"
			}],
			async: true
		}).then(function(items) {
			if(!items.length) return;

			var container = $(".main-slider"),
				templateTimer =
					'<div class="main-countdown-holder">'
						+ '<h4>{0}</h4>'
						+ '<div class="main-countdown" data-date="{1}"></div>'
					+ '</div>',
				templateSlide =
					'<div class="main-slider_item {{class}}" style="{0}">'
						+ '<img class="main-slider_img" src="/_layouts/15/ADMKRSKNEW/img/main-slider-mask.png" alt="Название слайда">'
						+ '<div class="main-slider_content">'
							+ '<div class="container">'
								+ '<div class="main-slider_content-holder">'
									+ '<h1 class="main-slider_title">{1}</h1>'
									+ '<p class="main-slider_text">{2}</p>'
									+ '{3}'
								+ '</div>'
							+ '</div>'
						+ '</div>'
					+ '</div>',
				html = items.map(function(item) {
						var htmlTimer = item.Timer
							? templateTimer
								.replace("{0}", item.TimerTitle || "")
								.replace("{1}", Private.utils.dateForCounter(new Date(item.Timer)))
							: null;

						var background = item.background == "Изображение"
								? "background-image: url(\'{0}\');".replace("{0}", item.Image)
								: "background-color: {0};".replace("{0}", item.ColorField);

						return templateSlide
							.replace("{0}", background)
							.replace("{1}", item.Title)
							.replace("{2}", item.Text)
							.replace("{3}", htmlTimer != null ? htmlTimer : "")
							.replace("{{class}}", item.isDark ? "main-slider_item__dark" : "");
					});

			container.append(html);

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

			if ($('.main-countdown').length) {
				var $counter = $('.main-countdown');
				var counterDate = $counter.data('date');

				$('.main-countdown').countdown(counterDate).on('update.countdown', function(event) {
					var $this = $(this).html(event.strftime(''
						+ '<span class="countdown-item"><span class="countdown-value">%D</span><span class="countdown-label">дней</span></span>'
						+ '<span class="countdown-item"><span class="countdown-value">%H</span><span class="countdown-label">часов</span></span>'
						+ '<span class="countdown-item"><span class="countdown-value">%M</span><span class="countdown-label">минут</span></span>'
						+ '<span class="countdown-item"><span class="countdown-value">%S</span><span class="countdown-label">секунд</span></span>'));
				});
			}

			//container.show();
		});
	}

	// /MAINSLIDER

	// MAINLINKS
	var container = $(".main-promo");
	if(container.length) {
		ODUtils.getItems({
			resource: "root.mainpromo",
			params: [{
				name: "select",
				value: "Type,Title,Link"
			}, {
				name: "orderby",
				value: "Rank"
			}],
			async: true
		}).then(function(items) {
			var tmpLink =
				'<li class="main-nav_item">'
					+ '<a class="main-nav_link" href="{0}">{1}</a>'
				+ '</li>',
				htmlLinks = items
					.filter(function(item) {
						return item.Type === "Ссылка";
					})
					.map(function(item) {
						return tmpLink
							.replace("{0}", item["Link"])
							.replace("{1}", item["Title"]);
					}).join("");
				slogan = items
					.filter(function(item) {
						return item.Type === "Слоган"
					})[0];

			if(slogan) {
				$(".main-subtitle", container).append(slogan.Title);
			}

			$(".main-nav", container).append(htmlLinks);

			container.show();
		});
	}
	// /MAINLINKS

	// DIRECTIONS
	if($("#directions .directions").length) {
		ODUtils.getItems({
			resource: "root.activities",
			params: [{
				name: "select",
				value: "Title,Link,Icon"
			}, {
				name: "filter",
				value: "Show eq '1'"
			}, {
				name: "orderby",
				value: "Rank"
			}],
			async: true
		}).then(function(items) {
			var container = $("#directions .directions"),
				template =
					'<div class="directions_item">'
						+ '<div class="directions_img">'
							+ '<img class="directions_img-picture" src="{0}" alt="" width="64" height="70" alt="Название направления">'
						+ '</div>'
						+ '<a href="{1}" class="directions_content">'
							+ '<h4 class="directions_title">{2}</h4>'
						+ '</a>'
					+ '</div>',
				html = items.map(function(item) {
					return template
						.replace("{0}", item.Icon)
						.replace("{1}", item.Link)
						.replace("{2}", item.Title)
				}).join('');

			container.append(html);

			$('.directions_title').hover(function(){
				$(this).closest('.directions_item').find('.directions_img').toggleClass('svg-active');
			});
		});
	}
	// /DIRECTIONS


	// SITUATIONS
	if($("#situations .situations").length) {
		ODUtils.getItems({
			resource: "root.situations",
			params: [{
				name: "select",
				value: "ID,Title,parent/ID,Icon2"
			}, {
				name: "filter",
				value: "Show eq '1'"
			}, {
				name: "orderby",
				value: "Rank"
			}],
			async: true
		}).then(function(situations) {
			var container = $("#situations .situations"),
				templateLink = '/administration/singlewindow/Pages/situation.aspx?SituationID={0}'
				templateSituations =
					'<div class="situations_item">'
						+ '<div class="situations_img">'
							+ '<img class="situations_img-picture" src="{0}" alt="" width="64" height="70" alt="Название ситуациии">'
						+ '</div>'
						+ '<div class="situations_content">'
							+ '<h3 class="situations_title">'
								+ '<a>{1}</a>'
							+ '</h3>'
							+ '<div class="situations_another">{2}</div>'
						+ '</div>'
					+ '</div>',
				templateSituationsAnother =
					'<a href="{0}">{1}</a>',
				templateHiddenContainer =
					'<div class="situations hidden" id="hidden-situations">{0}</div>',
				root = situations.filter(function(situation) {
						return situation.parent && situation.parent.ID === 3;
					}),
				htmlItems = root
					.slice(0, 6)
					.map(function(situation) {
						var another = situations
							.filter(function(item) {
								return item.parent && item.parent.ID === situation.ID;
							})
							.map(function(item) {
								return templateSituationsAnother
									.replace("{0}", templateLink.replace("{0}", item.ID))
									.replace("{1}", item.Title);
							})
							.join(', ');


						return templateSituations
							.replace("{0}", situation.Icon2)
							.replace("{1}", situation.Title)
							.replace("{2}", another);
					}).join(''),
				htmlItemsHidden = root
					.slice(6, root.length)
					.map(function(situation) {
						var another = situations
							.filter(function(item) {
								return item.parent && item.parent.ID === situation.ID;
							})
							.map(function(item) {
								return templateSituationsAnother
									.replace("{0}", templateLink.replace("{0}", item.ID))
									.replace("{1}", item.Title);
							})
							.join(', ');

						return templateSituations
							.replace("{0}", situation.Icon2)
							.replace("{1}", situation.Title)
							.replace("{2}", another);
					}).join('');

			container.append(htmlItems);
			container.append(templateHiddenContainer.replace("{0}", htmlItemsHidden));

			$('.situations_content').hover(function(){
				$(this).siblings('.situations_img').toggleClass('svg-active');
			});
		});
	}
	// /SITUATIONS

	// AREAS
	if($(".regions").length) {
		ODUtils.getItems({
			resource: "root.areas",
			params: [{
				name: "select",
				value: "Area,Type,Link,LinkType,LinkName"
			}],
			async: true
		}).then(function(areas) {
			var container = $(".regions"),
				templateArea =
					'<div class="region-item">'
						+ '<div class="region-item-holder">'
							+ '<a href="{0}" class="region-item_main">'
								+ '<h3 class="region-item_title">{1}</h3>'
								+ '<img class="region-item_img img-responsive" src="{2}" alt="Название района">'
							+ '</a>'
							+ '<div class="region-item_menu">'
							   + '{3}'
							+ '</div>'
						+ '</div>'
					+ '</div>',
				templateLink =
					'<a class="region-item_menu-item" href="{0}">{1}</a>',
				areaNames = areas.filter(function(area) {
						return area.LinkType == "Главная страница";
					}).map(function(area) {
						return area.Area;
					}),
				html = areaNames.map(function(areaName) {
						var entities = areas.filter(function(area) {
									return area.Area == areaName;
								}),
							mainLink = entities.filter(function(area) {
									return area.Type == "Ссылка" && area.LinkType == "Главная страница";
								})[0],
							mainImage = entities.filter(function(area) {
									return area.Type == "Изображение";
								})[0],
							links = entities.filter(function(area) {
									return area.Type == "Ссылка" && area.LinkType == "Страницы раздела";
								}),
							htmlLinks = links.map(function(link) {
								return templateLink
									.replace("{0}", link.Link)
									.replace("{1}", link.LinkName);
							}).join(""),
							htmlArea = templateArea
								.replace("{0}", mainLink.Link ? mainLink.Link : "#")
								.replace("{1}", mainLink.LinkName ? mainLink.LinkName : areaName)
								.replace("{2}", ((mainImage && mainImage) ? mainImage.Link : "/assets/Маски/mask-768-302.jpg") + "?RenditionID=5")
								.replace("{3}", htmlLinks);

						return htmlArea;
					}).join("")

			container.append(html);
		});
	}
	// /AREAS


	// NEWS
	if($("#news .news-list").length) {
		ODUtils.getItems({
			resource: "root.news",
			params: [{
				name: "select",
				value: "ID,OData__Comments,PhotoMainNews,TextMainNews,ArticleStartDate,n_rubric/Title"
			}, {
				name: "filter",
				value: "OData__ModerationStatus eq '0' and tagOnly eq '0' and MainNews eq '1'"
			}, {
				name: "orderby",
				value: "ID desc"
			}, {
				name: "top",
				value: "1"
			}],
			async: true
		}).then(function(news) {
			var container = $("#news .news-list"),
				templateLink = '/press/news/Pages/news.aspx?RecordID={0}',
				templateNewsItemWide =
					'<div class="news-item news-item__wide">'
						+ '<div class="news-item_container">'
							+ '<div class="news-item_picture">'
								+ '<a href="{0}" class="news-item_img"><img class="img-responsive" src="{1}" alt="Заголовок новости"></a>'
							+ '</div>'
							+ '<div class="news-item_content">'
								+ '<h3 class="news_title"><a href="{0}">{2}</a></h3>'
								+ '{3}'
								+ '<ul class="events_info">'
									+ '<li class="events_info-item events_info-item__place"><a href="/press/news/Pages/list.aspx?rubric={5}">{5}</a></li>'
									+ '<li class="events_info-item">{4}</li>'
								+ '</ul>'
							+ '</div>'
						+ '</div>'
					+ '</div>';

			if(news.length > 0) {
				var item = news[0];
				if(!item.PhotoMainNews) return false;

				var	date = new Date(item.ArticleStartDate)
							.toLocaleString("ru-RU", Private.dateOptions),
					html = templateNewsItemWide
							.replace(/\{0\}/g, templateLink.replace("{0}", item.ID))
							.replace("{1}", item.PhotoMainNews + "?RenditionID=7")
							.replace("{2}", item.OData__Comments)
							.replace("{3}", item.TextMainNews)
							.replace("{4}", item.ArticleStartDate ? date : "")
							.replace(/\{5\}/g, item.n_rubric ? item.n_rubric.Title : "");

				container.append(html);

				return true;
			}

			return false;
		}).done(function(hasMain) {
			ODUtils.getItems({
				resource: "root.news",
				params: [{
					name: "select",
					value: "ID,OData__Comments,ArticleStartDate,n_rubric/Title"
				}, {
					name: "filter",
					value: "OData__ModerationStatus eq '0' and tagOnly eq '0' and MainNews ne '1'"
				}, {
					name: "orderby",
					value: "ID desc"
				}, {
					name: "top",
					value: hasMain ? "3" : "9"
				}],
				async: true
			}).then(function(news) {
				var container = $("#news .news-list"),
					templateLink = '/press/news/Pages/news.aspx?RecordID={0}',
					templateNewsItem =
						'<div class="news-item">'
							+ '<h4 class="news_title"><a href="{0}">{1}</a></h4>'
							+ '<ul class="events_info">'
								+ '<li class="events_info-item events_info-item__place"><a href="/press/news/Pages/list.aspx?rubric={3}">{3}</a></li>'
								+ '<li class="events_info-item">{2}</li>'
							+ '</ul>'
						+ '</div>',
					html = news
						.map(function(item) {
							var date = new Date(item.ArticleStartDate)
								.toLocaleString("ru-RU", Private.dateOptions);

							return templateNewsItem
								.replace("{0}", templateLink.replace("{0}", item.ID))
								.replace("{1}", item.OData__Comments)
								.replace("{2}", item.ArticleStartDate ? date : "")
								.replace(/\{3\}/g, item.n_rubric ? item.n_rubric.Title : "");
						}).join('');

				container.append(html);
			});
		});
	}
	// /NEWS

	// ANNOUNCEMENTS
	if($("#announcement .news-list").length) {
		var AnnouncementUtils = {
			buildFilter: function() {
				var filter = "",
					startDate = moment.utc(moment(new Date()).startOf('day')).format(),
					endDate = moment.utc(moment(new Date()).endOf('day')).format();

				filter +=
					  " and ("
						+ "ArticleStartDate ge datetime'" + startDate + "' and ArticleStartDate le datetime'" + endDate + "'"
						+ " or "
						+ "expire ge datetime'" + startDate + "' and expire le datetime'" + endDate + "'"
						+ " or "
						+ "ArticleStartDate le datetime'" + startDate + "' and expire ge datetime'" + endDate + "'"
					+ ")";

				return filter;
			},
			getDatetime: function(item) {
				var datetime = item["ArticleStartDate"] ? moment(item["ArticleStartDate"]).lang("ru").format("LL") : "";
				if(item["expire"]) {
					datetime += " - ";
					datetime += item["expire"] ? moment(item["expire"]).lang("ru").format("LLL") : ""
				}

				return datetime;
			}
		}

		ODUtils.getItems({
			resource: "root.announcements",
			params: [{
				name: "select",
				value: "ID,OData__Comments,ArticleStartDate,expire,n_rubric/Title,MainNews,TextMainNews"
			}, {
				name: "filter",
				value: "OData__ModerationStatus eq '0' and onMian eq '1'" + AnnouncementUtils.buildFilter()
			}, {
				name: "orderby",
				value: "ArticleStartDate desc"
			}, {
				name: "top",
				value: "10"
			}],
			async: true
		}).then(function(news) {
			var container = $("#announcement .news-list"),
				templateLink = '/press/announcements/Pages/announcements.aspx?RecordID={0}'
				templateAnnouncementItemWide =
					'<div class="news-item news-item__wide news-item__wide-anons">'
						+ '<div class="news-item_holder">'
							+ '<h3 class="news_title"><a href="{0}">{1}</a></h3>'
							+ '{4}'
							+ '<ul class="events_info">'
								+ '<li class="events_info-item events_info-item__place"><a href="/press/announcements/Pages/list.aspx?rubric={3}">{3}</a></li>'
								+ '<li class="events_info-item">{2}</li>'
							+ '</ul>'
						+ '</div>'
					+ '</div>',
				templateAnnouncementItem =
					'<div class="news-item">'
						+ '<h4 class="news_title news_title__announcement"><a href="{0}">{1}</a></h4>'
						+ '<ul class="events_info">'
							+ '<li class="events_info-item events_info-item__place"><a href="/press/announcements/Pages/list.aspx?rubric={3}">{3}</a></li>'
							+ '<li class="events_info-item">{2}</li>'
						+ '</ul>'
					+ '</div>',
				htmlItems = news
					.filter(function(item) {
						return !item.MainNews;
					})
					.map(function(item) {
						return templateAnnouncementItem
							.replace("{0}", templateLink.replace("{0}", item.ID))
							.replace("{1}", item.OData__Comments)
							.replace("{2}", AnnouncementUtils.getDatetime(item))
							.replace(/\{3\}/g, item.n_rubric ? item.n_rubric.Title : "");
					}).join('')
				htmlWideItems = news
					.filter(function(item) {
						return item.MainNews;
					})
					.map(function(item) {
						return templateAnnouncementItemWide
							.replace("{0}", templateLink.replace("{0}", item.ID))
							.replace("{1}", item.OData__Comments)
							.replace("{2}", AnnouncementUtils.getDatetime(item))
							.replace(/\{3\}/g, item.n_rubric ? item.n_rubric.Title : "")
							.replace(/\{4\}/g, item.TextMainNews ? item.TextMainNews : "");
					}).pop();

			container.append(htmlWideItems);
			container.append(htmlItems);
		});
	}
	// /ANNOUNCEMENTS


	// GOSLINKS
	if($(".goslinks").length) {
		ODUtils.getItems({
			resource: "root.goslinks",
			params: [{
				name: "select",
				value: "Title,Link,Type,Icon"
			}],
			async: true
		}).then(function(goslinks) {
			var container = $(".goslinks"),
				templateGoslinkTab =
					'<div class="smart-menu-tabs_item tab-control slide-hover-item" data-href="{0}">'
						+ '<a class="smart-menu-tabs_control">{1}</a>'
					+ '</div>',
				templateGoslinkImg =
					'<img class="goslinks_img" src="{0}" alt="Название ссылки">',
				templateGoslinkBg =
					'<span class="goslinks_bg" style="background-image: url("{0}");"></span>',
				templateGoslinkItem =
					'<div class="goslinks-col" data-filter-type="{0}">'
						+ '<a href="{1}" class="goslinks-item" target="_blank">'
							+ '{2}'
							+ '<h4 class="goslinks_title">{3}</h4>'
						+ '</a>'
					+ '</div>',
				templateHiddenContainer =
					'<div class="hidden" id="hidden-goslinks">{0}</div>',
				types = goslinks
					.map(function(goslink) {
						return goslink.Type
					})
					.filter(function(item, pos, array) {
						return array.indexOf(item) == pos;
					}),
				htmlTabs = types
					.map(function(type, index) {
						return templateGoslinkTab
							.replace("{0}", index + 1)
							.replace("{1}", type);
					}).join(''),
				htmlItems = goslinks
					.slice(0, 9)
					.map(function(goslink) {
						var idType = types.indexOf(goslink.Type) + 1,
							image = goslink.Icon ? templateGoslinkImg.replace("{0}", goslink.Icon) : "";

						return templateGoslinkItem
							.replace("{0}", idType)
							.replace("{1}", goslink.Link)
							.replace("{2}", image)
							.replace("{3}", goslink.Title);
					}).join('')
				htmlItemsHidden = goslinks
					.slice(9, goslinks.length)
					.map(function(goslink) {
						var idType = types.indexOf(goslink.Type) + 1,
							image = goslink.Icon ? templateGoslinkImg.replace("{0}", goslink.Icon) : "";

						return templateGoslinkItem
							.replace("{0}", idType)
							.replace("{1}", goslink.Link)
							.replace("{2}", image)
							.replace("{3}", goslink.Title);
					}).join('');

			//container.append(html);
			$(".tab-controls-holder", container).append(htmlTabs);
			$(".goslinks-list", container).append(htmlItems);
			$(".goslinks-list", container).append(templateHiddenContainer.replace("{0}", htmlItemsHidden));

		});
	}
	// /GOSLINKS

	// EVENTS

	var EventsUtils = {
		buildFilter: function() {
			var filter = "",
				startDate = moment.utc(moment(new Date()).startOf('day')).format(),
				endDate = moment.utc(moment(new Date()).endOf('day')).format();

			filter +=
				  " and ("
					+ "ArticleStartDate ge datetime'" + startDate + "' and ArticleStartDate le datetime'" + endDate + "'"
					+ " or "
					+ "TaskDueDate ge datetime'" + startDate + "' and TaskDueDate le datetime'" + endDate + "'"
					+ " or "
					+ "ArticleStartDate le datetime'" + startDate + "' and TaskDueDate ge datetime'" + endDate + "'"
					+ " or "
					+ "ArticleStartDate le datetime'" + startDate + "' and WithoutEndDate eq '1'"
				+ ")";

			return filter;
		},
		getDatetime: function(item) {
			var datetime = item["ArticleStartDate"] ? moment(item["ArticleStartDate"]).locale("ru").format("LL") : "";
			if(item["TaskDueDate"]) {
				datetime += " - ";
				datetime += item["TaskDueDate"] ? moment(item["TaskDueDate"]).locale("ru").format("LL") : ""
			}

			return datetime;
		},
		getLink: function(item) {
			if(item["externalurl"]) return item["externalurl"];

			return "/press/events/Pages/000/events.aspx?RecordID=" + item["ID"]
		}
	}

	if($(".events").length) {
		ODUtils.getItems({
			resource: "root.events",
			params: [{
				name: "select",
				value: "Title,Type,Place,Text,Image,ArticleStartDate,TaskDueDate,externalurl"
			}, {
				name: "filter",
				value: "isFirstPage eq '1' and isMainEvent eq '1'" + EventsUtils.buildFilter()
			}],
			async: true
		}).then(function(events) {
			var container = $(".events"),
					templateEventWide =
						'<div class="events-item events-item__wide">'
							+ '<div href="{0}" class="events_img-holder">'
								+ '<img class="events_img img-responsive" src="{1}" alt="Название мероприятия">'
								+ '<div class="events_content">'
									+ '<ul class="events_info hidden-xs">'
										+ '<li class="events_info-item events_info-item__place"><a href="#">{2}</a></li>'
										+ '<li class="events_info-item">{3}</li>'
									+ '</ul>'
									+ '<h3 class="events_title"><a href="{0}">{4}</a></h3>'
									+ '<p class="events_text">{5}</p>'
									+ '<ul class="events_info visible-xs">'
										+ '<li class="events_info-item events_info-item__place"><a href="#">{2}</a></li>'
										+ '<li class="events_info-item">{3}</li>'
									+ '</ul>'
								+ '</div>'
							+ '</div>'
						+ '</div>',
					html = events.map(function(event) {
						return templateEventWide
							.replace(/\{0\}/g, EventsUtils.getLink(event))
							.replace("{1}", event.Image ? event.Image : "/assets/Городские проекты и события/demo.png")
							.replace(/\{2\}/g, event.Type)
							.replace(/\{3\}/g, EventsUtils.getDatetime(event))
							.replace("{4}", event.Title ? event.Title : "")
							.replace("{5}", event.Text ? event.Text : "");
					}).join("");

			$(".events-list", container).append(html);
		}).done(function() {
			ODUtils.getItems({
				resource: "root.events",
				params: [{
					name: "select",
					value: "ID,Title,Type,Place,Text,Image,ArticleStartDate,TaskDueDate,externalurl"
				}, {
					name: "filter",
					value: "isFirstPage eq '1' and isMainEvent ne '1'" + EventsUtils.buildFilter()
				}, {
					name: "orderby",
					value: "ArticleStartDate desc"
				}],
				async: true
			}).then(function(events) {
				var container = $(".events"),
					templateEvent =
						'<div class="events-item">'
							+ '<a href="{0}" class="events_img-holder">'
								+ '<img class="events_img img-responsive" src="{1}" alt="Название мероприятия">'
							+ '</a>'
							+ '<h4 class="events_title"><a href="{0}">{2}</a></h4>'
							+ '<ul class="events_info">'
								+ '<li class="events_info-item events_info-item__place"><a href="#">{3}</a></li>'
								+ '<li class="events_info-item">{4}</li>'
							+ '</ul>'
						+ '</div>'
					templateEventWide = "",
					countEvents = 0,
					showEvents = 3,
					html = events
						.map(function(event) {
							return templateEvent
								.replace(/\{0\}/g, EventsUtils.getLink(event))
								.replace("{1}", event.Image ? event.Image : "/assets/Городские проекты и события/demo.png")
								.replace("{2}", event.Title)
								.replace("{3}", event.Type ? event.Type : "Событие")
								.replace("{4}", EventsUtils.getDatetime(event));
						}).join("");

						/*.filter(function(event, index) {
							if(events.length <= showEvents || (events.length - index - (showEvents - countEvents) == 0)) return true;
							if(countEvents == showEvents) return false;

							var yes_no = Math.random() >= 0.5;
							countEvents += (yes_no ? 1 : 0);

							return yes_no;
						})*/

				$(".events-list", container).append(html);
			});
		});
	}
	// /EVENTS


	// SOCIALS
	ODUtils.getItems({
		resource: "root.socials",
		params: [{
			name: "select",
			value: "Title,Link,Icon"
		}],
		async: true
	}).then(function(items) {
		var container = $(".footer-socials_holder"),
			templateSocialItem = '<a href="{0}" target="_blank" class="footer-socials_item">{1}</a>',
			html = items.map(function(item) {
					return item.Link
						? templateSocialItem
							.replace("{0}", item.Link)
							.replace("{1}", item.Icon)
						: "";
				}).join("")

		container.append(html);
	});
	// /SOCIALS

	// GID
	if($(".gid").length) {
		ODUtils.getItems({
			resource: "root.gid",
			params: [{
				name: "select",
				value: "Title,Type,Link"
			}],
			async: true
		}).then(function(items) {
			var container = $(".gid"),
				templateImageItem = '<img src="{0}" alt="{1}">',
				templateLinkItem = '<li class="gid-menu_item"><a href="{0}" class="gid-menu_link">{1}</a></li>',
				images = items.filter(function(item) {
						return item.Type == "Изображение";
					}),
				links = items.filter(function(item) {
						return item.Type == "Ссылка";
					}),
				htmlImages = images.map(function(image) {
						return templateImageItem
							.replace("{0}", image.Link + "?RenditionID=6")
							.replace("{1}", image.Title);
					}),
				htmlLinks = links.map(function(link) {
						return templateLinkItem
							.replace("{0}", link.Link)
							.replace("{1}", link.Title);
					});

			$(".gid-slider").append(htmlImages);
			$(".gid-menu").append(htmlLinks);

			// GID SLIDER
			$('.gid-slider').slick({
				infinite: true,
				slidesToShow: 1,
				dots: true,
				arrows: false,
				fade: true,
				cssEase: 'linear',
				speed: 600,
				autoplay: true,
				autoplaySpeed: 5000,
				pauseOnHover: false
			});
			// /GID SLIDER
		});
	}
	// /GID
});