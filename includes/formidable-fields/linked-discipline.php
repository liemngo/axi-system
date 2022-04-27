<?php
namespace AXi_System\Formidable;

use AXi_System\Location;

/**
 * Custom Linked Discipline Formidable field for AcademyXi.
 * 
 * @since 1.0.0
 */
class Field_Linked_Discipline
{
    /**
     * Field type
     *
     * @var string
     * @access private
     */
    protected const TYPE = 'axi_linked_discipline';

    /**
     * Class instance.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var self
     */
    private static $_instance = null;
    
    /**
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return self An instance of the class.
     */
    public static function instance()
    {
        if ( is_null( self::$_instance ) )
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        add_filter( 'frm_available_fields', [ $this, 'available_fields' ] );
        add_filter( 'frm_before_field_created', [ $this, 'before_field_created' ] );
        add_filter( 'frm_display_field_options', [ $this, 'display_field_options' ] );

        add_action( 'frm_field_options_form_top', [ $this, 'field_options_form_top' ], 10, 3 );
        add_filter( 'frm_update_field_options', [ $this, 'update_field_options' ], 10, 3 );

        add_action( 'frm_display_added_fields', [ $this, 'display_added_fields'] );

        add_action( 'frm_form_fields', [ $this, 'form_fields' ], 10, 3 );
        add_filter( 'frm_validate_field_entry', [ $this, 'validate_field_entry' ], 10, 4 );
    }

    /**
     * Register our field.
     *
     * @param  array $fields
     * @return array
     */
    function available_fields( $fields )
    {
        $fields['axi_linked_discipline'] = array(
            'name' => esc_html__( 'AXi Linked Disciplines', 'axi-system' ),
            'icon' => 'frm_icon_font frm_caret_square_down_icon',
        );
        return $fields;
    }
    
    /**
     * Set default field settings.
     *
     * @param  array $field_values
     * @return array
     */
    function before_field_created( $field_values )
    {
        if ( $field_values['type'] == self::TYPE )
        {
            $field_values['name'] = esc_html( 'Linked Disciplines', 'axi-system' );
        }
        return $field_values;
    }

    /**
     * Display settings for our field.
     *
     * @param  array $settings
     * @return array
     */
    function display_field_options( $settings)
    {
        if ( $settings['type'] == self::TYPE )
        {
            $settings['clear_on_focus'] = true;
        }
        return $settings;
    }

    /**
     * Additional options for our field within builder area.
     *
     * @param array $field
     * @param array $display
     * @param array $values
     * @return void
     */
    function field_options_form_top( $field, $display, $values ) {}

    /**
     * Save our additional options for the field.
     *
     * @param  array $field_options
     * @param  array $field
     * @param  array $values
     * @return array
     */
    function update_field_options( $field_options, $field, $values )
    {
        return $field_options;
    }

    /**
     * Render field within builder area.
     *
     * @param  array $field
     * @return void
     */
    function display_added_fields( $field ) {}

    /**
     * Front-end field render.
     *
     * @param array $field
     * @param array $field_name
     * @param array $atts
     * @return void
     */
    function form_fields( $field, $field_name, $atts )
    {
        if ( $field['type'] !== self::TYPE )
        {
            return;
        }

        $empty_opt = esc_html( $field['placeholder'] );
        if ( empty( $empty_opt ) )
        {
            $empty_opt = esc_html__( '- Empty -', 'axi-system' );
        }

        $options = $this->get_select_choices();

        echo '<div class="axi-select-ui" data-axiel="select-ui" data-field-type="' . esc_attr( $field['type'] ) . '">';
        printf(
                '<div class="axi-select-display%1$s" data-axirole="display">%2$s</div>',
                $field['value'] ? '' : ' placeholder',
                $empty_opt
        );
        echo    '<ul class="axi-select-dropdown" data-axirole="dropdown">';
        foreach( $options as $id => $option )
        {
            printf(
                '<li class="%1$s"><a data-value="%2$s" href="javascript:void(0);">%3$s</a></li>',
                $field['value'] == $id ? 'active' : 'idle',
                esc_attr( $id ),
                esc_html( $option )
            );
        }
        echo    '</ul>';
        printf(
                '<select id="%1$s" name="%2$s" data-axirole="select" style="display:none">',
                esc_attr( $atts['html_id'] ), esc_attr( $field_name )
        );
        printf(
            '<option value="0">%s</option>',
            $empty_opt
        );
        foreach( $options as $id => $option )
        {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                esc_attr( $id ),
                selected( $id, $field['value'], false ),
                esc_html( $option )
            );
        }
        echo    '</select>';
        echo '</div>';
    }

    /**
     * Validate field values.
     *
     * @param  array $errors
     * @param  array $posted_field
     * @param  array $value
     * @param  array $args
     * @return array
     */
    function validate_field_entry( $errors, $posted_field, $value, $args )
    {
        return $errors;
    }

    /**
     * Get choices in id => name pair
     *
     * @return array
     */
    private function get_select_choices()
    {
        global $post;
        if ( ! taxonomy_exists( 'axi_discipline' ) )
        {
            return array();
        }

        $data = [];
        $post_disciplines = wp_get_post_terms( $post->ID, 'axi_discipline' );

        if ( is_wp_error( $post_disciplines ) || empty( $post_disciplines ) )
        {
            $cookie_location = Location::get_current_term();
            $ploc_id = $pmode_id = 0;

            if ( $cookie_location && ! is_wp_error( $cookie_location ) )
            {
                $ploc_id = $cookie_location->term_id;
            }
            else
            {
                $post_locs  = wp_get_post_terms( $post->ID, 'axi_location' );
                if ( ! is_wp_error( $post_locs ) && ! empty( $post_locs ) )
                {
                    $ploc_id = $post_locs[0]->term_id;
                }
            }

            $post_modes = wp_get_post_terms( $post->ID, 'axi_delivery_mode' );
            if ( ! is_wp_error( $post_modes ) && ! empty( $post_modes ) )
            {
                $pmode_id = $post_modes[0]->term_id;
            }

            $qargs = array(
                'taxonomy'   => 'axi_discipline_link',
                'hide_empty' => false
            );
            
            if ( $ploc_id || $pmode_id )
            {
                $qargs['meta_query'] = array();
                if ( $ploc_id )
                {
                    $qargs['meta_query'][] = array(
                        'key' => '_location',
                        'value' => $ploc_id,
                        'compare' => '='
                    );
                    
                }
                if ( $pmode_id )
                {
                    $qargs['meta_query'][] = array(
                        'key' => '_delivery_mode',
                        'value' => $pmode_id,
                        'compare' => '='
                    );
                }
            }
            $dlinks = get_terms( $qargs );
            foreach( $dlinks as $dlink )
            {
                $term_discipline_id = absint( get_term_meta( $dlink->term_id, '_discipline', true ) );
                if ( ! $term_discipline_id )
                {
                    continue;
                }
                $term_discipline = get_term( $term_discipline_id, 'axi_discipline' );
                if ( empty( $term_discipline ) || is_wp_error( $term_discipline ) || array_key_exists( $term_discipline_id, $data ) )
                {
                    continue;
                }
                $data[ $term_discipline->term_id ] = $term_discipline->name;
            }
        }
        else
        {
            $data[ $post_disciplines[0]->term_id ] = $post_disciplines[0]->name;
        }
        return $data;
    }
}