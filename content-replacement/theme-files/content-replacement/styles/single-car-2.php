<?php
	$post_id = get_the_ID();

	//= Find out which of the default fields are hidden
	$show_hide = get_show_hide_fields();

	//= Get the labels for the default fields
	$field_labels = get_default_field_labels();

	//= Get the basic car object
	$car = car_demon_get_car($post_id);
	/*
	Here is a  sample of the fields that are available from car_demon_get_car($post_id) function
	All custom vehicle options will be available
		$_vin_value = $car["_vin_value"];
		$stock_number = $car["stock_number"];
		$mileage = $car["mileage"];
		$msrp = $car["msrp"];
		$price = $car["price"];
		$vehicle_condition = $car["condition"];
		$decoded_model_year = $car["decoded_model_year"];
		$decoded_make = $car["decoded_make"];
		$decoded_model = $car["decoded_model"];
		$exterior_color = $car["exterior_color"];
		$interior_color = $car["interior_color"];
		$decoded_body_style = $car["decoded_body_style"];
		$decoded_engine_type = $car["decoded_engine_type"];
		$decoded_transmission_long = $car["decoded_transmission_long"];
		$decoded_driveline = $car["decoded_driveline"];
		$decoded_tank = $car["decoded_tank"];
		$decoded_overall_length = $car["decoded_overall_length"];
		$decoded_Trim Level = $car["decoded_Trim Level"];
		$decoded_ Production Seq. Number = $car["decoded_ Production Seq. Number"];
		$decoded_ Exterior Color = $car["decoded_ Exterior Color"];
		$decoded_ Interior Color = $car["decoded_ Interior Color"];
		$vin = $car["vin"];
		$year = $car["year"];
		$make = $car["make"];
		$model = $car["model"];
		$body_style = $car["body_style"];
		$location = $car["location"];
		$fuel = $car["fuel"];
		$transmission = $car["transmission"];
		$cylinders = $car["cylinders"];
		$engine = $car["engine"];
		$doors = $car["doors"];
		$trim = $car["trim"];
		$warranty = $car["warranty"];
		$title = $car["title"];
		$title_slug = $car["title_slug"];
		$car_link = $car["car_link"];

	*/
	//= Let's set some of the common fields to variables
	if (isset($car['_vin_value'])) $vin = $car['_vin_value'];
	if (isset($car['title'])) $car_title = $car['title'];
	if (isset($car['title_slug'])) $car_head_title = $car['title_slug'];
	if (isset($car['car_link'])) $car_url = $car['car_link'];
	if (isset($car["condition"])) $vehicle_condition = $car["condition"];
	//= Let's clean up one of the fields that stores a slug
	if (isset($car['location'])) $location = $car['location'];
	$location = str_replace('-', ' ', $location);
	$location = ucwords($location);
	//= Since this is the single car page we want to get all the details for this vehicle
	//= This includes all vehicle option data in an array
	$vehicle_details = get_post_meta($post_id, 'decode_string', true);
	//= We aren't using detail output for content replacement so set it to a blank value
	$detail_output = '';
	//= Now let's get the contact information array for this vehicle
	$car_contact = get_car_contact($post_id);
	//= Let's set a couple of the contact fields to variables
	$contact_trade_url = $car_contact['trade_url'];
	$contact_finance_url = $car_contact['finance_url'];
	//===============================================================
	//= Find out which of the default fields are hidden
	$show_hide = get_show_hide_fields();
	//= Get the labels for the default fields
	$field_labels = get_default_field_labels();
	//= Since we're using the lightbox we need to include the starter function for it
	echo car_demon_photo_lightbox();
