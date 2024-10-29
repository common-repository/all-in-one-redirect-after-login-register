<?php
/**
 * @wordpress-plugin
 * Plugin Name:   All In One Redirect After Login Register
 * Plugin URI:    https://worldwincoder.com/product/woocommerce-redirect-after-registration-login-and-logout/
 * Description:   The "All in One Redirect After Login Register" is a complimentary plugin for WooCommerce that allows you to easily configure the pages to which users will be automatically redirected after they register, login, or logout. Best of all, it's completely free!
 * Version:       1.0.1
 * Author:        WorldWin Coder Pvt. Ltd.
 * Author URI:    https://worldwincoder.com/
 * Text Domain:   all-in-one-redirect-after-login-register
 * Domain Path:   /languages/
 * License:       GPLv2 or later
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 * You should have received a copy of the GNU General Public License
 * along with All In One Course Review. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 * @package All_In_One_Redirect_After_Login_Register
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class All_In_One_Redirect_After_Login_Register{
    public function __construct() {
        add_action( 'admin_menu',                                           array( $this, 'all_in_one_redirect_after_login_register_admin_menu' ) );
        add_action( 'admin_enqueue_scripts',                                array( $this, 'all_in_one_redirect_after_login_register_admin_script' ) );
        add_action( 'wp_enqueue_scripts',                                   array( $this, 'all_in_one_redirect_after_login_register_public_script' ) );
        add_filter( 'woocommerce_login_redirect',                           array( $this, 'redirect_after_login' ), 10, 2 );
        add_filter( 'woocommerce_registration_redirect',                    array( $this, 'redirect_after_registration' ), 10, 1 );
        add_filter( 'login_redirect',                                       array( $this, 'redirect_after_wp_login' ), 10, 3 );
        add_action( 'wp_logout',                                            array( $this, 'redirect_after_logout' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),   array( $this, 'All_In_One_Redirect_After_Login_Register_Settings_links' ) );

    }

    /**
     * Add the menu page.
     */
    public function All_In_One_Redirect_After_Login_Register_admin_menu() {
        add_menu_page(
            __( 'All In One Redirect', 'all-in-one-redirect-after-login-register' ),
            __( 'All In One Redirect', 'all-in-one-redirect-after-login-register' ),
            'manage_options',
            'All_In_One_Redirect',
            [ $this, 'All_In_One_Redirect_After_Login_Register_admin_page' ],
            'dashicons-external',57
        );
    }

    /**
     * Enqueue plugin scripts and styles.
     */
    public function all_in_one_redirect_after_login_register_admin_script() {
        wp_enqueue_script( 'all_in_one_redirect_after_login_register_admin_scripts', plugin_dir_url( __FILE__ ) . 'assets/admin/js/AllInOneRedirectAfterLoginRegister.js', array(), filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/AllInOneRedirectAfterLoginRegister.js' ), true );
        wp_enqueue_style( 'all_in_one_redirect_after_login_register_admin_styles', plugin_dir_url( __FILE__ ) . 'assets/admin/css/AllInOneRedirectAfterLoginRegister.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/AllInOneRedirectAfterLoginRegister.css' ), 'all' );
    }

    /**
     * Enqueue plugin scripts, ajax and styles.
     */
    public function all_in_one_redirect_after_login_register_public_script() {
        wp_enqueue_style( 'all_in_one_redirect_after_login_register_public_styles', plugin_dir_url( __FILE__ ) . 'assets/public/css/AllInOneRedirectAfterLoginRegister.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/AllInOneRedirectAfterLoginRegister.css' ), 'all' );
    }

    /**
     * Redirects users to a custom URL after login based on their user role.
     *
     * @param string $redirect_url The default redirect URL.
     * @param object $user The user object.
     * @return string The custom redirect URL.
     */
    public function redirect_after_login( $redirect_url, $user ) {
        $redirect_array = get_option( 'AIORALR_Data' );
        if ( isset( $user->roles[0] ) ) {
            $current_user_role = $user->roles[0];
            if ( ! empty( $redirect_array[ 'login_' . $current_user_role ] ) ) {
                $redirect_url = $redirect_array[ 'login_' . $current_user_role ];
            }
        }
        // Return the custom redirect URL.
        return $redirect_url;
    }


    /**
     * Redirects users to a custom URL after registration based on their user role.
     *
     * @param string $redirect_url The default redirect URL.
     * @return string The custom redirect URL.
     */
    public function redirect_after_registration( $redirect_url ) {
        $redirect_array = get_option( 'AIORALR_Data' );
        $user = wp_get_current_user();
        if ( isset( $user->roles[0] ) ) {
            $current_user_role = $user->roles[0];
            if ( ! empty( $redirect_array[ 'register_' . $current_user_role ] ) ) {
                $redirect_url = $redirect_array[ 'register_' . $current_user_role ];
            }
        }
        // Return the custom redirect URL.
        return $redirect_url;
    }


    /**
     * Redirects users to a custom URL after logging into WordPress based on their user role.
     *
     * @param string $redirect_url The default redirect URL.
     * @param string $request The originally requested URL.
     * @param WP_User $user The logged-in user.
     * @return string The custom redirect URL.
     */
    public function redirect_after_wp_login( $redirect_url, $request, $user ) {
        $redirect_array = get_option( 'AIORALR_Data' );
        if ( isset( $user->roles[0] ) ) {
            $current_user_role = $user->roles[0];
            if ( ! empty( $redirect_array[ 'login_' . $current_user_role ] ) ) {
                $redirect_url = $redirect_array[ 'login_' . $current_user_role ];
            }
        }
        // Return the custom redirect URL.
        return $redirect_url;
    }



    /**
     * Redirects users to a custom URL after logging out of WordPress based on their user role.
     */
    public function redirect_after_logout() {
        $redirect_array = get_option( 'AIORALR_Data' );
        $user = wp_get_current_user();
        if ( isset( $user->roles[0] ) ) {
            $current_user_role = $user->roles[0];
            if ( ! empty( $redirect_array[ 'logout_' . $current_user_role ] ) ) {
                $redirect_url = $redirect_array[ 'logout_' . $current_user_role ];
            } else {
                $redirect_url = home_url();
            }
        } else {
            $redirect_url = home_url();
        }
        wp_redirect( $redirect_url );
        exit();
    }



    /**
     * Displays the All In One Redirect After Login & Register plugin's admin page
     */
    public function all_in_one_redirect_after_login_register_admin_page() {   
        wp_enqueue_style( 'all_in_one_redirect_after_login_register_admin_scripts' );
        wp_enqueue_script( 'all_in_one_redirect_after_login_register_admin_styles' );
        wp_enqueue_script( 'all_in_one_redirect_after_login_register_public_styles' );
    
        global $wp_roles;
        $roles = $wp_roles->roles;
    
        /*
         * Settings page form data
         */

        if ( isset( $_POST['allinoneredirect_submit'] ) && $_POST['allinoneredirect_submit'] === 'Save' ) {

            /*
             * Verify Nonce
             */
            if ( ! wp_verify_nonce( $_REQUEST['allinoneredirect_generate_nonce'], 'allinoneredirect_form_submit' ) ) {
                wp_die( esc_html__( 'Sorry, your nonce did not verify.', 'all-in-one-redirect-after-login-register' ) );
            }
            $data_array = array();
            $allowed_filters = array(
                'login_'    => FILTER_VALIDATE_URL,
                'register_' => FILTER_VALIDATE_URL,
                'logout_'   => FILTER_VALIDATE_URL,
            );
            foreach ( $roles as $key => $value ) {
                foreach ( $allowed_filters as $filter_key => $filter ) {
                    $post_key = $filter_key . $key;
                    if ( empty( $_POST[ $post_key ] ) ) {
                        $data_array[ $post_key ] = '';
                    } else {
                        $data_array[ $post_key ] = esc_url_raw( filter_var( $_POST[ $post_key ], $filter ) );
                    }
                }
            }
            update_option( 'AIORALR_Data', $data_array );
            printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html__( 'Settings saved.', 'all-in-one-redirect-after-login-register' ) );
        }
    
        $AIORALR_data = get_option( 'AIORALR_Data' );
        ?>
        <h1 class="all-in-one-redirect text-center"><?php esc_html_e( 'All In One Redirect After Login & Register', 'all-in-one-redirect-after-login-register' ); ?> </h1>
        <table class="widefat table" role="presentation">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'User Role', 'all-in-one-redirect-after-login-register' ); ?></th>
                    <th><?php esc_html_e( 'Login Redirect', 'all-in-one-redirect-after-login-register' ); ?></th>
                    <th><?php esc_html_e( 'Registration Redirect', 'all-in-one-redirect-after-login-register' ); ?></th>
                    <th><?php esc_html_e( 'Logout Redirect', 'all-in-one-redirect-after-login-register' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <form method="post" action="" name="allinoneredirect_form_submit_data">
                    <?php wp_nonce_field( 'allinoneredirect_form_submit', 'allinoneredirect_generate_nonce' ); ?>
    
                    <?php foreach ( $roles as $key => $value ) {
                        $login_url    = ! empty( $AIORALR_data[ 'login_' . $key ] ) ? $AIORALR_data[ 'login_' . $key ] : '';
                        $register_url = ! empty( $AIORALR_data[ 'register_' . $key ] ) ? $AIORALR_data[ 'register_' . $key ] : '';
                        $logout_url   = ! empty( $AIORALR_data[ 'logout_' . $key ] ) ? $AIORALR_data[ 'logout_' . $key ] : '';
                        ?>
                        <tr class="all-in-one-redirect-row">
                            <td class="user_role_system"><?php echo esc_html( ucfirst( $key ) ); ?></td>
                            <td class="">
                                <input type="url" placeholder="https://" name="login_<?php echo esc_html ($key); ?>" class="login_input" value="<?php echo esc_url( $login_url ); ?>">
                                <p><?php echo esc_html__( 'Enter URL for ', 'all-in-one-redirect-after-login-register' ) . '<b>' . esc_html( ucfirst( $key ) ) . '</b>' . esc_html__( ' Redirect After Login.', 'all-in-one-redirect-after-login-register' ); ?> </p>

                            </td>
                            <td class="">
                                
                                <input type="url" placeholder="https://" name="register_<?php echo esc_html ($key); ?>" class="register_input" value="<?php echo esc_url( $register_url ); ?>">
                                <p><?php echo esc_html__( 'Enter URL for ', 'all-in-one-redirect-after-login-register' ) . '<b>' . esc_html( ucfirst( $key ) ) . '</b>' . esc_html__( ' Redirect After Register.', 'all-in-one-redirect-after-login-register' ); ?> </p>

                            </td>
                            <td class="">
                                <input type="url" placeholder="https://" name="logout_<?php echo esc_html ($key); ?>" class="logout_input" value="<?php echo esc_url( $logout_url ); ?>">
                                <p><?php echo esc_html__( 'Enter URL for ', 'all-in-one-redirect-after-login-register' ) . '<b>' . esc_html( ucfirst( $key ) ) . '</b>' . esc_html__( ' Redirect After Logout.', 'all-in-one-redirect-after-login-register' ); ?> </p>

                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="4" class="all-in-one-cls-footer">
                            <input type="submit" name="allinoneredirect_submit" value="<?php esc_attr_e( 'Save', 'all-in-one-redirect-after-login-register' ); ?>" class="button button-primary allinone-submit">
                        </td>
                    </tr>
               </form>
               <tr>
                    <td colspan="4" class="all-in-one-cls-footer">
                        <p><?php printf( __( 'Developed by <a href="%s" target="_blank">WorldWin Coder Pvt. Ltd.</a> || <a href="%s" target="_blank">Contact us for WordPress Customization and Development</a>', 'all-in-one-redirect-after-login-register' ), esc_url( 'https://worldwincoder.com/' ), esc_url( 'https://worldwincoder.com/contact/' ) ); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php 
    }
    /**
     * Adds a link to the plugin's settings page on the WordPress plugins page
     *
     * @param array $links The existing links on the plugins page
     * @return array The modified array of links
     */
    public function all_in_one_redirect_after_login_register_settings_links( $links ) {
        // Add Settings link
        $links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=All_In_One_Redirect' ) ) . '">' . esc_html__( 'Settings', 'all-in-one-redirect-after-login-register' ) . '</a>';
        return $links;
    }      
}new All_In_One_Redirect_After_Login_Register();