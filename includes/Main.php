<?php
class Main
{
	public function load() {
		add_action( 'woocommerce_before_single_product', [$this, 'process_separate_tabs'] );
	}

	public function process_separate_tabs() {
	    if ( ! is_singular('product') ) {
	        return;
        }
		$product = wc_get_product();
	    if ( ! $product ) {
            return;
        }
		$attributes = array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' );
		if ( ! $attributes ) {
			return;
        }
		$counter = 0;
		foreach ( $attributes as $attribute ) {
		    $term_id = $attribute->get_id();
		    $separate_tab = get_term_meta( $term_id, 'sep_tab', true );
		    if ( $separate_tab ) {
		    	$counter++;
			    $unset_attribute = function( $attributes ) use ($attribute, $product) {
					unset( $attributes['attribute_' . sanitize_title_with_dashes( $attribute->get_name() )]  );
					return $attributes;
			    };

		    	add_filter( 'woocommerce_display_product_attributes', $unset_attribute );
                $tab = new SeparateTab( $attribute );
                $tab->init();
            }
        }
		if ( $counter == count($attributes) && ! apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) ) {
			add_filter( 'woocommerce_product_tabs', [$this, 'remove_attributes_tabs'], 99, 1);
		}
    }

    public function remove_attributes_tabs( $tabs ) {
		unset ( $tabs['additional_information'] );

		return $tabs;
    }
}