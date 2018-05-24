window.onload = function () {
	getInformes(getId(), 'informes', 'peticionsInforme');
	getSubseccions(getId(), "parelles", "parelles");
	getSubseccions(getId(), "fills", "fills");
	getSubseccions(getId(), "oficis", "oficis");
	getSubseccions(getId(), "tipus-situacio", "tipusSituacio");
	getSubseccions(getId(), "partits", "partits");
	getSubseccions(getId(), "sindicats", "sindicats");
	getSubseccions(getId(), "comites", "comites");
	getSubseccions(getId(), "dates-ajuntament", "datesAjuntament");
	getSubseccions(getSumariId(), "llibertats", "llibertats");
	getSubseccions(getSumariId(), "commutacions", "commutacions");
	setAllSelected();
	commutarExiliat();
	commutarTrp();
	commutarSumari();
}

function getId() {
	return document.getElementsByTagName("h1")[0].id;
}

function getSumariId() {
	return document.getElementById("sumari").getAttribute("value");
}

function editarFitxa(elem) {
	obrirPagina("fitxa.php?id=" + elem.previousElementSibling.getAttribute("value"));
}

/**
 * Funcions per canviar el valor d'un element
 */

function changeSimple(elem, property, entity="Persona", id=null) {
	var value = getElemValue(elem);
	if (id == null) id = getId();
	var dataToSend = "entityName=" + entity + "&id=" + id + "&property=" + property + "&value=" + value + "&function=changeSimple";
	send(dataToSend, AJAXCONTROLLER, changeSimpleReturn, [elem]);
}

function changeSimpleReturn(msg, options) {
	if (DEBUG) console.log(msg);
	var elem = options[0];
	var value = JSON.parse(msg);
	if (value != null) setElemValue(elem, value);
	showSuccesfulUpdate();
}

function changeNom(elem) {
	if (elem.value != "") {
		changeSimple(elem, elem.id);
		document.getElementsByTagName("h1")[0].innerHTML = document.getElementById("nom").value + " " + document.getElementById("cognoms").value;
	}
}

function changeSimpleSumari(elem, property) {
	changeSimple(elem, property, "Sumari", getSumariId());
}

function changeSimpleGeneric(elem, property, entity) {
	var container = elem;
	while (container.tagName != "DIV") container = container.parentNode;
	var id = container.getAttribute("value");
	changeSimple(elem, property, entity, id);
}	

function changeSelect(elem, property, linkedEntity, entity="Persona", id=null) {
	var value = getElemValue(elem);
	if (id == null) id = getId();
	var dataToSend = "entityName=" + entity + "&id=" + id + "&property=" + property + "&value=" + value + "&linkedEntityName=" + linkedEntity + "&function=changeSelect";
	send(dataToSend, AJAXCONTROLLER, changeSimpleReturn, [elem]);
}

function changeSelectSumari(elem, property, linkedEntity) {
	changeSelect(elem, property, linkedEntity, "Sumari", getSumariId());
}

function changeSelectGeneric(elem, property, linkedEntity, entity) {
	var container = elem;
	while (container.tagName != "DIV") container = container.parentNode;
	var id = container.getAttribute("value");
	changeSelect(elem, property, linkedEntity, entity, id);
}

function changeLinkedEntity(elem, property, linkedEntityName) {
	var value = getElemValue(elem);
	var oldvalue = elem.getAttribute("oldvalue");
	var dataToSend = "entityName=Persona&id=" + getId() + "&linkedProperty=" + property + "&linkedEntityId=" + value +
		"&oldLinkedEntityId=" + oldvalue + "&linkedEntityName=" + linkedEntityName + "&function=changeLinkedEntity";
	send(dataToSend, AJAXCONTROLLER, changeSimpleReturn, [elem, oldvalue]);
}

/**
 * Funcions per commutar la visibilització d'una capa a on-off
 */

function commutar(elem, layer, type='initial') {
	var container = document.getElementById(layer);
	changeVisibility(container, elem.checked, type);
}

function commutarAndChangeSimple(elem, property, layer, type='initial') {
	commutar(elem, layer, type);
	changeSimple(elem, property);
}

function commutarAndChangeSimpleSumari(elem, property, layer, type='initial') {
	commutar(elem, layer, type);
	changeSimpleSumari(elem, property);
}

function commutarExiliat() {
	commutar(document.getElementById("exiliat"), "exili");
}

function commutarTrp() {
	commutar(document.getElementById("trp"), "tribunalrp");
}

function commutarConsellGuerra() {
	commutar(document.getElementById("consell-guerra"), "consell-guerra-container");
}

function commutarExecucio() {
	commutar(document.getElementById("hasExecucio"), "execucio-container");
}

function commutarSumari() {
	commutar(document.getElementById("has-sumari"), "sumari", 'block');
	commutarConsellGuerra();
	commutarExecucio();
}

/**
 * Funcions per afegir un element
 */

function addElement(elem, name, property, saveToDbase = false, entity="Persona", id=null) {
	var seccio = document.getElementById(name + "-container");
	var template = seccio.firstElementChild;
	var subseccio = template.cloneNode(true);
	subseccio.className = "";
	if (saveToDbase) {
		if (id == null) id = getId();
		var dataToSend = "entityName=" + entity + "&id=" + id + "&property=" + property + "&function=addEntity";
		send(dataToSend, AJAXCONTROLLER, addElementReturn, [seccio, subseccio]);
	} else {
		return [seccio, subseccio];
	}
}

function addElementSumari(elem, name, property, saveToDbase=false) {
	addElement(elem, name, property, saveToDbase, "Sumari", getSumariId());
}

