<?php

class Adverts_Block_Details {
    
    public $path = null;
    
    public function __construct() {
        add_action( "init", array( $this, "init" ) );
    }
    
    public function init() {
        
        $package = "wpadverts";
        $module = "details";
        
        $js_handler = sprintf( "block-%s-%s", $package, $module );
        
        // automatically load dependencies and version
        $asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');

        $this->path = dirname(__FILE__);
        
        wp_register_style(
            'wpadverts-blocks-editor-details',
            ADVERTS_URL . '/assets/css/blocks-editor-details.css',
            array( 'wp-edit-blocks' ),
            filemtime( ADVERTS_PATH . '/assets/css/blocks-editor-details.css' )
        );

        wp_register_script(
            $js_handler,
            plugins_url( 'build/index.js', __FILE__ ),
            $asset_file['dependencies'],
            $asset_file['version']
        );

        wp_register_script(
            "wpadverts-block-details",
            ADVERTS_URL . '/assets/js/block-details.js',
            array( 'jquery' ),
            '2.0.0'
        );
        
        register_block_type( sprintf( "%s/%s", $package, $module ), array(
            'apiVersion' => 2,
            'editor_style' => 'wpadverts-blocks-editor-details',
            'editor_script' => $js_handler,
            'render_callback' => array( $this, "render" ),
            'style' => 'wpadverts-blocks',
            'script' => 'wpadverts-block-details',
            'attributes' => array(
                'post_type' => array(
                    'type' => 'string'
                )
            )
        ) );

    }
    
    public function render( $atts = array() ) {

        $params = shortcode_atts(array(
            'name' => 'default',
            'post_type' => 'advert'
        ), $atts, 'adverts_details' );

        extract( $params );

        $post_id = get_the_ID();
        
        $post_content = get_post( $post_id )->post_content;
        $post_content = wp_kses($post_content, wp_kses_allowed_html( 'post' ) );
        $post_content = apply_filters( "adverts_the_content", $post_content );

        $data_table = array(
            array(
                "label" => __( "Category", "wpadverts" ),
                "icon" => "fas fa-folder",
                "value" => $this->_get_categories_parsed( $post_id )
            ),
            array(
                "label" => __( "Location", "wpadverts" ),
                "icon" => "fas fa-map-marker-alt",
                "value" => $this->_get_location_parsed( $post_id )
            )

        );

        $template = dirname( __FILE__ ) . "/templates/single.php";
        ob_start();
        include $template;
        return ob_get_clean();
    }
    
    protected function _get_categories_parsed( $post_id ) {
        $advert_category = get_the_terms( $post_id, 'advert_category' );

        if( empty( $advert_category ) ) {
            return false;
        }

        ob_start();
        foreach($advert_category as $c) {
            ?>
            <a href="<?php echo esc_attr( get_term_link( $c ) ) ?>"><?php echo join( " / ", advert_category_path( $c ) ) ?></a><br/>
            <?php
        }

        return ob_get_clean();
    }

    protected function _get_location_parsed( $post_id ) {
        $location = get_post_meta( $post_id, "adverts_location", true );

        if( empty( $location ) ) {
            return false;
        }

        return apply_filters( "adverts_tpl_single_location", esc_html( $location ), $post_id );
    }
}