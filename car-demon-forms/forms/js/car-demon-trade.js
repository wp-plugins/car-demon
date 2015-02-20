// JavaScript Document
function findValue(li) {
	if( li == null ) return alert("No match!");
	if( !!li.extra ) var sValue = li.extra[0];
	else var sValue = li.selectValue;
}
function selectItem(car_title) {
	jQuery.ajax({
		type: 'POST',
		data: {action: 'cd_trade_show_stock', 'car_title': car_title, 'show_stock': 2},
		url: cdTradeParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			document.getElementById("find_voi").style.display = 'none';
			document.getElementById("show_voi").style.display = 'block';
			document.getElementById("show_voi").innerHTML = html;
		}
	})
}
function selectCarItem(car_title) {
	jQuery.ajax({
		type: 'POST',
		data: {action: 'cd_trade_show_stock', 'car_title': car_title, 'show_stock': 2},
		url: cdTradeParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			document.getElementById("find_voi").style.display = 'none';
			document.getElementById("show_voi").style.display = 'block';
			document.getElementById("show_voi").innerHTML = html;
		}
	})
}
function formatItem(row) {
	return row[0] + " (" + row[1] + ")";
}
function formatCarItem(row) {
	return row[0];
}
function select_voi(my_type) {
	if (my_type == "stock") {
		document.getElementById("select_description").style.display = "none";
		document.getElementById("select_stock").style.display = "block";
		if (document.getElementById("trade_locations")) {
			document.getElementById("trade_locations").style.display = "none";
		}
		document.getElementById("select_stock").value = "";
	}
	if (my_type == "search") {
		document.getElementById("select_stock").style.display = "none";
		document.getElementById("select_description").style.display = "block";
		if (document.getElementById("trade_locations")) {
			document.getElementById("trade_locations").style.display = "none";
		}
	}
	if (my_type == "na") {
		document.getElementById("select_stock").style.display = "none";
		document.getElementById("select_description").style.display = "none";
		if (document.getElementById("trade_locations")) {
			document.getElementById("trade_locations").style.display = "block";
		}
	}			
}
function show_voi() {
	document.getElementById("find_voi").style.display = "block";
	if (document.getElementById("trade_locations")) {
		document.getElementById("trade_locations").style.display = "block";
	}
	document.getElementById("not_voi").style.display = "none";
	document.getElementById("show_voi").style.display = "none";
	document.getElementById("select_description").style.display = "none";
	document.getElementById("select_stock").style.display = "none";
	document.getElementById("select_stock_txt").value = "";
	document.getElementById("select_car_txt").value = "";
	document.getElementById("purchase_stock").value = "";
	var radioObj = document.getElementById("pick_voi_3");
	setCheckedValue(radioObj, 3);
}
function setCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
		}
	}
}
function car_demon_validate() {
	var form_id = "";
	var msg = "";
	var name_valid = 0;
	document.forms["trade_form"+form_id].style.display = "none";
	if (trade_form.cd_name.value == "") {
		var msg = cdTradeParams.error1+"<br />";
		cd_not_valid("cd_name","trade_form"+form_id);
	} else {
		var name_valid = 1;
	}
	if (trade_form.cd_name.value == "Your Name") {
		var msg = cdTradeParams.error2+"<br />";
		cd_not_valid("cd_name","trade_form"+form_id);
	} else {
		if (name_valid == 1) {
			cd_valid("cd_name","trade_form"+form_id);
		}
	}
	if (cdTradeParams.validate_phone == 1) {
		if (trade_form.cd_phone.value == "") {
			var msg = msg + cdTradeParams.error3+"<br />";
			cd_not_valid("cd_phone","trade_form"+form_id);
		} else {
			if (trade_form.cd_phone.value.length != 14) {
				var msg = msg + cdTradeParams.error4+"<br />";
				cd_not_valid("cd_phone","trade_form"+form_id);			
			} else {
				cd_valid("cd_phone","trade_form"+form_id);
			}
		}
	}
	var e_msg = validateEmail(trade_form.cd_email);
	if (e_msg == "") {
		cd_valid("cd_email","trade_form"+form_id);
	} else {
		var msg = msg + e_msg + "<br />";
	}
	if (trade_form.year.value == "") {
		var msg = msg + cdTradeParams.error7+"<br />";
		cd_not_valid("year","trade_form"+form_id);
	} else {
		cd_valid("year","trade_form"+form_id);
	}
	if (trade_form.make.value == "") {
		var msg = msg + cdTradeParams.error8+"<br />";
		cd_not_valid("make","trade_form"+form_id);
	} else {
		cd_valid("make","trade_form"+form_id);
	}
	if (trade_form.model.value == "") {
		var msg = msg + cdTradeParams.error9+"<br />";
		cd_not_valid("model","trade_form"+form_id);
	} else {
		cd_valid("year","trade_form"+form_id);
		}
	if (trade_form.miles.value == "") {
		var msg = msg + cdTradeParams.error10+"<br />";
		cd_not_valid("miles","trade_form"+form_id);
	} else {
		cd_valid("miles","trade_form"+form_id);
	}
	var no_car = 0;
	var no_location = 1;
	var selected_car = "";
	var voi_radios = document.getElementsByName("pick_voi");
	var voi_type = 1;
	for (var i = 0; i < voi_radios.length; i++) {
		if (voi_radios[i].type === 'radio' && voi_radios[i].checked) {
			var voi_type = voi_radios[i].value;
		}
	}
	if (voi_type == 1) { no_car = 1; no_location = 1; }
	if (voi_type == 2) { no_car = 1; no_location = 1; }
	if (voi_type == 3) { no_car = 0; no_location = 0; }
	if (no_car == 1) {
		if (document.getElementById("purchase_stock")) {
			if (document.getElementById("purchase_stock").value == "") {
				var no_car = 0;
			}
			else {
				var no_car = 1;
				var selected_car = document.getElementById("purchase_stock").value;
			}
		}
		if (selected_car == "") {
			var msg = msg + cdTradeParams.error11+"<br />";
			cd_not_valid("voi_title","trade_form"+form_id);
		}
		else {
			cd_valid("voi_title","trade_form"+form_id);
		}
	}
	if (document.getElementById("purchase_stock")) {
		if (document.getElementById("purchase_stock").value == "") {
			var no_car = 0;
		}
		else {
			var no_car = 1;
			var selected_car = document.getElementById("purchase_stock").value;
		}
	}
	if (no_car == 0) {
		var radios = document.getElementsByName("trade_location");
		var location_value = "";
		for (var i = 0; i < radios.length; i++) {
			if (radios[i].type === 'radio' && radios[i].checked) {
				location_value = radios[i].value;
			}
		}
		if (location_value == "") {
			var msg = msg + cdTradeParams.error12+"<br />";
			cd_not_valid("trade_locations","trade_form"+form_id);
		} else {
			document.getElementById("select_location").style.background = "";
			cd_valid("trade_locations","trade_form"+form_id);
			if (no_location == 0) {
//				cd_valid("voi_title","trade_form"+form_id);
			}
		}
	}
	if (msg != "") {
		document.getElementById("trade_msg").style.display = "block";
		document.getElementById("trade_msg").innerHTML = msg;
		jQuery("#trade_form"+form_id).fadeIn(
			function () {
				var top = document.getElementById('trade_msg'+form_id).offsetTop; //Getting Y of target element
				window.scrollTo(0, top);
			}
		);
		return;
	} else {
		var action = "";
		var your_name = document.getElementById("cd_name").value;
		var phone = document.getElementById("cd_phone").value;
		var email = document.getElementById("cd_email").value;
		var radios = document.getElementsByName("trade_location");
		var trade_location = cd_get_radios(radios);
		var options = cd_get_options();
		var year = document.getElementById("year").value;
		var make = document.getElementById("make").value;
		var model = document.getElementById("model").value;
		var miles = document.getElementById("miles").value;
		var vin = document.getElementById("vin").value;
		var comment = document.getElementById("comment").value;
		cdTradeParams.hook_js;
		var form_data = cdTradeParams.form_data;
		var nonce = document.forms["trade_form"+form_id].nonce.value;
		jQuery.ajax({
			type: 'POST',
			data: {action: 'cd_trade_handler', 'nonce': nonce, 'your_name': your_name,'phone':phone, 'email':email, 'trade_location':trade_location, 'year':year, 'make':make, 'model':model, 'miles':miles, 'vin':vin, 'comment':comment, 'options':options, 'selected_car':selected_car},
			url: cdTradeParams.ajaxurl,
			timeout: 5000,
			error: function() {},
			dataType: "html",
			success: function(html){
				document.getElementById("trade_msg").style.display = "block";
				document.getElementById("trade_msg").style.background = "#FFFFFF";
				document.getElementById("trade_msg").innerHTML = html;
				document.getElementById("trade_form").style.display = "none";
				javascript:scroll(0,0);
			}
		})
	}
	return false;
}
function cd_get_options() {
	var checkboxes = document.getElementsByName('Options[]');
	var vals = "";
	for (var i=0, n=checkboxes.length;i<n;i++) {
	  if (checkboxes[i].checked) vals += ","+checkboxes[i].value;
	}
	if (vals) vals = vals.substring(1); // drop leading comma
	return vals;
}

jQuery("#select_stock_txt").autocomplete (
  {
		source:cdTradeParams.ajaxurl+"?show_stock=2&action=cd_trade_find_vehicle",
		delay:10,
		minChars:2,
		matchSubset:1,
		matchContains:1,
		cacheLength:10,
		onFindValue:findValue,
		formatItem:formatCarItem,
		autoFill:true,
		width:300,
       select: function(event, ui) {
			selectItem(ui.item.value);
        }
	}
);

jQuery("#select_car_txt").autocomplete (
  {
		source:cdTradeParams.ajaxurl+"?show_stock=2&action=cd_trade_find_vehicle",
		delay:10,
		minChars:2,
		matchSubset:1,
		matchContains:1,
		cacheLength:10,
		onFindValue:findValue,
		formatItem:formatCarItem,
		autoFill:true,
		width:300,
       select: function(event, ui) {
			selectCarItem(ui.item.value);
        }
	}
);