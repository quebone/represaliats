window.onload = function () {
	setAllSelected();
}

function getFields() {
	var elems = document.querySelectorAll("#fields input");
	var fields = [];
	for (var i=0; i<elems.length; i++) {
		var value = getElemValue(elems[i]);
		if (value === true) {
			var label = elems[i].nextElementSibling.innerHTML;
			fields.push([label,elems[i].getAttribute("fields")]);
		}
	}
	return fields;
}

function addVista() {
	var vista = document.querySelector('#vista_header input');
	var name = getElemValue(vista);
	if (name != null && name.length > 0) {
		var preset = getElemValue(document.querySelector('#filter_preset'));
		var fields = getFields();
		if (fields.length > 0) {
			var dataToSend = "name=" + name + "&preset=" + preset + "&fields=" + JSON.stringify(fields) + "&function=addVista";
			send(dataToSend, AJAXCONTROLLER, returnAddVista, vista);
		} else {
			alert("Cal seleccionar com a mínim un atribut");
		}
	} else {
		alert("El nom de la vista no pot estar buit");
	}
}

function returnAddVista(msg, vista) {
	var result = JSON.parse(msg);
	if (result[0] == true) {
		var id = JSON.parse(msg)[1].id;
		if (vista.id.split('-')[1] == id) {
			alert("Vista actualitzada correctament");
		} else {
			location.assign('vistes.php?id=' + id);
		}
	}
}

function deleteVista() {
	var vista = document.querySelector('#vista_header input');
	var name = getElemValue(vista);
	if (name != null && name.length > 0) {
		if (confirm("Segur que vols eliminar la vista " + name + "?")) {
			var id = vista.id.split('-')[1];
			var dataToSend = "id=" + id + "&function=deleteVista";
			send(dataToSend, AJAXCONTROLLER, returnDeleteVista);
		}
	}
}

function returnDeleteVista(msg) {
	if (JSON.parse(msg) === true) {
		location.replace('vistes.php');
	} else {
		alert("No s'ha pogut eliminar la vista");
		if (DEBUG) console.log(JSON.parse(msg)[1]);
	}
}

function print() {
	var vista = document.querySelector('#vista_header input');
	var name = getElemValue(vista);
	if (name != null && name.length > 0) {
		var id = vista.id.split('-')[1];
		window.open('vistapdf.php?id=' + id);
	}
}

function returnPrint(msg) {
	if (DEBUG) console.log(JSON.parse(msg));
}

function xls() {
	var vista = document.querySelector('#vista_header input');
	var name = getElemValue(vista);
	if (name != null && name.length > 0) {
		var id = vista.id.split('-')[1];
		var dataToSend = "id=" + id + "&function=getSpreadsheet";
		send(dataToSend, AJAXCONTROLLER, returnGetSpreadsheet);
	}
}

function returnGetSpreadsheet(msg) {
	window.location.replace(msg);
}