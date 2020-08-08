<?php
namespace ElementorWpResidence\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wpresidence_Display_Categories extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'WpResidence_Display_Categories';
	}

        public function get_categories() {
		return [ 'wpresidence' ];
	}
        
        
	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WpResidence Display Categories', 'residence-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return ' eicon-product-categories';
	}

	

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
	return [ '' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
         public function elementor_transform($input){
            $output=array();
            if( is_array($input) ){
                foreach ($input as $key=>$tax){
                    $output[$tax['value']]=$tax['label'];
                }
            }
            return $output;
        }




        protected function _register_controls() {
          
                global $all_tax;
                
                $all_tax_elemetor=$this->elementor_transform($all_tax);
                
                $featured_listings  =   array('no'=>'no','yes'=>'yes');
                $items_type         =   array('properties'=>'properties','articles'=>'articles');
                $alignment_type     =   array('vertical'=>'vertical','horizontal'=>'horizontal');
                $type1_alignment    =   array('one row'=>'one row','multiple rows'=>'multiple rows');
                $list_cities_or_areas=  array(1=>1,2=>2);
                        
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'residence-elementor' ),
			]
		);

		$this->add_control(
			'place_list',
			[
                            'label' => __( 'Type Categories,Actions,Cities,Areas,Neighborhoods or County name you want to show', 'residence-elementor' ),
                            'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $all_tax_elemetor,
			]
		);
                
                $this->add_control(
			'place_per_row',
			[
                            'label' => __( 'Items per row', 'residence-elementor' ),
                            'type' => Controls_Manager::TEXT,
			]
		);
                
                
                
               $this->add_control(
			'place_type',
			[
                            'label' => __('Type', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            
                            'options' => $list_cities_or_areas
			]
		);
                
               $this->add_control(
			'place_type1_align',
			[
                            'label' => __('Type 1 Alingmet', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            
                            'options' => $type1_alignment
			]
		);
                
                
                 $this->add_control(
			'item_height',
			[
                            'label' => __('Item Height', 'residence-elementor' ),
                            'type' => Controls_Manager::TEXT,
			]
		);
                
                
                $this->add_control(
			'extra_class_name',
			[
                            'label' => __( 'Extra Class Name', 'residence-elementor' ),
                            'type' => Controls_Manager::TEXT,
			]
		);
                
                
                
                

		$this->end_controls_section();

		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
        
        public function wpresidence_send_to_shortcode($input){
            $output='';
            if($input!==''){
                $numItems = count($input);
                $i = 0;

                foreach ($input as $key=>$value){
                    $output.=$value;
                    if(++$i !== $numItems) {
                      $output.=', ';
                    }
                }
            }
            return $output;
        }
        
	protected function render() {
		$settings = $this->get_settings_for_display();
   
                $attributes['place_list']          =   $this -> wpresidence_send_to_shortcode( $settings['place_list'] );      
                $attributes['place_per_row']       =   $settings['place_per_row'];
                $attributes['place_type']          =   $settings['place_type'];
                $attributes['place_type1_align']   =   $settings['place_type1_align'];
                $attributes['extra_class_name']    =   $settings['extra_class_name'];
                $attributes['item_height']         =   $settings['item_height'];
                
                
              echo  wpestate_places_list_function($attributes);
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<div class="title">
			{{{ settings.title }}}
		</div>
		<?php
	}
}
