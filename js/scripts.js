const DEBUG = true;

const AJAXCONTROLLER = 'AjaxController.php';

// converteix la primera lletra a majúscula
function capitalizeFirstLetter(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

// converteix una variable amb guions a camel case
function toCamel(hyphened) {
	var arr = hyphened.split("-");
	var camel = arr[0];
	for (var i=1; i<arr.length; i++) {
		camel += capitalizeFirstLetter(arr[i]);
	}
	return camel;
}

// retorna el nom de la pàgina sense directoris ni extensió
function getCurrentPageName() {
	var path = window.location.pathname;
	var page = path.split("/").pop();
	if (page.length == 0) return 'index';
	return page.split(".")[0];
}

// envia dades POST a un controlador PHP a través d'AJAX
function send(dataToSend, receiver, returnFunction, options = [], method="POST") {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			returnFunction(xmlhttp.responseText, options);
		}
	}
	xmlhttp.open(method, receiver, true);
	//Must add this request header to XMLHttpRequest request for POST
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	dataToSend += '&caller=' +  capitalizeFirstLetter(getCurrentPageName());
	if (DEBUG) console.log(dataToSend);
	xmlhttp.send(dataToSend);
}

//envia el valor value de la propietat property de l'entitat entity amb identificador id
function sendValue(entityName, id, property, value, tr) {
	var dataToSend = "entity=" + capitalizeFirstLetter(entityName) + "&id=" + id + "&propertyName=" + property + "&value=" + value + "&function=changeValue";
	send(dataToSend, AJAXCONTROLLER, sendValueReturn, [tr]);
}

function sendValueReturn(msg, options) {
	if (DEBUG) console.log(msg);
	msg = JSON.parse(msg);
	if (msg[0] == false) {
		alert(msg[1]);
	} else {
		var entity = msg[1];
		if (options.length > 0) {
			var tr = options[0];
			tr.id = tr.id.split("-")[0] + "-" + entity.id;
/*			if (filaBuida(tr)) {
				eliminarFila(tr);
			}
*/		}
	}
}

function changeVisibility(elem, visibility, tipus) {
	var display = visibility ? tipus : "none";
	elem.style.display = display;
}

//icona d'eliminació de files d'una taula
function DeleteIcon(e) {
	this.icon = document.createElement("i");
	this.icon.className = "material-icons md-18 link";
	this.icon.setAttribute("title", "eliminar");
	this.icon.setAttribute("onclick", "eliminarFila(this)");
	this.icon.innerHTML = "remove_circle";
	return this.icon;
}

//afegeix una fila buida al final de la taula
function afegirFila(tag, cols) {
	var container = document.getElementById("content");
	var tr = document.createElement("tr");
	for (var i=0; i<cols.length; i++) {
		var td = document.createElement("td");
		td.className = "link";
		td.setAttribute("name", cols[i]);
		td.setAttribute("onclick", "edit(this)");
		tr.appendChild(td);
	}
	var td = document.createElement("td");
	td.appendChild(new DeleteIcon());
	tr.appendChild(td);
	var newId = getMaxId("tr", tag) + 1;
	tr.id = tag + "-" + newId;
	container.appendChild(tr);
	edit(tr.firstChild);
}

//comprova si tots els elements d'una fila estan buits
function filaBuida(tr) {
	console.log(tr.children);
	for (var i=0; i<tr.chidren.length; i++) {
		console.log(tr.children[i].textContent);
		if (tr.children[i].textContent.length > 0) {
			return false;
		}
	}
	return true;
}

//elimina una fila d'una taula
function eliminarFila(elem, save=true) {
	while (elem.tagName.toLowerCase() != "tr") {
		elem = elem.parentNode;
	}
	var table = elem.parentNode;
	var r = confirm("Estàs segur que vols eliminar la fila?" + "\n" + "(aquesta acció no es pot desfer)");
	if (r) {
		table.removeChild(elem);
		if (save) {
			var entity = capitalizeFirstLetter(elem.id.split("-")[0]);
			var id = elem.id.split("-")[1];
			var dataToSend = "entity=" + entity + "&id=" + id + "&function=deleteEntity";
			send(dataToSend, AJAXCONTROLLER, sendValueReturn);
		}
	}
}

