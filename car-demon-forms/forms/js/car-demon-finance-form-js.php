<?php
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('/forms/js', '', $car_demon_pluginpath);
?>
<script language="javascript">
		function findValue(li) {
			if( li == null ) return alert("No match!");
			if( !!li.extra ) var sValue = li.extra[0];
			else var sValue = li.selectValue;
		}
<?php
		$car_demon_pluginpath = CAR_DEMON_PATH;
		$car_demon_pluginpath_images = str_replace('car-demon-forms/forms','',$car_demon_pluginpath);	
		$cd_wp_admin = str_replace('wp-content/plugins/car-demon','',$car_demon_pluginpath);	
?>
		function selectItem(car_title) {
			jQuery.ajax({
				type: 'POST',
				data: {action: 'cd_trade_show_stock', 'car_title': car_title, 'show_stock': 2},
				url: "<?php echo $cd_wp_admin ?>wp-admin/admin-ajax.php?show_stock=2&action=cd_trade_find_vehicle",
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
				url: "<?php echo $cd_wp_admin ?>wp-admin/admin-ajax.php?show_stock=2&action=cd_trade_find_vehicle",
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
		function clearField(fld) {
			if (fld.value == "Your Name") {
				fld.value = "";
			}
		}
		function setField(fld) {
		}
		function select_voi(my_type) {
			if (my_type == "stock") {
				document.getElementById("select_description").style.display = "none";
				document.getElementById("select_stock").style.display = "block";
				document.getElementById("finance_locations").style.display = "none";
				document.getElementById("select_stock").value = "";
			}
			if (my_type == "search") {
				document.getElementById("select_stock").style.display = "none";
				document.getElementById("select_description").style.display = "block";
				document.getElementById("finance_locations").style.display = "none";
			}
			if (my_type == "na") {
				document.getElementById("select_stock").style.display = "none";
				document.getElementById("select_description").style.display = "none";
				document.getElementById("finance_locations").style.display = "block";
				document.getElementById("finance_locations").style.width = "425px";
				document.getElementById("finance_locations").style.marginLeft = "20px";
			}			
		}
		function show_voi() {
			document.getElementById("find_voi").style.display = "block";
			document.getElementById("finance_locations").style.display = "block";
			document.getElementById("finance_locations").style.width = "425px";
			document.getElementById("finance_locations").style.marginLeft = "20px";
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
	//===========================================================================
	function stopRKey(evt) {
		var evt  = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type=="text")) { return false; }
	}
	document.onkeypress = stopRKey;
	
	function checkNum() {
		var carCode = event.keyCode;
		if (carCode != 45) {
			if ((carCode < 48) || (carCode > 57)) {
				alert('Please enter only numbers.');
				event.cancelBubble = true
				event.returnValue = false;
			}
		}
	}
	
	function send_entire_form() {
		jQuery.ajax({
			type: 'POST',
			data: $("#frm_app").serialize(),
			url: "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-finance-form-handler.php?send_finance=1",
			timeout: 5000,
			error: function() {},
			dataType: "html",
			success: function(html){
				document.getElementById('body_content_credit').style.display = "none";
				document.getElementById('form_results').innerHTML = html;
			}
		})
	}
	
	function mainValidation() {
		var xFail = 0
		var xTmp = 'bobfrog'+document.frm_app.app_street_num.value
		if (xTmp != 'bobfrog') {
			if (document.frm_app.app_street_num.value == '') {document.getElementById('msgDivapp_street_num').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivapp_street_num').style.display = 'none'}
			if (document.frm_app.app_street_name.value == '') {document.getElementById('msgDivapp_street_name').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivapp_street_name').style.display = 'none'}
		}
		var xTmp = 'bobfrog'+document.frm_app.app_po_box_num.value
		if (xTmp != 'bobfrog') {
			if (document.frm_app.app_po_box_num.value == '') {document.getElementById('msgDivapp_po_box_num').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivapp_po_box_num').style.display = 'none'}
		}
		if (document.frm_app.app_street_type.value == '') {document.getElementById('msgDivapp_street_type').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivapp_street_type').style.display = 'none'}
		if (document.frm_app.yaca.value == '') {document.getElementById('msgDivyaca').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivyaca').style.display = 'none'}
		if (document.frm_app.bdy.value == '') {document.getElementById('msgDivbdy').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivbdy').style.display = 'none'}
		if (document.frm_app.ea.value == '') {document.getElementById('msgDivea').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivea').style.display = 'none'}
		if (document.frm_app.en.value == '') {document.getElementById('msgDiven').style.display = ''; var xFail = 1;} else {document.getElementById('msgDiven').style.display = 'none'}
		if (document.frm_app.epn.value == '') {document.getElementById('msgDivepn').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivepn').style.display = 'none'}
		if (document.frm_app.fn.value == '') {document.getElementById('msgDivfn').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivfn').style.display = 'none'}
		if (document.frm_app.gmi.value == '') {document.getElementById('msgDivgmi').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivgmi').style.display = 'none'}
		if (document.frm_app.ln.value == '') {document.getElementById('msgDivln').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivln').style.display = 'none'}
		if (document.frm_app.mac.value == '') {document.getElementById('msgDivmac').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivmac').style.display = 'none'}
		if (document.frm_app.maca.value == '') {document.getElementById('msgDivmaca').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivmaca').style.display = 'none'}
		if (document.frm_app.oi.value == '') {document.getElementById('msgDivoi').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivoi').style.display = 'none'}
		if (document.frm_app.ramp.value == '') {document.getElementById('msgDivramp').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivramp').style.display = 'none'}
		if (document.frm_app.roo.value == '') {document.getElementById('msgDivroo').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivroo').style.display = 'none'}
		if (document.frm_app.st.value == '') {document.getElementById('msgDivst').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivst').style.display = 'none'}
		if (document.frm_app.yac.value == '') {document.getElementById('msgDivyac').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivyac').style.display = 'none'}
		if (document.frm_app.zi.value == '') {document.getElementById('msgDivzi').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivzi').style.display = 'none'}
		if (document.frm_app.p.value == '') {document.getElementById('msgDivp').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp').style.display = 'none'}
		if (document.frm_app.bcp.value == '') {document.getElementById('msgDivbcp').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivbcp').style.display = 'none'}
		if (document.frm_app.hpn.value == '') {document.getElementById('msgDivhpn').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivhpn').style.display = 'none'}
		if (document.frm_app.ssn.value == '') {document.getElementById('msgDivssn').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivssn').style.display = 'none'}
		if (document.frm_app.cty.value == '') {document.getElementById('msgDivcty').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivcty').style.display = 'none'}
		if (document.frm_app.bct.value == '') {document.getElementById('msgDivbct').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivbct').style.display = 'none'}
		if (document.frm_app.bdd.value == '') {document.getElementById('msgDivbdd').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivbdd').style.display = 'none'}
		if (document.frm_app.bdm.value == '') {document.getElementById('msgDivbdm').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivbdm').style.display = 'none'}
		if (document.frm_app.yaca.value < 2) {
			var xTmp = 'bobfrog'+document.frm_app.p1app_street_num.value
			if (xTmp != 'bobfrog') {
				if (document.frm_app.p1app_street_num.value == '') {document.getElementById('msgDivp1app_street_num').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1app_street_num').style.display = 'none'}
				if (document.frm_app.p1app_street_name.value == '') {document.getElementById('msgDivp1app_street_name').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1app_street_name').style.display = 'none'}
			}
			var xTmp = 'bobfrog'+document.frm_app.p1app_po_box_num.value
			if (xTmp != 'bobfrog') {
				if (document.frm_app.p1app_po_box_num.value == '') {document.getElementById('msgDivap1pp_po_box_num').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1app_po_box_num').style.display = 'none'}
			}
			if (document.frm_app.p1cty.value == '') {document.getElementById('msgDivp1cty').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1cty').style.display = 'none'}
			if (document.frm_app.p1st.value == '') {document.getElementById('msgDivp1st').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1st').style.display = 'none'}
			if (document.frm_app.p1zi.value == '') {document.getElementById('msgDivp1zi').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1zi').style.display = 'none'}
			if (document.frm_app.p1app_street_type.value == '') {document.getElementById('msgDivp1app_street_type').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1app_street_type').style.display = 'none'}
		}
	
		if (document.frm_app.yac.value < 2) {
			if (document.frm_app.p2p.value == '') {document.getElementById('msgDivp2p').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp2p').style.display = 'none'}
			if (document.frm_app.p2en.value == '') {document.getElementById('msgDivp2en').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp2en').style.display = 'none'}
			if (document.frm_app.p2epn.value == '') {document.getElementById('msgDivp2epn').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp2epn').style.display = 'none'}
			if (document.frm_app.p2gmi.value == '') {document.getElementById('msgDivp2gmi').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp2gmi').style.display = 'none'}
			if (document.frm_app.p2mac.value == '') {document.getElementById('msgDivp2mac').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp2mac').style.display = 'none'}
			if (document.frm_app.p2oi.value == '') {document.getElementById('msgDivp2oi').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp2oi').style.display = 'none'}
			if (document.frm_app.p2yac.value == '') {document.getElementById('msgDivp2yac').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp2yac').style.display = 'none'}
		}
		//if copapp yes
		if (document.frm_app.MakeCoSigner.value == 'Yes') {
			document.getElementById('CoSignerDiv').style.display = 'inline-block';
			if (document.frm_app.co_app_street_num.value == '') {document.getElementById('msgDivco_app_street_num').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_app_street_num').style.display = 'none'}
			if (document.frm_app.co_app_street_name.value == '') {document.getElementById('msgDivco_app_street_name').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_app_street_name').style.display = 'none'}
			if (document.frm_app.co_app_street_type.value == '') {document.getElementById('msgDivco_app_street_type').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_app_street_type').style.display = 'none'}
			if (document.frm_app.co_hpn2.value == '') {document.getElementById('msgDivco_hpn2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_hpn2').style.display = 'none'}
			if (document.frm_app.co_bcp2.value == '') {document.getElementById('msgDivco_bcp2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_bcp2').style.display = 'none'}
			if (document.frm_app.co_bct2.value == '') {document.getElementById('msgDivco_bct2 ').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_bct2 ').style.display = 'none'}
			if (document.frm_app.co_bdd.value == '') {document.getElementById('msgDivco_bdd').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_bdd').style.display = 'none'}
			if (document.frm_app.co_bdm.value == '') {document.getElementById('msgDivco_bdm').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_bdm').style.display = 'none'}
			if (document.frm_app.co_bdy.value == '') {document.getElementById('msgDivco_bdy').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_bdy').style.display = 'none'}
			if (document.frm_app.co_cty2.value == '') {document.getElementById('msgDivco_cty2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_cty2').style.display = 'none'}
			if (document.frm_app.co_ea2.value == '') {document.getElementById('msgDivco_ea2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_ea2').style.display = 'none'}
			if (document.frm_app.co_en2.value == '') {document.getElementById('msgDivco_en2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_en2').style.display = 'none'}
			if (document.frm_app.co_epn2.value == '') {document.getElementById('msgDivco_epn2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_epn2').style.display = 'none'}
			if (document.frm_app.co_fn2.value == '') {document.getElementById('msgDivco_fn2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_fn2').style.display = 'none'}
			if (document.frm_app.co_gmi2.value == '') {document.getElementById('msgDivco_gmi2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_gmi2').style.display = 'none'}
			if (document.frm_app.co_ln2.value == '') {document.getElementById('msgDivco_ln2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_ln2').style.display = 'none'}
			if (document.frm_app.co_mac2.value == '') {document.getElementById('msgDivco_mac2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_mac2').style.display = 'none'}
			if (document.frm_app.co_maca2.value == '') {document.getElementById('msgDivco_maca2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_maca2').style.display = 'none'}
			if (document.frm_app.co_oi2.value == '') {document.getElementById('msgDivco_oi2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_oi2').style.display = 'none'}
			if (document.frm_app.co_p2.value == '') {document.getElementById('msgDivco_p2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_p2').style.display = 'none'}
			if (document.frm_app.co_ramp2.value == '') {document.getElementById('msgDivco_ramp2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_ramp2').style.display = 'none'}
			if (document.frm_app.co_roo2.value == '') {document.getElementById('msgDivco_roo2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_roo2').style.display = 'none'}
			if (document.frm_app.co_ssn2.value == '') {document.getElementById('msgDivco_ssn2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_ssn2').style.display = 'none'}
			if (document.frm_app.co_st2.value == '') {document.getElementById('msgDivco_st2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_st2').style.display = 'none'}
			if (document.frm_app.co_yaca2.value == '') {document.getElementById('msgDivco_yaca2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_yaca2').style.display = 'none'}
			if (document.frm_app.co_zi2.value == '') {document.getElementById('msgDivco_zi2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivco_zi2').style.display = 'none'}
			if (document.frm_app.co_yaca2.value < 2) {
				if (document.frm_app.p1co_app_street_name.value == '') {document.getElementById('msgDivp1co_app_street_name').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_app_street_name').style.display = 'none'}
				if (document.frm_app.p1co_app_street_num.value == '') {document.getElementById('msgDivp1co_app_street_num').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_app_street_num').style.display = 'none'}
				if (document.frm_app.p1co_cty2.value == '') {document.getElementById('msgDivp1co_cty2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_cty2').style.display = 'none'}
				if (document.frm_app.p1co_app_street_type.value == '') {document.getElementById('msgDivp1co_app_street_type').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_app_street_type').style.display = 'none'}
				if (document.frm_app.p1co_st2.value == '') {document.getElementById('msgDivp1co_st2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_st2').style.display = 'none'}
				if (document.frm_app.p1co_zi2.value == '') {document.getElementById('msgDivp1co_zi2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_zi2').style.display = 'none'}
			}
			if (document.frm_app.co_yac2.value < 2) {
				if (document.frm_app.p1co_oi2.value == '') {document.getElementById('msgDivp1co_oi2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_oi2').style.display = 'none'}
				if (document.frm_app.p1co_gmi2.value == '') {document.getElementById('msgDivp1co_gmi2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_gmi2').style.display = 'none'}
				if (document.frm_app.p1co_en2.value == '') {document.getElementById('msgDivp1co_en2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_en2').style.display = 'none'}
				if (document.frm_app.p1co_epn2.value == '') {document.getElementById('msgDivp1co_epn2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_epn2').style.display = 'none'}
				if (document.frm_app.p1co_mac2.value == '') {document.getElementById('msgDivp1co_mac2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_mac2').style.display = 'none'}
				if (document.frm_app.p1co_p2.value == '') {document.getElementById('msgDivp1co_p2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_p2').style.display = 'none'}
				if (document.frm_app.p1co_yac2.value == '') {document.getElementById('msgDivp1co_yac2').style.display = ''; var xFail = 1;} else {document.getElementById('msgDivp1co_yac2').style.display = 'none'}
			}
		}
		//=====VOI Validation
			var no_car = 0;
			var no_location = 1;
			var selected_car = "";
			var voi_radios = document.getElementsByName("pick_voi");
			var voi_type = 1;
			var msg = "";
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
					var msg = msg + "<?php _e('You indicated you were interested in purchasing a vehicle but did not select one.', 'car-demon'); ?>";
					cd_not_valid("voi_title");
				}
				else {
					cd_valid("voi_title");
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
				var radios = document.getElementsByName("finance_location");
				var location_value = "";
				for (var i = 0; i < radios.length; i++) {
					if (radios[i].type === 'radio' && radios[i].checked) {
						location_value = radios[i].value;
					}
				}
				if (location_value == "") {
					var msg = msg + "You did not select a finance location.";
					cd_not_valid("select_location");
				}
				else {
					document.getElementById("select_location").style.background = "";
					cd_valid("select_location");
					if (no_location == 0) {
						cd_valid("voi_title");
					}
				}
			}
			if (msg != "") {
				var xFail = 1;
				alert(msg);
			}
		//=====END VOI Validation
		//HANDLE FAILURE WITH MESSAGES
		if (xFail == 1) {
			alert('<?php _e('Issues were detected with your Application.', 'car-demon'); ?> \n <?php _e('Please review the application and submit again.', 'car-demon'); ?>');
			var top = document.getElementById('frm_app').offsetTop; //Getting Y of target element
			window.scrollTo(0, top);
		}
		else {
			send_entire_form();
		}
	}
	
	function cd_not_valid(fld) {
		document.getElementById(fld).style.fontweight = "bold";
		document.getElementById(fld).style.background = "Yellow";
	}
	function cd_valid(fld) {
		document.getElementById(fld).style.fontweight = "normal";
		document.getElementById(fld).style.background = "#ffffff";
	}
	
	function ValidateCoAppPrevAddress() {
		var xTmp = 'bobfrog'+document.frm_app.p1co_app_street_num.value
		if (xTmp != 'bobfrog') {
			document.frm_app.p1co_app_po_box_num.disabled = 'disabled';
			document.frm_app.p1co_app_rural_route.disabled = 'disabled';
		}
		else {
			document.frm_app.p1co_app_po_box_num.disabled = '';
			document.frm_app.p1co_app_rural_route.disabled = '';
		}
	
		var xTmp = 'bobfrog'+document.frm_app.p1co_app_po_box_num.value
		if (xTmp != 'bobfrog') {
			document.frm_app.p1co_app_street_num.disabled = 'disabled';
			document.frm_app.p1co_app_street_name.disabled = 'disabled';
		}
		else {
			document.frm_app.p1co_app_street_num.disabled = '';
			document.frm_app.p1co_app_street_name.disabled = '';
		}
	}
	
	function ValidateCoAppAddress() {
		var xTmp = 'bobfrog'+document.frm_app.co_app_street_num.value
		if (xTmp != 'bobfrog') {
			document.frm_app.co_app_po_box_num.disabled = 'disabled';
			document.frm_app.co_app_rural_route.disabled = 'disabled';
		}
		else {
			document.frm_app.co_app_po_box_num.disabled = '';
			document.frm_app.co_app_rural_route.disabled = '';
		}
			
		var xTmp = 'bobfrog'+document.frm_app.co_app_po_box_num.value
		if (xTmp != 'bobfrog') {
			document.frm_app.co_app_street_num.disabled = 'disabled';
			document.frm_app.co_app_street_name.disabled = 'disabled';
		}
		else {
			document.frm_app.co_app_street_num.disabled = '';
			document.frm_app.co_app_street_name.disabled = '';
		}
	}
	
	function ValidateAppAddress() {
		var xTmp = 'bobfrog'+document.frm_app.app_street_num.value
		if (xTmp != 'bobfrog') {
			document.frm_app.app_po_box_num.disabled = 'disabled';
			document.frm_app.app_rural_route.disabled = 'disabled';
		}
		else {
			document.frm_app.app_po_box_num.disabled = '';
			document.frm_app.app_rural_route.disabled = '';
		}
			
		var xTmp = 'bobfrog'+document.frm_app.app_po_box_num.value
		if (xTmp != 'bobfrog') {
			document.frm_app.app_street_num.disabled = 'disabled';
			document.frm_app.app_street_name.disabled = 'disabled';
		}
		else {
			document.frm_app.app_street_num.disabled = '';
			document.frm_app.app_street_name.disabled = '';
		}
	}
	
	function ValidateAppPrevAddress() {
		var xTmp = 'bobfrog'+document.frm_app.p1app_street_num.value
		if (xTmp != 'bobfrog') {
			document.frm_app.p1app_po_box_num.disabled = 'disabled';
			document.frm_app.p1app_rural_route.disabled = 'disabled';
		}
		else {
			document.frm_app.p1app_po_box_num.disabled = '';
			document.frm_app.p1app_rural_route.disabled = '';
		}
			
		var xTmp = 'bobfrog'+document.frm_app.p1app_po_box_num.value
		if (xTmp != 'bobfrog') {
			document.frm_app.p1app_street_num.disabled = 'disabled';
			document.frm_app.p1app_street_name.disabled = 'disabled';
		}
		else {
			document.frm_app.p1app_street_num.disabled = '';
			document.frm_app.p1app_street_name.disabled = '';
		}
	}
	
	function MakeTheCoSigner(x) {
		if (x.value == 'Yes') {
			document.getElementById('CoSignerDiv').style.display = 'inline-block';
		}
		else {
			document.getElementById('CoSignerDiv').style.display = 'none';
		}
	}
	
	function AddEmployers(xthis,x) {
		if (x == 1) {
			if (xthis.value < 3) {
				document.getElementById('AppEmployer1').style.display = 'block';
			}
			else {
				document.getElementById('AppEmployer1').style.display = 'none';
			}
		}
	}
	
	function AddAddresses(xthis,x) {
		if (x == 1) {
			if (xthis.value < 3) {
				document.getElementById('AppAddress1').style.display = 'block';
			}
			else {
				document.getElementById('AppAddress1').style.display = 'none';
			}
		}
	}
	
	function AddCoEmployer(xthis,x) {
		if (x == 1) {
			if (xthis.value < 3) {
				document.getElementById('CoEmployer1').style.display = 'block';
			}
			else {
				document.getElementById('CoEmployer1').style.display = 'none';
			}
		}
	}
	
	function AddCoAddresss(xthis,x) {
		if (x == 1) {
			if (xthis.value < 3) {
				document.getElementById('CoAppAddress1').style.display = 'block';
			}
			else {
				document.getElementById('CoAppAddress1').style.display = 'none';
			}
		}
	}
	
	function validate_form ( ) {
		valid = true;
		if (valid == true) {
			document.frm_app.submit();
		}
		return valid;
	}
	
	function YesOrNo() {
		if (document.frm_app.co_fn2.disabled==True) {
			var r=confirm("<?php _e('Do you have a Co-Signer?', 'car-demon'); ?>");
			if (r==true) {
				enableCoSigner();
				document.frm_app.co_fn2.focus;
			}
			else {
				document.frm_app.co_fn.focus;
			}
		}
	}
	
	function enableCoSigner() {
		document.frm_app.co_fn2.disabled=false;
		document.frm_app.co_ln2.disabled=false;
		document.frm_app.co_hpn2.disabled=false;
		document.frm_app.co_ea2.disabled=false;
		document.frm_app.co_ssn2.disabled=false;
		document.frm_app.co_add2.disabled=false;
		document.frm_app.co_cty2.disabled=false;
		document.frm_app.co_st2.disabled=false;
		document.frm_app.co_zi2.disabled=false;
		document.frm_app.co_bd2.disabled=false;
		document.frm_app.co_en2.disabled=false;
		document.frm_app.co_p2.disabled=false;
		document.frm_app.co_yac2.disabled=false;
		document.frm_app.co_mac2.disabled=false;
		document.frm_app.co_epn2.disabled=false;
		document.frm_app.co_gmi2.disabled=false;
		document.frm_app.co_oi2.disabled=false;
		document.frm_app.co_yaca2.disabled=false;
		document.frm_app.co_maca2.disabled=false;
		document.frm_app.co_roo2.disabled=false;
		document.frm_app.co_ramp2.disabled=false;
		document.frm_app.co_bcp2.disabled=false;
		document.frm_app.co_bct2.disabled=false;
	}
	
	function updateFrm() {
		parent.frames[1].document.forms[0].child.value = parent.frames[0].document.forms[0].parent.value;
		alert(document.frames("myFrame").document.forms("app_form").elements("app_first_name").value);
	}
	<?php
		$car_demon_pluginpath = CAR_DEMON_PATH;
		$car_demon_pluginpath_images = str_replace('car-demon-forms/forms','',$car_demon_pluginpath);	
		$cd_wp_admin = str_replace('wp-content/plugins/car-demon','',$car_demon_pluginpath);	
	?>

jQuery("#select_stock_txt").autocomplete (
  {
		source: "<?php echo $cd_wp_admin ?>wp-admin/admin-ajax.php?show_stock=2&action=cd_trade_find_vehicle",
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
		source: "<?php echo $cd_wp_admin ?>wp-admin/admin-ajax.php?show_stock=2&action=cd_trade_find_vehicle",
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

</script>
	<style>
	.finance_msg {
		display:none;
		background: #f1cadf;
		margin:10px;
		padding:5px;
		font-weight:bold;
	}
	.cdform {
		font-family:Arial, Helvetica, sans-serif;
		font-size:12px;
	}
	.cdform fieldset {
		margin-top: 10px;
		padding: 5px 0 15px 0;
		border: 1px solid #ADADAD;
		border-left-color: #ECECEC;
		border-top-color: #ECECEC;
		background: #F7F7F7;
	}
	.cdform legend {
		margin-left: 10px;
		padding: 0 2px;
		font: normal 20px Times;
		color: 
		#666;
	}
	.cdform label {
		width: 140px;
		margin: 4px 10px 0 0;
		display: -moz-inline-box;
		display: inline-block;
		text-align: right;
		vertical-align: top;
	}
	.cd-box-title {
		margin-left:5px!important;
	}
	.cdlabel_right {
		width: 190px;
		margin: 4px 10px 0 0;
		display: -moz-inline-box;
		display: inline-block;
		text-align: left;
		vertical-align: top;
	}
	.cd_date {
		font-size:11px;
	}
	span.reqtxt, span.emailreqtxt {
		margin: 3px 0 0 3px;
		font-size: 0.9em;
		display: -moz-inline-box;
		vertical-align: top;
		color:#ff0000;
	}
	ol.cd-ol {
		margin: 0!important;
		padding: 0!important;
	}
	ol.cd-ol li {
		background: none;
		margin: 5px 0;
		padding: 0;
		list-style: none!important;
		text-align: left;
		line-height: 1.3em;
	}
	ol.cf-ol li.cd-box-group {
		margin: 10px 0pt 0px !important;
		padding-left: 100px;
	}
	input.cd-box {
		margin: 2px 8px; 0 0;
		width: 14px;
		height: 22px;
		border: none!important;
		background: none!important;
	}
	label.cd-group-after {
		width: 92px;
		text-align: left;
	}
	label.cd-group-after span {
		width: 92px;
		display: block;
	}
</style>