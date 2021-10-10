ymaps.ready(function(){
	var o=new ymaps.Map("map1",{center:[46.70273057439617,141.86565049999993],zoom:17,controls:[]}),
	a=new ymaps.Placemark([46.70273057439617,141.86565049999993],{},{iconLayout:"default#image",iconImageHref:"images/map-arr-1.png",
	iconImageSize:[62,71],
	iconImageOffset:[-31,-55],
	balloonLayout:"default#imageWithContent",
	balloonContentSize:[270,99]});
	o.controls.add("zoomControl",{left:35,top:35}),o.geoObjects.add(a),o.behaviors.disable(["rightMouseButtonMagnifier","scrollZoom"])
});