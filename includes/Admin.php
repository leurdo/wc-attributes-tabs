<?php
class Admin
{
	public function load() {
		add_action( 'woocommerce_after_add_attribute_fields', [$this, 'separate_tab_switch']);
		add_action( 'woocommerce_after_edit_attribute_fields', [$this, 'separate_tab_switch'], 20);
		add_action( 'woocommerce_attribute_updated', [$this, 'save_atts_meta'], 20 );
	}

	public function separate_tab_switch() {
		$attribute_id = isset( $_GET['edit'] ) ? absint( $_GET['edit'] ) : 0;
		$sep_tab = get_term_meta( $attribute_id, 'sep_tab', true ) ? 'checked' : '';

		?>
		<tr class="form-field form-required">
			<th scope="row" valign="top">
				<label for="att_separate_tab"><?php _e('Show in separate tab', 'wc-attributes-tabs') ?></label>
			</th>
			<td>
				<input type="checkbox" name="att_separate_tab" id="att_separate_tab" <?= $sep_tab ?>>
				<p class="description">
					<?php _e('When checked, this group of attribute will be shown in separate tab', 'wc-attributes-tabs')  ?>
				</p>
			</td>
		</tr>
		<?php
	}

	public function save_atts_meta( $term_id ) {
	    if ( isset( $_POST['att_separate_tab'] ) ) {
		    $separate_tab = (bool) $_POST['att_separate_tab'];
		    update_term_meta( $term_id, 'sep_tab', $separate_tab );
        } else {
		    update_term_meta( $term_id, 'sep_tab', '' );
        }
	}
}