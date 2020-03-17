<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Woo Floating Minicart
 *
 * Allows user to get WooCommerce Floating Minicart.
 *
 * @class   Floating_Mini_Cart_Frontend 
 */


class Floating_Mini_Cart_Frontend {

	/**
	 * Init and hook in the integration.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->id                 = 'Woo_floating_minicart_menu';
		$this->method_title       = __( 'Woocommerce Floating Minicart', 'minicartt' );
		$this->method_description = __( 'Woocommerce Floating Minicart.', 'minicartt' );

		// Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'woo_floating_minicart_scripts' ));
		
		// Actions
		add_action( 'wp_footer', array( $this, 'woo_floating_minicart' ));
		add_action( 'wp_footer', array( $this, 'fmc_footer_style' ));		

		do_action( 'fmc_woocommerce_fragments_compatibilty', $this );

	}
	public function fmc_version_check( $version = '3.0.0' ) {
		//if ( class_exists( 'WooCommerce' ) ) {
			global $woocommerce;
			if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
				return true;
			//}
			}
			return false;
	}

	/**
	 * Loading scripts.
	 *
	 * @return void
	 */

	public function woo_floating_minicart_scripts(){
		// loading plugin custom css file
		wp_enqueue_style( 'floating-mini-cart-style', plugins_url( 'floting-mini-card/includes/assets/css/main-style.css' ) );
		// lodaing third-party css file for custom scroll bar 
		wp_enqueue_style( 'floating-mini-cart-custom-style', plugins_url( 'floting-mini-card/includes/assets/js/jquery.mCustomScrollbar.min.css' ) );
		// loading plugin custom js file
		wp_enqueue_script( 'floating-mini-cart-script', plugins_url( 'floting-mini-card/includes/assets/js/custom.js' ), array('jquery'), '1.0.0', true );
		
		// lodaing third-party js file for custom scroll bar 
		wp_enqueue_script( 'floating-mini-cart-scroll-script', plugins_url( 'floting-mini-card/includes/assets/js/jquery.mCustomScrollbar.concat.min.js' ), array('jquery'), '1.0.0', true );		
	
	} 
	/**
	 * Loading minicart option on wp_head section.
	 *
	 * @return void
	 */

	public function woo_floating_minicart(){

		if( !( is_cart() || is_checkout()) ){

			echo "<div class='floating-warp-content'>";
				$this->fmc_woocommerce_mini_cart();
			echo "</div>";

		}

	}


	/**
	 * Handling WooCommerce ajax on cart items update .
	 *
	 * @param  obj $fragments WooCommerce.
	 *
	 * @return obj
	 */
	public function woo_floating_minicart_add_to_cart_fragment( $fragments ) {
			
			ob_start();
			
			echo "<div class='floating-warp-content'>";
				$this->fmc_woocommerce_mini_cart( );
			echo "</div>";

			$fragments['div.floating-warp-content'] = ob_get_clean();

			return $fragments;

			}
			
	

	/**
	 * Initiating WooCommerce minicart function .
	 *
	 * @return void
	 */


	public function fmc_woocommerce_mini_cart(){

			// While empty cart options start
			$show_shop_page_link 	= get_option('floating_shop_page_minicart');
			$show_best_selling 	= get_option('floating_minicart_best_selling_product');

			
		?>

		<?php if ( $empty_cart_hide == false ) : ?>
			<div id="minicartt" class="minicartt">
			
			<div id="minicartt-wrapper">
			
			<div id="minicartt-icon">
				<?php	
					echo "<span class='cart_contents_count'>";
					echo WC()->cart->cart_contents_count;
					echo "</span>";				
				?>	
				<?php $baskt_image_change = get_option( 'wc_settings_tab_demo_fmc_section_text' ); ?>
				<?php if( ! $baskt_image_change ): ?>	
				<span class="cart-icon"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/bucket.png'; ?>" title="WooCommerce Floating Cart" alt="WooCommerce Floating Cart" width="32" height="32" /></span>
			    <?php else: ?>
					<span class="cart-icon"><img src="<?php echo $baskt_image_change; ?>" title="WooCommerce Floating Cart" alt="WooCommerce Floating Cart" width="32" height="32" /></span>
				<?php endif; ?>
			</div><!-- END .minicartt-inactive -->

			
			<?php do_action( 'woocommerce_before_mini_cart' ); ?>
			
			
			<?php if ( $empty_cart_status == false ):  ?>
			<p class="cart-items"><?php echo sprintf(_n('%d product in the cart.', '%d products in the cart.', WC()->cart->cart_contents_count, 'minicartt'), WC()->cart->cart_contents_count); ?></p>
			
			<ul class="cart_list product_list_widget <?php echo $args['list_class']; ?>">
			
								

					<?php
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

								$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
								$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
								$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
								
								?>
								
								<li class="<?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
									
									<?php if ( ! $_product->is_visible() ) : ?>
										<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . $product_name . '&nbsp;'; ?>
									<?php else : ?>
										<a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>" class="item-thumbnail">
											<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
										</a>

										<a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>" class="item-detail">
											<?php echo $product_name . '&nbsp;'; ?>
											<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
											<?php echo WC()->cart->get_item_data( $cart_item ); ?>
											<?php
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
												'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
												esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
												__( 'Remove this item', 'minicartt' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											), $cart_item_key );
											?>
										</a>
									<?php endif; ?>

