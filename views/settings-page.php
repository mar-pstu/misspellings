<?php if ( ! defined( 'ABSPATH' ) ) { exit; }; ?>
<div class="wrap">
	<h1><?php echo get_admin_page_title(); ?></h1>
	<form action="options.php" method="POST">
		<?php settings_fields( $this->slug ); ?>
		<?php do_settings_sections( $this->slug ); ?>
		<?php submit_button(); ?>  
	</form>
</div>