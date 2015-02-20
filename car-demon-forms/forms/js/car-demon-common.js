function clearField(fld) {
	if (fld.value == "Your Name") {
		fld.value = "";
	}
}
function setField(fld) {
}
function cd_get_radios(radios) {
	var my_val = "";
	for (var i = 0; i < radios.length; i++) {
		if (radios[i].type === "radio" && radios[i].checked) {
			// get value, set checked flag or do whatever you need to
			my_val = radios[i].value;       
		}
	}
	return my_val;
}
function cd_not_valid(fld, form_id) {
	if(document.forms[form_id][fld]) {
		document.forms[form_id][fld].style.fontweight = "bold";
		document.forms[form_id][fld].style.background = "Yellow";
	}
}
function cd_valid(fld, form_id) {
	if (document.forms[form_id][fld]) {
		document.forms[form_id][fld].style.fontweight = "normal";
		document.forms[form_id][fld].style.background = "#ffffff";
	}
}
function trim(s) {
  return s.replace(/^\s+|\s+$/, '');
} 
function validateEmail(fld) {
	var error="";
	var tfld = trim(fld.value);                        // value of field with whitespace trimmed off
	var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
	var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
	
	if (fld.value == "") {
		fld.style.background = 'Yellow';
		error = cdCommonParams.error1+"\n";
	} else if (!emailFilter.test(tfld)) {              //test email for illegal characters
		fld.style.background = 'Yellow';
		error = cdCommonParams.error2+"\n";
	} else if (fld.value.match(illegalChars)) {
		fld.style.background = 'Yellow';
		error = cdCommonParams.error3+"\n";
	} else {
		fld.style.background = 'White';
	}
	return error;
}
var zChar = new Array(' ', '(', ')', '-', '.');
var maxphonelength = 14;
var phonevalue1;
var phonevalue2;
var cursorposition;
function ParseForNumber1(object){
  phonevalue1 = ParseChar(object.value, zChar);
}
function ParseForNumber2(object){
  phonevalue2 = ParseChar(object.value, zChar);
}
function backspacerUP(object,e) { 
  if(e){ 
	e = e 
  } else {
	e = window.event 
  } 
  if(e.which){ 
	var keycode = e.which 
  } else {
	var keycode = e.keyCode 
  }
  ParseForNumber1(object)
  if(keycode >= 48){
	ValidatePhone(object)
  }
}
function backspacerDOWN(object,e) { 
  if(e){ 
	e = e 
  } else {
	e = window.event 
  } 
  if(e.which){ 
	var keycode = e.which 
  } else {
	var keycode = e.keyCode 
  }
  ParseForNumber2(object)
} 
function GetCursorPosition(){
  var t1 = phonevalue1;
  var t2 = phonevalue2;
  var bool = false
  for (i=0; i<t1.length; i++) {
	if (t1.substring(i,1) != t2.substring(i,1)) {
	  if(!bool) {
		cursorposition=i
		window.status=cursorposition
		bool=true
	  }
	}
  }
}		
function ValidatePhone(object){
  var p = phonevalue1
  p = p.replace(/[^\d]*/gi,"")
  if (p.length < 3) {
	object.value=p
  } else if(p.length==3){
	pp=p;
	d4=p.indexOf('(')
	d5=p.indexOf(')')
	if(d4==-1){
	  pp="("+pp;
	}
	if(d5==-1){
	  pp=pp+")";
	}
	object.value = pp;
  } else if(p.length>3 && p.length < 7){
	p ="(" + p; 
	l30=p.length;
	p30=p.substring(0,4);
	p30=p30+") " 
	p31=p.substring(4,l30);
	pp=p30+p31;
	object.value = pp; 
  } else if(p.length >= 7){
	p ="(" + p; 
	l30=p.length;
	p30=p.substring(0,4);
	p30=p30+") " 
	p31=p.substring(4,l30);
	pp=p30+p31;
	l40 = pp.length;
	p40 = pp.substring(0,9);
	p40 = p40 + "-"
	p41 = pp.substring(9,l40);
	ppp = p40 + p41;
	object.value = ppp.substring(0, maxphonelength);
  }
  GetCursorPosition()
  if(cursorposition >= 0){
	if (cursorposition == 0) {
	  cursorposition = 2
	} else if (cursorposition <= 2) {
	  cursorposition = cursorposition + 1
	} else if (cursorposition <= 4) {
	  cursorposition = cursorposition + 3
	} else if (cursorposition == 5) {
	  cursorposition = cursorposition + 3
	} else if (cursorposition == 6) { 
	  cursorposition = cursorposition + 3 
	} else if (cursorposition == 7) { 
	  cursorposition = cursorposition + 4 
	} else if (cursorposition == 8) { 
	  cursorposition = cursorposition + 4
	  e1=object.value.indexOf(')')
	  e2=object.value.indexOf('-')
	  if (e1>-1 && e2>-1){
		if (e2-e1 == 4) {
		  cursorposition = cursorposition - 1
		}
	  }
	} else if (cursorposition == 9) {
	  cursorposition = cursorposition + 4
	} else if (cursorposition < 11) {
	  cursorposition = cursorposition + 3
	} else if (cursorposition == 11) {
	  cursorposition = cursorposition + 1
	} else if (cursorposition == 12) {
	  cursorposition = cursorposition + 1
	} else if (cursorposition >= 13) {
	  cursorposition = cursorposition
	}
	var txtRange = object.createTextRange();
	txtRange.moveStart( "character", cursorposition);
	txtRange.moveEnd( "character", cursorposition - object.value.length);
	txtRange.select();
  }		
}
function ParseChar(sStr, sChar) {
  if (sChar.length == null) {
	zChar = new Array(sChar);
  }
	else zChar = sChar;
  for (i=0; i<zChar.length; i++) {
	sNewStr = "";
	var iStart = 0;
	var iEnd = sStr.indexOf(sChar[i]);
	while (iEnd != -1) {
	  sNewStr += sStr.substring(iStart, iEnd);
	  iStart = iEnd + 1;
	  iEnd = sStr.indexOf(sChar[i], iStart);
	}
	sNewStr += sStr.substring(sStr.lastIndexOf(sChar[i]) + 1, sStr.length);		
	sStr = sNewStr;
  }
  return sNewStr;
}
function getDocHeight() {
    var D = document;
    return Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    );
}