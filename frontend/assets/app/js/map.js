// MAP
(function(api) {
    var PointFactory = {
        getPoints: function(items) {
            var features = [];

            $.each(items, function(i, item) {
                features.push({
                    type: 'Feature',
                    geometry: {
                        type: 'Point',
                        coordinates: [item.point.latitude, item.point.longitude]
                    },
                    properties: {
                        ID: item.ID,
                        type: item.type,
                        hintContent: item.title,
                        balloonContentHeader: item.title,
                        balloonContent: PointFactory.createBaloonContent(item.info),
                        iconContent: "<img src='" + item.icon + "' style='height: 14px; width: 15px; margin-top: 0px; margin-left: 1px;'>"
                    },
                    options: {
                        preset: 'default#blueIcon'
                    }
                });
            });

            return ymaps.geoQuery({
                type: 'FeatureCollection',
                features: features
            });
        },
        createBaloonContent: function(rows) {
            var HTML = '';

            $.each(rows, function(i, row) {
                if(!row.value || !row.value.length) return true;
                HTML += '<tr>'
                        + '<td style="padding-right: 10px;" ><b><nobr>' + row.name + '</nobr></b></td>'
                        + '<td>' + row.value + '</td>'
                    + '</tr>';
            });

            return '<table>' + HTML + '</table>';
        }
    };

    var View = {
        initMap: function(container) {
            container.css("height", "550px");

            var map = new ymaps.Map(container.attr("id"), {
                    center: [56.020569, 92.862545],
                    zoom: 11,
                    controls: [
                        "fullscreenControl",
                        "zoomControl"
                    ]
                }, { suppressMapOpenBlock: true });

            return map;
        },
        initBalloonBuilder: function(map, infoProvider) {
            map.geoObjects.events.add("click", function(e) {
                var target = e.get("target");

                var obj;
                if (target.options._name === "geoObject") {
                    obj = target;
                } else if (target.options._name === "cluster") {
                    obj = target.properties.get('geoObjects')[0];
                } else {
                    return true;
                }

                if (obj.properties._data.balloonContentBody) {
                    return true;
                }

                var id = obj.properties._data.ID;
                var type = obj.properties._data.type;

                if (id && type) {
                    e.preventDefault();
                    infoProvider(id, type, function(info) {
                        var baloonContent = PointFactory.createBaloonContent(info);

                        obj.properties.set("balloonContentBody", baloonContent);
                        if (target.options._name === "geoObject") {
                            obj.balloon.open();
                        }
                    });
                }
            });

            // $(document).click(function(e){
            //     var clusterer = {
            //         _objects: map.geoObjects
            //     };

            //     var $target = $(e.target);
            //     if ($target.attr("class") && $target.attr("class").indexOf("cluster-tabs__menu-item-text") < 0) {
            //         return true;
            //     }

            //     var obj;
            //     map.geoObjects.each(function (geoObject) {
            //         var objects = geoObject._objects;

            //         for(var i in geoObject._objects) {
            //             var o = geoObject._objects[i].geoObject;
            //             if (o.properties.get("balloonContentHeader") === e.target.innerText) {
            //                 if (o.properties.get("balloonContentBody") == null) {
            //                     obj = o;
            //                     break;
            //                 }
            //             }
            //         }
            //     });

            //     if (!obj) {
            //         return true;
            //     }

            //     var closestClusterTabs = $(e.target).parentsUntil("#map-container").filter(function(){
            //         return $(this).attr("class") && $(this).attr("class").indexOf("cluster-tabs__section_type_nav") > 0;
            //     }).first();

            //     var id = obj.properties._data.ID;
            //     var type = obj.properties._data.type;

            //     if (id && type) {
            //         infoProvider(id, type, function(info) {
            //             var baloonContent = PointFactory.createBaloonContent(info);

            //             var scrollBefore = closestClusterTabs.scrollTop();
            //             obj.properties.set("balloonContentBody", baloonContent);
            //             closestClusterTabs.scrollTop(scrollBefore);
            //         });
            //     }
            // });
        }
    };

    var map = null;
    $.extend(api, {
        init: function(container, items, infoProvider) {
            ymaps.ready(function() {
                map = View.initMap(container);
                if(infoProvider) View.initBalloonBuilder(map, infoProvider);

                var result = PointFactory.getPoints(items);
                map.geoObjects.add(result.clusterize({
                    //clusterDisableClickZoom: true,
                    clusterBalloonSidebarWidth: 200,
                    clusterBalloonWidth: 370
                }));
            });
        },
        update: function(items) {
            map.geoObjects.removeAll();
            var result = PointFactory.getPoints(items);
            map.geoObjects.add(result.clusterize({
                //clusterDisableClickZoom: true,
                clusterBalloonSidebarWidth: 200,
                clusterBalloonWidth: 370
            }));
        }
    });
})(this.PointsMap = {});

