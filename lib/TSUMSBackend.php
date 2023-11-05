<?php
namespace lib;

/**
 * Class to handle the backend of the Matomo plugin, so you can enter all your settings 
 * comfortably at one place.
 *
 * @author marconagel
 */
defined( 'ABSPATH' ) or die( 'Are you ok?' );

class TSUMSBackend {
    //constructor
    public function __construct() {
        //add backend panes
        add_action('admin_menu', array($this, 'tsu_ms_add_menu_items'));
        //add plugin settings
        add_action( 'admin_init', array($this, 'tsu_ms_register_settings'));
    }
    /**
     * tsu_ms_add_menu_items()
     * Adds items to the administrative pane of the ig management plugin Dashboard
     */
    public function tsu_ms_add_menu_items() {
        //main page
        add_menu_page(esc_html__( 'Dashboard', 'tsu-matomo-simple' ), esc_html__( 'Matomo Simple', 'tsu-matomo-simple' ), 'manage_options',
                'tsu_matomo_simple', array($this, 'tsu_ms_dash'), '', '25');
    } 
    /*
     * itsu_ms_dash_dash()
     * *******************
     * Display Plugin Dashboard
     */
    public function tsu_ms_dash() { 
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php echo esc_html__( 'Matomo Simple Integration', 'tsu-matomo-simple' ); ?></h1>
            <hr class="wp-header-end">
            <p><?php echo esc_html__( 'Main Dashboard page for displaying relevant plugin Information.', 'tsu-matomo-simple' ); ?></p>   
            <p><b><?php echo esc_html__( 'Plugin Version: ', 'tsu-matomo-simple' ) . TSU_MS_VERSION; ?></b></p>
            <form action="options.php" method="post">
                <?php
                    settings_fields('tsu_matomo_simple_options');
                    do_settings_sections('tsu_matomo_simple');
                ?>
                <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>" />
            </form> 
        </div>
        <?php        
    }
    function tsu_matomo_simple_options_validate($input) {
        //Site ID Check
        $newinput['site_id'] = trim($input['site_id']);
        if (!is_numeric ($newinput['site_id'])) {
            $newinput['site_id'] = '';
        }
        //URL Sanitize check
        if (!empty($input['url'])) {
            $newinput['url'] = sanitize_url($input['url'], array( 'http', 'https' ));
        }
        
        return $newinput;
    }
    public function tsu_ms_register_settings() {
        register_setting( 'tsu_matomo_simple_options', 'tsu_matomo_simple_options', 'tsu_matomo_simple_options_validate' );
        add_settings_section( 'matomojs_settings', esc_html__( 'Matomo JS snippet settings', 'tsu-matomo-simple' ), array($this, 'tsu_matomo_simple_section_text'), 'tsu_matomo_simple' );

        add_settings_field( 'tsu_matomo_simple_setting_url', esc_html__( 'Matomo Server URL', 'tsu-matomo-simple' ), array($this, 'tsu_matomo_simple_setting_url'), 'tsu_matomo_simple', 'matomojs_settings' );
        add_settings_field( 'tsu_matomo_simple_setting_site_id', esc_html__( 'Site ID (This Site)', 'tsu-matomo-simple' ), array($this, 'tsu_matomo_simple_setting_site_id'), 'tsu_matomo_simple', 'matomojs_settings' );
        add_settings_field( 'tsu_matomo_simple_setting_cookie_domain', esc_html__( 'Track users on all Subdomains of', 'tsu-matomo-simple' ) . ' ' . get_site_url(), array($this, 'tsu_matomo_simple_setting_cookie_domain'), 'tsu_matomo_simple', 'matomojs_settings' );
        add_settings_field( 'tsu_matomo_simple_setting_doc_title', esc_html__( 'Set domain in front of page title', 'tsu-matomo-simple' ), array($this, 'tsu_matomo_simple_setting_doc_title'), 'tsu_matomo_simple', 'matomojs_settings' );
        add_settings_field( 'tsu_matomo_simple_setting_set_domains', esc_html__( 'Hide clicks on known alias-URLS of this site in report', 'tsu-matomo-simple' ), array($this, 'tsu_matomo_simple_setting_set_domains'), 'tsu_matomo_simple', 'matomojs_settings' );
        add_settings_field( 'tsu_matomo_simple_setting_noscript', esc_html__( 'Track users with deactivated Javascript', 'tsu-matomo-simple' ), array($this, 'tsu_matomo_simple_setting_noscript'), 'tsu_matomo_simple', 'matomojs_settings' );
        add_settings_field( 'tsu_matomo_simple_setting_do_not_track', esc_html__( 'Enable client-site Do-Not-Track recognition', 'tsu-matomo-simple' ), array($this, 'tsu_matomo_simple_setting_do_not_track'), 'tsu_matomo_simple', 'matomojs_settings' );
        add_settings_field( 'tsu_matomo_simple_setting_deactivate_all_cookies', esc_html__( 'Disable all tracking cookies for this site', 'tsu-matomo-simple' ), array($this, 'tsu_matomo_simple_setting_deactivate_all_cookies'), 'tsu_matomo_simple', 'matomojs_settings' );
    }
    public function tsu_matomo_simple_section_text() {
        echo esc_html__( 'Some of these settings might be affected by how you setup your Matomo instance on your server.', 'tsu-matomo-simple' );
    }
    public function tsu_matomo_simple_setting_url() {
        $options = get_option( 'tsu_matomo_simple_options' );
        echo "<input id='tsu_matomo_simple_setting_url' name='tsu_matomo_simple_options[url]' type='text' value='" . esc_attr( $options['url'] ?? '' ) . "' />";
    }   
    public function tsu_matomo_simple_setting_site_id() {
        $options = get_option( 'tsu_matomo_simple_options' );
        echo "<input id='tsu_matomo_simple_setting_site_id'' name='tsu_matomo_simple_options[site_id]' type='text' value='" . esc_attr( $options['site_id'] ?? '' ) . "' />";
    }     
    public function tsu_matomo_simple_setting_cookie_domain() {
        $options = get_option( 'tsu_matomo_simple_options' );
        echo "<input id='tsu_matomo_simple_setting_cookie_domain' name='tsu_matomo_simple_options[cookie_domain]' type='checkbox' value='1' " . checked( 1, $options['cookie_domain'] ?? false, false ) . " />";
    }   
    public function tsu_matomo_simple_setting_doc_title() {
        $options = get_option( 'tsu_matomo_simple_options' );
        echo "<input id='tsu_matomo_simple_setting_doc_title' name='tsu_matomo_simple_options[doc_title]' type='checkbox' value='1' " . checked( 1, $options['doc_title'] ?? false, false ) . " />";
    }     
    public function tsu_matomo_simple_setting_set_domains() {
        $options = get_option( 'tsu_matomo_simple_options' );
        echo "<input id='tsu_matomo_simple_setting_set_domains' name='tsu_matomo_simple_options[set_domains]' type='checkbox' value='1' " . checked( 1, $options['set_domains'] ?? false, false ) . " />";
    }      
    public function tsu_matomo_simple_setting_noscript() {
        $options = get_option( 'tsu_matomo_simple_options' );
        echo "<input id='tsu_matomo_simple_setting_noscript' name='tsu_matomo_simple_options[noscript]' type='checkbox' value='1' " . checked( 1, $options['noscript'] ?? false, false ) . " />";
    }   
    public function tsu_matomo_simple_setting_do_not_track() {
        $options = get_option( 'tsu_matomo_simple_options' );
        echo "<input id='tsu_matomo_simple_setting_do_not_track' name='tsu_matomo_simple_options[do_not_track]' type='checkbox' value='1' " . checked( 1, $options['do_not_track'] ?? false, false ) . " />";
    } 
    public function tsu_matomo_simple_setting_deactivate_all_cookies() {
        $options = get_option( 'tsu_matomo_simple_options' );
        echo "<input id='tsu_matomo_simple_setting_deactivate_all_cookies' name='tsu_matomo_simple_options[deactivate_all_cookies]' type='checkbox' value='1' " . checked( 1, $options['deactivate_all_cookies'] ?? false, false ) . " />";
    }    
    
}
