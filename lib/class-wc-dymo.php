<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WPF_DYMO {
	/*
     * When class is called, perform base actions
     *
     * @params string $settings_plugin_name The plugin name
     * @params string $settings_plugin_version The plugin version
     * @params string $settings_plugin_slug The plugin slug
     * @params string $settings_plugin_file The plugin file
     * @params string $settings_plugin_dir The plugin directory
     * @params string $settings_upgrade_url The plugin upgrade url
     * @params string $settings_renew_url The plugin renew subscription url
     * @params string $settings_docs_url The plugin docs url
     * @params string $settings_support_url The plugin support url
     */
	 
	public $plugin_name;
	public $plugin_slug;
	public $plugin_id;
	public $plugin_file;
	public $plugin_dir;
	public $plugin_docs_url;
	public $plugin_support_url;
	public $version;
	public $plugin_url;

    public function __construct($settings_plugin_name, $settings_plugin_version, $settings_plugin_id, $settings_plugin_slug, $settings_plugin_dir, $settings_plugin_file, $settings_upgrade_url, $settings_renew_url, $settings_docs_url, $settings_support_url)
    {
		
        // Set plugin wide properties
        $this->plugin_name = $settings_plugin_name;
        $this->plugin_slug = $settings_plugin_slug;
        $this->plugin_id = $settings_plugin_id;
        $this->plugin_file = $settings_plugin_file;
        $this->plugin_dir = $settings_plugin_dir;
        $this->plugin_docs_url = $settings_docs_url;
        $this->plugin_support_url = $settings_support_url;
        $this->plugin_url = plugins_url($this->plugin_id).'/';
        $this->version = $settings_plugin_version;
		
		add_filter('plugin_action_links_'.$this->plugin_file, array($this, 'plugin_links'));

        add_action('plugins_loaded', array($this, 'translation_load_textdomain'));

        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }

        if (!in_array('woocommerce/woocommerce.php',get_option('active_plugins')) && !is_plugin_active_for_network( 'woocommerce/woocommerce.php' )) {

            add_action('admin_notices', array($this,'show_admin_messages'));

        } elseif (is_admin()) {
			
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
			add_action('woocommerce_admin_order_actions_end', array($this,'alter_order_actions'), 99);
			add_action('admin_menu', array($this,'create_admin_menu'));
			add_action('add_meta_boxes', array($this,'create_meta_box'));
			add_action('admin_notices', array($this,'create_error_container'));
			
			//AJAX calls
			add_action( 'wp_ajax_dymo_get_order_data', array($this,'get_order_data') ); 
        }
  
    }
	
	public function plugin_links($links) {

		$new = array(
           'docs' => '<a href="'.$this->plugin_docs_url.'" target="_blank">'.__('Docs', $this->plugin_slug).'</a>',
           'support' => '<a href="'.$this->plugin_support_url.'" target="_blank">'.__('Support', $this->plugin_slug).'</a>',
           'settings' => '<a href="'.admin_url('admin.php?page='.$this->plugin_slug).'">'.__('Settings', $this->plugin_slug).'</a>',

        );
		
		return wp_parse_args($links, $new);
	}
	
	public function translation_load_textdomain()
    {
        load_plugin_textdomain($this->plugin_id, false, dirname( $this->plugin_file) . '/languages/');
    }
	
	/*
	* Load scripts and styles on specific admin pages
	*/
	public function enqueue_scripts($hook) {
		$current_screen=get_current_screen();
		
		$version=filemtime(__FILE__);
		wp_register_script( 'woocommerce-dymo-print', plugins_url( '/assets/js/dymo.connect.framework.js', dirname(__FILE__) ),array('jquery'),$version);
	
		if(in_array($hook,array('edit.php','post.php')) && $current_screen->post_type=='shop_order') {
		
			$dymo_data=array(
				'ajax_nonce'=>wp_create_nonce('dymo_nonce'),
				'template'=>plugins_url( 'views/labels/label.label' , dirname(__FILE__) ),
				'company'=>get_option('woocommerce_dymo_company_name'),
				'extra'=>nl2br(stripslashes(get_option('woocommerce_dymo_company_extra'))),
				'no_printers_installed'=>sprintf(__('No printers are installed or recognized by your browser. Please see %s for more information.', 'woocommerce-dymo'),'<a href="https://wpfortune.com/documentation/plugins/woocommerce-dymo-print/" target="_blank">'.__('our documentation','woocommerce-dymo').'</a>'),
				'no_printers_connected'=>esc_html__('DYMO Label Writer is not connected. Please re-connect your DYMO Label Writer.', 'woocommerce-dymo'),
				'general_error'=>esc_html__('There was an error while printing your label.', 'woocommerce-dymo'),
				'no_framework'=>esc_html__('Uncaught Error: DYMO Label Framework is not installed. Please check DYMO Web Service','woocommerce-dymo'),
				'label_printed'=>esc_html__('1 DYMO label printed.','woocommerce-dymo'),
				'order'=>esc_html__('Order:','woocommerce-dymo'),
				'job_title'=>__('WooCommerce DYMO Print','woocommerce-dymo-print'),
			);
			
			wp_register_script( 'woocommerce-dymo-js', plugins_url( '/assets/js/woocommerce-dymo.js', dirname(__FILE__) ),array('jquery','wp-a11y'),$version );
			wp_localize_script('woocommerce-dymo-js','dymo_data',$dymo_data);
			wp_enqueue_script( 'woocommerce-dymo-js');
			
			wp_enqueue_script( 'woocommerce-dymo-print');
			
			wp_enqueue_style('woocommerce-dymo',plugins_url( '/assets/css/woocommerce-dymo.css', dirname(__FILE__) ),array(),$version );
		
		}
		if($current_screen->base=='woocommerce_page_wc_dymo') {
			wp_enqueue_script( 'woocommerce-dymo-print');
			
			wp_register_script( 'woocommerce-dymo-settings', plugins_url( '/assets/js/woocommerce-dymo-settings.js', dirname(__FILE__) ),array('jquery','woocommerce-dymo-print'),$version );
			
			$last_order=wc_get_orders(array('limit' => 1, 'return' => 'ids'));
			$order=new WC_Order($last_order[0]);
			$shipping_address=$order->get_formatted_shipping_address();
			
			$settingsJS = array(
				'security'=>wp_create_nonce( "wc-dymo-ajax" ),
				'autocut'=>__('The printer supports auto-cut','woocommerce-dymo'),
				'no_autocut'=>__('The printer does NOT supports auto-cut','woocommerce-dymo'),
				'twinturbo'=>__('The printer is TwinTurbo','woocommerce-dymo'),
				'no_twinturbo'=>__('The printer is NOT TwinTurbo','woocommerce-dymo'),
				'printer'=>__('Printer:','woocommerce-dymo'),
				'printerType'=>__('Printer type','woocommerce-dymo'),
				'is_local'=>__('Local printer','woocommerce-dymo'),
				'local'=>__('Local','woocommerce-dymo'),
				'connected'=>__('Connected','woocommerce-dymo'),
				'modelname'=>__('Model name','woocommerce-dymo'),
				'printername'=>__('Printer name','woocommerce-dymo'),
				'is_online'=>__('Printer connected:','woocommerce-dymo'),
				'isBrowserSupported'=>__('Browser supports DYMO Framework:','woocommerce-dymo'),
				'isFrameworkInstalled'=>__('DYMO Framework is installed:','woocommerce-dymo'),
				'isWebServicePresent'=>__('DYMO WebService is running:','woocommerce-dymo'),
				'errorDetails'=>__('Error details:','woocommerce-dymo'),
				'ServiceDiscovery'=>sprintf(__('Error: No connection to DYMO Web Service. Check if DYMO Web Service is installed and running. For more information see %s.','woocommerce-dymo'),'<a href="https://wpfortune.com/documentation/plugins/woocommerce-dymo-print/dymo-web-service/#debuginformation" target="_blank">'.__('our documentation','woocommerce-dymo')),
				'environmentCheck'=>__('DYMO Javascript Framework environment check...','woocommerce-dymo'),
				'pluginVersion'=>sprintf(__('WooCommerce DYMO Print plugin FREE version: %s','woocommerce-dymo'),$this->version),
				'frameworkVersion'=>__('DYMO Javascript Framework version:','woocommerce-dymo'),
				'debugmode'=>1,
				'debugmodeTxt'=>__('Debug mode is active','woocommerce-dymo'),
				'noFramework'=>sprintf(__('DYMO Javascript Framework not active or DYMO Web Service not running on your system. Make sure DYMO Label Software version 8.7.2 or higher is installed. For more information see %s.','woocommerce-dymo'),'<a href="https://wpfortune.com/documentation/plugins/woocommerce-dymo-print/debug-dymo-framework/" target="_blank">'.__('our documentation','woocommerce-dymo')),
				'clearCache'=>__('This might be a caching issue. Clear your browser history and use CTRL+F5 (Windows) or Cmd+Shift+R (Mac) to Hard Refresh this page.','woocommerce-dymo'),
				'seeDebugInfo'=>__('See debug information in documentation.','woocommerce-dymo'),
				'template'=>plugins_url( 'views/labels/label.label' , dirname(__FILE__) ),
				'last_address'=> preg_replace('/<br(\s+)?\/?>/i', "\n", $shipping_address),
				'companyText'=>preg_replace('/<br(\s+)?\/?>/i', "\n", esc_attr(get_option('woocommerce_dymo_company_name'))),
				'extraText'=>preg_replace('/<br(\s+)?\/?>/i', "\n", esc_attr(get_option('woocommerce_dymo_company_extra'))),
			);
			
			wp_localize_script( 'woocommerce-dymo-settings', 'objects', $settingsJS );
			wp_enqueue_script( 'woocommerce-dymo-settings');
		}
	}

	/*
	* Admin messages
	* Since 1.2
	*/
	public function showDymoMessage($message, $errormsg = false)
	{
		if ($errormsg) { 
			echo '<div id="message" class="error">';
		} else {
			echo '<div id="message" class="updated fade">';
		}
		echo "<p>$message</p></div>";
		
	} 
	   
	
	public function show_admin_messages() {
		$this->showDymoMessage(__( 'WooCommerce is not active. Please activate WooCommerce plugin before using WooCommerce DYMO Print plugin.', 'woocommerce_dymo'), true);
	}
	
	
	/*
	* WordPress Administration Menu
	*/
	public function create_admin_menu() {
		add_submenu_page('woocommerce', $this->plugin_name, __( 'DYMO Print', $this->plugin_id), 'manage_woocommerce', $this->plugin_slug, array($this, 'admin_render'));
	}
	
    /*
     * Renders the admin page
     *
     * @return void
     */
    public function admin_render()
    {
        include_once($this->plugin_dir . 'views/admin/dymo-settings.php');
    }
	
	/*
	* Create order meta box
	*/
	public function create_meta_box() {
		add_meta_box( 'woocommerce-dymo-box', __( 'Print DYMO labels', 'woocommerce-dymo' ), array($this,'render_meta_box'), 'shop_order', 'side', 'default' );
	}
	
	/* 
	*Create order meta box content 
	*/
	public function render_meta_box() {
		global $post_id;
		$order = new WC_Order($post_id);
		?>
		
		<a class="button dymo-link" data-dymo-id="<?php echo $order->get_ID();?>" href="#"><?php _e('Print Shipping Label', 'woocommerce-dymo'); ?></a>
		<span class="animate dymo-progress dymo-progress-<?php echo $order->get_ID();?>" data-dymo-id="<?php echo $order->get_ID();?>"><span></span></span>
		
		<div class="wc-dymo-errors order_notes wc-dymo-hidden"></div>
	
		<?php 
	}
	
	/*
	* Convert address to javascript string
	* @since 1.1.1
	*/
	public function sanitize_address($address) {
		$address= preg_replace('/<br\/(\s+)?\/?>/i', "|", preg_replace("/[\n\r]/","|",$address));
		$address= preg_replace('/<br(\s+)?\/?>/i', "", preg_replace("/[\n\r]/","|",$address));
		$address= str_replace("||","|",$address);
		$address=rtrim(str_replace("'", "\'", htmlspecialchars_decode($address,ENT_QUOTES)),"|");
		return stripslashes($address);
	}
	
	/*
	* Template for default labels
	*
	* @Removed: 3.0.0
	*
	public function get_label_template() {
		return '<DieCutLabel Version="8.0" Units="twips">
		<PaperOrientation>Landscape</PaperOrientation>
		<Id>LargeAddress</Id>
		<PaperName>30321 Large Address</PaperName>
		<DrawCommands>
			<RoundRectangle X="0" Y="0" Width="2025" Height="5020" Rx="270" Ry="270" />
		</DrawCommands>
		<ObjectInfo>
			<TextObject>
				<Name>EXTRA</Name>
				<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
				<BackColor Alpha="0" Red="255" Green="255" Blue="255" />
				<LinkedObjectName></LinkedObjectName>
				<Rotation>Rotation0</Rotation>
				<IsMirrored>False</IsMirrored>
				<IsVariable>False</IsVariable>
				<HorizontalAlignment>Right</HorizontalAlignment>
				<VerticalAlignment>Top</VerticalAlignment>
				<TextFitMode>None</TextFitMode>
				<UseFullFontHeight>True</UseFullFontHeight>
				<Verticalized>False</Verticalized>
				<StyledText>
					<Element>
						<String> </String>
						<Attributes>
							<Font Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />
							<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
						</Attributes>
					</Element>
				</StyledText>
			</TextObject>
			<Bounds X="2812" Y="1715" Width="1958" Height="225" />
		</ObjectInfo>
		<ObjectInfo>
			<AddressObject>
				<Name>ADDRESS</Name>
				<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
				<BackColor Alpha="0" Red="255" Green="255" Blue="255" />
				<LinkedObjectName></LinkedObjectName>
				<Rotation>Rotation0</Rotation>
				<IsMirrored>False</IsMirrored>
				<IsVariable>True</IsVariable>
				<HorizontalAlignment>Left</HorizontalAlignment>
				<VerticalAlignment>Top</VerticalAlignment>
				<TextFitMode>ShrinkToFit</TextFitMode>
				<UseFullFontHeight>True</UseFullFontHeight>
				<Verticalized>False</Verticalized>
				<StyledText>
					<Element>
						<String>adres</String>
						<Attributes>
							<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
							<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
						</Attributes>
					</Element>
				</StyledText>
				<ShowBarcodeFor9DigitZipOnly>False</ShowBarcodeFor9DigitZipOnly>
				<BarcodePosition>AboveAddress</BarcodePosition>
				<LineFonts>
					<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
					<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
					<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
					<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
					<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
				</LineFonts>
			</AddressObject>
			<Bounds X="337" Y="165" Width="4455" Height="1220" />
		</ObjectInfo>
		<ObjectInfo>
			<TextObject>
				<Name>COMPANY</Name>
				<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
				<BackColor Alpha="0" Red="255" Green="255" Blue="255" />
				<LinkedObjectName></LinkedObjectName>
				<Rotation>Rotation0</Rotation>
				<IsMirrored>False</IsMirrored>
				<IsVariable>False</IsVariable>
				<HorizontalAlignment>Left</HorizontalAlignment>
				<VerticalAlignment>Top</VerticalAlignment>
				<TextFitMode>None</TextFitMode>
				<UseFullFontHeight>True</UseFullFontHeight>
				<Verticalized>False</Verticalized>
				<StyledText>
					<Element>
						<String> </String>
						<Attributes>
							<Font Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />
							<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
						</Attributes>
					</Element>
				</StyledText>
			</TextObject>
			<Bounds X="322" Y="1670" Width="3165" Height="270" />
		</ObjectInfo></DieCutLabel>';

	}
	*/
	
	/* 
	* Admin notice for DYMO Print version 2.0
	*
	* @Removed since 3.0.0
	*
	public function update_notice() {
		?>
		<div class="notice error dymo-update-notice is-dismissible" >
			<p><?php _e( 'WooCommerce DYMO Print 2.0 does not work with older versions of DYMO Label Software (DLS). Please update your DYMO Label Software (DLS) to version 8.7.2. or above.', 'woocommerce-dymo' ); ?> <a href="https://wordpress.org/plugins/woocommerce-dymo-print/installation/" target="_blank"><?php _e('Read installation instructions','woocommerce-dymo');?></a></p>
		</div>
		<?php
	}
	*/
	
	/* 
	* Close admin notice and do not show it again
	*
	* @Removed since 3.0.0
	*
	public function dismiss_update_notice() {
		update_option( 'woocommerce_dymo_update_notice', 1);	
		return true;
		die();
	}
	
	*/
	
	
	/* 
	* Show action buttons in order overview table 
	*/
	public function alter_order_actions($order) {
		if (empty($order)) {
			return;
		}
		?>
		<a class="button wc-action-button wc-action-button-dymo dymo-link tips" data-dymo-id="<?php echo $order->get_ID();?>" style="text-align:center;" data-tip="<?php _e('Print Shipping Label', 'woocommerce-dymo'); ?>" href="#"><img src="<?php echo plugins_url( 'assets/img/icon-print-shipping.png' , dirname(__FILE__) ); ?>" style="margin:0 20%;width:60%;object-fit:contain;height:100%;" alt="<?php _e('Print Shipping Label', 'woocommerce-dymo'); ?>" width="14"></a>
		<span class="animate dymo-progress dymo-progress-<?php echo $order->get_ID();?>" data-dymo-id="<?php echo $order->get_ID();?>"><span></span></span>
		<?php
	}
	
	/* 
	* AJAX call to get order data (address) from specific order
	*/
	public function get_order_data() {
		check_ajax_referer( 'dymo_nonce', 'security' );
		$order_id=$_GET['order_id'];
		
		$order = new \WC_Order($order_id);
		
	
		if($order->get_formatted_shipping_address()!="") { 
			$address = $order->get_formatted_shipping_address(); 
		} else { 
			$address = $order->get_formatted_billing_address(); 
		}
		$address=html_entity_decode($address,ENT_COMPAT,'UTF-8'); 
				
		$return=array(
			'address'=>$this->sanitize_address($address),
			'order_number'=>$order->get_order_number(),
		);
		
		wp_send_json($return);
	
		die();
	}
	
	/*
	* Show errors above order table 
	*/
	function create_error_container() {
		$current_screen=get_current_screen();
		if($current_screen->id=='edit-shop_order') {
			echo '<div class="error wc-dymo-errors wc-dymo-hidden"></div>';
		}
	}

}