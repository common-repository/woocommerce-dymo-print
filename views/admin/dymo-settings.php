<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check the user capabilities
	if ( !current_user_can( 'manage_woocommerce' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'woocommerce-dymo' ) );
	}
// Save the field values
	if ( isset( $_POST['dymo_fields_submitted'] ) && $_POST['dymo_fields_submitted'] == 'submitted' ) {
		foreach ( $_POST as $key => $value ) {
			if ( get_option( $key ) != $value ) {
				update_option( $key, $value );
			} else {
				add_option( $key, $value, '', 'no' );
			}
		}
	}
?>
<style>
table td p {padding:0px !important;} table.dymocheck{width:100%;border:1px solid #ccc !important;text-align:center;margin:0 0 20px 0}.dymocheck tr th{border-bottom:1px solid #ccc !important;background:#ccc;}#dymo-debug-log-output {display:block;width:100%;max-width:500px;height:200px;margin:0 0 20px;overflow:auto;border:1px solid #666;padding:10px;box-sizing:border-box;}
#dymo-debug-log-output .error {color:red;}
#dymo-debug-log-output .undefined {color:orange;}
#dymo-debug-log-output .bold {font-weight:700;margin-top:20px;}
.postbox .inside{display:flow-root;}
.postbox.pro { box-sizing: border-box;padding:10px 20px 10px 100px;background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHQAAADICAYAAAA5ksUpAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABI1JREFUeNrs3bFvHEUUgPFZdC0ofSLhCkGVLpQIQZ9/ACrqNKmQIlkKUhA0gYg6kh366OhBupRx5VRBri4SrjmF1tKyc76DOMr51vbu7Ozu75O2ya196/myb9682XtXnBwVZcBgeM8QEApCQSgIBaGEglAQCkJBKKEgFISCUBBKKAgFoSAUhBIKQkEoCAWhhIJQEIommNQ56dVxCPtP013UZ59Wx63Nr3/3S9pB2r2z+bVnB9XxPJ+xqSV0fhwHMd2nDndDsUVomVhosVno87zGRsg1h4JQEApCQSihGEJhoQ7XPgjh5ifbz3vxMoTF6/b/sHgt8ZrOI15HvJ62STk2kyYH8I9fi63nffF1maSy8vDe+QvwZVHgoLqer8ok/7lSjY2Qaw4FoSAUhIJQQkEoCAWhIJRQEApCQSgIJRSEglAQCkIJBaEgFISCUEJBKAgFoSCUUBCKDpkYgvQsWwH8GcKrv07b7r14WYbFP830DCQ0AVHcN9+WK3nt9pggNAFR5DxRN1NzqKQIkqJc5rLjcGYe++33ktC+8ORp7PlThGcHZbIGU4S2yGlL9TKMDVlupuxcD+HDGyHc/Di2liuWveavvb+9xRyhHbLuAbiUd734rz/htpZ2nQtdJiGrSkhcZI9R3O0vm5OWRGhMPNZfebF4XZ4pbY2dKPHxD0WS95o0eRem6GwJc+iF76bTkFjU7nNLaEYZ5M6N4o3ExB06uLSf0Ex5/GPRWiaZM4rz7tB2iCFxvcCOazb0QOj/81jx1hxHRLZC1xkjaT0XGu++Ol9lAUkRCCUUhIJQEApCCQWhIBSEglBCQSgIBaEgFMN9jPPug7Kxx1769CTGYIWO5RPbQi6hIBSEglAQSih6Rq11aPyMyu6dYus5qdh2LSmJHwHZDfmMTXFyVOh0IeSCUBAKQgk1BCNctsStqLvfS4YvS2xJ8PBeRr3+mvpOEQi5IJRQEApCQSgIHT2N7Yfi/PFLhf1QIReEglAQCkIJBaEgFISCUEJBKNIxrY7PfSFsv1lUx6Pq2Jt8VM7jPxDaTw6jyEri3tsvENovosD9SuRs0wmE5k8MpftvhtXzIDRfZqu7ce8iP0RonmE1zo+Hl/lhQvMJq+tsdXGVX0Ro92E13o3Tpn4hod2sHaPA+3WSHELzDqv3o8yrhlVCu2W6CquzFG9GaHthdZ2tzlO+MaHNcrjKVlsNq4SmWTvupwqrhLYXVs/sdOQAoZdbO164JEdonmH10iU5QvNZO8adjp+7SnIIbS6sNlqSI7SbJKe1khyhacNqIzsdhHbLdJWtTof0R41NaGclOUKbD6ut73QQmmbtmEVJjtCrhdXsSnKEXpyNDx8T2r+wmn1JjtDtSU6vSnKEvptZyHing9D6Sc5UWO2/0HkYUEluzEJnoac7HYSeDavrbHVOS3+FRnmjKMkNXWjSh48JbS+sjrYkNyShSnIDEboXRrbTMUShMZTW7geAfIXOgpLcIISu145Kcj0WOg9KcoMQOgtKcr0XOoiHjwlVkhuMUCW5vnNyVPxdHT9Vx47R6D//CjAAdm5duR32Z1gAAAAASUVORK5CYII=') no-repeat 20px top #fff;background-size:auto 100px;}
#poststuff {display:grid;grid-template-columns:minmax(450px,1fr) 400px;grid-gap:1em;}
#labelImageDiv {background:#eee;padding:10px;display:inline-block;}
#labelImage {max-width:100%;height:auto;float:left;}
@media only screen and (max-width:1200px) {
	#poststuff {display:block;}
	#printersInfoContainer {overflow:auto;}
}
@media only screen and (max-width:450px) {
	.postbox.pro {padding:100px 10px 20px;}
	#labelImageDiv {padding:2px;}
}
</style>
<div class="wrap">
	<h2><?php _e( 'WooCommerce - Print DYMO shipping labels', 'woocommerce-dymo' ); ?></h2>
	<?php if ( isset( $_POST['dymo_fields_submitted'] ) && $_POST['dymo_fields_submitted'] == 'submitted' ) { ?>
	<div id="message" class="updated fade"><p><strong><?php _e( 'Your settings have been saved.', 'woocommerce-dymo' ); ?></strong></p></div>
	<?php } ?>

	<div id="content">
			<div id="poststuff">
				<div>
					<div class="postbox">
						<form method="post" action="" id="dymo_settings">
						<input type="hidden" name="dymo_fields_submitted" value="submitted">

						<div class="inside dymo-settings">
						<h3><?php _e( 'General Settings', 'woocommerce-dymo' ); ?></h3>
							<table class="form-table">
								<tbody>
								<tr>
    								<th>
    									<label for="woocommerce_dymo_preview"><b><?php _e( 'Preview:', 'woocommerce-dymo' ); ?></b></label>
    								</th>
    								<td>
    									<div id="labelImageDiv">
											<img id="labelImage" src="<?php echo plugins_url( '../assets/img/icon-loading.gif', dirname(__FILE__) );?>" alt="label preview">
										</div>
										<p class="description"><?php _e('Please reload page if preview is not correct','woocommerce-dymo');?></p>
    								</td>
    							</tr>
								<tr>
    								<th>
    									<label for="woocommerce_dymo_company_name"><b><?php _e( 'Company name:', 'woocommerce-dymo' ); ?></b></label>
    								</th>
    								<td>
    									<input type="text" name="woocommerce_dymo_company_name" id="woocommerce_dymo_company_name" class="regular-text" value="<?php echo stripslashes(get_option( 'woocommerce_dymo_company_name' )); ?>" /><br />
    									<p class="description">
											<?php _e( 'Your company name printed on the bottom left of the label.', 'woocommerce-dymo' ); ?>
											<br>
											<?php printf(__('Note: %s','woocommerce-dymo'),__( 'Leave blank to not print company name.', 'woocommerce-dymo' ));?>
										</p>
    								</td>
    							</tr>
    							<tr>
    								<th>
    									<label for="woocommerce_dymo_company_extra"><b><?php _e( 'Extra info:', 'woocommerce-dymo' ); ?></b></label>
    								</th>
    								<td>
    									<input type=text name="woocommerce_dymo_company_extra" id="woocommerce_dymo_company_extra" cols="45" rows="3" class="regular-text" value="<?php echo stripslashes(get_option( 'woocommerce_dymo_company_extra' )); ?>"/><br />
    									<p class="description">
											<?php _e( 'Extra info printed on the bottom right of the label.', 'woocommerce-dymo' ); ?>
											<br>
											<?php printf(__('Note: %s','woocommerce-dymo'),__( 'Leave blank to not print extra info.', 'woocommerce-dymo' ));?>
    									</p>
										<p class="description">
											<?php printf(__('Use %s to output the order number in the extra info','woocommerce-dymo'),'<code>[order]</code>');?>
										</p>
									</td>
    							</tr>
								</tbody><tfoot>
								<tr>
									<th colspan="2">
										<input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'woocommerce-dymo' ); ?>" />
									</th>
								</tr>
								</tfoot>
							</table>
						</div>
						</form>
					</div>
					<div class="postbox">
						<div class="inside dymo-check">
						<h3><?php _e( 'Debug', 'woocommerce-dymo' ); ?></h3>

							<p><?php _e( 'If you have any problems, please check below data.', 'woocommerce-dymo' );?></p>
							<div id="printersInfoContainer"></div>
							<div class="printControls">
								<button class="button inputbutton" style="float:right" id="updateTableButton"><?php _e( 'Refresh', 'woocommerce-dymo' ); ?></button>
								<button class="button button-primary" id="printButton"><?php _e( 'Print printers information on', 'woocommerce-dymo' ); ?></button>
								<select id="printersSelect"></select>
							</div>
						</div>
					</div>
					<div class="postbox">
						<div class="inside">
							<h3><?php _e( 'DYMO Javascript Framework debug log', 'woocommerce-dymo' ); ?></h3>
							<p><?php _e('DYMO plugin not printing or other problems with this plugin?','woocommerce-dymo'); ?> <a href="https://wordpress.org/support/plugin/woocommerce-dymo-print" target="_blank"><?php _e('Submit a support request and send us the debug log.','woocommerce-dymo');?></a></p>
							<div class="dymo-debug-log">
								<div class="dymo-debug-log-container">
									<ul id="dymo-debug-log-output"></ul>
									<button id="debugClear" class="button"><?php _e('Clear debug log','woocommerce-dymo');?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
                <div>
					<div class="postbox pro">
						<div class="inside umf-preview">
						<h3><?php _e( 'Buy Pro!', 'woocommerce-dymo' ); ?></h3>
							<p><?php printf(__('Check out our %s to find out more about WooCommerce DYMO Print Pro.','woocommerce-dymo'),'<a href="https://wpfortune.com/shop/plugins/woocommerce-dymo-print/">'.__('website','woocommerce-dymo').'</a>');?></p>
							<p><?php _e('For only &euro; 29,00 you will get a lot of features and customer support by our helpdesk staff.', 'woocommerce-dymo' );?></p>
							<p><?php _e('A couple of features:', 'woocommerce-dymo' );?></p>

                            <ul style="list-style:square;padding-left:20px;margin-top:-10px;">
							<li><strong><?php _e('New:', 'woocommerce-dymo' );?></strong> <?php _e('Make label combinations: print multiple labels in 1 click','woocommerce-dymo');?></li>
							<li><?php _e('Print all order data on your label','woocommerce-dymo');?></li>
							<li><?php _e('Print DYMO order and order item labels', 'woocommerce-dymo' );?></li>
							<li><?php _e('Customize your own labels', 'woocommerce-dymo' );?></li>
							<li><?php _e('Choose your label size and layout', 'woocommerce-dymo' );?></li>
							<li><?php _e('Print any image on your label', 'woocommerce-dymo' );?></li>
							<li><?php _e('Use a DYMO Labelwriter 450 Twin Turbo', 'woocommerce-dymo' );?></li>
							<li><?php _e('Bulk printing', 'woocommerce-dymo' );?></li>
							<li><?php _e('Barcode & QRcode printing (for POS systems for example)','woocommerce-dymo');?></li>
							<li><?php _e('Print Product list', 'woocommerce-dymo' );?></li>
							<li><?php _e('Create and upload your own labels', 'woocommerce-dymo' );?></li>
							</ul>
						</div>
					</div>
				</div>

			</div>
	</div>
</div>
