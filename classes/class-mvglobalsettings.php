<?php
/**
 * Global settings for menu visibility.
 *
 * @package menu-visibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'MvGlobalSettings' ) ) {

	class MvGlobalSettings {

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'mv_visibility_menu' ) );
			add_action( 'admin_init', array( $this, 'mv_visibility_menu_register_init' ) );
		}

		public function mv_visibility_menu() {
			add_submenu_page(
				'options-general.php',
				'Menu Visibility',
				'Menu Visibility',
				'manage_options',
				'menu-visibility',
				array( $this, 'mv_menu_visibility_page_callback' )
			);
		}

		public function mv_menu_visibility_page_callback() {
			?>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'menu_visibility_group' );
				do_settings_sections( 'menu_visibility_group' );
				?>
				<input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e( 'Save' ); ?>" />
			</form>
			<?php
		}

		public function mv_visibility_menu_register_init() {
			register_setting( 'menu_visibility_group', 'menu_visibility_menus' );
			register_setting( 'menu_visibility_group', 'menu_visibility_post_types' );
			register_setting( 'menu_visibility_group', 'menu_visibility_user_roles' );
			register_setting( 'menu_visibility_group', 'menu_visibility_page_urls' );

			add_settings_section(
				'menu_visibility_section',
				__( 'Menu Visibility Settings', 'menu_visibility_group' ),
				array( $this, 'mv_settings_section_callback' ),
				'menu_visibility_group'
			);

			$fields = array(
				'mv_field_menus'     => __( 'Menu Type', 'menu_visibility_group' ),
				'mv_field_ct'        => __( 'Post Type', 'menu_visibility_group' ),
				'mv_field_roles'     => __( 'User Roles', 'menu_visibility_group' ),
				'mv_field_page_urls' => __( 'Page Urls', 'menu_visibility_group' ),
			);

			foreach ( $fields as $field => $label ) {
				add_settings_field(
					$field,
					$label,
					array( $this, $field . '_cb' ),
					'menu_visibility_group',
					'menu_visibility_section',
					array(
						'label_for'             => $field,
						'class'                 => $field . '_row',
						$field . '_custom_data' => 'custom',
					)
				);
			}
		}

		public function mv_settings_section_callback() {
			// Section callback content (if any).
		}

		public function mv_field_menus_cb() {
			$menus   = wp_get_nav_menus();
			$options = get_option( 'menu_visibility_menus' );

			if ( ! empty( $menus ) ) {
				foreach ( $menus as $menu ) {
					$checked = ( is_array( $options ) && in_array( $menu->slug, array_keys( $options ), true ) ) ? 'checked' : '';
					?>
					<div>
						<input type="checkbox" id="<?php echo esc_attr( $menu->slug ); ?>" <?php echo esc_attr( $checked ); ?>
							name="menu_visibility_menus[<?php echo esc_attr( $menu->slug ); ?>]">
						<label for="<?php echo esc_attr( $menu->slug ); ?>"> <?php echo esc_html( $menu->name ); ?> </label>
					</div>
					<?php
				}
			}
		}

		public function mv_field_ct_cb() {
			$options = get_option( 'menu_visibility_post_types' );

			foreach ( get_post_types() as $post_type ) {
				$post_object = get_post_type_object( $post_type );
				$checked     = ( is_array( $options ) && in_array( $post_object->name, array_keys( $options ), true ) ) ? 'checked' : '';
				if ( $post_object->public ) {
					?>
					<div>
						<input type="checkbox" id="<?php echo esc_attr( $post_object->name ); ?>" <?php echo esc_attr( $checked ); ?>
							name="menu_visibility_post_types[<?php echo esc_attr( $post_object->name ); ?>]">
						<label for="<?php echo esc_attr( $post_object->name ); ?>"> <?php echo esc_html( $post_object->label ); ?> </label>
					</div>
					<?php
				}
			}
		}

		public function mv_field_roles_cb() {
			global $wp_roles;
			$options = get_option( 'menu_visibility_user_roles' );

			foreach ( $wp_roles->roles as $role_id => $role ) {
				$checked = ( is_array( $options ) && in_array( $role_id, array_keys( $options ), true ) ) ? 'checked' : '';
				?>
				<div>
					<input type="checkbox" id="<?php echo esc_attr( $role_id ); ?>" <?php echo esc_attr( $checked ); ?>
						name="menu_visibility_user_roles[<?php echo esc_attr( $role_id ); ?>]">
					<label for="<?php echo esc_attr( $role_id ); ?>"> <?php echo esc_html( $role['name'] ); ?> </label>
				</div>
				<?php
			}
		}

		public function mv_field_page_urls_cb() {
			$options = get_option( 'menu_visibility_page_urls' );
			?>
			<textarea type="text" id="page_urls" name="menu_visibility_page_urls" rows="4" cols="50"><?php echo esc_html( $options ); ?></textarea>
			<div>Specify pages by using their paths. Enter one path per line.<br />The '*' character is a wildcard.<br /> An example
				path is <em class="placeholder">/movies/*</em> for every movie page.</div>
			<?php
		}

	}

	if ( is_admin() ) {
		$global_settings = new MvGlobalSettings();
	}
} // class_exists() ends
