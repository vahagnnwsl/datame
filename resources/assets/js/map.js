// YANDEX MAP
ymaps.ready(init);
var myMap, 
	myPlacemark;
function init(){ 
	myMap = new ymaps.Map("map", {
		center: [45.021755, 38.900000],
		zoom: 10,
		controls: []
	}); 

	if($(window).width() > 991) {
		myMap.panTo([45.021755, 38.900000], {
			flying: true
		});
	} else if ($(window).width() > 767){
		myMap.panTo([45.021755, 38.750000], {
			flying: true
		});
	} else {
		myMap.panTo([45.071755, 39.017399], {
			flying: true
		});
	};
	$(window).resize(function() {
		if($(window).width() > 991) {
			myMap.panTo([45.021755, 38.900000], {
				flying: true
			});
		} else if ($(window).width() > 767){
			myMap.panTo([45.021755, 38.750000], {
				flying: true
			});
		} else {
			myMap.panTo([45.071755, 39.017399], {
				flying: true
			});
		};
	});

	myMap.controls.add(new ymaps.control.ZoomControl({options: { position: { right: 10, top: 50 }}}));

	myPlacemark = new ymaps.Placemark([45.021755, 39.017399], {
		balloonContentHeader: 'Метркухни',
		balloonContentBody: 'г. Краснодар, ул.Ставропольская, д. 109/2',
		iconCaption: 'Метркухни'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'img/map_mark.png',
		iconImageSize: [100, 114],
		iconImageOffset: [-49, -106]
	});


	myMap.behaviors.disable('multiTouch');
	myMap.behaviors.disable('scrollZoom');
	myMap.geoObjects.add(myPlacemark);
	
	var isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
		}
	};
	if(isMobile.any()){
		myMap.behaviors.disable('drag');
	}
}