?>
<style>
	.vehicle_container {
		width: 100%;
	}
	.single-vehicle {
		width: 100%;
		max-width:960px;
		min-width: 260px;
		margin-left: auto;
		margin-right: auto;
		display: block;
	}
	.vehicle_images {
		max-width:470px;
		min-width: 260px;
		float:left
	}
	.vehicle_details {
		max-width:470px;
		min-width: 260px;
		float:left
	}
	.vehicle_main_image {
		max-width:470px;
		min-width:260px;
		width: 100%;
		height:360px;
		float:left
	}
	.vehicle_thumbnail_images {
		max-width:470px;
		min-width: 260px;
		height:90px;
		float:left
	}
	.table_details {
		border: #000000 1px solid;
	}
	.vehicle_details_heading {
		background:#365a91;
		padding: 3px;
		color: #fff;
		font-size:1.5em;
	}
	.detail_label {
		background: #cccccc;
		width: 35%;
	}
	.detail_value {
		width: 65%;
	}
	.vehicle_content {
		width: 100%;
		padding: 5px;
	}
	.single_vehicle_widget_content {
		width: 100%;
		padding: 5px;
		clear: both;
	}
	.table_details td {
		padding: 3px;	
	}
	.detail_value.detail_price {
		border: solid 1px #000;	
	}
	.nohor {
		width: 100% !important;	
		max-width: 455px;
		overflow: scroll;
		height: 80px;
		overflow-x: scroll;
		overflow-y: hidden;
		white-space: nowrap;
	}
	.nohor img {
		width: 75px;
		display: inline-block;
		padding: 3px;
	}
	.car_main_photo_box #look_close {
		max-width: 460px !important;
		max-height: 100% !important;
		width: 100% !important;
	}
	.car_demon_main_photo {
		width: 100% !important;
		max-width: 460px;
		/* height: 100% !important; */
		max-height: 400px;
	}
</style>
<?php
	echo car_demon_email_a_friend($post_id, $car['stock_number']);
