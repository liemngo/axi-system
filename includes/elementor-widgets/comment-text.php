<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Comment Text Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_Comment_Text extends \Elementor\Widget_Base
{
    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'axi-comment-text';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__( 'AXi Comment Textx', 'axi-system' );
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-testimonial';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return [ 'academyxi' ];
    }
    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return [ 'axi-elementor' ];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {
        /*--------------------------------------------------------------
        # Content
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_base',
            [
                'label' => esc_html__( 'Content', 'axi-system' ),
            ]
        );
        $this->add_control(
            'comment',
            [
                'label'   => esc_html__( 'Comment Text', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Comment', 'axi-system' )
            ]
        );
        $this->add_control(
            'author',
            [
                'label' => esc_html__( 'Author\'s Name', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::WYSIWYG
            ]
        );
        $this->add_control(
            'view',
            [
                'label'   => esc_html__( 'View', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );
        $this->end_controls_section();
        /* /Content */

        /*--------------------------------------------------------------
        # Left Quote
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_left_quote',
            [
                'label' => esc_html__( 'Left Quote', 'axi-system' ),
            ]
        );
        $this->add_responsive_control(
            'hide_left_quote',
            [
                'label' => esc_html__( 'Hide Icon on Mobile?', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER,
                'devices' => [
                    self::RESPONSIVE_MOBILE,
                ]
            ]
        );
        $this->add_control(
            'left_quote_type', [
                'label'   => esc_html__( 'Type', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Default Icon', 'axi-system' ),
                    'icon'    => esc_html__( 'Select Icon', 'axi-system' ),
                    'none'    => esc_html__( 'Disabled', 'axi-system' ),
                ],
                'default' => 'default'
            ]
        );
        $this->add_control(
            'left_quote_image',
            [
                'label'   => esc_html__( 'Choose Image', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'left_quote_type' => 'icon',
                ]
            ]
        );
        $this->add_control(
            'left_quote_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .left-quote-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'left_quote_type' => 'default',
                ]
            ]
        );
        $this->add_responsive_control(
            'left_quote_icon_width',
            [
                'label'   => esc_html__( 'Icon Width', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .left-quote-icon' => 'width:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'left_quote_type!' => 'none',
                ]
            ]
        );
        $this->add_responsive_control(
            'left_quote_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .left-quote-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'left_quote_type!' => 'none'
                ]
            ]
        );
        $this->add_responsive_control(
            'left_quote_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .left-quote-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'left_quote_type!' => 'none'
                ]
            ]
        );
        $this->end_controls_section();
        /* /Left Quote */

        /*--------------------------------------------------------------
        # Right Quote
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_right_quote',
            [
                'label' => esc_html__( 'Right Quote', 'axi-system' ),
            ]
        );
        $this->add_responsive_control(
            'hide_right_quote',
            [
                'label' => esc_html__( 'Hide Icon on Mobile?', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER,
                'devices' => [
                    self::RESPONSIVE_MOBILE,
                ]
            ]
        );
        $this->add_control(
            'right_quote_type', [
                'label'   => esc_html__( 'Type', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Default Icon', 'axi-system' ),
                    'icon' => esc_html__( 'Select Icon', 'axi-system' ),
                    'none' => esc_html__( 'Disabled', 'axi-system' ),
                ],
                'default' => 'none'
            ]
        );
        $this->add_control(
            'right_quote_image',
            [
                'label'   => esc_html__( 'Choose Image', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'right_quote_type' => 'icon',
                ]
            ]
        );
        $this->add_control(
            'right_quote_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-quote-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'right_quote_type' => 'default',
                ]
            ]
        );
        $this->add_responsive_control(
            'right_quote_icon_width',
            [
                'label'   => esc_html__( 'Icon Width', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .right-quote-icon' => 'width:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'right_quote_type!' => 'none',
                ]
            ]
        );
        $this->add_responsive_control(
            'right_quote_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .right-quote-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'right_quote_type' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'right_quote_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .right-quote-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'right_quote_type' => 'yes'
                ]
            ]
        );
        $this->end_controls_section();
        /* / Right Quote */

        /*--------------------------------------------------------------
        # Comment Styling
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_styling_comment',
            [
                'label' => esc_html__( 'Comment', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'comment_color',
            [
                'label' => esc_html__( 'Text Color', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'comment_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'comment_align',
            [
                'label' => esc_html__( 'Alignment', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'axi-system' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'axi-system' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'axi-system' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'axi-system' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .entry-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'comment_typo',
                'selector' => '{{WRAPPER}} .entry-content',
            ]
        );
        $this->add_responsive_control(
            'comment_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .entry-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'comment_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .entry-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /* /Comment Styling */

        /*--------------------------------------------------------------
        # Author Styling
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_styling_author',
            [
                'label' => esc_html__( 'Author', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'author_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-author' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'author_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-author' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_align',
            [
                'label'   => esc_html__( 'Alignment', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'axi-system' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'axi-system' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'axi-system' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'axi-system' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .entry-author' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'author_typo',
                'selector' => '{{WRAPPER}} .entry-author',
            ]
        );
        $this->add_responsive_control(
            'author_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .entry-author' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .entry-author' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /* /Author Styling */
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'wrapper', 'class', 'axi-comment-text' );

        $this->add_inline_editing_attributes( 'comment' );
        $this->add_inline_editing_attributes( 'author' );

        $left_quote_classes = 'left-quote';
        $right_quote_classes = 'right-quote';

        if ( $settings['hide_left_quote_mobile'] )
        {
            $left_quote_classes .= ' hide-on-mobile';
        }
        if ( $settings['hide_right_quote_mobile'] )
        {
            $right_quote_classes .= ' hide-on-mobile';
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php
                if ( $settings['left_quote_type'] != 'none' ) :
                    $left_quote_icon_html = '';
                    if ( $settings['left_quote_type'] == 'default' ) :
                        $left_quote_icon_html = '<span class="default-quote-icon">' .
                            '<svg viewBox="0 0 512 512" class="default-quote-icon-svg" role="img">' .
                                ' <use href="#axi-icon-quot" xlink:href="#axi-icon-quot"></use>' .
                            '</svg>' .
                        '</span>';
                    else :
                        if ( ! empty( $settings['left_quote_image'] ) ) :
                            $image_id = absint( $settings['left_quote_image']['id'] );
                            $left_quote_icon_html = wp_get_attachment_image( $image_id, 'medium' );
                        endif;
                    endif;
                    printf(
                        '<div class="%1$s"><div class="left-quote-icon">%2$s</div></div>',
                        esc_attr( $left_quote_classes ),
                        $left_quote_icon_html
                    );
                endif;
            
                $comment = $this->get_settings_for_display( 'comment' );
                $comment = $this->parse_text_editor( $comment );

                $author = $this->get_settings_for_display( 'author' );
                $author = $this->parse_text_editor( $author );

                if ( $comment || $author ) :
                    echo '<div class="entry-body">';
                    if ( $comment ) :
                        printf( '<div class="entry-content">%s</div>', $comment );
                    endif;
                    if ( $author ) :
                        printf( '<div class="entry-author">%s</div>', $author );
                    endif;
                    echo '</div>';
                endif;

                if ( $settings['right_quote_type'] != 'none' ) :
                    $right_quote_icon_html = '';
                    if ( $settings['right_quote_type'] == 'default' ) :
                        $right_quote_icon_html = '<span class="default-quote-icon">' .
                            '<svg viewBox="0 0 512 512" class="default-quote-icon-svg" role="img">' .
                                ' <use href="#axi-icon-quot" xlink:href="#axi-icon-quot"></use>' .
                            '</svg>' .
                        '</span>';
                    else :
                        if ( ! empty( $settings['right_quote_image'] ) ) :
                            $image_id = absint( $settings['right_quote_image']['id'] );
                            $right_quote_icon_html = wp_get_attachment_image( $image_id, 'medium' );
                        endif;
                    endif;
                    printf(
                        '<div class="%1$s"><div class="right-quote-icon">%2$s</div></div>',
                        esc_attr( $right_quote_classes ),
                        $right_quote_icon_html
                    );
                endif;
            ?>
        </div>
        <?php
    }
}