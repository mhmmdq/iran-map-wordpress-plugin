<?php
namespace IranMap;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class CreatePostTypes {

    public function change_title_text( $title ) {
        $screen = get_current_screen();
        if ( 'iran_map_city' == $screen->post_type ) {
            $title = 'نام شهر';
        }
        return $title;
    }


    public function register_post_types() {
        register_post_type( 'iran_map_city', [
            'labels' => [
                'name' => 'نقشه ایران',
                'singular_name' => 'شهر',
                'add_new_item' => 'اضافه کردن شهر جدید',
                'edit_item' => 'ویرایش شهر',
                'view_item' => 'نمایش شهر',
                'all_items' => 'همه شهر ها',
                'search_items' => 'جستجوی شهر ها',
                'not_found' => 'شهری یافت نشد',
                'not_found_in_trash' => 'شهری یافت نشد در سطل زباله',
                'add_new' => 'اضافه کردن شهر',
                'title_promot' => 'نام شهر',
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => [
                'slug' => 'city',
            ],
            'supports' => [
                'title',
                'elementor',
                'thumbnail',
				'editor',
            ],
            'menu_icon' => 'dashicons-location-alt',
            'menu_position' => 3,
        ] );
    }

    public function register_taxonomies() {
        register_taxonomy( 'iran_map_state', 'iran_map_city', [
            'labels' => [
                'name' => 'استان ها',
                'singular_name' => 'استان',
                'add_new_item' => 'اضافه کردن استان جدید',
                'edit_item' => 'ویرایش استان',
                'view_item' => 'نمایش استان',
                'all_items' => 'همه استان ها',
                'search_items' => 'جستجوی استان ها',
                'not_found' => 'استانی یافت نشد',
                'not_found_in_trash' => 'استانی یافت نشد در سطل زباله',
                'add_new' => 'اضافه کردن استان',
                'title_promot' => 'نام استان',
            ],
            'public' => true,
            
            'hierarchical' => true,
            'rewrite' => [
                'slug' => 'state',
            ],
            
            'show_admin_column' => true,
            'show_in_rest' => true,
        ] );

        register_taxonomy('iran_map_product_category' , 'iran_map_city' , [
            'lables' => [
                'name' => 'دسته بندی محصولات',
                'singular_name' => 'دسته بندی محصولات',
                'add_new_item' => 'اضافه کردن دسته بندی محصولات جدید',
                'edit_item' => 'ویرایش دسته بندی محصولات',
                'view_item' => 'نمایش دسته بندی محصولات',
                'all_items' => 'همه دسته بندی محصولات',
                'search_items' => 'جستجوی دسته بندی محصولات',
                'not_found' => 'دسته بندی محصولاتی یافت نشد',
                'not_found_in_trash' => 'دسته بندی محصولاتی یافت نشد در سطل زباله',
                'add_new' => 'اضافه کردن دسته بندی محصولات',
                'title_promot' => 'نام دسته بندی محصولات',
            ],
            'public' => true,
            'supports' => [ 'thumbnail' ],
            
            'hierarchical' => true,
            'rewrite' => [
                'slug' => 'iran-map-product-category',
            ],
            
            'show_admin_column' => true,
            'show_in_rest' => true,
        ]);

        // register taxonomy with thumbnail
         

    }
    public function vendors_box() {
        add_meta_box(
            'iran_map_city_vendors',
            'فروشنده های این شهر',
            array( $this, 'render_vendors_box' ),
            'iran_map_city',
            'normal',
            'high'
        );
    }
    public function render_vendors_box( $post ) {
        
        $sellers_in_city = get_post_meta( $post->ID, 'iran_map_sellers' , true);
        
        if ( ! $sellers_in_city || empty( $sellers_in_city ) ) {
            $sellers_in_city = array();
            $sellers_in_city = json_encode( $sellers_in_city );
            
        }
        $sellers_in_city = str_replace( '"', "'", $sellers_in_city );

        $sellers = dokan_get_sellers([ 'number' => 100 ]);        

        require_once IRMAP_PLUGIN_DIR . 'includes/views/vendor-meta-box.php';

        

    }

    public function save_post( $post_id ) {

        if(isset($_POST['iran_map_sellers'])) {
            $sellers = $_POST['iran_map_sellers'];
            update_post_meta( $post_id, 'iran_map_sellers', $sellers );
        }

    }

    public function extra_category_fields( $tag ) {
        $taxonomy = 'iran_map_product_category';
        $term_id = $tag->term_id;

        $t_id = $tag->term_id;
        $cat_meta = get_option( "category_$t_id");  
        
        

        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="cat_Image_url">آدرس تصویر این دسته بندی</label>
            </th>
            <td>
                <input type="text" name="category_image" id="Cat_meta[img]" size="3" style="width:60%;" value="<?php echo $cat_meta['img'] ? $cat_meta['img'] : ''; ?>"><br />
                <span class="description">آدرس تصویر را در اینجا وارد کنید</span>
            </td>
        </tr>
        <?php
    }

    public function save_extra_category_fields( $term_id ) {

        $term = get_term( $term_id, 'iran_map_product_category' );

        $cat_meta = get_option( "category_$term_id");
        $cat_meta = $cat_meta ? $cat_meta : array();


        if(isset($_REQUEST['category_image'])) {
            $cat_meta['img'] = $_REQUEST['category_image'];
            update_option( "category_$term_id", $cat_meta );
        }
    }


}

