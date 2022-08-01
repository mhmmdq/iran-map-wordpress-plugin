<?php
namespace IranMap;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( !function_exists('iran_map_view') ) {
    function iran_map_view( $view, $data = [] ) {
        $view = str_replace( '.', DIRECTORY_SEPARATOR, $view );
        $view_path = IRMAP_PLUGIN_DIR . 'includes'. DIRECTORY_SEPARATOR .'views'. DIRECTORY_SEPARATOR . $view . '.php';
        if( file_exists($view_path) ) {
            extract($data);
            include $view_path;
        }
    }
}

if( !class_exists('IranMapPlugin') ) {

    class IranMapPlugin {

        const VERSION = '1.0.0';

        const MINIMUN_ELEMENTOR_VERSION = '2.0.0';

        const MINIMUN_PHP_VERSION = '7.0';

        public function __construct()
        {
            $this->init();
        }

        public function init()
        {
            if( $this->check_required_plugins() && $this->check_the_minimum_required_version() ) {
                $this->includes();
                $this->hooks();
            }
        }

        public function hooks() {
            add_action('init', array($this, 'register_widgets'));
            add_action('init', array( new CreatePostTypes , 'register_post_types'));
            add_filter('enter_title_here', array( new CreatePostTypes , 'change_title_text'));
            add_action('init', array( new CreatePostTypes , 'register_taxonomies'));
            add_action('admin_init' , array( $this , 'insert_categories'));
            add_action('wp_ajax_nopriv_iran_map_get_cities', array( new Ajax , 'get_cities'));
            add_action('wp_ajax_iran_map_get_cities', array( new Ajax , 'get_cities'));
            add_action('wp_head', array( $this , 'add_ajaxUrl'));
            add_action('add_meta_boxes', array( new CreatePostTypes , 'vendors_box'));
            add_action('save_post', array( new CreatePostTypes , 'save_post'));
            add_action('show_user_profile', array( new Users , 'render_template'));
            add_action('edit_user_profile', array( new Users , 'render_template'));
            add_action('personal_options_update', array( new Users , 'save_user'));
            add_action('edit_user_profile_update', array( new Users , 'save_user'));

            add_action('the_posts', array( new Post , 'change_content'));
            add_action('wp_enqueue_scripts', array( $this , 'add_ajax_script'));
            add_action('wp_ajax_nopriv_iran_map_ajax_get_sellers' , array( new Ajax , 'change_categories_to_sellers_page' ));
            add_action('wp_ajax_iran_map_ajax_get_sellers' , array( new Ajax , 'change_categories_to_sellers_page' ));
            add_action('add_meta_boxes', array( new Products , 'add_categories_meta_box'));
            add_action('save_post', array( new Products , 'save'));
            add_action('pre_get_posts' , array( new Post , 'pre_get_post' )); 
            add_action ('iran_map_product_category_add_form_fields', [new CreatePostTypes , 'extra_category_fields']);
            add_action ('iran_map_product_category_edit_form_fields', [new CreatePostTypes , 'extra_category_fields']);
            add_action('edited_iran_map_product_category', [new CreatePostTypes , 'save_extra_category_fields']);
            add_action('saved_iran_map_product_category', [new CreatePostTypes , 'save_extra_category_fields']);
        }

        public function add_ajaxUrl() {
            ?>
            <script>
                var ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
            </script>
            <?php
        }

        public function includes() {
            $classes = glob(IRMAP_PLUGIN_DIR . 'includes/classes/*.php');
            
            foreach ($classes as $class) {
                require_once $class;
            }

        }


        public function check_required_plugins() {

            $return = true;
            $error_message = '<p> <strong>نقشه ایران : </strong> این افزونه برای اجرا نیازمند نصب افزونه های زیر است.</p>';
            $error_message .= '<ul>';
            if( !is_plugin_active('woocommerce/woocommerce.php') )  {
                $error_message .= '<li> <a class="thickbox open-plugin-details-modal" href="'. site_url() .'/wp-admin/plugin-install.php?tab=plugin-information&plugin=woocommerce&TB_iframe=true&width=600&height=550">ووکامرس - برای نصب کلیک کنید</a> </li>';
                $return = false;
            }
            if( !is_plugin_active('dokan-lite/dokan.php') )  {
                $error_message .= '<li> <a class="thickbox open-plugin-details-modal" href="'. site_url() .'/wp-admin/plugin-install.php?tab=plugin-information&plugin=dokan-lite&TB_iframe=true&width=600&height=550">دکان - برای نصب کلیک کنید</a> </li>';
                $return = false;
            }
            if( !is_plugin_active('elementor/elementor.php') ) {
                $error_message .= '<li> <a class="thickbox open-plugin-details-modal" href="'. site_url() .'/wp-admin/plugin-install.php?tab=plugin-information&plugin=elementor&TB_iframe=true&width=600&height=550">المنتور - برای نصب کلیک کنید</a> </li>';
                $return = false;
            }
            $error_message .= '</ul>';
            $error_message .= '<p> <strong>درصورت نصب بودن افزونه ها آنها را فعال کنید</strong> </p>';

            if( !$return ) {
                add_action('admin_notices', function() use ($error_message) {
                    echo '<div class="error">'.$error_message.'</div>';
                });
            }

            return $return;

        }

        public function check_the_minimum_required_version() {

            if( !version_compare( PHP_VERSION, self::MINIMUN_PHP_VERSION, '>=' ) ) {
                add_action('admin_notices', function() {
                    echo '<div class="error">
                        <p>
                            <strong>
                                نقشه ایران : 
                            </strong>
                            نسخه پی اچ پی شما کمتر از نسخه '. self::MINIMUN_PHP_VERSION .' است.
                        </p>
                    </div>';
                });
                return false;
            }

            if( !version_compare( ELEMENTOR_VERSION, self::MINIMUN_ELEMENTOR_VERSION, '>=' ) ) {
                add_action('admin_notices', function() {
                    echo '<div class="error">
                        <p>
                            <strong>
                                نقشه ایران : 
                            </strong>
                            نسخه المنتور شما کمتر از نسخه '. self::MINIMUN_ELEMENTOR_VERSION .' است.
                        </p>
                    </div>';
                });
                return false;
            }

            return true;
        }

        public function register_widgets() {
            require_once IRMAP_PLUGIN_DIR . '/includes/widgets/IranMap.php';
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \IranMap\Widget\IranMap() );
        }

        public function add_elementor_category() {
            \Elementor\Plugin::instance()->elements_manager->add_category(
                'iran-map',
                [
                    'title' => 'نقشه ایران',
                    'icon' => 'fa fa-map',
                ],
                1
            );
        }
        
        public function get_all_categories() {
            return include_once IRMAP_PLUGIN_DIR . '/includes/categories.php';
        }

        public function insert_categories() {
            $categories = $this->get_all_categories();
            foreach ($categories as $category) {
                
                if( !term_exists( $category['name'], 'iran_map_state' ) ) {
                    wp_insert_term(
                        $category['name'],
                        'iran_map_state',
                        [
                            'description' => $category['description'],
                            'slug' => $category['slug'],
                        ]
                    );
                }

            }
        }

        public function add_ajax_script() {
            wp_enqueue_script('iran-map-ajax', IRMAP_PLUGIN_URL . 'assets/js/iran-map-ajax.js', array('jquery'), '1.0.1', true);
        }
        

    }
    
}