function over(object)
{
	document.getElementById(object).style.backgroundColor = '#B4872D'
	document.getElementById(object).cursor = 'hand'
}

function out(object)
{
	document.getElementById(object).style.backgroundColor = '#444444'
}

//Gradual-Highlight image script- By Dynamic Drive
//For full source code and more DHTML scripts, visit http://www.dynamicdrive.com
//This credit MUST stay intact for use

function high(which2) {
	theobject=which2
	highlighting=setInterval("highlightit(theobject)",50)
}

function low(which2) {
	clearInterval(highlighting)
	if (which2.style.MozOpacity)
	which2.style.MozOpacity=0.3
	else if (which2.filters)
	which2.filters.alpha.opacity=30
}

function highlightit(cur2) {
	if (cur2.style.MozOpacity<1)
	cur2.style.MozOpacity=parseFloat(cur2.style.MozOpacity)+0.1
	else if (cur2.filters&&cur2.filters.alpha.opacity<100)
	cur2.filters.alpha.opacity+=10
	else if (window.highlighting)
	clearInterval(highlighting)
}

function popUp(URL) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=500, left = 112,top = 64');");
}

