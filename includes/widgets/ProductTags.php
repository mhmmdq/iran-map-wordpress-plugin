<?php
namespace IranMap\Widget;

use Elementor\Widget_Base;

use function IranMap\iran_map_view;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class IranMap extends Widget_Base {

    public function __construct( $data = array() , $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'iranMap-style', IRMAP_PLUGIN_URL . 'assets/css/iranMap.css', array(), '1.0.0' );   
        wp_register_script( 'iranMap-script' , IRMAP_PLUGIN_URL . 'assets/js/iranMap.js' , ['jquery'] , '1.0.0' , true );
    }

    public function get_name() {
        return 'iranMap-tags';
    }

    public function get_title() {
        return '';
    }

    public function get_icon() {
        return 'fa fa-map';
    }

    public function get_categories() {
        return array( 'iran_map' );
    }

    public function get_style_depends()
    {
        return ['iranMap-style'];
    }

    public function get_script_depends()
    {
        return ['iranMap-script'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Content',
            ]
        );
        $this->add_control(
            'map_type',
            [
                'label' => 'نوع نقشه',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'iran',
                'options' => [
                    'iran' => 'نقشه ایران',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        iran_map_view('widgets/iran-map');
    }

    protected function _content_template() {
        iran_map_view('widgets/iran-map');
    }
}