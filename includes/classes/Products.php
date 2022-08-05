<?php
namespace IranMap;

defined( "ABSPATH" ) or die( "No script kiddies please!" );

class Products {

    public function add_categories_meta_box() {

        add_meta_box(
            'iran_map_product_category',
            'دسته بندی نقشه',
            [$this, 'render_categories_meta_box'],
            'product',
            'side',
            'default'
        );
        

    }

    public function render_categories_meta_box() {

        $selected = get_post_meta( get_the_ID(), 'iran_map_product_category', true );

        

        $categories = get_terms( [
            'taxonomy' => 'iran_map_product_category',
            'hide_empty' => false,
        ] );

        $html = '<ul>';
		$html .= '<li><input type="radio" name="iran_map_product_category" value="0"> هیچ کدام</li>';
        foreach( $categories as $category ) {
            $checked = '';
            if( $selected == $category->term_id )
            {
                $checked = 'checked';
            }
            
            $html .= '<li><input type="radio" name="iran_map_product_category" value="' . $category->term_id . '"  '. $checked .'  /> ' . $category->name . '</li>';
        }
        $html .= '</ul>';

        echo $html;


    }

    public function save() {
            
            if( isset( $_POST['iran_map_product_category'] ) ) {
                update_post_meta( get_the_ID(), 'iran_map_product_category', $_POST['iran_map_product_category'] );
            }
            
    }

}
