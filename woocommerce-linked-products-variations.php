<?php
/**
 * Plugin Name: Woocommerce Linked Products Variations
 * Plugin URI: https://github.com/lucasmiranda5/woocommerce-linked-products-variations
 * Description: Plugin for linked differents products how variations.
 * Author: Lucas Miranda
 * Author URI: http://lucasmiranda.com.br
 * Version: 0.1
 */

if ( ! class_exists( 'WC_woocommerce_linked_products_variations' ) ) :

	class WC_woocommerce_linked_products_variations {

	private static $instance;

	private function __construct() {
		// Checks if WooCommerce is installed.
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_data_tabs' ) );
		add_action( 'woocommerce_product_data_panels',  array( $this, 'product_data_fields' ) );
		$this->define_admin_hooks();
		add_action( 'woocommerce_single_product_summary', array($this,'show_in_product'), 20 );
		add_action( 'admin_enqueue_scripts', array($this,'load_custom_wp_admin_script') );

	}

    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function load_custom_wp_admin_script() {
            wp_register_script( 'woocommerce_linked_products_variations_js', plugins_url('js/woocommerce_linked_products_variations.js',__FILE__), false, '1.0.0' );
            wp_enqueue_script( 'woocommerce_linked_products_variations_js' );
    }
    

    private function define_admin_hooks() {
        add_action( 'save_post', array( $this, 'save_variation_settings_fields' ), 10, 2 );
    }

    public function add_product_data_tabs( $product_data_tabs  ) {
    $product_data_tabs['linkar-produtos'] = array(
      'label'  => esc_attr__( 'Linkar Produtos', 'woocommerce_linked_products_variations' ),
      'target' => 'linkarproduto_variacao',
      'class'  => array('show_if_simple' ),

    );
    return $product_data_tabs;
  }

	public function show_in_product(){
		global $woocommerce, $post;
		print "
		<style>
		.linkvariacaolinkar{ border: 1px solid #000; padding: 5px 5px;box-shadow: 0 0 0 0 !important; margin-right:5px } </style>";
		$nomesvariacao = get_post_meta( $post->ID, 'woocoomerce_linkar_produto_nomevaraiacao', true );
	    $formavariacao = get_post_meta( $post->ID, 'woocoomerce_linkar_produto_formavariacao', true );
	    $produtos = get_post_meta( $post->ID, 'woocoomerce_linkar_produto_produtos', true );
	    $produtosnome = get_post_meta( $post->ID, 'woocoomerce_linkar_produto_produtosnome', true );
	    if(!empty($nomesvariacao) and $nomesvariacao[0] != ''){
		    foreach($nomesvariacao as $key => $valor){
		    	print "<span>".$valor.': </span><br>';
		    	foreach($produtos[$key] as $key2 => $valor2) {
		    		if($formavariacao[$key] == 'nome')
		    			print "<a class='linkvariacaolinkar' href='".get_permalink($valor2)."'>".$produtosnome[$key][$key2]."</a>";
		    		else
		    			print "<a class='linkvariacaolinkar' href='".get_permalink($valor2)."'><img src='".get_the_post_thumbnail_url($valor2)."' style='width:90px'></a>";
		    		
		    	}
		    	print "<br>";
		    }
		}
		
	}
	

  public function product_data_fields() {
    global $woocommerce, $post;
    $nomesvariacao = get_post_meta( $post->ID, 'woocoomerce_linkar_produto_nomevaraiacao', true );
    $formavariacao = get_post_meta( $post->ID, 'woocoomerce_linkar_produto_formavariacao', true );
    $produtos = get_post_meta( $post->ID, 'woocoomerce_linkar_produto_produtos', true );
    $produtosnome = get_post_meta( $post->ID, 'woocoomerce_linkar_produto_produtosnome', true );
    if(empty($nomesvariacao)){
    	$nomesvariacao = [];
    	$formavariacao = [];
    	$produtos = [];
    	$produtosnome = [];
    	$nomesvariacao[0] = '';
    	$formavariacao[0] = '';
    	$produtos[0][0] = '';
    	$produtosnome[0][1] = '';
    }
    if(empty($produtos)){
    	$produtos = [];
    	$produtosnome = [];
    	$produtos[0][0] = '';
    	$produtosnome[0][1] = '';
    }
    
    ?>
   
    <div id="linkarproduto_variacao" class="panel woocommerce_options_panel">
    	<?php
    	foreach($nomesvariacao as $key => $valor){ ?>
    	<div class='divvariacao'>
            <p class="form-field nome_variacao_fild ">
			<label for="minimum_amount">Nome da Variação <spam style="font-weight: bold;color: red;" class='excluirvariacao'>X</spam></label>
			<input type="text" class="short" style="" name="nomevariacao[<?=$key;?>]" id="nomevariacao" value="<?=$valor;?>" placeholder="Nome da Variação"> 
			
			</p>
			<p class="form-field nome_variacao_fild ">
				<label for="minimum_amount">Forma de aparecer</label>
				<select name="formavariacao[<?=$key;?>]">
				<option <?=($formavariacao[$key] == 'nome' ? 'selected' : '') ?> value="nome">Nome</option>
				<option <?=($formavariacao[$key] == 'foto' ? 'selected' : '') ?> value="foto">Foto</option>
			</select>
			</p>
			<?php foreach($produtos[$key] as $key2 => $valor2) {?>
			<p class="form-field">
			<label for="upsell_ids"><?php esc_html_e( 'Produtos', 'woocommerce' ); ?> <spam style="font-weight: bold;color: red;" class='excluirprodutovariacao'>X</spam></label>
			<select class="wc-product-search" style="width: 50%;" name="linkarprodutos[<?=$key;?>][<?=$key2?>]" id="primeiroinputlinkar" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
				<?php
				$product = wc_get_product( $valor2 );
					if ( is_object( $product ) ) {
						echo '<option value="' . esc_attr( $valor2 ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
					}
					?>
			</select>
			<input type="text" class="short" style="" name="linkarprodutonome[<?=$key;?>][<?=$key2?>]" value="<?=$produtosnome[$key][$key2];?>" placeholder="Nome que irar aparecer">
			</p>
		<?php } ?>
			<button type="button" class="add-mais-produto" data-produto="<?=$key;?>" data-variacao="<?=$key+1;?>">Adicionar Mais Produto</button>
		</div>
	<?php } ?>

		<input type="hidden" id="quantidadelinkar" value="<?=$key+1?>">
			<button type="button" class="add-mais">Adicionar Mais Variação</button>      
    </div>

    <?php
  }

   public function save_variation_settings_fields( $post_id ) {
      update_post_meta( $post_id, 'woocoomerce_linkar_produto_nomevaraiacao', $_POST['nomevariacao'] );
      update_post_meta( $post_id, 'woocoomerce_linkar_produto_formavariacao', $_POST['formavariacao'] );
      update_post_meta( $post_id, 'woocoomerce_linkar_produto_produtos', $_POST['linkarprodutos'] );
      update_post_meta( $post_id, 'woocoomerce_linkar_produto_produtosnome', $_POST['linkarprodutonome'] );

     
    
  }

	
}

add_action( 'plugins_loaded', array( 'WC_woocommerce_linked_products_variations', 'get_instance' ) );
endif;