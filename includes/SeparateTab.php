<?php
class SeparateTab
{
	private $attribute;

	private $taxonomy;

	private $tab_name;

	public function __construct(WC_Product_Attribute $attr) {
		$this->attribute = $attr;
		$this->taxonomy = $this->attribute->get_taxonomy_object();
		$this->tab_name = $this->taxonomy->attribute_label;
	}

	public function init() {
		add_filter( 'woocommerce_product_tabs', [$this, 'add_separate_attribute_tabs'], 20, 1);
	}

	public function add_separate_attribute_tabs( $tabs ) {
		$id = $this->attribute->get_id();

		$tabs['attribute_' . $id] = array(
			'title'    => esc_html( $this->tab_name ),
			'priority' => 25,
			'callback' => [$this, 'woocommerce_product_additional_attribute_tab'],
		);

		return $tabs;
	}

	public function woocommerce_product_additional_attribute_tab() {
		global $product;

		echo '<h2>' . esc_html( $this->tab_name ) . '</h2>';
		$attribute_values   = wc_get_product_terms( $product->get_id(), $this->attribute->get_name(), array( 'fields' => 'all' ) );

		foreach ( $attribute_values as $attribute_value ) {
			$value_name = esc_html( $attribute_value->name );

			if ( $this->taxonomy->attribute_public ) {
				$values[] = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $this->attribute->get_name() ) ) . '" rel="tag">' . $value_name . '</a>';
			} else {
				$values[] = $value_name;
			}
		}

		$product_attributes[ 'attribute_' . sanitize_title_with_dashes( $this->attribute->get_name() ) ] = array(
			'label' => wc_attribute_label( $this->attribute->get_name() ),
			'value' => apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $this->attribute, $values ),
		);

		wc_get_template(
			'single-product/product-attributes.php',
			array(
				'product_attributes' => $product_attributes,
			)
		);

	}
}