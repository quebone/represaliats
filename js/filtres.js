function addAllToSelects() {
	selects = document.querySelectorAll("#filters select:not(#filter-presets)");
	for (var i = 0; i < selects.length; i++) {
		addOption(selects[i], 0, "Tots");
	}
}

function addOption(elem, value, inner) {
	var option = document.createElement("OPTION");
	option.setAttribute("value", value);
	option.innerHTML = inner;
	elem.insertBefore(option, elem.childNodes[0]);
	elem.value = value;
}

function removeOption(elem, value) {
	var options = elem.children;
	for (var i=0; i<options.length; i++) {
		if (getElemValue(options[i]) === value) {
			elem.removeChild(options[i]);
		}
	}
}

function cleanFilters() {
	var elems = document.querySelectorAll("#filters input, #filters select");
	for (var i=0; i<elems.length; i++) {
		switch(elems[i].tagName.toUpperCase()) {
			case "SELECT": setElemValue(elems[i], 0);
				break;
			case "INPUT": setElemValue(elems[i], "");
				break;
		}
	}
	applyFilters();
}

function getFilters() {
	var elems = document.querySelectorAll("#filters input, #filters select:not(#filter-presets)");
	var filters = [];
	for (var i=0; i<elems.length; i++) {
		filters.push([elems[i].getAttribute("fields"), [getElemValue(elems[i])]]);
	}
	return filters;
}

function setFilters(fields) {
	var elems = document.querySelectorAll("#filters input, #filters select:not(#filter-presets)");
	for (var i=0; i<elems.length; i++) {
		for (var j=0; j < fields.length; j++) {
			if (elems[i].getAttribute("fields") == fields[j][0]) {
				var value = fields[j][1][0];
				setElemValue(elems[i], value);
			}
		}
	}
}

function applyFilters() {
	var filters = getFilters();
	var dataToSend = "entity=Persona&filters=" + JSON.stringify(filters) + "&function=applyFilters";
	send(dataToSend, AJAXCONTROLLER, returnApplyFilters);
}

function returnApplyFilters(msg) {
	var shortcuts = new Map();
	var instances = JSON.parse(msg);
	var total = document.querySelector('#total span');
	total.innerHTML = instances.length;
	var tbody = document.querySelector('table tbody');
	while (tbody.firstChild) tbody.removeChild(tbody.firstChild);
	for (var i=0; i<instances.length; i++) {
		var tr = document.createElement("TR");
		tr.id = "persona-" + instances[i].id;
		let td = document.createElement("TD");
		td.className = "link";
		td.setAttribute("name", "nom");
		td.setAttribute("onclick", "editFitxa(this)");
		td.innerHTML = instances[i].cognoms + ", " + instances[i].nom;
		if (!shortcuts.has(instances[i].cognoms[0])) {
			var a = document.createElement("A");
			var cap = instances[i].cognoms[0];
			a.id = "shortcut-" + cap;
			shortcuts.set(cap, a.id);
			td.appendChild(a);
		}
		tr.appendChild(td);
		td = document.createElement("TD");
		td.setAttribute("name", "situacio");
		td.innerHTML = instances[i].tipusSituacio.nom;
		tr.appendChild(td);
		var icon = document.createElement("I");
		icon.setAttribute("class", "material-icons md-18 link");
		icon.setAttribute("title", "eliminar");
		icon.setAttribute("onclick", "eliminarFila(this)");
		icon.innerHTML = "remove_circle";
		td = document.createElement("TD");
		td.appendChild(icon);
		tr.appendChild(td);
		icon = document.createElement("I");
		icon.setAttribute("class", "material-icons md-18 link");
		icon.setAttribute("title", "imprimir");
		icon.setAttribute("onclick", "print(this)");
		icon.innerHTML = "print";
		td = document.createElement("TD");
		td.appendChild(icon);
		icon = document.createElement("I");
		icon.setAttribute("class", "material-icons md-18 link");
		icon.setAttribute("title", "desar excel");
		icon.setAttribute("onclick", "xls(this)");
		icon.innerHTML = "insert_chart";
		td.appendChild(icon);
		tr.appendChild(td);
		tbody.appendChild(tr);
	}
	createShortcuts(shortcuts);
}

function createShortcuts(shortcuts) {
	var container = document.getElementById('caps');
	while (container.firstChild) container.removeChild(container.firstChild);
	var iterator = shortcuts[Symbol.iterator]();
	for (let item of iterator) {
		var anchor = document.createElement("A");
		anchor.setAttribute("href", "#" + item[1]);
		anchor.innerHTML = "#" + item[0];
		container.appendChild(anchor);
	}
}

function addFilter() {
	var nom = prompt("Entra el nom del filtre");
	if (nom != null && nom.length > 0) {
		if (nom == 'Tots') {
			alert("El filtre 'Tots' no es pot canviar");
		} else {
			var filters = getFilters();
			var dataToSend = "name=" + nom + "&filters=" + JSON.stringify(filters) +  "&function=addFiltre";
			send(dataToSend, AJAXCONTROLLER, returnAddFilter);
		}
	}
}

function returnAddFilter(msg) {
	var result = JSON.parse(msg);
	var value = result[1].id;
	var name = result[1].name;
	var select = document.getElementById('filter-presets');
	var options = document.querySelectorAll('#filter-presets option');
	var found = false;
	for (var i=0; i<options.length; i++) {
		if (options[i].value == value) found = true;
	}
	if (!found) addOption(select, value, name);	
}

function deleteFilter() {
	var value = getElemValue(document.getElementById('filter-presets'));
	if (value > 1) {
		var dataToSend = "id=" + value + "&function=deleteFiltre";
		send(dataToSend, AJAXCONTROLLER, returnDeleteFilter, value);
	} else {
		alert("El filtre 'Tots' no es pot eliminar");
	}
}

function returnDeleteFilter(msg, value) {
	if (JSON.parse(msg)[0] == true) {
		var select = document.getElementById('filter-presets');
		removeOption(select, value);
		setElemValue(select, 1);
		changePreset(select);
	}
}

function changePreset(elem) {
	var id = getElemValue(elem);
	var dataToSend = "id=" + id + "&function=getFiltre";
	send(dataToSend, AJAXCONTROLLER, returnChangePreset);
}

function returnChangePreset(msg) {
	if (DEBUG) console.log(msg);
	if (JSON.parse(msg)[0] == true) {
		var filter = JSON.parse(msg)[1];
		var fields = JSON.parse(filter.fields);
		setFilters(fields);
		applyFilters();
	}
}