//busca l'element d'un cert tipus amb l'identificador més gran
function getMaxId(tag, root) {
	var elems = document.getElementsByTagName(tag);
	var ids = new Array();
	for (var i=0; i<elems.length; i++) {
		var id = elems[i].id.split("-")[1];
		if (id != undefined) {
			ids.push(parseInt(id));
		}
	}
	if (ids.length > 0) {
		var max = ids.reduce(function(a, b) {
			return Math.max(a, b);
		});
		return max;
	} else {
		return 1;
	}
}

//introdueix "selected" a l'opció corresponent segons el valor del select
function setSelected(select) {
	var value = select.getAttribute("value");
	var options = select.childNodes;
	for (var i=0; i<options.length; i++) {
		if (options[i].getAttribute("value") == value) {
			options[i].setAttribute("selected", true);
		}
	}
}

//canvia els selected de tots els select
function setAllSelected() {
	var selected = document.getElementsByTagName("select");
	for (var i=0; i<selected.length; i++) {
		setSelected(selected[i]);
	}
}

//elimina les opcions existents en els valors dels selects del mateix contenidor
function cleanOptions(container) {
	var divs = new Array();
	var selects = new Array();
	for (var i=0; i<container.children.length; i++) {
		if (container.children[i].className != "hidden") {
			divs.push(container.children[i]);
		}
	}
	for (var i=0; i<divs.length; i++) {
		selects.push(divs[i].firstElementChild);
	}
	for (var i=0; i<selects.length; i++) {
		if (selects[i].getAttribute("exclusive") == "true") {
			for (var k=0; k<selects[i].children.length; k++) {
				selects[i].children[k].removeAttribute("disabled");
			}
		}
	}
	for (var i=0; i<selects.length; i++) {
		for (var j=0; j<selects.length; j++) {
			if (i != j) {
				for (var k=0; k<selects[i].children.length; k++) {
					if (selects[i].children[k].getAttribute("value") == selects[j].value) {
						selects[i].children[k].setAttribute("disabled", true);
					}
				}
			}
		}
	}
}

function obrirPagina(pagina) {
	document.location.assign(pagina);
}

function getElemValue(elem) {
	switch(elem.tagName) {
		case "INPUT":
			switch(elem.getAttribute("type")) {
				case "checkbox":
					return elem.checked;
				case "TEXT":
				default:
					return elem.value;
			}
		case "SELECT":
		case "TEXTAREA":
		default:
			return elem.value;
	}
}

function setElemValue(elem, value) {
	switch(elem.tagName) {
		case "INPUT":
			switch(elem.getAttribute("type").toUpperCase()) {
				case "CHECKBOX":
					if (value === true) {
						elem.checked = true;
					} else {
						elem.checked = false;
						elem.removeAttribute("checked");
					}
					break;
				case "TEXT":
					if (value != null && isNaN(value)) value = value.replace(/^"(.*)"$/, '$1').replace(/\\"/g, '"');
					elem.value = value;
					break;
				case "NUMBER":
				default:
					elem.value = value;
			}
			break;
		case "TEXTAREA":
			elem.value = value.replace(/^"(.*)"$/, '$1').replace(/\\"/g, '"');
			break;
		case "SELECT":
		default:
			elem.value = value;
			elem.setAttribute("value", value);
			elem.setAttribute("oldvalue", value);
	}
}


/** Cerques **/

function search() {
	var needle = getElemValue(document.querySelector('form input'));
	if (needle.length > 0) {
		var dataToSend = "needle=" + needle + "&function=find";
		send(dataToSend, AJAXCONTROLLER, searchReturn);
		document.querySelector('form input').setAttribute("disabled", true);
		document.querySelector('form button').setAttribute("disabled", true);
	}
	return false;
}

function searchReturn(msg) {
	if (DEBUG) console.log(msg);
	msg = JSON.parse(msg);
	if (msg != false) {
		if (DEBUG) console.log(msg);
		document.querySelector(".search_content").innerHTML = msg;
		document.getElementById('search_result').className = '';
	}
	document.querySelector('form input').removeAttribute("disabled");
	document.querySelector('form button').removeAttribute("disabled");
}

function hideSearch() {
	document.getElementById('search_result').className = 'hidden';
}


/** Dades actualitzades correctament **/

function showSuccesfulUpdate() {
	var alert = document.querySelector('.updated');
	alert.style['visibility'] = 'visible';
	updatedInterval = setTimeout(hideSuccessfulUpdate, 1000, alert);
}

function hideSuccessfulUpdate(alert) {
	alert.style['visibility'] = 'hidden';
}	