//element en edició
var edited = null;

//tecles return i escape
window.onkeypress = function (e) {
	if (edited != null) {
		switch(e.keyCode) {
			case 13: editDone(edited, true);
				break;
			case 27: editDone(edited, false);
		}
	}
}

//creem un element d'edició dins de l'element actual
function edit(elem) {
	if (edited != null) {
		editDone(edited, false);
	}
	var editElem = new Edition(elem.innerHTML);
	var input = editElem.getElementsByTagName("input")[0];
	elem.innerHTML = "";
	elem.removeAttribute('onclick');
	elem.removeAttribute('class');
	elem.appendChild(editElem);
	input.focus();
}

//edició finalitzada
function editDone(elem, ok) {
	var span = elem.parentNode;
	var input = span.firstChild;
	var td = span.parentNode;
	td.removeChild(span);
	td.setAttribute("onclick", "edit(this)");
	td.className = "link";
	if (ok && input.value != input.getAttribute("old")) {
		td.innerHTML = input.value;
		var tr = td.parentNode;
		var entity = tr.id.split("-")[0];
		var id = parseInt(tr.id.split("-")[1]);
		sendValue(entity, id, td.getAttribute("name"), input.value, tr);
	} else {
		td.innerHTML = input.getAttribute('old');
	}
	edited = null;
}

//objecte edició
function Edition(val) {
	this.val = val;
	this.container = this.createContainer();
	return this.container;
}

//creem el contenidor amb input, ok i cancel
Edition.prototype.createContainer = function () {
	var container = document.createElement("span");
	var input = document.createElement("input");
	input.setAttribute("type", "text");
	input.setAttribute("value", this.val);
	input.setAttribute("old", this.val);
	edited = input;
	container.appendChild(input);
	container.appendChild(new OkIcon());
	container.appendChild(new CancelIcon());
	return container;
}

//objecte icona
function Icon() {
	this.icon = document.createElement("i");
	this.icon.className = "material-icons md-18 link";
	return this.icon;
}	

//objecte icona ok
function OkIcon() {
	this.icon = Icon.call();
	this.icon.innerHTML = "done";
	this.icon.addEventListener("click", this.done, false);
	return this.icon;
}

OkIcon.prototype.done = function (e) {
	e.stopPropagation();
	editDone(this, true);
}

//objecte icona cancel
function CancelIcon() {
	this.icon = document.createElement("i");
	this.icon.className = "material-icons md-18 link";
	this.icon.innerHTML = "clear";
	this.icon.addEventListener("click", this.done, false);
	return this.icon;
}

CancelIcon.prototype.done = function (e) {
	e.stopPropagation();
	editDone(this, false);
}

