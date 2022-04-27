<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Comparison Table Elementor Widget for AcademyXi.
 * 
 * @since 1.0.0
 */
class Widget_Comparison_Table extends \Elementor\Widget_Base
{
    /**
     * Maximum number of tables
     *
     * @var integer
     * @access protected
     */
    protected $max_table_count;

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
        $this->max_table_count = 5;
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
        return 'axi-comparison-table';
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
        return esc_html__( 'AXi Comparison Table', 'axi-system' );
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
        return 'eicon-price-table';
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
            'table_count',
            [
                'label'   => esc_html__( 'Table Count', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'min'     => 2,
                'max'     => $this->max_table_count,
                'default' => 2
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
        # Features
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_features',
            [
                'label' => esc_html__( 'Features', 'axi-system' ),
            ]
        );

        $this->add_control(
            'features_subheading',
            [
                'label'       => esc_html__( 'Sub Heading', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'labeL_block' => true,
            ]
        );

        $feature = new \Elementor\Repeater();

        $feature->add_control(
            'label',
            [
                'label'       => esc_html__( 'Label', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'Feature Label', 'axi-system' ),
                'label_block' => true
            ]
        );

        $this->add_control(
            'features',
            [
                'label'   => esc_html__( 'Features', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::REPEATER,
                'fields'  => $feature->get_controls(),
                'default' => [
                    [
                        'label' => esc_html__( 'Time commitment', 'axi-system' ),
                    ],
                    [
                        'label' => esc_html__( 'Outcomes program', 'axi-system' ),
                    ],
                    [
                        'label' => esc_html__( 'Pricing', 'axi-system' ),
                    ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();
        /* /Features */

        /*--------------------------------------------------------------
        # Tables
        --------------------------------------------------------------*/
        $table_feature = new \Elementor\Repeater();

        $table_feature->add_control(
            'content_type',
            [
                'label'       => esc_html__( 'Content Type', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'     => [
                    'image' => [
                        'title' => esc_html__( 'Image', 'axi-system' ),
                        'icon'  => 'far fa-image',
                    ],
                    'yes' => [
                        'title' => esc_html__( 'Yes', 'axi-system' ),
                        'icon'  => 'fas fa-check',
                    ],
                    'no' => [
                        'title' => esc_html__( 'No', 'axi-system' ),
                        'icon'  => 'fas fa-times',
                    ],
                    'text' => [
                        'title' => esc_html__( 'Custom Text', 'axi-system' ),
                        'icon'  => 'fas fa-font',
                    ]
                ],
                'default' => 'text',
            ]
        );

        $table_feature->add_control(
            'content_image',
            [
                'label'       => esc_html__( 'Image', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition'   => [
                    'content_type' => 'image'
                ]
            ]
        );

        $table_feature->add_control(
            'content_text',
            [
                'label'       => esc_html__( 'Content', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::WYSIWYG,
                'label_block' => true,
                'condition'   => [
                    'content_type' => 'text'
                ]
            ]
        );
        
        for ( $i = 0; $i < $this->max_table_count; $i++ )
        {
            $index = $i + 1;

            $this->start_controls_section(
                'section_table_' . $index,
                [
                    'label'     => esc_html__( 'Table #' . $index, 'axi-system' ),
                    'condition' => [
                        'table_count' => $this->table_conddition( $index )
                    ]
                ]
            );

            $this->add_control(
                'table_' . $index . '_heading',
                [
                    'label'       => esc_html__( 'Header', 'axi-system' ),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true
                ]
            );

            $this->add_control(
                'table_' . $index . '_subheading',
                [
                    'label'       => esc_html__( 'Sub Header', 'axi-system' ),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true
                ]
            );

            $this->add_control(
                'table_' . $index . '_features',
                [
                    'label'   => esc_html__( 'Features', 'axi-system' ),
                    'type'    => \Elementor\Controls_Manager::REPEATER,
                    'fields'  => $table_feature->get_controls()
                ]
            );

            $this->end_controls_section();
        }
        /* / Tables */

        /*--------------------------------------------------------------
        # Column Header Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_styling_column_header',
            [
                'label' => esc_html__( 'Column Header', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'column_header_bgcolor',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-th' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'column_header_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-th' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'column_header_bordercolor',
            [
                'label'     => esc_html__( 'Border Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-th' => 'border-right-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'column_header_typography',
                'selector' => '{{WRAPPER}} .axi-comparison-table .ct-th .cell-content'
            ]
        );

        $this->add_responsive_control(
            'column_header_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-comparison-table .ct-th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* / Column Header Style */

        /*--------------------------------------------------------------
        # Column Sub Header Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_styling_column_subheader',
            [
                'label' => esc_html__( 'Column Sub Header', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'column_subheader_bgcolor',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-subhead .ct-td' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'column_subheader_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-subhead .cell-content' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'column_subheader_bordercolor',
            [
                'label'     => esc_html__( 'Border Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-subhead .ct-td' => 'border-right-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__( 'Sub Header Typography', 'axi-system' ),
                'name'     => 'column_subheader_typography',
                'selector' => '{{WRAPPER}} .axi-comparison-table .ct-tr-subhead .ct-td-content .cell-content'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__( 'Features Sub Header Typography', 'axi-system' ),
                'name'     => 'column_subheaderfeature_typography',
                'selector' => '{{WRAPPER}} .axi-comparison-table .ct-tr-subhead .ct-td-feature .cell-content'
            ]
        );

        $this->add_responsive_control(
            'column_subheader_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-subhead .ct-td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* / Column Sub Header Style */

        /*--------------------------------------------------------------
        # Column Feature Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_styling_column_feature',
            [
                'label' => esc_html__( 'Column Feature', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'column_feature_bgcolor',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-content .ct-td-feature' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'column_feature_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-content .ct-td-feature .cell-content' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'column_feature_bordercolor',
            [
                'label'     => esc_html__( 'Border Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-content .ct-td-feature' => 'border-bottom-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'column_feature_typography',
                'selector' => '{{WRAPPER}} .axi-comparison-table .ct-tr-content .ct-td-feature .cell-content'
            ]
        );

        $this->end_controls_section();
        /* / Column Feature Style */

        /*--------------------------------------------------------------
        # Column Content Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_styling_column_content',
            [
                'label' => esc_html__( 'Column Content', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'column_content_image_max_width',
            [
                'label'  => esc_html__( 'Custom Image Width', 'axi-system' ),
                'description' => esc_html__( 'This setting is for image content type.', 'axi-system' ),
                'type'   => \Elementor\Controls_Manager::SLIDER,
                'units'  => [ 'px' ],
                'range'  => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-content .ct-td-content .cell-content-wimage img' => 'max-width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'column_content_bgcolor',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-content .ct-td-content' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'column_content_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-content .ct-td-content .cell-content' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'column_content_bordercolor',
            [
                'label'     => esc_html__( 'Border Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-content .ct-td-content' => 'border-right-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'column_content_typography',
                'selector' => '{{WRAPPER}} .axi-comparison-table .ct-tr-content .ct-td-content .cell-content'
            ]
        );

        $this->add_responsive_control(
            'column_content_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-comparison-table .ct-tr-content .ct-td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* / Column Content Style */
    }

    /**
     * Add custom condition for each table
     *
     * @param  integer $index
     * @return array
     */
    private function table_conddition( $index )
    {
        $values = [];
        for ( $i = $index; $i <= $this->max_table_count; $i++ )
        {
            $values[] = $i;
        }
        return $values;
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
        $this->add_render_attribute( 'wrapper', 'class', 'axi-comparison-table');

        $carousel_options = [
            'slidesToShow'   => 2,
            'slidesToScroll' => 1,
            'arrows'         => false,
            'dots'           => true,
            'autoplay'       => false,
            'infinite'       => false
        ];
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper'); ?>>
            <div class="axi-comparison-table-desktop axi-comparison-table-desktop-c<?php echo esc_attr( $settings['table_count'] ); ?>">
                <div class="ct-thead">
                    <div class="ct-tr ct-tr-head">
                        <div class="ct-th ct-th-feature"></div>
                        <?php
                        for ( $i = 0; $i < $settings['table_count']; $i ++ ) :
                            $index = $i + 1;
                            ?>
                            <div class="ct-th ct-th-content">
                                <div class="cell-content cell-content-text">
                                    <?php echo esc_html( $settings['table_' . $index . '_heading'] ); ?>
                                </div>
                            </div>
                            <?php
                        endfor;
                        ?>
                    </div>
                </div>
                <div class="ct-tbody">
                    <div class="ct-tr ct-tr-subhead">
                        <div class="ct-td ct-td-feature">
                            <div class="cell-content cell-content-text">
                                <?php 
                                if ( $settings['features_subheading'] ) :
                                    echo esc_html( $settings['features_subheading'] );
                                endif;
                                ?>
                            </div>
                        </div>
                        <?php
                        for ( $i = 0; $i < $settings['table_count']; $i ++ ) :
                            $index = $i + 1;
                            ?>
                            <div class="ct-td ct-td-content">
                                <div class="cell-content cell-content-text">
                                    <?php echo $settings['table_' . $index . '_subheading']; ?>
                                </div>
                            </div>
                            <?php
                        endfor;
                        ?>
                    </div>
                    <?php
                    for ( $fi = 0; $fi < count( $settings['features'] ); $fi++ ) :
                        ?>
                        <div class="ct-tr ct-tr-content">
                            <div class="ct-td ct-td-feature">
                                <div class="cell-content cell-content-text">
                                    <?php echo esc_html( $settings['features'][ $fi ]['label'] ); ?>
                                </div>
                            </div>
                            <?php
                            for ( $i = 0; $i < $settings['table_count']; $i ++ ) :
                                $index = $i + 1;
                                ?>
                                <div class="ct-td ct-td-content">
                                    <?php
                                    if ( isset( $settings['table_' . $index . '_features'][ $fi ] ) ) :
                                        $feature = $settings['table_' . $index . '_features'][ $fi ];
                                        switch( $feature['content_type'] ) :
                                            case 'image':
                                                echo '<div class="cell-content cell-content-wimage">';
                                                $image_id = absint( $feature['content_image']['id'] );
                                                $image_html = wp_get_attachment_image( $image_id, 'medium' );
                                                if ( $image_html ) :
                                                    echo $image_html;
                                                endif;
                                                echo '</div>';
                                                break;
                                            case 'yes':
                                                echo '<div class="cell-content cell-content-icon">';
                                                \Elementor\Icons_Manager::render_icon(
                                                    [
                                                        'library' => 'solid',
                                                        'value'   => 'fas fa-check'
                                                    ],
                                                    [
                                                        'class' => 'icon-yes'
                                                    ]
                                                );
                                                echo '</div>';
                                                break;
                                            case 'no':
                                                echo '<div class="cell-content cell-content-icon">';
                                                \Elementor\Icons_Manager::render_icon(
                                                    [
                                                        'library' => 'solid',
                                                        'value'   => 'fas fa-times'
                                                    ],
                                                    [
                                                        'class' => 'icon-no'
                                                    ]
                                                );
                                                echo '</div>';
                                                break;
                                            default:
                                                echo '<div class="cell-content cell-content-text">';
                                                echo wpautop( $this->parse_text_editor( $feature['content_text'] ) );
                                                echo '</div>';
                                                break;
                                        endswitch;
                                    endif;
                                ?>
                                </div>
                                <?php
                            endfor;
                            ?>
                        </div>
                        <?php
                    endfor;
                    ?>
                </div>
            </div><!-- /.axi-comparison-table-desktop -->
            
            <div class="axi-comparison-table-mobile" data-axiel="mobile-compare-table">
                <div class="ct-features">
                    <div class="ct-feature-entries" data-axirole="features">
                        <div class="ct-tr-head" data-axirole="feature" data-index="heading"></div>
                        <div class="ct-tr-subhead" data-axirole="feature" data-index="subheading">
                            <div class="ct-td ct-td-feature">
                                <div class="cell-content cell-content-text">
                                    <?php
                                    if ( $settings['features_subheading'] ) :
                                        echo esc_html( $settings['features_subheading'] );
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        for ( $fi = 0; $fi < count( $settings['features'] ); $fi++ ) : ?>
                            <div class="ct-tr-content" data-axirole="feature" data-index="feature-<?php echo esc_attr( $fi + 1 ); ?>">
                                <div class="ct-td ct-td-feature">
                                    <div class="cell-content cell-content-text">
                                        <?php echo esc_html( $settings['features'][ $fi ]['label'] ); ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endfor;
                        ?>
                    </div>
                </div>
                <div class="ct-tables">
                    <div class="axi-carousel-wrapper ct-table-entries" data-axirole="tables" data-options="<?php echo esc_attr( json_encode( $carousel_options ) ); ?>">
                        <?php
                        for ( $i = 0; $i < $settings['table_count']; $i ++ ) :
                            $index = $i + 1;
                            ?>
                            <div class="ct-table-entry">
                                <div class="ct-table" data-axirole="table">
                                    <div class="ct-tr-head"  data-axirole="feature" data-index="heading">
                                        <div class="ct-th ct-th-content">
                                            <div class="cell-content cell-content-text">
                                                <?php echo esc_html( $settings['table_' . $index . '_heading'] ); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ct-tr-subhead"  data-axirole="feature" data-index="subheading">
                                        <div class="ct-td ct-td-content">
                                            <div class="cell-content cell-content-text">
                                                <?php echo esc_html( $settings['table_' . $index . '_subheading'] ); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    for ( $fi = 0; $fi < count( $settings['features'] ); $fi++ ) : ?>
                                        <div class="ct-tr-content" data-axirole="feature" data-index="feature-<?php echo esc_attr( $fi + 1 ); ?>">
                                            <div class="ct-td ct-td-content">
                                                <?php
                                                if ( isset( $settings['table_' . $index . '_features'][ $fi ] ) ) :
                                                    $feature = $settings['table_' . $index . '_features'][ $fi ];
                                                    switch( $feature['content_type'] ) :
                                                        case 'image':
                                                            echo '<div class="cell-content cell-content-wimage">';
                                                            $image_id = absint( $feature['content_image']['id'] );
                                                            $image_html = wp_get_attachment_image( $image_id, 'medium' );
                                                            if ( $image_html ) :
                                                                echo $image_html;
                                                            endif;
                                                            echo '</div>';
                                                            break;
                                                        case 'yes':
                                                            echo '<div class="cell-content cell-content-icon">';
                                                            \Elementor\Icons_Manager::render_icon(
                                                                [
                                                                    'library' => 'solid',
                                                                    'value'   => 'fas fa-check'
                                                                ],
                                                                [
                                                                    'class' => 'icon-yes'
                                                                ]
                                                            );
                                                            echo '</div>';
                                                            break;
                                                        case 'no':
                                                            echo '<div class="cell-content cell-content-icon">';
                                                            \Elementor\Icons_Manager::render_icon(
                                                                [
                                                                    'library' => 'solid',
                                                                    'value'   => 'fas fa-times'
                                                                ],
                                                                [
                                                                    'class' => 'icon-no'
                                                                ]
                                                            );
                                                            echo '</div>';
                                                            break;
                                                        default:
                                                            echo '<div class="cell-content cell-content-text">';
                                                            echo wpautop( $this->parse_text_editor( $feature['content_text'] ) );
                                                            echo '</div>';
                                                            break;
                                                    endswitch;
                                                endif;
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                    endfor;
                                    ?>
                                </div><!-- /.ct-table -->
                            </div>
                            <?php
                        endfor;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}