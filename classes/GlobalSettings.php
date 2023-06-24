<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! class_exists( 'GlobalSettings' ) ) {
	class GlobalSettings {
		/**
		 * Autoload method
		 *
		 * @return void
		 */
		public function __construct() {
			// Add admin menu.
			add_action( 'admin_menu', array( $this, 'visibility_menu' ) );
			add_action( 'admin_init', array( $this, 'visibility_menu_register_init' ) );
		}

		/**
		 * Register submenu
		 *
		 * @return void
		 */
		public function visibility_menu() {
			add_submenu_page(
				'options-general.php',
				'Menu Visibility',
				'Menu Visibility',
				'manage_options',
				'menu-visibility',
				array( $this, 'menu_visibility_page_callback' )
			);
		}

		/**
		 * Render submenu
		 *
		 * @return void
		 */
		public function menu_visibility_page_callback() {
			?>
	  <form action="options.php" method="post">
			<?php
			settings_fields( 'menu_visibility_group' );
			do_settings_sections( 'menu_visibility_group' );
			?>
		<input
		  type="submit"
		  name="submit"
		  class="button button-primary"
		  value="<?php esc_attr_e( 'Save' ); ?>"
		/>
	  </form>

			<?php
		}

		/**
		 * Register admin menu.
		 *
		 * @return void
		 */
		public function visibility_menu_register_init() {
			// Register a new setting for "wporg" page.
			register_setting( 'menu_visibility_group', 'menu_visibility_menus' );
			register_setting( 'menu_visibility_group', 'menu_visibility_post_types' );
			register_setting( 'menu_visibility_group', 'menu_visibility_user_roles' );
			register_setting( 'menu_visibility_group', 'menu_visibility_page_urls' );

			// Register a new section in the "wporg" page.
			add_settings_section(
				'menu_visibility_section',
				__( 'Menu Visibility Settings', 'menu_visibility_group' ),
				'',
				'menu_visibility_group'
			);

			// Register a new field in the "menu_visibility_section" section, inside the "wporg" page.
			add_settings_field(
				'mv_field_menus', // As of WP 4.6 this value is used only internally. Use $args' label_for to populate the id inside the callback.
				__( 'Menu Type', 'menu_visibility_group' ),
				array( $this, 'mv_field_menus_cb' ),
				'menu_visibility_group',
				'menu_visibility_section',
				array(
					'label_for'                  => 'mv_field_menus',
					'class'                      => 'mv_field_menus_row',
					'mv_field_menus_custom_data' => 'custom',
				)
			);

			add_settings_field(
				'mv_field_ct', // As of WP 4.6 this value is used only internally. Use $args' label_for to populate the id inside the callback.
				__( 'Post Type', 'menu_visibility_group' ),
				array( $this, 'mv_field_ct_cb' ),
				'menu_visibility_group',
				'menu_visibility_section',
				array(
					'label_for'               => 'mv_field_ct',
					'class'                   => 'mv_field_ct_row',
					'mv_field_ct_custom_data' => 'custom',
				)
			);

			add_settings_field(
				'mv_field_roles', // As of WP 4.6 this value is used only internally. Use $args' label_for to populate the id inside the callback.
				__( 'User Roles', 'menu_visibility_group' ),
				array( $this, 'mv_field_roles_cb' ),
				'menu_visibility_group',
				'menu_visibility_section',
				array(
					'label_for'                  => 'mv_field_roles',
					'class'                      => 'mv_field_roles_row',
					'mv_field_roles_custom_data' => 'custom',
				)
			);

			add_settings_field(
				'mv_field_page_urls', // As of WP 4.6 this value is used only internally. Use $args' label_for to populate the id inside the callback.
				__( 'Page Urls', 'menu_visibility_group' ),
				array( $this, 'mv_field_page_urls_cb' ),
				'menu_visibility_group',
				'menu_visibility_section',
				array(
					'label_for'                      => 'mv_field_page_urls',
					'class'                          => 'mv_field_page_urls_row',
					'mv_field_page_urls_custom_data' => 'custom',
				)
			);
		}

		/**
		 * Menu fields callback.
		 *
		 * @param array $args
		 * @return void
		 */
		public function mv_field_menus_cb( $args ) {
			$menus = wp_get_nav_menus();
			// Get the value of the setting we've registered with register_setting().
			$options = get_option( 'menu_visibility_menus' );
			if ( ! empty( $menus ) ) {
				foreach ( $menus as $menu ) {
					$checked = ( is_array( $options ) && in_array( $menu->slug, array_keys( $options ), true ) ) ? 'checked' : '';
					?>
		  <div>
			<input type="checkbox" id="<?php echo esc_html( $menu->slug ); ?>" <?php echo esc_html( $checked ); ?> name="menu_visibility_menus[<?php echo esc_html( $menu->slug ); ?>]">
			<label for="<?php echo esc_html( $menu->slug ); ?>"> <?php esc_html_e( $menu->name, 'menu_visibility_menus' ); ?> </label>
		  </div>
					<?php
				}
			}
		}

		/**
		 * Content Type field callback.
		 *
		 * @param array $args
		 * @return void
		 */
		public function mv_field_ct_cb( $args ) {
			// Get the value of the setting we've registered with register_setting().
			$options = get_option( 'menu_visibility_post_types' );
			foreach ( get_post_types() as $post_type ) {
				$post_object = get_post_type_object( $post_type );
				$checked     = ( is_array( $options ) && in_array( $post_object->name, array_keys( $options ), true ) ) ? 'checked' : '';
				if ( $post_object->public ) {
					?>
		  <div>
			<input type="checkbox" id="<?php echo esc_html( $post_object->name ); ?>" <?php echo esc_html( $checked ); ?> name="menu_visibility_post_types[<?php echo esc_html( $post_object->name ); ?>]">
			<label for="<?php echo esc_html( $post_object->name ); ?>"> <?php esc_html_e( $post_object->label, 'menu_visibility_post_types' ); ?> </label>
		  </div>
					<?php
				}
			}
		}

		/**
		 * User roles field callback.
		 *
		 * @param array $args
		 * @return void
		 */
		public function mv_field_roles_cb( $args ) {
			global $wp_roles;
			// Get the value of the setting we've registered with register_setting().
			$options = get_option( 'menu_visibility_user_roles' );
			foreach ( $wp_roles->roles as $role_id => $role ) {
				$checked = ( is_array( $options ) && in_array( $role_id, array_keys( $options ), true ) ) ? 'checked' : '';
				?>
		<div>
		  <input type="checkbox" id="<?php echo esc_html( $role_id ); ?>" <?php echo esc_html( $checked ); ?> name="menu_visibility_user_roles[<?php echo esc_html( $role_id ); ?>]">
		  <label for="<?php echo esc_html( $role_id ); ?>"> <?php esc_html_e( $role['name'], 'menu_visibility_user_roles' ); ?> </label>
		</div>
				<?php
			}
		}

		/**
		 * Page url field callback.
		 *
		 * @param array $args
		 * @return void
		 */
		public function mv_field_page_urls_cb( $args ) {
			// Get the value of the setting we've registered with register_setting().
			$options = get_option( 'menu_visibility_page_urls' );
			?>
	  <textarea type="text" id="page_urls" name="menu_visibility_page_urls" rows="4" cols="50"><?php echo esc_html( $options ); ?></textarea>
    <div>Specify pages by using their paths. Enter one path per line.<br/>The '*' character is a wildcard.<br/> An example path is <em class="placeholder">/movies/*</em> for every user page.</div>
			<?php
		}

	}
	if ( is_admin() ) {
		$global_settings = new GlobalSettings();
	}
} // class_exists() ends