(function(api) {
    var Resource = {
        getByType: function(type) {
            var resources = Resource.getAll();
            var resource = resources.filter(function(item) {
                return item.types.indexOf(type) != -1;
            })[0];

            return resource ? resource : Resource.wifi;
        },
        getAll: function() {
            return [
                Resource.wifi,
                Resource.esia,
                Resource.agencies,
                Resource.manorgs,
                Resource.policeSt,
                Resource.policeDep
            ];
        },
        getTypes: function() {
            var resources = Resource.getAll();

            var types = [];
            $.each(resources, function(i, resource) {
                types = types.concat(resource.types);
            });

            return types;
        },
        wifi: {
            name: "map.wifi",
            select: {
                point: "ID,Title,x,y",
                info: "address"
            },
            filter: {
                point: "x ge '90'"
            },
            config: {
                point: {
                    ID: "ID",
                    title: "Title",
                    latitude: "y",
                    longitude: "x"
                },
                info: {
                    address: "Адрес"
                }
            },
            types: [
                'Бесплатный Wi-Fi'
            ]
        },
        esia: {
            name: "scesia",
            select: {
                point: "ID,Title,x,y",
                info: "district,s_address,phone,sched"
            },
            filter: {
                point: "x ge '90'"
            },
            config: {
                point: {
                    ID: "ID",
                    title: "Title",
                    latitude: "y",
                    longitude: "x"
                },
                info: {
                    district: "Район",
                    s_address: "Адрес",
                    phone: "Телефон",
                    sched: "График работы"
                }
            },
            types: [
                'Центры обслуживания ЕСИА'
            ]
        },
        agencies: {
            name: "agencies",
            select: {
                point: "ID,shortClientName,agencyAddress_latitude,agencyAddress_longitude,industry/value,okvedMainAlias/value",
                info: "agencyAddress_fullAddress,phone,email,website"
            },
            filter: {
                point: ""
            },
            config: {
                point: {
                    ID: "ID",
                    title: "shortClientName",
                    latitude: "agencyAddress_latitude",
                    longitude: "agencyAddress_longitude"
                },
                info: {
                    agencyAddress_fullAddress: "Адрес",
                    phone: "Телефон",
                    email: "E-mail",
                    website: "Сайт"
                }
            },
            types: [
                'Градостроительство',
                'Жилищно-коммунальное хозяйство',
                'Культура',
                'Молодежная политика',
                'Муниципальное имущество',
                'Образование',
                'Школы',
                'Детские сады',
                'Социальная защита населения',
                'Транспорт',
                'Физическая культура, спорт и туризм'
            ]
        },
        manorgs: {
            name: "manorgs",
            select: {
                point: "ID,Title,x,y",
                info: "OData__x0424__x043e__x0440__x043c__x0430__x0020__x0443__x043f__x0440__x0430__x0432__x043b__x0435__x043d__x0438__x044f_,ManagersName,OData__x0420__x0435__x0436__x0438__x043c__x0020__x0440__x0430__x0431__x043e__x0442__x044b_,OData__x0422__x0435__x043b__x0435__x0444__x043e__x043d__x0020__x043f__x0440__x0438__x0435__x043c__x043d__x043e__x0439_,OData__x0413__x043e__x0441__x002e__x0020__x0440__x0435__x0433__x002e__x0020__x043d__x043e__x043c__x0435__x0440_,OData__x042d__x043b__x0435__x043a__x0442__x0440__x043e__x043d__x043d__x0430__x044f__x0020__x043f__x043e__x0447__x0442__x0430_,xmladdress"
            },
            filter: {
                point: "x ge '90'"
            },
            config: {
                point: {
                    ID: "ID",
                    title: "Title",
                    latitude: "y",
                    longitude: "x"
                },
                info: {
                    OData__x0424__x043e__x0440__x043c__x0430__x0020__x0443__x043f__x0440__x0430__x0432__x043b__x0435__x043d__x0438__x044f_: "Форма управления",
                    ManagersName: "Руководитель",
                    OData__x0420__x0435__x0436__x0438__x043c__x0020__x0440__x0430__x0431__x043e__x0442__x044b_: "Режим работы",
                    OData__x0422__x0435__x043b__x0435__x0444__x043e__x043d__x0020__x043f__x0440__x0438__x0435__x043c__x043d__x043e__x0439_: "Телефон приемной",
                    OData__x0413__x043e__x0441__x002e__x0020__x0440__x0435__x0433__x002e__x0020__x043d__x043e__x043c__x0435__x0440_: "Гос. рег. номер (ОГРН)",
                    OData__x042d__x043b__x0435__x043a__x0442__x0440__x043e__x043d__x043d__x0430__x044f__x0020__x043f__x043e__x0447__x0442__x0430_: "Электронная почта",
                    xmladdress: "Фактический адрес"
                }
            },
            types: [
                'Управляющие огранизации'
            ]
        },
        policeSt: {
            name: "map.police_st",
            select: {
                point: "ID,Title,x,y",
                info: "address,phone,reception,department/Title"
            },
            filter: {
                point: "x ge '90'"
            },
            config: {
                point: {
                    ID: "ID",
                    title: "Title",
                    latitude: "y",
                    longitude: "x"
                },
                info: {
                    address: "Адрес",
                    phone: "Телефон",
                    reception: "Время приёма граждан",
                    'department/Title': "Отдел полиции"
                }
            },
            types: [
                'Участковые пункты'
            ]
        },
        policeDep: {
            name: "map.police_dep",
            select: {
                point: "ID,Title,x,y",
                info: "address,phone,chief,chiefphone"
            },
            filter: {
                point: "x ge '90'"
            },
            config: {
                point: {
                    ID: "ID",
                    title: "Title",
                    latitude: "y",
                    longitude: "x"
                },
                info: {
                    address: "Адрес",
                    phone: "Телефон дежурной части",
                    chief: "Начальник",
                    chiefphone: "Телефон начальника"
                }
            },
            types: [
                'Отдел полиции'
            ]
        }
    };

    var Points = {
        items: null,
        getPointsByType: function(type) {
            var resource = Resource.getByType(type);

            var rows = ODUtils.getItems({
                resource: resource.name,
                params: [{
                    name: "select",
                    value: resource.select.point
                }, {
                    name: "filter",
                    value: resource.filter.point
                }],
                async: false
            });

            return rows.map(function(item) {
                return Points.factory.point(item, resource);
            });
        },
        getPointDescription: function(id, type) {
            var resource = Resource.getByType(type.split(";")[0]);

            var item = ODUtils.getItems({
                resource: resource.name,
                params: [{
                    name: "select",
                    value: resource.select.info
                }, {
                    name: "filter",
                    value: "ID eq '" + id + "'"
                }],
                async: false
            })[0];

            if(!item) return [];

            return Points.factoryInfo.getInfo(item, resource);
        },
        factoryInfo: {
            getInfo: function(item, resource) {
                var fields = resource.select.info.split(","),
                    info = Points.factoryInfo.common(item, fields, resource);

                if(resource.name == Resource.agencies.name) {
                    info = Points.factoryInfo.agency(item, fields, resource);
                }

                return info;
            },
            agency: function(item, fields, resource) {
                return fields.map(function(field) {
                    var row = {
                        name: resource.config.info[field],
                        value: item[field]
                    };

                    if(resource.config.info[field] == resource.config.info.website) {
                        var row = {
                            name: resource.config.info[field],
                            value: item[field] ? "<a href='" + item[field] + "' target='_blank'>" + item[field] + "</a>" : ""
                        };
                    }

                    return row;
                });
            },
            common: function(item, fields, resource) {
                return fields.map(function(field) {
                    return {
                        name: resource.config.info[field],
                        value: item[field]
                    };
                });
            }
        },
        factory: {
            point: function(item, resource) {
                var config = resource.config.point,
                    base = Points.factory.common(item, config, resource.types[0]);

                if(resource.name == Resource.agencies.name) {
                    base = Points.factory.agency(item, config);
                } else if(resource.name == Resource.wifi.name) {
                    base = Points.factory.wifi(item, config, resource.types[0])
                }

                return $.extend({
                    ID: item[config.ID],
                    title: item[config.title],
                    info: [],
                    icon: Points.factory.utils.buildIcon(base.type)
                }, base);
            },
            agency: function(item, config) {
                var latitude = item[config.latitude],
                    longitude = item[config.longitude];

                var type = item.industry ? item.industry.value : "";
                if(type == "Образование") {
                    type += item.okvedMainAlias ? (";" +  item.okvedMainAlias.value) : "";
                }

                return {
                    point: {
                        latitude: latitude ? parseFloat(latitude.replace(",", ".")) : null,
                        longitude: longitude ? parseFloat(longitude.replace(",", ".")) : null,
                    },
                    type: type
                };
            },
            wifi: function(item, config, type) {
                return {
                    title: "Бесплатный Wi-Fi (" + item[config.title] + ")",
                    point: {
                        latitude: item[config.latitude],
                        longitude: item[config.longitude]
                    },
                    type: type
                };
            },
            common: function(item, config, type) {
                return {
                    point: {
                        latitude: item[config.latitude],
                        longitude: item[config.longitude]
                    },
                    type: type
                };
            },
            utils: {
                buildIcon: function(type) {
                    switch(type.split(";")[0]) {
                        case "Культура":
                            return "/Style Library/res/images/agencies/culture.png";
                            break;
                        case "Транспорт":
                            return "/Style Library/res/images/agencies/transport.png";
                            break;
                        case "Образование":
                        /*case "Образование;Школы":
                        case "Образование;Школы":
                        case "Школы":
                        case "Детские сады":*/
                            return "/Style Library/res/images/agencies/education.png";
                            break;
                        case "Физическая культура, спорт и туризм":
                            return "/Style Library/res/images/agencies/sport.png";
                            break;
                        case "Градостроительство":
                            return "/Style Library/res/images/agencies/building.png";
                            break;
                        case "Социальная защита населения":
                            return "/Style Library/res/images/agencies/protection.png";
                            break;
                        case "Молодежная политика":
                            return "/Style Library/res/images/agencies/politic.png";
                            break;
                        case "Жилищно-коммунальное хозяйство":
                            return "/Style Library/res/images/agencies/communal.png";
                            break;
                        case "Муниципальное имущество":
                            return "/Style Library/res/images/agencies/real_estate.png";
                            break;
                        case "Бесплатный Wi-Fi":
                            return "/Style Library/res/images/agencies/wifi.png";
                            break;
                        case "Центры обслуживания ЕСИА":
                            return "/Style Library/res/images/agencies/esia.png";
                            break;
                        case "Участковые пункты":
                            return "/Style Library/res/images/agencies/police_st.png";
                            break;
                        case "Отдел полиции":
                            return "/Style Library/res/images/agencies/police_dep.png";
                            break;
                        default:
                            return "/Style Library/res/images/agencies/default.png";
                    }
                }
            }
        }
    };

    $.extend(api, {
        get: function(type) {
            var points = Points.getPointsByType(type);

            if(type) {
                return points.filter(function(point) {
                    return point.type.toLowerCase().split(";").indexOf(type.toLowerCase()) != -1;
                });
            }

            return points;
        },
        infoProvider: function(id, type, callback) {
            var info = Points.getPointDescription(id, type);
            callback(info);
        },
        getTypes: Resource.getTypes
    });
})(this.Points = {});

var Manager = {
    init: {
        main: function() {
            Manager.init.controls();
            Manager.init.map();
        },
        map: function() {
            $(window)
                .bind('hashchange', function () {
                    var type = QSUtils.getHashParameterByName('type'),
                        points = Points.get(type),
                        map = $("#map-container");

                    if(!map.children().length) {
                        PointsMap.init(map, points, Points.infoProvider);
                    } else {
                        PointsMap.update(points);
                    }
                })
                .trigger('hashchange');
        },
        controls: function() {
            var url = location.pathname,
            controls = $("#map-controls"),
            types = Points.getTypes();

            var activeType = QSUtils.getHashParameterByName('type') || "Бесплатный Wi-Fi";
            controls.val(activeType);

            controls.on("selectmenuchange", function(event, ui) {
                QSUtils.setHashParameter([{
                    name: "type",
                    value: $(this).val()
                }]);
            });
        }
    }
};

Manager.init.main();

// /MAP