<?php
    $this->registerJsFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU',['position'=>\yii\web\View::POS_BEGIN]);

?>
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
            $('#lat<?=$cid?>').val(coords[0]);
            $('#lon<?=$cid?>').val(coords[1]);
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
<div class="input-group input-group-space">
    <input id="lat<?=$cid?>" class="form-control" type="text" value="" name="<?=$fname?>[]" placeholder="Широта"/>
    <input id="lon<?=$cid?>" class="form-control" type="text" value="" name="<?=$fname?>[]" placeholder="Долгота" />
    <span class="btn btn-default btn-visible" id="toggle_wim<?=$cid?>"><i class="fa fa-map-marker"></i></span>
</div>
<div class="widget-input-map hidden" id="wim<?=$cid?>">
</div>