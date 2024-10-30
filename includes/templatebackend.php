<?php
/**
 * Admin Settings.
 */

$data = $this->data();
?>

<div class="ht-body">

	<h2><?php esc_html_e( 'HT call now setting', 'ht-call-now' ); ?></h2>
	<?php $this->alert(); ?>

	<form method="post" id="ht-form" action="<?php echo $this->form_action(); ?>">
		<p> <label> <?php esc_html_e('Phone Number','ht-call-now'); ?></label>
		<input id="ht_phone" name="ht_options[phone]" type="text" class="ht-text" value="<?php echo esc_attr( $data['phone'] ); ?>" /></p>

		<p><label> <?php esc_html_e('Bg Color','ht-call-now'); ?></label>
		<input name="ht_options[bg_color]" type="text" value="<?php echo esc_attr( $data['bg_color']); ?>" class="ht-bgcolor-field" /></p>

		<p><label> <?php esc_html_e('Color','ht-call-now'); ?></label>

		<input name="ht_options[color]" type="text" value="<?php echo esc_attr( $data['color']); ?>" class="ht-color-field" /></p>

		<?php submit_button(); ?>
		<?php wp_nonce_field( 'ht-settings', 'ht-settings-nonce' ); ?>

	</form>

</div>
