<?php 
/**
 * integral functions and definitions
 */

/**
 * Theme setup and custom theme supports.
 */
require get_template_directory() . '/inc/setup.php';

/**
* Enqueue Scripts.
*/
require get_template_directory() . '/inc/enqueue.php';

/**
 * Redux Framework Options.
 */
require get_template_directory() . '/inc/options.php';

/**
* Theme Welcome Page.
*/
require get_template_directory() . '/inc/welcome/theme-welcome.php';

/**
* Wordpress Bootstrap Nav Walker.
*/
require get_template_directory() . '/inc/wp_bootstrap_navwalker.php';

/**
 * Custom Comments.
 */
require get_template_directory() . '/inc/custom-comments.php';

/**
 * Extras.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Wordpress Customizer.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Register Widgets.
 */
require get_template_directory() . '/inc/widgets.php';

/**
* Load WooCommerce Functions.
*/
require get_template_directory() . '/inc/woocommerce.php';

/**
* TGM Plugin Activation.
*/
require get_template_directory() . '/inc/tgm-plugin-activation.php';

/**
* Theme Demo Import functions.
*/
require get_template_directory() . '/inc/theme-demo-import.php';

/**
* Upgrade Notice
*/
require get_template_directory() . '/inc/upgrade/class-customize.php';

/**
 * Retorna o número de voluntários
 */
function get_volunteers_counter() {
  global $wpdb;

  return $wpdb->get_var("select count(*) from Mensagens") + 2710;
}
add_shortcode('get_volunteers_counter', 'get_volunteers_counter');
