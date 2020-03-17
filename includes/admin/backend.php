<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Woo Floating Minicart Backend
 *
 * Allows admin to set WooCommerce Floating Minicart of specific product.
 *
 * @class   Floting_Mini_Cart 
 */

class Floting_Mini_Cart {

	/**
	 * Init and hook in the integration.
	 *
	 * @return void
	 */

	public function __construct() {
		$this->id                 = 'Floting_Mini_Cart';

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'settings_tab'), 50 );
        add_action( 'woocommerce_settings_tabs_floating_minicart', array( $this, 'add_settings_tab') );
        add_action( 'woocommerce_update_options_floating_minicart', array( $this, 'fmc_update_settings') );
		
		//add custom type
        add_action( 'woocommerce_admin_field_fmc_section_title', array( $this,'output_fmc_section_title'), 100, 1 );

		
	}

	public static function output_fmc_section_title($value){
	        ?>
        	<tr valign="top">
						<th scope="row" class="titledesc fmc-section-title" colspan="2">					
							<h2><?php echo $value['title']; ?></h2>
						</th>						
			</tr>
        <?php
    }

	 /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function settings_tab( $settings_tabs ) {
        $settings_tabs['floating_minicart'] = __( 'Floating Minicart', 'minicartt' );
        return $settings_tabs;
    }

    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::fmc_get_settings()
     */
    public static function add_settings_tab() {
        woocommerce_admin_fields( self::woo_floating_setting_mini_cart() );
    }

    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::fmc_get_settings()
     */
    public static function fmc_update_settings() {
        woocommerce_update_options( self::woo_floating_setting_mini_cart() );
    }
   
	/**
	 * Loading  floating minicart setting to the woocommerce general product product admin setting section.
	 *
	 * @return array
	 */


	public static function woo_floating_setting_mini_cart(){    
	   
		
		$settings[] = array( 
			'name' => __( 'Minicart Setting', 'minicartt' ), 
			'type' => 'title', 
			'desc' => __('This setting display title.', 'minicartt'), 
			'id' => 'Woo_floating_minicart_title' 
		);

		$settings[] = array(
                'name' => __( 'Position', 'woocommerce-settings-tab-demo' ),
                'type' => 'fmc_section_title',
                'desc' => __('Set position of the floating cart', ''),
                'id'   => 'wc_settings_tab_demo_fmc_section_title'
            );
					
		$settings[] = array(
			'title'    	=> __( 'Position', 'minicartt' ),
			'css'       => 'min-width:350px;',
			'id'       	=> 'Woo_floating_minicart_position',
			'desc'  	=> __( 'Floating minicart position', 'minicartt' ),
			'type' => 'select',  
                  'options' => array( 
                      '' => __( 'Select Minicart Position', 'minicartt' ),  
                      'floating-left' => __( 'Float Minicart left', 'minicartt' ),  
					  'floating-right' => __( 'Float Minicart right', 'minicartt' ),   
 				),  
             'desc_tip' =>  true, 
			
		);

		$settings[] = array(
			'title'    	=> __( 'Offset from top (%)', 'minicartt' ),
			'css'       => 'width: 95px;',
			'id'       	=> 'Woo_floating_minicart_offset',
			'desc'  	=> __( 'Set desired offset from top in %', 'minicartt' ),
			'type'     	=> 'number',
			'default'	=> '',
			'desc_tip'	=> true,
			'placeholder' => __( '50', 'minicartt' ),
		);
		/**
		 * Border radius
		 */
		$settings[] = array(
			'title'    	=> __( 'Border Radius (%)', 'minicartt' ),
			'css'       => 'width: 95px;',
			'id'       	=> 'border_radius_percent',
			'desc'  	=> __( 'Set desired offset from top in %', 'minicartt' ),
			'type'     	=> 'number',
			'default'	=> '',
			'desc_tip'	=> true,
			'placeholder' => __( '50', 'minicartt' ),
		);

		$settings[] = array(
                'name' => __( 'Background color', 'woocommerce-settings-tab-demo' ),
                'type' => 'fmc_section_title',
                'desc' => __('Set color hex codes to the respective section', ''),
                'id'   => 'wc_settings_tab_demo_fmc_section_title'
            );

		$settings[] = array(
			'title'    	=> __( 'Primary Background', 'minicartt' ),
			'css'      => 'width:70px;',
			'id'       	=> 'Woo_floating_minicart_primary_color',
			'desc'  	=> __( 'Select/paste minicart primary color', 'minicartt' ),
			'type'     	=> 'color',
			'default'	=> '',
			'desc_tip'	=> true,
			'placeholder' => __( '#42a2ce', 'minicartt' ),
		);

		$settings[] = array(
			'title'    	=> __( 'Secondary Background', 'minicartt' ),
			'css'      => 'width:70px;',
			'id'       	=> 'Woo_floating_minicart_secondary_color',
			'desc'  	=> __( 'Select/paste floating minicart secondary color and also depandent to button hover color', 'minicartt' ),
			'type'     	=> 'color',
			'default'	=> '',
			'desc_tip'	=> true,
			'placeholder' => __( '#3c3c3c', 'minicartt' ),
		);
		$settings[] = array(
			'name' => __( 'Image link', 'woocommerce-settings-tab-demo' ),
			'type' => 'text',
			'desc' => __('https://img.icons8.com/officel/16/000000/float.png', ''),
			'id'   => 'wc_settings_tab_demo_fmc_section_text'
		);

		$settings[] = array(
			'title'    	=> __( 'Button Background', 'minicartt' ),
			'css'      => 'width:70px;',
			'id'       	=> 'Woo_floating_minicart_button_color',
			'desc'  	=> __( 'Select/paste floating minicart button color', 'minicartt' ),
			'type'     	=> 'color',
			'default'	=> '',
			'desc_tip'	=> true,
			'placeholder' => __( '#71b02f', 'minicartt' ),
		);

		$settings[] = array(
			'name' => __( 'Empty cart setting', 'woocommerce-settings-tab-demo' ),
			'type' => 'fmc_section_title',
			'desc' => __('Set color hex codes to the respective section', ''),
			'id'   => 'wc_settings_tab_demo_fmc_section_title'
		);
	
			
		
		//shop page url
		
		if(function_exists('wc_get_page_id')){
				//for new version (<3.0.0) WooCommerce
				$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) ); 
			} else {
				// for old version ( >3.0.0 )WooCommerce
				$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) ); 
			}		
		$settings[] = array(
			'title'    	=> __( 'Show Shop page link while cart is empty.', 'minicartt' ),			
			'id'       	=> 'floating_shop_page_minicart',
			'desc'  	=> __( 'Check for "Yes". '.$shop_page_url, 'minicartt' ),
			'type'     	=> 'checkbox',
			'default'	=> '',
			'desc_tip'	=> true,			
		);


		// best selling popular products
		$settings[] = array(
			'title'    	=> __( 'Show best selling products while cart is empty.', 'minicartt' ),			
			'id'       	=> 'floating_minicart_best_selling_product',
			'desc'  	=> __( 'Check for "Yes". 5 best selling products link will be shown on empty cart.', 'minicartt' ),
			'type'     	=> 'checkbox',
			'default'	=> '',
			'desc_tip'	=> true,			
		);



		$settings[] = array( 'type' => 'sectionend', 'id' => 'Woo_floating_minicart_sectionend');

		return apply_filters( 'woo_floating_setting_mini_cart_fields', $settings );   
	}
}

$fmc_backend = new Floting_Mini_Cart();