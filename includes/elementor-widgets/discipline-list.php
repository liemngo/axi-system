<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Discipline List Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_Discipline_List extends \Elementor\Widget_Base
{
    /**
     * Widget base constructor.
     *
     * Initializing the widget base class.
     *
     * @since 1.0.0
     * @access public
     *
     * @throws \Exception If arguments are missing when initializing a full widget
     *                   instance.
     *
     * @param array      $data Widget data. Default is an empty array.
     * @param array|null $args Optional. Widget default arguments. Default is null.
     */
    public function __construct( $data = [], $args = null )
    {
        parent::__construct( $data, $args );
    }
    
    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'axi-discipline-list';
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
        return esc_html__( 'AXi Discipline List', 'axi-system' );
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
        return 'eicon-post-list';
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
        # General
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'axi-system' ),
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html( 'Columns', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 3,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 4,
                    ],
                ]
            ]
        );

        $this->add_control(
            'item_count',
            [
                'label'   => esc_html__( 'Number of Items', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'min'     => 2,
                'max'     => 100,
                'default' => 6
            ]
        );

        $this->add_control(
            'tags_label',
            [
                'label'       => esc_html__( 'Tags filter label', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'Showing:', 'axi-system' ),
                'label_block' => true
            ]
        );

        $this->add_control(
            'show_more_btn', [
                'label' => esc_html__( 'Show More Button', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER
            ]
        );

        $this->add_control(
            'more_btn_text',
            [
                'label'       => esc_html__( 'More Button Text', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'See more disciplines', 'axi-system' ),
                'label_block' => true,
                'condition'   => [
                    'show_more_btn' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'pagination_limit',
            [
                'label'     => esc_html__( 'Pagination Limit', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'min'       => 2,
                'max'       => 100,
                'default'   => 6,
                'condition' => [
                    'show_more_btn' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'show_item_btn', [
                'label' => esc_html__( 'Show Item Button', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'item_btn_text',
            [
                'label'       => esc_html__( 'Item Button Text', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'Learn More', 'axi-system' ),
                'label_block' => true,
                'condition'   => [
                    'show_item_btn' => 'yes'
                ]
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
        /* /General */

        /*--------------------------------------------------------------
        # Filter Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_filter_style',
            [
                'label' => esc_html__( 'Filter', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'filter_label_typography',
                'selector' => '{{WRAPPER}} .list-filters .tags-label'
            ]
        );

        $this->add_responsive_control(
            'filter_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-filters' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'filter_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /* /Filter Style */

        /*--------------------------------------------------------------
        # Item Header Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_item_header_style',
            [
                'label' => esc_html__( 'Item Header', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_header_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-entry .entry-header' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_header_img_width',
            [
                'label' => esc_html__( 'Image Width', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .list-entry .entry-header-image img'  => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_header_img_padding',
            [
                'label'      => esc_html__( 'Image Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-header-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'item_title_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-entry .entry-title' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'item_title_typography',
                'selector' => '{{WRAPPER}} .list-entry .entry-title'
            ]
        );

        $this->end_controls_section();
        /* /Item Header Style */

        /*--------------------------------------------------------------
        # Item Main Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_item_content_style',
            [
                'label' => esc_html__( 'Item Main', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_main_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-entry' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_main_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /* /Item Main Style */

        /*--------------------------------------------------------------
        # Item Text Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_item_text_style',
            [
                'label' => esc_html__( 'Item Text', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-entry .entry-desc' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'item_text_typography',
                'selector' => '{{WRAPPER}} .list-entry .entry-desc'
            ]
        );

        $this->add_responsive_control(
            'item_text_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_text_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /* /Item Text Style */

        /*--------------------------------------------------------------
        # Item Promotions Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_item_promotions_style',
            [
                'label' => esc_html__( 'Item Promotions', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_promotion_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-entry .entry-promotion' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'item_promotion_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-entry .entry-promotion' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'item_promotion_typography',
                'selector' => '{{WRAPPER}} .list-entry .entry-promotion'
            ]
        );

        $this->add_responsive_control(
            'item_promotion_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-promotion' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_promotion_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-promotion' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_promotions_margin',
            [
                'label'      => esc_html__( 'Container Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-promotions' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_promotions_padding',
            [
                'label'      => esc_html__( 'Container Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-promotions' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /* /Item Promotions Style */

        /*--------------------------------------------------------------
        # Item Actions Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_item_actions_style',
            [
                'label' => esc_html__( 'Item Buttons', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_action_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-entry .entry-action' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'item_action_typography',
                'selector' => '{{WRAPPER}} .list-entry .entry-action'
            ]
        );

        $this->add_responsive_control(
            'item_action_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-action' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_action_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_actions_margin',
            [
                'label'      => esc_html__( 'Container Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-actions' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_actions_padding',
            [
                'label'      => esc_html__( 'Container Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-actions' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /* /Item Actions Style */

        /*--------------------------------------------------------------
        # Load More Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_loadmore_style',
            [
                'label' => esc_html__( 'Load More', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'loadmore_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-entry .list-loadmore-btn' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'loadmore_typography',
                'selector' => '{{WRAPPER}} .loadmore-btn-label'
            ]
        );
        
        $this->add_responsive_control(
            'loadmore_margin',
            [
                'label'      => esc_html__( 'Container Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .list-entry .entry-actions' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /* /Load More Style */
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
        global $post;
        $settings = $this->get_settings_for_display();

        $essential_settings = [
            'tags_label' => $settings['tags_label'],
            'item_count' => $settings['item_count'],
            'pagination_limit' => $settings['pagination_limit'],
            'columns' => $settings['columns']['size'],
            'show_more_btn' => $settings['show_more_btn'],
            'item_btn_text' => $settings['item_btn_text'],
            'more_btn_text' => $settings['more_btn_text']
        ];

        $data = self::get_data_for_render( $post, $essential_settings );
        $nonce = wp_create_nonce( 'axi_discipline_list_noncea' );

        $this->add_render_attribute(
            'wrapper',
            [
                'class' => [
                    'axi-carousel-wrapper',
                    'axi-discipline-list'
                ],
                'data-axiel' => 'discipline-list',
                'data-essential-settings' => json_encode( $essential_settings ),
                'data-action' => 'axi_discipline_list_get_filtered_items',
                'data-nonce' => $nonce,
                'data-post-id' => $post->ID
            ]
        );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper'); ?>>
            <?php
            self::render_discipline_list_tags( $data, $essential_settings );
            self::render_discipline_list( $data, $essential_settings );
            ?>
        </div>
        <?php
    }

    public static function render_discipline_list_tags( $data, $settings )
    {
        ?>
        <div class="list-filters" data-axirole="filters">
            <?php
            if ( $settings['tags_label'] ) :
                printf( '<span class="tags-label">%s</span>', esc_html( $settings['tags_label'] ) );
            endif;
            ?>
            <div class="axi-select-ui" data-axiel="select-ui">
                <div class="axi-select-display" data-axirole="display"><?php esc_html_e( 'All', 'axi-system' ); ?></div>
                <ul class="axi-select-dropdown" data-axirole="dropdown">
                    <li class="idle active"><a data-value="0" href="javascript:void(0);"><?php esc_html_e( 'All', 'axi-system' ); ?></a></li>
                    <?php
                    foreach( $data['tags'] as $t_id => $t_item ) :
                        printf(
                            '<li class="idle"><a data-value="%1$s" href="javascript:void(0);">%2$s</a></li>',
                            esc_attr( $t_id ),
                            esc_html( $t_item->name )
                        );
                    endforeach;
                    ?>
                </ul>
                <select data-axirole="select" style="display:none">
                    <option value="0" selected="selected"><?php esc_html_e( 'All', 'axi-system' ); ?></option>
                    <?php
                    foreach( $data['tags'] as $t_id => $t_item ) :
                        printf(
                            '<option value="%1$s">%2$s</option>',
                            esc_attr( $t_id ),
                            esc_html( $t_item->name )
                        );
                    endforeach;
                    ?>
                </select>
            </div>
            <div class="loading-icon"></div>
        </div>
        <?php
    }

    public static function render_discipline_list( $data, $settings )
    {
        $item_count = $settings['item_count'];
        $pagination_limit = $settings['pagination_limit'];
        $carousel_options = [
            'slidesToShow'   => 1,
            'slidesToScroll' => 1,
            'arrows'         => false,
            'dots'           => true,
            'autoplay'       => false,
            'infinite'       => false
        ];
        ?>
        <div 
            class="axi-carousel list-entries list-entries-col-<?php echo esc_attr( $settings['columns'] ); ?>"
            data-axirole="entries"
            data-coptions="<?php echo esc_attr( json_encode( $carousel_options ) ); ?>">
            <?php
            $page_num = 0;
            $index = 0;
            foreach( $data['items'] as $d_id => $d_item ) :
                $page_num = ( $index >= $item_count ) ? floor( ( $index - $item_count ) / $pagination_limit ) + 2 : 1;
                ?>
                <div class="list-entry-col<?php echo ( $page_num > 1 ? ' hidden' : '' );?>" data-page="<?php echo esc_attr( $page_num ); ?>">
                    <div class="list-entry">
                        <div class="entry-header">
                            <div class="entry-header-inner">
                                <?php
                                if ( $d_item['image'] ) :
                                    printf(
                                        '<div class="entry-header-image">' .
                                            '<div class="image-holder">%s</div>' .
                                        '</div>',
                                        $d_item['image']
                                    );
                                endif;
                                ?>
                                <h4 class="entry-title"><?php echo esc_html( $d_item['title'] ); ?></h4>
                            </div>
                        </div>
                        <div class="entry-body">
                            <div class="entry-desc"><?php
                                echo wpautop( $d_item['description'] );
                            ?></div>
                            <?php
                                if ( $d_item['promotions'] ) :
                                    ?>
                                    <div class="entry-promotions"><?php
                                        foreach( $d_item['promotions'] as $pkey => $promotion ) :
                                            ?><div class="entry-promotion"><?php
                                            echo esc_html( $promotion['html'] );
                                            ?></div><?php
                                        endforeach;
                                    ?></div>
                                    <?php
                                endif;
                            ?>
                        </div>
                        <?php
                            if ( $settings['show_more_btn'] && $settings['item_btn_text'] && $d_item['url'] ) :
                                ?>
                                <div class="entry-actions"><?php
                                    printf(
                                        '<a href="%1$s" class="entry-action" target="%2$s">%3$s</a>',
                                        esc_url( $d_item['url'] ),
                                        '_self',
                                        esc_html( $settings['item_btn_text'] )
                                    );
                                ?></div>
                                <?php
                            endif;
                        ?>
                    </div>
                </div>
                <?php
                $index++;
            endforeach;
            ?>
        </div>
        <?php 
        if ( $data['has_more'] ) :
            ?>
            <div class="list-loadmore" data-axirole="loadmore">
                <a class="list-loadmore-btn" href="javascript:void(0)" data-axirole="loadmore-btn" data-page="1" data-max-page="<?php echo esc_attr( $page_num ); ?>"><?php
                    echo '<span class="loadmore-btn-label">' . esc_html( $settings['more_btn_text'] ) . '</span>';
                    echo '<svg viewBox="0 0 512 512" class="arrow-icon loadmore-btn-icon" role="img">' .
                        ' <use href="#axi-icon-chevron" xlink:href="#axi-icon-chevron"></use>' .
                    '</svg>';
                ?></a>
            </div>
            <?php
        endif;
    }

    /**
     * Get all data for rendering
     *
     * @param \WP_Post $post
     * @param array $settings
     * @param int   $tag_id
     * @return array
     */
    public static function get_data_for_render( $post, $settings, $tag_id = 0 )
    {
        $data = [
            'items' => [],
            'tags'  => [],
            'has_more' => false
        ];

        $item_count = absint( $settings['item_count'] );

        $ploc_id  = 0;
        $pmode_id = 0;

        if ( $post->post_type == 'page' )
        {
            $page_loc = wp_get_post_terms( $post->ID, 'axi_location' );
            if ( ! empty( $page_loc ) && ! is_wp_error( $page_loc ) )
            {
                $ploc_id = $page_loc[0]->term_id;
            }

            $page_mode = wp_get_post_terms( $post->ID, 'axi_delivery_mode' );
            if ( ! empty( $page_mode ) && ! is_wp_error( $page_mode ) )
            {
                $pmode_id = $page_mode[0]->term_id;
            }
        }

        $d_ids = [];
        $dlink_ids = [];
        $dlinks = [];

        $dlinks_query = [
            'taxonomy'   => 'axi_discipline_link',
            'hide_empty' => false
        ];

        if ( $tag_id > 0 )
        {
            $tag_dlink_ids = maybe_unserialize( get_term_meta( $tag_id, '_discipline_links', true ) );
            if ( is_array( $tag_dlink_ids ) && ! empty( $tag_dlink_ids ) )
            {
                $dlinks_query['include'] = $tag_dlink_ids;
                $dlinks = get_terms( $dlinks_query );
            }
        }
        else
        {
            if ( $ploc_id )
            {
                $dlinks_query['meta_key'] = '_location';
                $dlinks_query['meta_value'] = $ploc_id;
                $dlinks_query['meta_compare'] = '=';
            }

            $dlinks = get_terms( $dlinks_query );
        }

        // List [1]
        if ( ! empty( $dlinks ) || ! is_wp_error( $dlinks ) )
        {
            foreach( $dlinks as $dlink )
            {
                if ( ! in_array( $dlink->term_id, $dlink_ids ) )
                {
                    $dlink_ids[] = $dlink->term_id;
                }

                $d_id = absint( get_term_meta( $dlink->term_id, '_discipline', true ) );
                if ( ! $d_id || in_array( $d_id, $d_ids ) )
                {
                    continue;
                }

                $discipline = get_term( $d_id, 'axi_discipline' );
                if ( ! $discipline )
                {
                    continue;
                }

                $data['items'][ $d_id ] = self::get_discipline_data_for_display( $discipline, $dlink );
                $d_ids[] = $d_id;
            }

            // Get tags for unfiltered list for first initialisation render.
            if ( $tag_id <= 0 )
            {
                $tags = get_terms( [
                    'taxonomy'   => 'axi_tag',
                    'hide_empty' => false
                ] );
                if ( ! empty( $tags ) && ! is_wp_error( $tags ) )
                {
                    foreach( $tags as $tag )
                    {
                        $t_dlinks = maybe_unserialize( get_term_meta( $tag->term_id, '_discipline_links', true ) );
                        if ( ! is_array( $t_dlinks ) || empty( $t_dlinks ) )
                        {
                            continue;
                        }
                        $is_valid_tag = false;
                        foreach( $dlinks as $dlink )
                        {
                            if ( in_array( $dlink->term_id, $t_dlinks ) )
                            {
                                $is_valid_tag = true;
                                break;
                            }
                        }
                        if ( $is_valid_tag )
                        {
                            $data['tags'][ $tag->term_id ] = $tag;
                        }
                    }
                }
            }
        }

        // List [2]
        if ( $pmode_id )
        {
            $courses = get_posts([
                'post_type' => 'axi_course',
                'post_status' => 'publish',
                'tax_query' => [
                    [
                        'taxonomy'         => 'axi_delivery_mode',
                        'field'            => 'id',
                        'terms'            => [ $pmode_id ],
                        'include_children' => true,
                        'operator'         => 'IN',
                    ]
                ]
            ]);

            foreach( $courses as $course )
            {
                $d_terms = wp_get_post_terms( $course->ID, 'axi_discipline', [ 'exclude' => $d_ids ] );
                if ( ! $d_terms || is_wp_error( $d_terms ) )
                {
                    continue;
                }
                if ( in_array( $d_terms[0]->term_id, $d_ids ) )
                {
                    continue;
                }
                $item = self::get_discipline_data_for_display( $d_terms[0] );
                if ( ! $item )
                {
                    continue;
                }
                $data['items'][ $d_terms[0]->term_id ] = $item;
            }
        }

        if ( count( $data['items'] ) > $item_count )
        {
            $data['has_more'] = true;
        }

        return $data;
    }

    /**
     * Format data for single item
     *
     * @param \WP_Term $discipline
     * @param \WP_Term $dlink
     * @return array
     */
    public static function get_discipline_data_for_display( $discipline, $dlink = null )
    {
        if ( ! $dlink instanceof \WP_Term )
        {
            $dlinks = get_terms( [
                'taxonomy'     => 'axi_discipline_link',
                'hide_empty'   => false,
                'meta_key'     => '_discipline',
                'meta_value'   => $discipline->term_id,
                'meta_compare' => '=',
                'number'       => 1
            ] );

            if ( empty( $dlinks ) || is_wp_error( $dlinks ) )
            {
                return [];
            }

            $dlink = $dlinks[0];
        }

        $item = [
            'id'          => $discipline->term_id,
            'title'       => $discipline->name,
            'image'       => '',
            'promotions'  => [],
            'description' => $dlink->description,
            'url'         => ''
        ];

        $dlink_image_id = get_term_meta( $dlink->term_id, '_discipline_logo', true );
        if ( $dlink_image_id )
        {
            $dlink_logo = wp_get_attachment_image( $dlink_image_id );
            if ( $dlink_logo )
            {
                $item['image'] = $dlink_logo;
            }
        }

        $promotions = get_posts([
            'post_type'    => 'axi_promotion',
            'post_status'  => 'publish',
            'meta_key'     => '_promotion_type',
            'meta_value'   => 'discipline',
            'meta_compare' => '=',
            'tax_query'    => [
                [
                    'taxonomy'         => 'axi_discipline',
                    'field'            => 'id',
                    'terms'            => [ $discipline->term_id ],
                    'include_children' => true,
                    'operator'         => 'IN',
                ]
            ]
        ]);

        foreach( $promotions as $promotion )
        {
            $promotion_item = [
                'id'   => $promotion->ID,
                'html' => ''
            ];

            $amount_type = get_post_meta( $promotion->ID, '_promotion_amount_type', true );
            if ( 'percent' != $amount_type )
            {
                $amount_val = absint( get_post_meta( $promotion->ID, '_promotion_percent', true ) );
                if ( $amount_val )
                {
                    $promotion_item['html'] = round( $amount_val / 100, 2 ) . '% ' . esc_html( 'off', 'axi-system' );
                }
            }
            else
            {
                $amount_val = absint( get_post_meta( $promotion->ID, '_promotion_amount', true ) );
                if ( $amount_val )
                {
                    $promotion_item['html'] = round( $amount_val / 100, 2 ) . '$ ' . esc_html( 'off', 'axi-system' );
                }
            }

            if ( ! $promotion_item['html'] )
            {
                continue;
            }

            $item['promotions'][ $promotion->ID ] = $promotion_item;
        }

        /* $page_id = absint( get_term_meta( $dlink->term_id, '_page_id', true ) );
        if ( $page_id )
        {
            $item['url'] = get_permalink( $page_id );
        } */
        $page_url = get_term_meta( $dlink->term_id, '_page_url', true );
        if ( $page_url )
        {
            $item['url'] = $page_url;
        }

        return $item;
    }

    /**
     * Get filtered list, tobe used for filtering
     *
     * @param int $post_id
     * @param array $settings
     * @param int $tag_id
     * @return string
     */
    public static function get_filtered_discipline_list( $post_id, $settings, $tag_id )
    {
        $post = get_post( $post_id );
        if ( ! $post )
        {
            return;
        }

        ob_start();
        $data = self::get_data_for_render( $post, $settings, $tag_id );
        self::render_discipline_list( $data, $settings );
        return ob_get_clean();
    }
}