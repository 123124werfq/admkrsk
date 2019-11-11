<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&" type="text/javascript"></script>
<script>
    ymaps.ready(init);

    function init () {
        var myMap<?=$cid?>, myPlacemark<?=$cid?>;

        function createPlacemark(coords) {
            return new ymaps.Placemark(coords, {
            }, {
                preset: 'islands#redDotIcon',
                draggable: true
            });
        }

        function updateCoords(coords)
        {
            $('[name="<?=$fname?><?=$cid?>"]').val(coords[0]+';'+coords[1]);
        }

        $('#toggle_wim<?=$cid?>').bind({
            click: function () {
                if (!myMap<?=$cid?>) {
                    $("#wim<?=$cid?>").removeClass('hidden');
                    myMap<?=$cid?> = new ymaps.Map('wim<?=$cid?>', {
                        center: [56.010563, 92.852572],
                        zoom: 12
                    }, {
                        searchControlProvider: 'yandex#search'
                    });

                    myMap<?=$cid?>.events.add('click', function (e) {
                        var coords = e.get('coords');

                        if (myPlacemark<?=$cid?>) {
                            myPlacemark<?=$cid?>.geometry.setCoordinates(coords);
                        }
                        else {
                            myPlacemark<?=$cid?> = createPlacemark(coords);
                            myMap<?=$cid?>.geoObjects.add(myPlacemark<?=$cid?>);
                            myPlacemark<?=$cid?>.events.add('dragend', function () {
                                updateCoords(myPlacemark<?=$cid?>.geometry.getCoordinates());
                            });
                        }

                        updateCoords(myPlacemark<?=$cid?>.geometry.getCoordinates());
                    });
                }
                else {
                    $("#wim<?=$cid?>").addClass('hidden');
                    myMap<?=$cid?>.destroy();
                    myMap<?=$cid?> = null;
                }
            }
        });


    }
</script>

<div class="input-group bootstrap-touchspin">
    <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;"></span>
    <input class="touchspin2 form-control" type="text" value="" name="<?=$fname?>[<?=$cid?>]" style="display: block;">
    <span class="input-group-addon bootstrap-touchspin-postfix" id="toggle_wim<?=$cid?>">НА КАРТЕ</span>
</div>
<div class="widget-input-map hidden" id="wim<?=$cid?>">

</div>