function addElementReturn(msg, options) {
	if (DEBUG) console.log(msg);
	if (msg != "") {
		var returnObj = JSON.parse(msg);
		var seccio = options[0];
		var subseccio = options[1];
		subseccio.setAttribute("value", returnObj.id);
		var elem = subseccio.firstElementChild;
		for (var property in returnObj.data) {
			while (elem.tagName != "INPUT" && elem.tagName != "SELECT" && elem.tagName != "TEXTAREA") {
				elem = elem.nextElementSibling;
			}
			setElemValue(elem, returnObj.data[property]);
			elem = elem.nextElementSibling;
		}
		seccio.appendChild(subseccio);
		showSuccesfulUpdate();
	}
}

/**
 * Funcions per eliminar un element
 */

function removeElementGlobal(elem, entityName, entityId, linkedProperty, linkedEntityName) {
	var subseccio = elem.parentNode;
	var seccio = subseccio.parentNode;
	var linkedEntityId = subseccio.getAttribute("value");
	var dataToSend = "entity=" + entityName + "&id=" + entityId + "&linkedProperty=" + linkedProperty + "&linkedEntityName=" + linkedEntityName + 
		"&linkedEntityId=" + linkedEntityId + "&function=removeEntity";
	send(dataToSend, AJAXCONTROLLER, removeElementReturn, [seccio, subseccio]);
}

function removeElement(elem, linkedProperty, linkedEntityName) {
	removeElementGlobal(elem, "Persona", getId(), linkedProperty, linkedEntityName);
}

function removeElementSumari(elem, linkedProperty, linkedEntityName) {
	removeElementGlobal(elem, "Sumari", getSumariId(), linkedProperty, linkedEntityName);
}

function removeElementReturn(msg, options) {
	if (DEBUG) console.log(msg);
	if (msg != "") {
		var seccio = options[0];
		var subseccio = options[1];
		seccio.removeChild(subseccio);
		if (subseccio.hasAttribute("exclusive")) {
			
		}
		showSuccesfulUpdate();
	}
}

/**
 * Funcions per recuperar subseccions d'una base de dades
 */

function getSubseccions(id, nomSeccio, property) {
	var dataToSend = "id=" + id + "&function=get" + capitalizeFirstLetter(property);
	send(dataToSend, AJAXCONTROLLER, setSubseccions, [nomSeccio, property]);
}

function setSubseccions(msg, options) {
	if (DEBUG) console.log(msg);
	var nomSeccio = options[0];
	var property = options[1];
	var returnObjs = JSON.parse(msg);
	for (var i=0; i<returnObjs.length; i++) {
		var arr = addElement(null, nomSeccio, property, false);
		addElementReturn(JSON.stringify(returnObjs[i]), arr);
	}
}

/**
 * Funcions relacionades amb els informes
 */

function addInforme(elem, saveToDbase = false) {
	var seccio = document.getElementById("informes-container");
	var template = seccio.firstElementChild;
	var subseccio = template.cloneNode(true);
	if (saveToDbase) {
		var dataToSend = "id=" + getId() + "&function=addInforme";
		send(dataToSend, AJAXCONTROLLER, addInformeReturn, [seccio, subseccio]);
	} else {
		return [seccio, subseccio];
	}
}

function addInformeReturn(msg, options) {
	if (DEBUG) console.log(msg);
	if (msg != "") {
		var returnObjs = JSON.parse(msg);
		var instances = [returnObjs.peticio, returnObjs.informe];
		var seccio = options[0];
		var subseccio = options[1];
		subseccio.removeAttribute("hidden");
		subseccio.setAttribute("value", returnObjs.peticio.id);
		var layers = [subseccio.firstElementChild, subseccio.firstElementChild.nextElementSibling];
		for (var i=0; i<instances.length; i++) {
			layers[i].setAttribute("value", instances[i].id);
			var elem = layers[i].firstElementChild;
			for (var attribute in instances[i].data) {
				while (elem.tagName != "INPUT" && elem.tagName != "SELECT") elem = elem.nextElementSibling;
				setElemValue(elem, instances[i].data[attribute]);
				elem = elem.nextElementSibling;
			}
		}
		seccio.appendChild(subseccio);
		showSuccesfulUpdate();
	}
}

function changeSimplePeticio(elem, property) {
	changeSimple(elem, property, "PeticioInforme", elem.parentNode.getAttribute("value"));
}

function changeSimpleInforme(elem, property) {
	changeSimple(elem, property, "Informe", elem.parentNode.getAttribute("value"));
}

function changeSelectPeticio(elem, property, linkedEntity) {
	changeSelect(elem, property, linkedEntity, "PeticioInforme", elem.parentNode.getAttribute("value"));
}

function changeSelectInforme(elem, property, linkedEntity) {
	changeSelect(elem, property, linkedEntity, "Informe", elem.parentNode.getAttribute("value"));
}

function getInformes(id, nomSeccio, property) {
	var dataToSend = "id=" + getId() + "&function=getPeticionsInforme";
	send(dataToSend, AJAXCONTROLLER, setInformes, [nomSeccio, property]);
}

function setInformes(msg, options) {
	if (DEBUG) console.log(msg);
	var nomSeccio = options[0];
	var property = options[1];
	var returnObjs = JSON.parse(msg);
	for (var i=0; i<returnObjs.length; i++) {
		var arr = addInforme(null, false);
		addInformeReturn(JSON.stringify(returnObjs[i]), arr);
	}
}

function print() {
	window.open('fitxapdf.php?id=' + getId());
}

function xls() {
	var dataToSend = "id=" + getId() + "&function=getSpreadsheet";
	send(dataToSend, AJAXCONTROLLER, returnGetSpreadsheet);
}

function returnGetSpreadsheet(msg) {
	window.location.replace(msg);
}