?>
<div class="vehicle_container">
    <div id="single_vehicle_widget_content" class="single_vehicle_widget_content">
        <?php
            do_action( 'car_demon_vehicle_header_sidebar' );
        ?>
    </div>

    <div class="single-vehicle" id="single-vehicle">
        <div class="vehicle_images" id="vehicle_images">
			<?php
            echo car_photos($post_id, $detail_output, $vehicle_condition);
            ?>
        </div>
        <div class="vehicle_details" id="vehicle_details">
            <table id="table_details" class="table_details" align="center" width="100%" cellpadding="2" cellspacing="1">
              <tr>
                <td bgcolor="#365a91" class="vehicle_details_heading" colspan="2">
                  Specifications
                </td>
              </tr>
              <?php
			  if ($show_hide['stock_number'] != true && !empty($car['stock_number'])) {
				  ?>
                  <tr>
                    <td class="detail_label detail_stock_number"><b><?php echo $field_labels['stock_number']; ?></b></td>
                    <td class="detail_value detail_stock_number"> <?php echo $car['stock_number']; ?> </td>
                  </tr>
				  <?php
			  }
			  if ($show_hide['vin'] != true && !empty($car['vin'])) {
				  ?>
				  <tr>
					<td class="detail_label detail_location"><b><?php echo $field_labels['vin']; ?></b></td>
					<td class="detail_value detail_location"> <?php echo $car['vin']; ?>  </td>
				  </tr>
				  <?php
			  }
			  if ($show_hide['location'] != true && !empty($car['location'])) {
				  ?>
				  <tr>
					<td class="detail_label detail_location"><b><?php echo __('Location', 'car-demon'); ?></b></td>
					<td class="detail_value detail_location"> <?php echo $location; ?>  </td>
				  </tr>
				  <?php
			  }
			  if ($show_hide['condition'] != true && !empty($car['condition'])) {
				  ?>
				  <tr>
					<td class="detail_label detail_condition"><b><?php echo $field_labels['condition']; ?></b></td>
					<td class="detail_value detail_condition"> <?php echo $car['condition']; ?>  </td>
				  </tr>
				  <?php
				  }
			  ?>
			<?php
            foreach($car as $key=>$car_option) {
                $label = trim($key);
                if (strpos($label,'decoded_') !== false) {
                    $key_name = str_replace('_', ' ', $key);
                    $key_name = str_replace('decoded ', '', $key_name);
                    $key_name = ucwords($key_name);
					$key = str_replace('decoded_','',$key);
					$key = str_replace('model_','',$key);
					if (isset($show_hide[$key])) {
						if ($show_hide[$key] != true && !empty($car_option)) {
							echo '<tr>';
								if (isset($field_labels[$key])) {
									echo '<td class="detail_label detail_'.$key.'"><b>'.$field_labels[$key].'</b></td>';
								} else {
									echo '<td class="detail_label detail_'.$key.'"><b>'.$key_name.'</b></td>';
								}
								echo '<td class="detail_value detail_'.$key.'"> '.$car_option.' </td>';
							echo '</tr>';
						}
					}
                }
            }
			if ($show_hide['price'] != true) {
				?>
				<tr>
                    <!--td class="detail_label detail_price"><b>Price</b></td-->
                    <td class="detail_value detail_price" colspan="2"><span id='spanSalePrice' name='spanSalePrice'><?php echo get_vehicle_price($post_id); ?> </span>&nbsp;<span id='spanCurrencyDropDown'></span>&nbsp;<span id='spanPriceEnterAs' name='spanPriceEnterAs'></span></td>
				</tr>
				<?php
			}

            $car_demon_pluginpath = CAR_DEMON_PATH;
            ?>
            </table>
        </div>
        <div class="car_buttons_div">
        <?php if (!empty($contact_finance_url)) { 
                if ($car_contact['finance_popup'] == 'Yes') {
                ?>
                <div class="featured-button">
                    <p><a onclick="window.open('<?php echo $contact_finance_url .'?stock_num='.$vehicle_details['stock_number']; ?>&sales_code=<?php echo $car_contact['sales_code']; ?>','finwin','width=<?php echo $car_contact['finance_width']; ?>, height=<?php echo $car_contact['finance_height']; ?>, menubar=0, resizable=0')"><?php _e('GET FINANCED', 'car-demon'); ?></a></p>
                </div>
                <?php 
                }
                else {
                ?>
                <div class="featured-button">
                    <p><a href="<?php echo $contact_finance_url .'?stock_num='.$vehicle_details['stock_number']; ?>&sales_code=<?php echo $car_contact['sales_code']; ?>"><?php _e('GET FINANCED', 'car-demon'); ?></a></p>
                </div>
        <?php 
                }
            } 
            if (!empty($contact_trade_url)) {
            ?>
                <div class="featured-button">
                    <p><a <?php echo 'href="'.$contact_trade_url .'?stock_num='.$vehicle_details['stock_number']; ?>&sales_code=<?php echo $car_contact['sales_code']; ?>"><?php _e('TRADE-IN QUOTE', 'car-demon'); ?></a></p>
                </div>
        <?php
            }
        ?>
            <div class="email_a_friend">
                <a href="http://www.facebook.com/share.php?u=<?php echo $car_url; ?>&amp;t=<?php echo $car_head_title; ?>" target="fb_win">
                    <img title="<?php _e('Share on Facebook', 'car-demon'); ?>" src="<?php echo $car_demon_pluginpath; ?>theme-files/images/social_fb.png" />
                </a>
                <a target="tweet_win" href="http://twitter.com/share?text=Check out this <?php echo $car_head_title; ?>" title="<?php _e('Click to share this on Twitter', 'car-demon'); ?>">
                    <img title="<?php _e('Share on Twitter', 'car-demon'); ?>" src="<?php echo $car_demon_pluginpath; ?>theme-files/images/social_twitter.png" />
                </a>
                <img onclick="email_friend();" title="<?php _e('Email to a Friend', 'car-demon'); ?>" src="<?php echo $car_demon_pluginpath; ?>theme-files/images/social_email.png" />
            </div>
        </div>
        <div id="single_vehicle_widget_content" class="single_vehicle_widget_content">
			<?php
	            do_action( 'car_demon_vehicle_sidebar' );
			?>
        </div>
        <div id="vehicle_content" class="vehicle_content">
			<?php
                echo car_demon_vehicle_detail_tabs($post_id, true);
            ?>
        </div>
    </div>
</div>