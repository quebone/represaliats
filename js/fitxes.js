window.onload = function () {
	addAllToSelects();
	applyFilters();
}

function addFitxa() {
	var persona = prompt("Cognoms, nom de la nova fitxa", "");
	if (persona != null && persona != "") {
		var arr = persona.split(",");
		var nom = arr[1].trim();
		var cognoms = arr[0].trim();
		var dataToSend = "nom=" + nom + "&cognoms=" + cognoms + "&function=addFitxa";
		send(dataToSend, AJAXCONTROLLER, addedFitxa);
	}
}

function addedFitxa(msg) {
	location.reload();
	console.log(msg);
}

function editFitxa(elem) {
	window.location = "fitxa.php?id=" + getId(elem);
}

function print(elem) {
	window.open('fitxapdf.php?id=' + getId(elem));
}

function xls(elem) {
	var dataToSend = "id=" + getId(elem) + "&function=getSpreadsheet";
	send(dataToSend, AJAXCONTROLLER, returnGetSpreadsheet);
}

function returnGetSpreadsheet(msg) {
	window.location.replace(msg);
}

function getId(elem) {
	while (elem.tagName.toUpperCase() != "TR") elem = elem.parentNode;
	return elem.id.split("-")[1];
}
