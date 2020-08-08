<?php
namespace ElementorWpResidence\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wpresidence_Contact_Us extends Widget_Base {

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
		return 'WpResidence_Contact_Form';
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
		return __( 'WpResidence Contact Form', 'residence-elementor' );
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
		return '   eicon-email-field';
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
                $text_align=array('left'=>'left','right'=>'right','center'=>'center');
                $button_size=array('normal','full');
                $this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'residence-elementor' ),
			]
		);

                $this->add_control(
			'text_align',
			[
                            'label' => __('Text Align', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => $text_align
			]
		);
                
                
                $this->add_control(
			'form_back_color',
			[
                            'label' => __('Input Background Color', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                        ]
		);
                
                $this->add_control(
			'form_text_color',
			[
                            'label' => __('Input Text Color', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                        ]
		);
             
                $this->add_control(
			'form_border_color',
			[
                            'label' => __('Border Color', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                        ]
		);
                
                 $this->add_control(
			'form_button_size',
			[
                            'label' => __('Button Size', 'residence-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => $button_size
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
          
            $attributes['text_align']          =   $settings['text_align'];
            $attributes['form_back_color']          =   $settings['form_back_color'];
            $attributes['form_text_color']          =   $settings['form_text_color'];
            $attributes['form_border_color']          =   $settings['form_border_color'];
            $attributes['form_button_size']          =   $settings['form_button_size'];
            echo  wpestate_contact_us_form($attributes);
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
