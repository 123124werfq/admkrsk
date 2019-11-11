(function() {
	window.Maxsoft = window.Maxsoft || {};

	var Press = Maxsoft.Press = Maxsoft.Press || {};

	var PressConstants = {
		templates: {
			pathListTemplates: "/Style Library/res/js/press/common/public/templates/templates_list.hbs"
		},
		events: {
			 filterChanged: "Maxsoft:Press:List:filterChanged"
		}
	};

	/*var PressDataProvider = {
		getRubrics: function() {
			return ODUtils.getItems({
				resource: "press.news.dictionary",
				params: [{
					name: "select",
					value: "Title"
				}, {
					name: "orderby",
					value: "Title"
				}, {
					name: "filter",
					value: "Type eq 'NewsRubric'"
				}],
				async: true
			}).pipe(function(items) {
				return items.map(function(item) {
					return item.Title;
				});
			});
		}
	};*/

	Press.ListPage = Press.ListPage || (function(DataProvider) {
		var HTML = {
			buildRubricItems: function(callback) {
				ADMKRSK.html.buildItems(PressConstants.templates.pathListTemplates, DataProvider.getRubrics(), "#rubric_item", callback);
			},
			buildNewsItems: function(callback, filters) {
				ADMKRSK.html.buildItems(PressConstants.templates.pathListTemplates, DataProvider.getItems(filters), "#press_item", callback);
			},
			buildNewsWide: function(callback, filters) {
				ADMKRSK.html.buildItems(PressConstants.templates.pathListTemplates, DataProvider.getItemWide(filters), "#press_item__wide", callback);
			}
		};

		var View = {
			buildNews: function(container, filters) {
				container.html("");

				HTML.buildNewsItems(function(htmlNewsItems) {
					container.append(htmlNewsItems);

					if(htmlNewsItems.length) {
						$(".load-more").show();
					} else {
						$(".load-more").hide();
					}
				}, filters);

				HTML.buildNewsWide(function(htmlNewsWide) {
					container.prepend(htmlNewsWide);
				}, filters);
			},
			filters: {
				values: {
					skipDefault: null,
					skip: null,
					top: null,
					rubric: null,
					startDate: null,
					endDate: null
				},
				init: function() {
					var pageSize = 5;

					this.update({
						skipDefault: pageSize,
						skip: pageSize,
						top: pageSize,
						rubric: QSUtils.getQSParameterByName("rubric") || null
					});
				},
				update: function(params) {
					this.resetSkip();

					$.extend(this.values, params);

					$(document).trigger(PressConstants.events.filterChanged, {
						rubric: this.values.rubric,
						startDate: this.values.startDate,
						endDate: this.values.endDate
					});
				},
				resetSkip: function() {
					var skip = this.values.skipDefault;
					$.extend(this.values, {
						skip: skip
					});
				},
				updateSkip: function() {
					var skip = this.values.skip + this.values.top;
					$.extend(this.values, {
						skip: skip
					});
				}
			},
			initLoader: function(container) {
				var loadButton = $(".load-more");
				loadButton.click(function(e) {
					e.preventDefault();

					loadButton.addClass("active");
					HTML.buildNewsItems(function(htmlNewsItems) {
						if(!htmlNewsItems.length) {
							loadButton.hide()
						}

						loadButton.removeClass("active");


						container.append(htmlNewsItems);
					}, $.extend({}, View.filters.values));

					View.filters.updateSkip();
				});
			},
			initFilters: function() {
				View.filters.init();

				var $rubricInput = $(".header-controls select");
				HTML.buildRubricItems(function(htmlRubricItems) {
					$rubricInput.append('<option class="rubric-item" value="null">Все рубрики</option>');
					$rubricInput.append(htmlRubricItems);

					var value = View.filters.values.rubric;
					if(value) {
						$rubricInput.val(value);
						$rubricInput.selectmenu('refresh');
					}
				});
				$rubricInput.on('selectmenuchange', function() {
					var value = $(this).val();

					View.filters.update({
						rubric: value === "null" ? null : value
					});
				});

				$(".header-controls .datepicker-ajax").on('datepicker-change change', function(event, params) {
					View.filters.update({
						startDate: params.date1,
						endDate: params.date2
					});
				});

				$(".header-controls .form-control-reset").click(function() {
					View.filters.update({
						startDate: null,
						endDate: null
					});
				})
			}
		};

		return {
			build: function() {
				var newsListContainer = $(".press-list");
				$(document).on(PressConstants.events.filterChanged, function(e, filters) {
					View.buildNews(newsListContainer, filters);
				});

				View.initFilters();
				View.initLoader(newsListContainer);
			}
		};
	})(Press.ItemsDataProvider);
})();