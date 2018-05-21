window.onload = function() {
	init();
}

function init () {
	esRepresaliatFranquisme();
}

function changeFilterSituacio(elem) {
	console.log(elem.value);
	switch(elem.value) {
		case "Represaliats franquisme": esRepresaliatFranquisme();
			break;
		case "Represaliats reraguarda": esRepresaliatReraguarda();
			break;
		case "Morts al front": esMortAlFront();
			break;
		case "Indeterminada": esIndeterminada();
			break;
	}
}

function esRepresaliatFranquisme() {
	var represaliatsFranquisme = document.getElementById("processats-consell-guerra");
	represaliatsFranquisme.style.display = 'block';
	var mortsFront = document.getElementById("morts-front");
	mortsFront.style.display = 'none';
}

function esRepresaliatReraguarda() {
	var represaliatsFranquisme = document.getElementById("processats-consell-guerra");
	represaliatsFranquisme.style.display = 'none';
	var mortsFront = document.getElementById("morts-front");
	mortsFront.style.display = 'none';
}

function esMortAlFront() {
	var represaliatsFranquisme = document.getElementById("processats-consell-guerra");
	represaliatsFranquisme.style.display = 'none';
	var mortsFront = document.getElementById("morts-front");
	mortsFront.style.display = 'block';
}

function esIndeterminada() {
	var represaliatsFranquisme = document.getElementById("processats-consell-guerra");
	represaliatsFranquisme.style.display = 'none';
	var mortsFront = document.getElementById("morts-front");
	mortsFront.style.display = 'none';
}