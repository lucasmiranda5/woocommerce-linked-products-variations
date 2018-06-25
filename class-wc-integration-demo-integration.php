<?php
/**
 * Integration Demo Integration.
 *
 * @package  WC_Integration_Demo_Integration
 * @category Integration
 * @author   Patrick Rauland
 */

class WC_Integration_Demo_Integration extends WC_Integration {

	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {
		$this->id                 = 'integration-demo';
		$this->method_title       = __( 'Integration Demo', 'woocommerce-integration-demo' );
		$this->method_description = __( 'An integration demo to show you how easy it is to extend WooCommerce.', 'woocommerce-integration-demo' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->api_key = $this->get_option( 'api_key' );
		$this->debug   = $this->get_option( 'debug' );

		// Actions.
		add_action( 'woocommerce_update_options_integration_' .  $this->id, array( $this, 'process_admin_options' ) );

		add_filter( 'woocommerce_product_data_tabs', array( $this, 'new_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'new_tab_content' ) );
	}

	/**
	 * Initialize integration settings form fields.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'api_key' => array(
				'title'             => __( 'API Key', 'woocommerce-integration-demo' ),
				'type'              => 'text',
				'description'       => __( 'Enter with your API Key. You can find this in "User Profile" drop-down (top right corner) > API Keys.', 'woocommerce-integration-demo' ),
				'desc_tip'          => true,
				'default'           => ''
			),
			'login' => array(
				'title'             => __( 'Login', 'woocommerce-integration-demo' ),
				'type'              => 'login_ml',
				'label'             => __( 'Entrar no ML' ),
				'desc_tip'          => true,
			),
			'api_key2' => array(
				'title'             => __( 'API Key2', 'woocommerce-integration-demo' ),
				'type'              => 'select',
				'description'       => __( 'Enter with your API Key. You can find this in "User Profile" drop-down (top right corner) > API Keys.', 'woocommerce-integration-demo' ),
				'desc_tip'          => true,
				'default'           => '',
				'class'             => 'wc-enhanced-select',
				'options'           => array(
					'test' => 'Test',
					'test2' => 'Test2',
					'test3' => 'Test3',
				),
			),
			'debug' => array(
				'title'             => __( 'Debug Log', 'woocommerce-integration-demo' ),
				'type'              => 'checkbox',
				'label'             => __( 'Enable logging', 'woocommerce-integration-demo' ),
				'default'           => 'no',
				'description'       => __( 'Log events such as API requests', 'woocommerce-integration-demo' ),
			),
		);
	}

	/**
	 * Generate Button ML Input HTML.
	 */
	public function generate_login_ml_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'label'             => '',
			'desc_tip'          => false,
			'description'       => '',
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>

					<button type="button" class="button" id="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $data['label'] ); ?></button>

					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	public function new_tab( $tabs ) {
		$tabs['ml'] = array(
			'label'  => __( 'ML', 'woocommerce' ),
			'target' => 'ml_product_data',
		);

		return $tabs;
	}

	public function new_tab_content() {
		?>

			<div id="ml_product_data" class="panel woocommerce_options_panel hidden">
				<?php
					woocommerce_wp_text_input( array(
						'id'                => '_test',
						'label'             => __( 'Stock quantity', 'woocommerce' ),
						'desc_tip'          => true,
						'description'       => __( 'Stock quantity. If this is a variable product this value will be used to control stock for all variations, unless you define stock at variation level.', 'woocommerce' ),
						'type'              => 'text',
					) );
				?>
			</div>

		<?php
	}

}
