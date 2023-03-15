<?php

/**
 * Plugin Name: Payt Upsell for Elementor
 * Description: Add Payt Upsell Button on Elementor.
 * Version: 1.0.0
 * Author: Payt.com.br
 */

// Make sure Elementor is active
if (!defined('ABSPATH') || !defined('ELEMENTOR_PATH')) {
    return;
}

// Add the widget
add_action('elementor/widgets/widgets_registered', function () {

    class Payt_Upsell_Widget extends \Elementor\Widget_Base
    {

        // Widget Name
        public function get_name()
        {
            return 'payt_upsell';
        }

        // Widget Title
        public function get_title()
        {
            return __('Compra Upsell Payt', 'payt_upsell');
        }

        // Widget Icon
        public function get_icon()
        {
            return 'eicon-dual-button';
        }

        // Widget Categories
        public function get_categories()
        {
            return ['general'];
        }

        // Widget Controls
        protected function _register_controls()
        {
            $this->start_controls_section(
                'section_content',
                [
                    'label' => esc_html__('Upsell', 'payt_upsell'),
                ]
            );

            $this->add_control(
                'title',
                [
                    'label' => esc_html__('Text', 'payt_upsell'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Comprar Agora', 'payt_upsell'),
                ]
            );
			$this->add_control(
				'font_size',
				[
					'type' => \Elementor\Controls_Manager::SLIDER,
					'label' => esc_html__( 'Font Size', 'payt_upsell' ),
					'size_units' => [ 'px', 'em', 'rem'],
					'range' => [
						'px' => [
							'min' => 9,
							'max' => 200,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => '16',
					],
				]
			);	
			$this->add_control(
				'border_radius',
				[
					'type' => \Elementor\Controls_Manager::SLIDER,
					'label' => esc_html__( 'Border Radius', 'payt_upsell' ),
					'size_units' => [ 'px', 'em', 'rem'],
					'range' => [
						'px' => [
							'min' => 9,
							'max' => 200,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => '4',
					],
				]
			);
            $this->add_control(
                'background_color',
                [
                    'label' => esc_html__('Background Color', 'payt_upsell'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#28a745',
                ]
            );		
			$this->add_control(
				'text_color',
				[
					'type' => \Elementor\Controls_Manager::COLOR,
					'label' => esc_html__('Text Color', 'payt_upsell'),
					'default' => '#FFFFFF',
				]
			);
			$this->add_control(
				'hr',
				[
					'type' => \Elementor\Controls_Manager::DIVIDER,
				]
			);
            $this->add_control(
                'payt_object_id',
                [
                    'label' => esc_html__('PayT Object ID', 'payt_upsell'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                ]
            );
            $this->add_control(
                'upsell_number',
                [
                    'label' => esc_html__('Upsell ID', 'payt_upsell'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                ]
            );
            $this->add_control(
                'enable_delay',
                [
                    'label' => esc_html__('Button Delay', 'payt_upsell'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'payt_upsell'),
                    'label_off' => __('No', 'payt_upsell'),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );
            $this->add_control(
                'delay_time',
                [
                    'label' => esc_html__('Delay Time (Seconds)', 'payt_upsell'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => '',
                    'condition' => [
                        'enable_delay' => 'yes',
                    ],
                ]
            );
            $this->end_controls_section();

			$this->start_controls_section(
				'downsell_section',
				[
					'label' => esc_html__( 'Downsell', 'payt_upsell' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
                'enable_downsell',
                [
                    'label' => esc_html__('Button Downsell', 'payt_upsell'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'payt_upsell'),
                    'label_off' => __('No', 'payt_upsell'),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

			$this->add_control(
                'text_down',
                [
                    'label' => esc_html__('Texto Downsell', 'payt_upsell'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Não, não desejo essa oferta.', 'payt_upsell'),
					'condition' => [
                        'enable_downsell' => 'yes',
                    ],
                ]
            );

			$this->add_control(
                'url_down',
                [
                    'label' => esc_html__('Url Downsell', 'payt_upsell'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('#', 'payt_upsell'),
					'condition' => [
                        'enable_downsell' => 'yes',
                    ],
                ]
            );

			$this->add_control(
				'text_downsell_color',
				[
					'type' => \Elementor\Controls_Manager::COLOR,
					'label' => esc_html__('Text Color', 'payt_upsell'),
					'default' => '#000000',
					'condition' => [
                        'enable_downsell' => 'yes',
                    ],
				]
			);

			$this->end_controls_section();

        }

        // Widget Display
        protected function render() {
            $settings = $this->get_settings_for_display();

            $object_id = $settings['payt_object_id'];
            $upsell_number = $settings['upsell_number'];
            $title = $settings['title'];
            $custom_css = $settings['custom_css'];
            $enable_delay = $settings['enable_delay'];
            $delay_time = $settings['delay_time'];
            $delay_style = '';
			
			$font_s = $settings['font_size'];			
			$size = $font_s['size'];
			$unit = $font_s['unit'];
			
			$border_s = $settings['border_radius'];			
			$sizeB = $border_s['size'];
			$unitB = $border_s['unit'];
			
			$align  = $settings['alignment'];
			
			$text_downsell = $settings['text_down'];
			$link_downsell = $settings['url_down'];
			$downsell_style = '';
			$enable_downsell = $settings['enable_downsell'];
			$downsell_color = $settings['text_downsell_color'];
			
            if ($enable_delay == 'yes') {
                $delay_style = 'display: none;';
                
            }

			if ($enable_downsell == 'yes') {
                $downsell_style = 'display: block;';
            }

			$downsell_script = '<script>
			function addSrc() {
					 // Início da config
					 var keyOnCurrentUrl = "_o";
					 var newKeyOnLinks = "_o";
					 var links = window.document.querySelectorAll("a.downsell");
					 var previousSrc = window.location.search.match(/_o=(.*?)(:?\&|#|$)/);
					 var sourceValue = !previousSrc ? "" : previousSrc[1]; 
					 for (var i in links) {
						 if (typeof links[i].href !== "string") return;
						 links[i].href = links[i].href.replace(
							 /(^.*?)(?:\?(.*?))?(#.*|$)/,
							 function (match, urlAndPath, originalQueryString, anchor) {
								 var newQueryString = [];
								 var keyExistsOnCurrentUrl = false;
								 if (typeof originalQueryString === "string") {
									 newQueryString = originalQueryString.split("&").reduce(function (queryString, queryPart) {
										 if (!queryPart.startsWith(keyOnCurrentUrl + "="))
											 queryString.push(queryPart)
										 else {
											 keyExistsOnCurrentUrl = true;
											 var data = queryPart.split("=")
											 queryString.push(newKeyOnLinks + "=" + sourceValue + "_" + data[1]);
										 }
										 return queryString;
									 }, []);
								 }
								 if (!keyExistsOnCurrentUrl) {
									 newQueryString.push(newKeyOnLinks + "=" + sourceValue);
								 }
								 return urlAndPath + "?" + newQueryString.join("&") + anchor;
							 }
						 );
					 }
				 }
		   window.addEventListener("load", addSrc);
		   </script>';

			?>
			<?php if ($enable_delay == 'yes') {
               $delayClass = delayed;
            } ?>
			<?php if ($enable_downsell == 'yes') {
				echo esc_html($downsell_script);
			} ?>
			
			
			<div class="payt-widget <?php echo esc_attr( $delayClass ); ?>" style="text-align: center; <?php echo esc_html($delay_style); ?>">
				<a href="#" payt_action="oneclick_buy" data-object="<?php echo esc_html($object_id); ?>" style="background: <?php echo esc_html($settings['background_color']); ?>; color: <?php echo esc_html($settings['text_color']); ?>; padding: 8px 12px; text-decoration: none; font-size: <?php echo esc_html($size) . esc_html($unit); ?>; font-family: sans-serif; border-radius: <?php echo esc_html($sizeB) . esc_html($unitB); ?>; display: block; margin: 10px auto; width: max-content;"><?php echo esc_html($title); ?></a>
				<select payt_element="installment" data-object="<?php echo esc_html($object_id); ?>" style="width: auto; margin: 0 auto;"></select>
				<script type="text/javascript" src="https://checkout.payt.com.br/multiple-oneclickbuyscript/<?php echo esc_html($upsell_number); ?>.js"></script>
				<p class="" style="margin-top:16px;text-align: center; <?php echo esc_html($downsell_style); ?>"><a href="<?php echo esc_html($link_downsell); ?>" class="downsell" style="color: <?php echo esc_html($downsell_color); ?>;"><?php echo esc_html($text_downsell); ?></a></p>
			</div> <?php
			
			?>
			    <script type="text/javascript">setTimeout(function() { jQuery(".delayed").show(); }, ( <?php echo esc_js( $delay_time ); ?> * 1000));</script>
			<?php

        }
        //end render

    }

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Payt_Upsell_Widget());
});