// JavaScript Document
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
function active_img(cnt) {
   var img = photo_img_array();
   document.getElementById("current_img_num").value = cnt;
   document.getElementById("current_img_name").value = img[cnt];
	var image_count = document.getElementById("image_count").value;
	var num = document.getElementById("current_img_num").value;
	if (image_count == num) {
		document.getElementById("photo_next").style.display = 'none';
	} else {
		document.getElementById("photo_next").style.display = 'inline';
	}
	if (num == 0) {
		document.getElementById("photo_prev").style.display = 'none';
	} else {
		document.getElementById("photo_prev").style.display = 'inline';
	}
}
function car_slide_show() {
	if (document.getElementById('run_slideshow').checked) {
		var image_count = document.getElementById("image_count").value;
		var num = document.getElementById("current_img_num").value;
		if (image_count == num) {
			active_img(-1);
		}
		get_next_img();
	}
}
function get_next_img() {
	var img = photo_img_array();
	var num = document.getElementById("current_img_num").value;
	num = parseInt(num);
	num = num + 1;
	MM_swapImage("car_demon_light_box_main_img","",img[num],1);
	var image_count = document.getElementById("image_count").value;
	if (image_count == num) {
		document.getElementById("photo_next").style.display = 'none';
	} else {
		document.getElementById("photo_next").style.display = 'inline';
	}
	active_img(num);
}
function get_prev_img() {
	var img = photo_img_array();
	var num = document.getElementById("current_img_num").value;
	num = parseInt(num);
	num = num - 1;
	MM_swapImage("car_demon_light_box_main_img","",img[num],1);
	if (num == 0) {
		document.getElementById("photo_prev").style.display = 'none';
	} else {
		document.getElementById("photo_prev").style.display = 'inline';
	}
	active_img(num);
}
function car_demon_switch_tabs(active, number, tab_prefix, content_prefix) {
    for (var i=1; i < number+1; i++) {  
      document.getElementById(content_prefix+i).style.display = 'none';  
      document.getElementById(tab_prefix+i).className = '';  
    }
    document.getElementById(content_prefix+active).style.display = 'block';
    document.getElementById(tab_prefix+active).className = 'active'; 
}
function open_car_demon_lightbox(my_img) {
	jQuery("#car_demon_light_box").lightbox_me({
		overlayCSS: {background: 'black', opacity: .6}
	});
	var num = document.getElementById("current_img_num").value;
	document.getElementById('car_demon_light_box_main_email').style.display = "none";
	if (num == 0) {
		document.getElementById('photo_prev').style.display = "none";
	} else {
		document.getElementById('photo_prev').style.display = "inline";
	}
	var image_count = document.getElementById("image_count").value;
	if (image_count == num) {
		document.getElementById("photo_next").style.display = 'none';
	} else {
		document.getElementById("photo_next").style.display = 'inline';
	}
	document.getElementById('car_demon_light_box_main_img').style.display = "block";
	document.getElementById('car_demon_thumb_box').style.display = "block";
	document.getElementById('car_demon_photo_box').style.display = "block";
	document.getElementById('run_slideshow_div').style.display = "inline";
	var my_src = jQuery("#"+my_img).attr("src");
	document.getElementById('car_demon_light_box_main_img').src = my_src;
	var box = document.getElementById('car_demon_thumbs').innerHTML;
	var regex = new RegExp(my_img, 'gi');
	var new_box=box.replace(regex,"car_demon_light_box_main_img");
	document.getElementById('car_demon_thumb_box').innerHTML = new_box;
}
function close_car_demon_lightbox() {
	if (document.getElementById('main_email_friend_div')) {
		document.getElementById('main_email_friend_div').innerHTML = "";
		document.getElementById('main_email_friend_div').style.display = "none";
		document.getElementById('car_demon_light_box_main_email').style.display = "none";
	}
	document.getElementById('run_slideshow').checked = false;
	jQuery("#car_demon_light_box").trigger('close');
}
function email_friend() {
	jQuery("#car_demon_light_box").lightbox_me({
		overlayCSS: {background: 'black', opacity: .6}
	});
	document.getElementById('car_demon_light_box_main_email').style.display = "block";
	document.getElementById('car_demon_light_box_main_img').style.display = "none";
	document.getElementById('car_demon_thumb_box').style.display = "none";
	document.getElementById('photo_next').style.display = "none";
	document.getElementById('photo_prev').style.display = "none";
	document.getElementById('run_slideshow_div').style.display = "none";
	document.getElementById('run_slideshow').checked = false;
	document.getElementById('car_demon_photo_box').style.display = "block";
	var box = document.getElementById('email_friend_div').innerHTML;
	var box = box.replace(/email_friend_form_tmp/gi, "email_friend_form");
	var box = box.replace("main_email_friend_div_tmp", "main_email_friend_div");
	var box = box.replace("ef_contact_msg_tmp", "ef_contact_msg");
	var box = box.replace(/_tmp/gi, "");
	document.getElementById('car_demon_light_box_main_email').innerHTML = box;
}
//-----Email a Friend Form Validation Functions
function ef_clearField(fld) {
	if (fld.value == "Your Name") {
		fld.value = "";
	}
	if (fld.value == "Friend Name") {
		fld.value = "";
	}
}
function ef_setField(fld) {
}
function ef_car_demon_validate() {
	var msg = "";
	var name_valid = 0;
	if (email_friend_form.ef_cd_name.value == "") {
		var msg = "You must enter your name.<br />";
		ef_cd_not_valid("ef_cd_name");
	} else {
		var name_valid = 1;
	}
	if (email_friend_form.ef_cd_name.value == "Your Name") {
		var msg = "You must enter your name.<br />";
		ef_cd_not_valid("ef_cd_name");
	} else {
		if (name_valid == 1) {
			ef_cd_valid("ef_cd_name");
		}
	}
	if (email_friend_form.ef_cd_friend_name.value == "") {
		var msg = msg + "You must enter your friend's name.<br />";
		ef_cd_not_valid("ef_cd_friend_name");
	} else {
		var name_valid = 1;
	}
	if (email_friend_form.ef_cd_friend_name.value == "Friend Name") {
		var msg = msg + "You must enter your friend's name.<br />";
		ef_cd_not_valid("ef_cd_friend_name");
	} else {
		if (name_valid == 1) {
			ef_cd_valid("ef_cd_friend_name");
		}
	}
	var e_msg = ef_validateEmail(email_friend_form.ef_cd_email, 0);
	if (e_msg == "") {
		ef_cd_valid("ef_cd_email");
	} else {
		var msg = msg + e_msg + "<br />";
	}
	var e_msg = ef_validateEmail(email_friend_form.ef_cd_friend_email, 1);
	if (e_msg == "") {
		ef_cd_valid("ef_cd_friend_email");
	} else {
		var msg = msg + e_msg + "<br />";
	}
	if (msg != "") {
		document.getElementById("ef_contact_msg").style.display = "block";
		document.getElementById("ef_contact_msg").innerHTML = msg;
		javascript:scroll(0,0);
	} else {
		var action = "";
		var your_name = document.getElementById("ef_cd_name").value;
		var your_email = document.getElementById("ef_cd_email").value;
		var friend_name = document.getElementById("ef_cd_friend_name").value;
		var friend_email = document.getElementById("ef_cd_friend_email").value;
		var comment = document.getElementById("ef_comment").value;
		var stock_num = document.getElementById("ef_stock_num").value;
		var nonce = document.getElementById("nonce").value;
		var sending = "<div align='center'><h3>Sending...</h3><img src='"+cdSingleCarParams.car_demon_path+"images/loading.gif' /></div>"
		document.getElementById("main_email_friend_div").innerHTML = sending;
		jQuery.ajax({
			type: 'POST',
			data: {'action': 'email_friend_handler', 'your_name': your_name, 'your_email':your_email, 'friend_name': friend_name, 'friend_email': friend_email, 'stock_num': stock_num, 'comment':comment, 'nonce': nonce},
			url: cdSingleCarParams.ajaxurl,
			timeout: 5000,
			error: function ef_() {},
			dataType: "html",
			success: function ef_(html){
				document.getElementById("ef_contact_final_msg").style.display = "block";
				document.getElementById("ef_contact_final_msg").style.background = "#DDDDDD";
				document.getElementById("ef_contact_final_msg").innerHTML = html;
				document.getElementById("main_email_friend_div").style.display = "none";
				javascript:scroll(0,0);
			}
		})
	}
	return false;
}
function ef_cd_not_valid(fld) {
	document.getElementById(fld).style.fontweight = "bold";
	document.getElementById(fld).style.background = "Yellow";
}
function ef_cd_valid(fld) {
	document.getElementById(fld).style.fontweight = "normal";
	document.getElementById(fld).style.background = "#ffffff";
}
function ef_trim(s) {
  return s.replace(/^\s+|\s+$/, '');
}
function ef_validateEmail(fld, msg) {
	var error="";
	var tfld = ef_trim(fld.value);                        // value of field with whitespace trimmed off
	var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
	var illegalChars= /[\(\)\<\>\,\;\:\\"\[\]]/ ;
	if (fld.value == "") {
		fld.style.background = 'Yellow';
		if (msg == 0) {
			error = "You didn't enter an email address.\n";
		}
		else {
			error = "You didn't enter an email address for your friend.\n";
		}
	} else if (!emailFilter.test(tfld)) {              //test email for illegal characters
		fld.style.background = 'Yellow';
		if (msg == 0) {
			error = "Please enter a valid email address.\n";
		}
		else {
			error = "Please enter a valid email address for your friend.\n";
		}
	} else if (fld.value.match(illegalChars)) {
		fld.style.background = 'Yellow';
		if (msg == 0) {
			error = "The email address contains illegal characters.\n";
		}
		else {
			error = "The email address for you friend contains illegal characters.\n";
		}
	} else {
		fld.style.background = 'White';
	}
	return error;
}
function cd_getElementLeft(elm) {
	var x = 0;
	x = elm.offsetLeft;
	elm = elm.offsetParent;	
	while(elm != null) {
		x = parseInt(x) + parseInt(elm.offsetLeft);
		elm = elm.offsetParent;
	}
	return x;
}
function cd_getElementTop(elm) {
	var y = 0;
	y = elm.offsetTop;	
	elm = elm.offsetParent;
	while(elm != null) {
		y = parseInt(y) + parseInt(elm.offsetTop);
		elm = elm.offsetParent;
	}	
	return y;
}
function cd_make_large(obj) {
	var imgbox=document.getElementById("imgbox");
	imgbox.style.visibility='visible';
	var img = document.createElement("img");
	img.src=obj.src;
	img.style.width='auto';
	img.style.height='400px';
	if(img.addEventListener){
		img.addEventListener('mouseout',cd_go_out,false);
	} else {
		img.attachEvent('onmouseout',cd_go_out);
	}
	imgbox.innerHTML='';
	imgbox.appendChild(img);
	imgbox.style.left=(cd_getElementLeft(obj)+10) +'px';
	imgbox.style.top=(cd_getElementTop(obj)-390) + 'px';
}
function cd_go_out() {
	document.getElementById("imgbox").style.visibility='hidden';
}