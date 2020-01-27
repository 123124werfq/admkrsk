function updatePoints(mapObject, pointsArray)
{
    mapObject.geoObjects.removeAll();

    let clusterer = new ymaps.Clusterer({
            //preset: 'islands#invertedVioletClusterIcons',
            groupByCoordinates: false,
            clusterDisableClickZoom: true,
            clusterHideIconOnBalloonOpen: false,
            geoObjectHideIconOnBalloonOpen: false
    }),
        geoObjects = [];

    for (let counter = 0; counter < pointsArray.length; counter++)
    {
        let pointTemp  = new ymaps.Placemark([pointsArray[counter]['x'], pointsArray[counter]['y']], {
                balloonContentBody: pointsArray[counter]['content'],
            }, {
                preset: pointsArray[counter]['icon']
            });
        //mapObject.geoObjects.add(pointTemp);
        geoObjects[counter] = pointTemp;
    }
    clusterer.add(geoObjects);
    mapObject.geoObjects.add(clusterer);
    mapObject.setBounds(mapObject.geoObjects.getBounds());
    mapObject.setZoom(mapObject.getZoom()-0.4);

}

var map = false;

function showMap(id_collection,block_id)
{
    $('#'+block_id).parent().addClass('open');

    ymaps.ready(
        function () {

        if (!map)
            map = new ymaps.Map(block_id, {
                center: [56.010563, 92.852572],
                zoom: 11,
                controls: ['smallMapDefaultSet']
            }, {
                searchControlProvider: 'yandex#search'
            });

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/collection/coords",
            data: {id:id_collection}
        }).done(function(data){
            updatePoints(map, data)
        });
    });
}

$(document).ready(function() {

    if ($("#map-controls").length > 0)
    {
        $("#map-controls").change(function(){
            showMap($(this).val(),'map-controls');
        });

        showMap($("#map-controls").val(),'map-container');
    }
});