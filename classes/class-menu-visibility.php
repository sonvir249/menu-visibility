<?php
/**
 * Menu visbility.
 *
 * @package menu-visibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Menu_Visibility' ) ) {
	/**
	 * Menu_Visibility class
	 */
	class Menu_Visibility {
		/**
		 * Global settings array
		 *
		 * @var array
		 */
		public $global_settings;
		/**
		 * Menu_Visibility class constructor.
		 */
		public function __construct() {
			$this->global_settings['selected_menus']      = get_option( 'menu_visibility_menus' );
			$this->global_settings['selected_post_types'] = get_option( 'menu_visibility_post_types' );
			$this->global_settings['selected_user_roles'] = get_option( 'menu_visibility_user_roles' );
			$this->global_settings['page_urls']           = get_option( 'menu_visibility_page_urls' );
			add_filter( 'wp_nav_menu_objects', array( $this, 'ad_filter_menu' ), 10, 2 );
		}
		/**
		 * Helper method for filter menu.
		 *
		 * @param array $sorted_menu_objects menu objects.
		 * @param array $args exta args.
		 * @return mixed
		 */
		public function ad_filter_menu( $sorted_menu_objects, $args ) {
			global $wp;
			global $wp_query;

			$is_post_type = ( is_array( $this->global_settings['selected_post_types'] ) && in_array( $wp_query->queried_object->post_type, array_keys( $this->global_settings['selected_post_types'] ), true ) );
			$is_menu_type = ( is_array( $this->global_settings['selected_menus'] ) && in_array( $args->menu->slug, array_keys( $this->global_settings['selected_menus'] ), true ) );

			// Get current URL.
			$current_url = home_url( $wp->request ) . '/';

			if ( $is_menu_type ) {
				if ( $is_post_type ) {
					$sorted_menu_objects = '';
				}
				if ( ! empty( $this->global_settings['page_urls'] ) ) {
					$page_urls = explode( PHP_EOL, $this->global_settings['page_urls'] );
					foreach ( $page_urls as $page_url ) {
						$page_url = preg_replace( '/[\*\r]/', '', $page_url );
						if ( str_contains( $current_url, $page_url ) ) {
							$sorted_menu_objects = '';
						}
					}
				}
				if ( is_user_logged_in() && ! empty( $this->global_settings['selected_user_roles'] ) ) {
					$user  = wp_get_current_user();
					$roles = (array) $user->roles;
					foreach ( $roles as $role ) {
						if ( in_array( $role, array_keys( $this->global_settings['selected_user_roles'] ), true ) ) {
								$sorted_menu_objects = '';
						}
					}
				}
			}
			return $sorted_menu_objects;
		}
	}

	$menu_visibility = new Menu_Visibility();
} // class_exists() ends