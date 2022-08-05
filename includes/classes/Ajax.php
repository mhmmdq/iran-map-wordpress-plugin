<?php
namespace IranMap;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Ajax {

    protected function get_all_categories() {
        return include IRMAP_PLUGIN_DIR . 'includes/categories.php';
    }

    public function get_cities() {

        
        $categories = $this->get_all_categories();

        $states = [];

        foreach( $categories as $category ) {
            

            $args = [
                'post_type' => 'iran_map_city',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => [
                    [
                        'taxonomy' => 'iran_map_state',
                        'field' => 'slug',
                        'terms' => [$category['slug']],
                    ],
                ],
            ];

            $query = new \WP_Query( $args );


            if( $query->have_posts() ) {
                
                $states[$category['slug']] = [
                    'name' => $category['slug'],
                    'cities' => [],
                ];
                while( $query->have_posts() ) {
                    $query->the_post();
                    
                    $states[$category['slug']]['cities'][] = [
                        'id' => get_the_ID(),
                        'title' => get_the_title(),
                        'slug' => get_post_field( 'post_name', get_the_ID() ),
                        'url' => get_permalink(),
                    ];

                }
                
            }

        }

        echo json_encode($states);
        exit;
    }

    public function change_categories_to_sellers_page() {
        

        if(isset($_REQUEST['term']) && isset($_REQUEST['city_id'])) {
            $term_id = (int) trim($_REQUEST['term']);
            $city_id = (int) trim($_REQUEST['city_id']);
            global $wpdb;
            $posts = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` = 'iran_map_product_category' AND `meta_value` = '{$term_id}'" );
            
			$sellers_of_this_city = get_post_meta($city_id , 'iran_map_sellers' , 'true');
			$sellers_of_this_city = str_replace("'" , '"' , $sellers_of_this_city);
			$sellers_of_this_city = json_decode($sellers_of_this_city);

            $sellers = [];
            foreach($posts as $post) {
                $post_id = $post->post_id;
                $seller_id = (int) $wpdb->get_var( $wpdb->prepare( "SELECT post_author FROM {$wpdb->posts} WHERE ID = %d ", $post_id ) );

				if(in_array($seller_id , $sellers_of_this_city)) {
					 $sellers[$seller_id] = [
						'id' => $seller_id,
						'name' => dokan_get_vendor($seller_id)->get_shop_name(),
						'image' => get_user_meta($seller_id, 'iran_map_logo', true),
                	];
				}
               
            }
            
			$sellers_unique = [];
			foreach($sellers as $seller) {
				$sellers_unique[] = $seller;
			}
            
            echo json_encode($sellers_unique);

        }
        exit;

    }

}