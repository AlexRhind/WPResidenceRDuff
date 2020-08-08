<?php
namespace ElementorWpResidence\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wpresidence_Recent_Items_SLider extends Widget_Base {

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
		return 'WpResidenc_Items_Slider';
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
		return __( 'WpResidence Items Slider', 'residence-elementor' );
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
		return 'eicon-post-slider';
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
                

                
                $property_category_values       =   wpestate_generate_category_values_shortcode();
                $property_city_values           =   wpestate_generate_city_values_shortcode();
                $property_area_values           =   wpestate_generate_area_values_shortcode();
                $property_county_state_values   =   wpestate_generate_county_values_shortcode();
                $property_action_category_values=   wpestate_generate_action_values_shortcode();
                $property_status_values         =   wpestate_generate_status_values_shortcode();
                
                $property_category_values           =   $this->elementor_transform($property_category_values);
                $property_city_values               =   $this->elementor_transform($property_city_values);
                $property_area_values               =   $this->elementor_transform($property_area_values);
                $property_county_state_values       =   $this->elementor_transform($property_county_state_values);
                $property_action_category_values    =   $this->elementor_transform($property_action_category_values);
                $property_status_values             =   $this->elementor_transform($property_status_values);
                
                
                
                $featured_listings  =   array('no'=>'no','yes'=>'yes');
                $items_type         =   array('properties'=>'properties','agents'=>'agents','articles'=>'articles');
                $alignment_type     =   array('vertical'=>'vertical','horizontal'=>'horizontal');
                $arrow_type         =   array('top'=>'top','sideways'=>'sideways'); 
                        
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'residence-elementor' ),
			]
		);

		$this->add_control(
			'title_residence',
			[
				'label' => __( 'Title', 'residence-elementor' ),
                          	'type' => Controls_Manager::TEXT,
                                'Label Block'
                            
			]
		);
                
                $this->add_control(
			'type',
			[
                            'label' => __( 'What type of items', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'properties',
                            'options' => $items_type
			]
		);
                 
                
                $this->add_control(
			'arrows',
			[
                            'label' => __( 'Slider Navigation Arrows Position', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'top',
                            'options' => $arrow_type
			]
		);
               
                $this->add_control(
			'category_ids',
			[
                            'label' => __( 'List of categories (*only for properties)', 'residence-elementor' ),
                            'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $property_category_values,
			]
		);
                
                $this->add_control(
			'action_ids',
			[
                            'label' => __( 'List of action categories (*only for properties)', 'residence-elementor' ),
                             'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $property_action_category_values,
			]
		);
                
                $this->add_control(
			'city_ids',
			[
                            'label' => __( 'List of city  (*only for properties)', 'residence-elementor' ),
                             'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $property_city_values,
			]
		);
                 $this->add_control(
			'area_ids',
			[
                            'label' => __( 'List of areas (*only for properties)', 'residence-elementor' ),
                             'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $property_area_values,
			]
		);
                $this->add_control(
			'state_ids',
			[
                            'label' => __( 'List of Counties/States (*only for properties)', 'residence-elementor' ),
                            'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $property_county_state_values,
			]
		);
                
                $this->add_control(
			'status_ids',
			[
                            'label' => __( 'List of Property Status (*only for properties)', 'residence-elementor' ),
                            'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $property_status_values,
			]
		);
                 
                 
                $this->add_control(
			'number',
			[
                            'label' => __( 'No of items', 'residence-elementor' ),
                            'type' => Controls_Manager::TEXT,
			]
		);
                
          
                $this->add_control(
			'show_featured_only',
			[
                            'label' => __( 'Show featured listings only?', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'no',
                            'options' => $featured_listings
			]
		);
                $this->add_control(
			'autoscroll',
			[
				'label' => __( 'Auto scroll period', 'residence-elementor' ),
                          	'type' => Controls_Manager::TEXT,
                                'Label Block'
                            
			]
		);
                
                $this->add_control(
			'random_pick',
			[
                            'label' => __( 'Random Pick ?', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'no',
                            'options' => $featured_listings
			]
		);
                $this->add_control(
			'featured_first',
			[
                            'label' => __( 'Show Featured Listings First ?', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'no',
                            'options' => $featured_listings
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


                $attributes['title']                =   $settings['title_residence'];
                $attributes['type']                 =   $settings['type'];
                $attributes['arrows']               =   $settings['arrows'];
                $attributes['category_ids']         =   $this -> wpresidence_send_to_shortcode( $settings['category_ids'] );      
                $attributes['action_ids']           =   $this -> wpresidence_send_to_shortcode( $settings['action_ids'] );   
                $attributes['city_ids']             =   $this -> wpresidence_send_to_shortcode( $settings['city_ids'] );   
                $attributes['area_ids']             =   $this -> wpresidence_send_to_shortcode( $settings['area_ids'] );   
                $attributes['state_ids']            =   $this -> wpresidence_send_to_shortcode( $settings['state_ids'] ); 
                $attributes['status_ids']           =   $this -> wpresidence_send_to_shortcode( $settings['status_ids'] ); 
               
                $attributes['number']               =   $settings['number']; 
                $attributes['autoscroll']           =   $settings['autoscroll']; 
                $attributes['show_featured_only']   =   $settings['show_featured_only']; 
                $attributes['featured_first']       =   $settings['featured_first']; 
                
                
              echo  wpestate_slider_recent_posts_pictures($attributes);
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
