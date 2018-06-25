jQuery.fn.addmaisproduto = function(){
    		jQuery('.add-mais-produto').click(function(){
    			variacao = jQuery(this).attr('data-variacao');
    			produto = jQuery(this).attr('data-produto');
    			html = '<p class="form-field"><label for="">Produto <spam style="font-weight: bold;color: red;" class="excluirprodutovariacao">X</spam></label><select class="wc-product-search" style="width: 50%;" name="linkarprodutos['+variacao+']['+produto+']" data-placeholder="Procurar Produto" data-action="woocommerce_json_search_products_and_variations" data-exclude="'+jQuery('#primeiroinputlinkar').attr('data-exclude')+'"></select><input type="text" class="short" style="" name="linkarprodutonome['+variacao+']['+produto+']" value="" placeholder="Nome que irar aparecer"></p>';
    			jQuery(this).attr('data-produto',parseInt(produto) + 1);
    			jQuery(html).insertBefore(this);
    			jQuery( document.body).trigger( 'wc-enhanced-select-init' );
    			jQuery('.divvariacao').addmaisproduto();
    		});
    		jQuery('.excluirvariacao').click(function() {
    			$(this).parents('.divvariacao').remove();
    		});
    		jQuery('.excluirprodutovariacao').click(function() {
    			$(this).parents('p').remove();
    		});
    	}
    	jQuery(function(){
    		jQuery('.divvariacao').addmaisproduto();
    		jQuery('.add-mais').click(function(){
    			produto = jQuery('#quantidadelinkar').val();
    			html = '<div class="divvariacao"><p class="form-field nome_variacao_fild "><label for="minimum_amount">Nome da Variação <spam style="font-weight: bold;color: red;" class="excluirvariacao">X</spam></label><input type="text" class="short" style="" name="nomevariacao['+produto+']" id="nomevariacao" value="" placeholder="Nome da Variação"> </p><p class="form-field nome_variacao_fild "><label for="minimum_amount">Forma de aparecer</label><select name="formavariacao['+produto+']"><option value="nome">Nome</option><option value="foto">Foto</option></select></p><p class="form-field"><label for="">Produto <spam style="font-weight: bold;color: red;" class="excluirprodutovariacao">X</spam></label><select class="wc-product-search" style="width: 50%;" name="linkarprodutos['+produto+'][0]" data-placeholder="Procurar Produto" data-action="woocommerce_json_search_products_and_variations" data-exclude="'+jQuery('#primeiroinputlinkar').attr('data-exclude')+'"></select><input type="text" class="short" style="" name="linkarprodutonome['+produto+'][0]" value="" placeholder="Nome que irar aparecer"></p><button type="button" class="add-mais-produto" data-produto="1" data-variacao="'+produto+'">Adicionar Mais Produto</button></div>';
    			jQuery('#quantidadelinkar').val(parseInt(produto) + 1);
    			jQuery(html).insertBefore(this);
    			jQuery('.divvariacao').addmaisproduto();
    			jQuery( document.body).trigger( 'wc-enhanced-select-init' );
    		})
    	})