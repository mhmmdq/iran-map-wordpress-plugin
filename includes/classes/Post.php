<?php

namespace IranMap;

use WP_Query;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Post {


    public function change_content( $post ) {
        

        if( is_array($post) && count( $post ) > 0 )
        {
            if( $post[0]->post_type === 'iran_map_city' ) {
                
                
                // $post[0]->post_title = 'سوغات ' . $post[0]->post_title;
                $post[0]->post_author = 0;
                $post[0]->post_date = null;
                
                @$category_state = wp_get_object_terms( $post[0]->ID , 'iran_map_state' )['slug'];
                $product_categories = wp_get_object_terms( $post[0]->ID , 'iran_map_product_category' );
                $post[0]->post_content = '<link rel="stylesheet" href="' . IRMAP_PLUGIN_URL . 'assets/css/iranMap.css" />';
                $post[0]->post_content .= '<div id="iranmap-categories">';
    
                foreach( $product_categories as $categories ):
    
                    
                    $category_image = get_option( "category_".$categories->term_id )['img'];
                    $term_link = get_term_link( $categories->term_id , 'iran_map_product_category' );
                        $post[0]->post_content .= '<div class="iranmap-category">';

                            $post[0]->post_content .= '<div class="iranmap-category-image">';
                                $post[0]->post_content .= '<a class="iranMapAjax" href="#'. $categories->term_id .'"  data-term="'. $categories->term_id .'"><img src="' . $category_image . '" /></a>';
                            $post[0]->post_content .= '</div>';

                            $post[0]->post_content .= '<div class="iranmap-category-title">';
                            $post[0]->post_content .= '<a class="iranmap-category-title-link iranMapAjax" href="#'. $categories->term_id .'" data-term="'. $categories->term_id .'" >' . $categories->name . '</a>';
                            $post[0]->post_content .= '</div>';


                        $post[0]->post_content .= '</div>';
                    
    
                endforeach;
    
                $post[0]->post_content .= '</div>';
       
            }
        }
        
        

        

        return $post;

    }

    public function pre_get_post( $query ) {
        
        if( isset($_GET['vendor_id']) && isset($_GET['category_id']) ) {

           $vendor_id = $_GET['vendor_id'];
           $category_id = $_GET['category_id'];

           $query->set('author' , $vendor_id);
           $query->set('meta_query' , [
            [
                'key' => 'iran_map_product_category',
                'value' => $category_id,
                'compare' => '=',
                'type' => 'CHAR'
            ]
           ]);

        }


        return $query;

    }


}