								</li>
								<?php
							}
						}
					?>
			</ul><!-- end product list -->
				
				<?php else : ?>
					<p class="cart-items"><?php _e( 'No products in the cart.', 'minicartt' ); ?></p>
			
			<?php endif; ?>

			
			<?php if ( $empty_cart_status == false ): ?>

				<div id="minicartt-base">				
				
					<p class="total"><strong><?php _e( 'Subtotal', 'minicartt' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

					<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); 

						if(function_exists('wc_get_cart_url')){
							$cart_url = wc_get_cart_url();
							$checkout_url = wc_get_checkout_url();
						}else{							
							$cart_url = WC()->cart->get_cart_url();
							$checkout_url = WC()->cart->get_checkout_url();
						}
					?>

					<p class="buttons">
						<a id="fmc-cart-button" href="<?php echo $cart_url; ?>" class="button wc-forward"><?php _e( 'Cart', 'minicartt' ); ?></a>
						<a id="fmc-checkout-button"  href="<?php echo $checkout_url; ?>" class="button checkout wc-forward"><?php _e( 'Checkout', 'minicartt' ); ?></a>
					</p>

				</div> <!-- end minicartt-base -->

			<?php else: ?>

				<div id="minicartt-base">
					<?php 
					if( $show_best_selling == 'yes' ):
						$best_selling_products = self::fmc_best_selling_products();
						if(!empty($best_selling_products)): 

					?>			
							<p  class="best-selling-header"><strong><?php _e( 'Best Selling Products', 'minicartt' ); ?></strong></p>
								<ul class="best_selling_list" id="fmc-best-selling-products-ul">
							<?php foreach( $best_selling_products as $product_id ): 
								  $product_link = get_permalink( $product_id );	
								  $product_title = get_the_title( $product_id );
								  $product_image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'shop_catalog' );	
							?>
									<li id="fmc-best-selling-products" >
										<a href="<?php echo $product_link; ?>" class="item-thumbnail" class="item-thumbnail" title="<?php _e($product_title,'minicartt'); ?>">
										<img src="<?php  echo $product_image[0]; ?>" alt="<?php echo $product_title; ?>">
										</a>										
									</li>							
							<?php endforeach; ?>
								</ul>
					<?php 
						endif; 
					endif; 
				?>
				<?php if( $show_shop_page_link == 'yes' ):  
							
							if(function_exists('wc_get_page_id')){
								// for old version ( >3.0.0 )WooCommerce
								$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) ); 
							} else {
								//for new version (<3.0.0) WooCommerce
								$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) ); 

							}


							?>
						<p class="buttons">
							<a id="fmc-cart-button" href="<?php echo $shop_page_url; ?>" class="button wc-forward"><?php _e( 'Go to Shop', 'minicartt' ); ?></a>						
						</p>
					<?php endif; ?>

				</div> <!-- end minicartt-base -->

			<?php endif; ?>

			<?php do_action( 'woocommerce_after_mini_cart' ); ?>
		</div>	
		</div> <!-- END .minicartt-active -->
		<?php endif; ?>
		<?php
		
	}

	public static function fmc_shop_page_link(){
		$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
	}

	public static function fmc_best_selling_products(){
		$args = array(
		  'post_type' => 'product',
		  'post_status' => 'publish',
		  'posts_per_page' => 6,
		  'meta_key' => 'total_sales',
		  'orderby' => 'meta_value_num',
		  'meta_query' 	=> array(
				array(
					'key' 		=> '_visibility',
					'value' 	=> array( 'catalog', 'visible' ),
					'compare' 	=> 'IN'
				)
			)
		 );

		 $best_selling_products = get_posts( $args );		 
		 $bs_product = array();
		 if(!empty($best_selling_products)){
			 foreach($best_selling_products as $product){
			 	$bs_product[] = $product->ID;			 	 	
			 }
		 	
		 }

		 return $bs_product;
	}


	/**
	 * Loading footer css.
	 *
	 * @return void
	 */
	public function fmc_footer_style(){

			$minicart_position 	= get_option('Woo_floating_minicart_position');
			$minicart_offset 	= get_option('Woo_floating_minicart_offset');
			$primary_color 		= get_option('Woo_floating_minicart_primary_color');
			$secondary_color 	= get_option('Woo_floating_minicart_secondary_color');
			$button_color 		= get_option('Woo_floating_minicart_button_color');
			$border_redus 		= get_option('border_radius_percent');
			
			
			echo '<style type="text/css">';			   

			// check if bar-chart color.
			if(!empty($minicart_position) && $minicart_position == 'floating-right'){
				echo '	#minicartt.active{
							right:0%;
						}
						

						#minicartt-icon {
						float: left;
						position:absolute;
						left: -56px;
						top: 0px;
						}

						#minicartt-icon .cart_contents_count{
							position:absolute;
							top:0px;
							right:40px;
						}

						
						#minicartt-icon  span.cart-icon{	
						    border-top-left-radius: '. $border_redus .'%; 
						    -webkit-border-top-left-radius: '. $border_redus .'%;   
						    
						}';


						//minicart offset from top
						if(!empty($minicart_offset)){
								echo '#minicartt{
									position: fixed;
									right:-220px;
									top:'.$minicart_offset.'%;
								}';

							} else {
								echo '#minicartt{
									position: fixed;
									right:-220px;
									top:15%;
								}';
							}


					} else {
						echo '	#minicartt.active{
							left:0%;
						}
						
						#minicartt-icon {
							float: right;
							position:absolute;
							right: -62px;
							top: 0px;
							}

							#minicartt-icon .cart_contents_count{
								position:absolute;
								top:0px;
								left:40px;
							}

							

							#minicartt-icon  span.cart-icon{	
								border-top-right-radius: '. $border_redus .'%; 
								-webkit-border-top-right-radius: '. $border_redus .'%;     
							    
							}';

							//minicart offset from top
							if(!empty($minicart_offset)){
									echo '#minicartt{
										position: fixed;
										left:-220px;
										top:'.$minicart_offset.'%;
									}';

								} else {
									echo '#minicartt{
										position: fixed;
										left:-220px;
										top:15%;
									}';
								}	

					}

						


						// minicart primary color			
						if(!empty($primary_color)){

							echo ' #minicartt-icon span.cart-icon {
   							 background-color: '.$primary_color.'; }

   							 #minicartt-base {
								    background-color: '.$primary_color.';
								}';

						} else {

							echo ' #minicartt-icon span.cart-icon {
   							 background-color: #42a2ce; }

   							 #minicartt-base {
								    background-color: #42a2ce;
								}';
						}	

						// minicart secondary color			
						if(!empty($secondary_color)){

							echo ' #minicartt p.cart-items {
									    background: '.$secondary_color.'; }';

						} else {

							echo ' #minicartt p.cart-items {
									    background: #3c3c3c;}';
						}	

						// minicart button color			
						if(!empty($button_color)){

							echo '
							#minicartt-base p.buttons a.button{
								background: '.$button_color.';
							}
							#minicartt-base p.buttons a.button:hover {
								background: '.$secondary_color.';}';

						} else {

							echo '
							#minicartt-base p.buttons a.button{
								background: #71b02f;
							}
							#minicartt-base p.buttons a.button:hover {
								    background: #79bc32;}';
						}		
		
		    	echo '</style>';
	}
}

$minicart = new Floating_Mini_Cart_Frontend();