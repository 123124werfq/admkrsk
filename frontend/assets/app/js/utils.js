(function() {
	if(!window.location.origin) {
		window.location["origin"] = window.location.protocol + "//" + window.location.host;
	}

	String.prototype.toProperCase = function () {
	    //return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	    return this.toLowerCase().replace(/^[а-я]/, function (x) {return x.toUpperCase()});
	};
})();


var QSUtils = {
	fieldNames: {
		minDate: "min_date",
		maxDate: "max_date",
		place: "place",
		area: "area",
		sector: "sector",
		category: "category_event"
	},
	getHashParameterByName: function(name) {
		var match = RegExp(name + '=([^&]*)').exec(window.location.hash);
		return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
		//return match && match[1].replace(/\+/g, ' ');
	},
	getQSParameterByName: function(name, url) {
		if (!url) {
			url = window.location.href;
	    }
	    name = name.replace(/[\[\]]/g, "\\$&");
	    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	        results = regex.exec(url);
	    if (!results) return null;
	    if (!results[2]) return '';
	    return decodeURIComponent(results[2].replace(/\+/g, " "));

	},
	setHashParameter: function(params) {
		var self = this;
		var qsHash = window.location.hash;

		$.each(params, function(i, param) {
			var match = RegExp(param.name + '=([^&]*)').exec(qsHash);
			if(!match) {
				//qsHash += (qsHash ? "&" : "#") + param.name + "=" + encodeURIComponent(param.value);
				qsHash += (qsHash ? "&" : "#") + param.name + "=" + param.value;
			} else {
				//qsHash = self.updateQueryStringParameter(qsHash, param.name, encodeURIComponent(param.value));
				qsHash = self.updateQueryStringParameter(qsHash, param.name, param.value);
			}
		});

		window.location.hash = 	qsHash;
	},
	updateQueryStringParameter: function(uri, key, value) {
		var re = new RegExp("([#|&])" + key + "=.*?(&|#|$)", "i");
		if (uri.match(re)) {
			return uri.replace(re, '$1' + key + "=" + value + '$2');
		} else {
			var hash =  '';
			if( uri.indexOf('#') !== -1 ){
			    hash = uri.replace(/.*#/, '#');
			    uri = uri.replace(/#.*/, '');
			}
			var separator = uri.indexOf('?') !== -1 ? "&" : "?";
			return uri + separator + key + "=" + value + hash;
		}
	},
	v2: {
		getQSAsObject: function() {
			var QSObject = {
				origin: window.location.origin,
				pathname: window.location.pathname,
				params: this.getParamsObject(),
				hash: this.getHashObject()
			};

			return QSObject;
		},
		getQSByObject: function(QSobject) {
			var URI = QSobject.origin + QSobject.pathname;

			if(Object.keys(QSobject.params).length) {
				URI += "?" + this.objectParamsToString(QSobject.params);
			}

			if(Object.keys(QSobject.hash).length) {
				URI += "#" + this.objectParamsToString(QSobject.hash);
			}

			return URI;
		},
		getHashObject: function() {
			var res = {};

			var hash = window.location.hash;
			if(hash.length) {
				res = this.stringParamsToObject(hash.replace("#", ""))
			}

			return res;
		},
		getParamsObject: function() {
			var res = {};

			var params = window.location.href;
			params = params
				.replace(window.location.origin, "")
				.replace(window.location.pathname, "")
				.replace(window.location.hash, "");

			if(params.length) {
				res = this.stringParamsToObject(params.replace("?", ""))
			}

			return res;
		},
		objectParamsToString: function(params) {
			var res = "";

			for (var name in params) {
				if (params.hasOwnProperty(name)) {
					res += name + "=" + params[name] + "&";
				}
			}

			return res.slice(0, res.length - 1);
		},
		stringParamsToObject: function(params) {
			var res = {};

			var parts = params.split("&");
			$.each(parts, function(i, part) {
				var p = part.split("=");
				res[p[0]] = p[1];
			});

			return res;
		}
	}
};

var ODUtils = (function() {
	var Private = {
		constants: {
			dataView: {
				tree: "Tree",
				flatList: "FlatList",
				list: "List"
			}
		},
		dataProcessor: {
			utils: {
				getRootItems: function(json, filter) {
					var levels = json.map(function(item) {
							return item.FileDirRef.split("/").length;
						}),
						rootLevel = filter.apply(null, levels);

					return json.filter(function(item) {
						return item.FileDirRef.split("/").length === rootLevel;
					});
				}
			},
			toTree: function(json) {
				var root = Private.dataProcessor.utils.getRootItems(json, Math.min);
				return Private.dataProcessor.toTreeRecursive(root, json);
			},
			toTreeRecursive: function(rows, json) {
				$.each(rows, function(i, row) {
					row["childs"] = json.filter(function(item) {
						return item.FileDirRef === row.FileDirRef + "/" + row.FileLeafRef;
					});
					var jsonChild = json.filter(function(item) {
						return item.FileDirRef.indexOf(row.FileDirRef + "/" + row.FileLeafRef + "/") != -1;
					});
					Private.dataProcessor.toTreeRecursive(row["childs"], jsonChild);
				});

				return rows;
			},

			toFlatList: function(json) {
				var rows = Private.dataProcessor.utils.getRootItems(json, Math.max);

				var res = [];
				$.each(rows, function(i, row) {
					res.push($.extend(Private.dataProcessor.toFlatListRecursive(row, json), row));
				});

				return res;
			},
			toFlatListRecursive: function(row, json) {
				var parts = row.FileDirRef.split("/"),
					FileLeafRef = parts.pop(),
					FileDirRef = parts.join("/"),
					newrow = json.filter(function(item) {
						return item.FileDirRef === FileDirRef && item.FileLeafRef === FileLeafRef;
					});

				if(newrow.length) {
					newrow = $.extend({}, newrow[0]);
					return $.extend(newrow, Private.dataProcessor.toFlatListRecursive(newrow, json));
				}

				return {};
			},
			convertList: function(json, dataView) {
				if(dataView === Private.constants.dataView.tree && json.length && json[0].FileDirRef) {
					return Private.dataProcessor.toTree(json);
				} else if(dataView === Private.constants.dataView.flatList && json.length && json[0].FileDirRef) {
					return Private.dataProcessor.toFlatList(json);
				}

				return json;
			}
		},
		getJSON: {
			sync: function(url, dataView) {
				var json;
				$.ajax({
					url: url,
					async: false,
					success: function(data) {
						json = data.d.results || [];
					}
				});

				return Private.dataProcessor.convertList(json, dataView);
			},
			async: function(url, dataView) {
				return $.ajax({
					url: url
				}).pipe(function(data) {
					var json = data.d.results || [];
					return Private.dataProcessor.convertList(json, dataView);
				});
			},
		},
		utils: {
			// УБРАТЬ ДОМЕН САЙТА
			getUrl: function(settings) {
				var params = settings.params
						? settings.params.map(function(item) {
							return "$" + item.name + "=" + encodeURI(item.value);
						}).join("&")
						: "",
					url = "http://www.admkrsk.ru/_layouts/15/restful/odata.ashx?"
						+ "r=" + settings.resource + "/items"
						+ (params.length ? "&" + params : "");

				return url;
			}
		}
	};

	var Public = {
		/*
			settings: {
				resource: "agency.payreport/items",
				params: [{
					name: "filter",
					value: "substringof('institutions/Lists/payreport/" + agencyName + "', FileDirRef)"
				}, {
					name: "top",
					value: 100
				}],
				dataView: "Tree/List",
				async: true/false,
			}
		*/
		getItems: function(settings) {
			if(!settings.resource) return null;
			var url = Private.utils.getUrl(settings);
			return settings.async
				? Private.getJSON.async(url, settings.dataView)
				: Private.getJSON.sync(url, settings.dataView);
		},
		dataView: Private.constants.dataView
	};

	return Public;
})();

var ArrayUtils = {
	sortByAlphabet: function(array, fieldName, mode) {
		if(!fieldName) return array;
		return array.sort(function(a, b) {
			if(a[fieldName] < b[fieldName]) return mode == "asc" ? -1 : 1;
		    if(a[fieldName] > b[fieldName]) return mode == "asc" ? 1 : -1;
		    return 0;
		});
	},
	orderByProperties: function(prop) {
		var args = Array.prototype.slice.call(arguments, 1);
		return function (a, b) {
			//var equality = a[prop] - b[prop];
			var equality = 0;
			if(a[prop] < b[prop]) equality = -1;
		    if(a[prop] > b[prop]) equality = 1;

			if (equality === 0 && args.length > 0) {
				return ArrayUtils.orderByProperties.apply(null, args)(a, b);
			}
			return equality;
		};
	},
	removeDuplicates: function(array) {
		return array.filter(function(item, pos) {
		    return array.indexOf(item) == pos;
		});
	}
};

var DataUtils = {
	intToDate: function(value) {
		if(!value) return "";

		var d = new Date(parseInt(value));

		var day = d.getDate();
		var month = (d.getMonth() + 1);
		var year = d.getFullYear();


		return (day < 10 ? "0" : "") + day + "."
			+ (month < 10 ? "0" : "") + month + "."
			+ d.getFullYear();
	},
	intToFileSize: function(value) {
		var res = "";

		if(value / 1024 < 1024) {
			res = ((value / 1024).toFixed(1) + " Кб").replace(".", ",");
		} else {
			res = ((value / (1024 * 1024)).toFixed(1) + " Мб").replace(".", ",");
		}

		return res;
	},
	getObjectFromArrayByPropperty: function(array, name, value) {
		return array.filter(function(item) {
			return item[name] == value;
		})[0];
	},
	stringDateToTime: function(date) {
		//return (new Date(date.replace("Z", "") + "+07:00")).getTime();

		return (new Date(date)).getTime();
	},
	getValue: function(obj) {
		return obj ? obj : "";
	}
};

var DataTableDefaultSettings = {
    paging: true,
    pagingType: "simple_numbers",
    searching: true,
    ordering: false,
    autoWidth: true,
    info: false,
    lengthChange: false,
    processing: false,
    language: {
        search: "Поиск:",
        lengthMenu: "_MENU_ записей",
        info: "_START_-_END_ из _TOTAL_",
        infoFiltered: "(из _MAX_)",
        zeroRecords: "Ничего не найдено.",
        paginate: {
            previous: "Назад",
            next: "Вперед",
            first: "",
            last: ""
        },
        searchPlaceholder: "",
        aria: {
            sortAscending: "",
            sortDescending: ""
        },
        processing: "Загрузка...",
        infoEmpty: "",
        infoPostFix: "",
        loadingRecords: "",
        emptyTable: ""
    }
};
