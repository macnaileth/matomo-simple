<?php
namespace lib;

/**
 * Core Class to start all the magic
 *
 * @author marconagel
 */
defined( 'ABSPATH' ) or die( 'Are you ok?' );

class TSUMSCore{
    
    //constructor
    public function __construct() {   
        //matomo
        add_action( 'wp_enqueue_scripts', array( $this, 'tsu_ms_add_matomo') );
        //backend startup
        require_once TSU_MS_PLUGIN_PATH . '/lib/TSUMSBackend.php';
        $start_be  = new TSUMSBackend();        
        
    } 
    /*
     * tsu_ms_add_matomo()
     * enques the needed javascript block to add site to matomo
     */
    public function tsu_ms_add_matomo(){
        
            //get needed options
            $options = get_option('tsu_matomo_simple_options');
            //pattern to match
            $pattern = '/(?:https?:\/\/)?(?:[a-zA-Z0-9.-]+?\.(?:[a-zA-Z])|\d+\.\d+\.\d+\.\d+)/';
            
            $noscript = isset( $options['noscript'] ) && $options['noscript'] === '1' ? '<noscript><p><img src="//' . $options['url'] . '/matomo.php?idsite=3&amp;rec=1" style="border:0;" alt="" /></p></noscript>' : '';
            
            if ( preg_match( $pattern, $options['url'] )) {
                 if( is_numeric( $options['site_id'] ) ) { 
                    //only do snippet if pattern and site id are valid
                    echo '<!-- Matomo -->
                            <script>
                              var _paq = window._paq = window._paq || [];
                              /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
                              ' . ( isset( $options['doc_title'] ) && $options['doc_title'] === '1' ? '_paq.push(["setDocumentTitle", document.domain + "/" + document.title]);' : '' ) . '
                              ' . ( isset( $options['cookie_domain'] ) && $options['cookie_domain'] === '1' ? '_paq.push(["setCookieDomain", "*.dev.blackwildcat.net"]);' : '' ) . '
                              ' . ( isset( $options['set_domains'] ) && $options['set_domains'] === '1' ? '_paq.push(["setDomains", ["*.dev.blackwildcat.net/dimb"]]);' : '' ) . '
                              ' . ( isset( $options['do_not_track'] ) && $options['do_not_track'] === '1' ? '_paq.push(["setDoNotTrack", true]);' : '' ) . '
                              ' . ( isset( $options['deactivate_all_cookies'] ) && $options['deactivate_all_cookies'] === '1' ? '_paq.push(["disableCookies"]);' : '' ) . '
                              _paq.push([\'trackPageView\']);
                              _paq.push([\'enableLinkTracking\']);
                              (function() {
                                var u="//' . $options['url'] . '/";
                                _paq.push([\'setTrackerUrl\', u+\'matomo.php\']);
                                _paq.push([\'setSiteId\', \'' . $options['site_id'] . '\']);
                                var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0];
                                g.async=true; g.src=u+\'matomo.js\'; s.parentNode.insertBefore(g,s);
                              })();
                            </script>
                            ' . $noscript . '
                          <!-- End Matomo Code -->';                     
                 } else {
                    echo '<!-- ' . esc_html__( 'Matomo script not inserted, site id is not valid', 'tsu-matomo-simple' ) . ' -->';                     
                 }
            } else {
                echo '<!-- ' . esc_html__( 'Matomo script not inserted, server URL is not valid', 'tsu-matomo-simple' ) . ' -->';
            }
    }
}
