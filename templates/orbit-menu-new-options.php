<div class="wrap orbit-options">

    <h1><?php _e( 'Plugin Options Page', 'orbit-menu-new' ) ?></h1>


	<form action="options.php" method="POST">
  

	<?php settings_fields( 'orbit_menu_new_plugin_general_group' ); ?>
	<?php do_settings_sections( 'orbit-menu-new-options' ); ?>
	<?php submit_button(); ?>


    </form>

</div>