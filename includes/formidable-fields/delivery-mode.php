<?php
namespace AXi_System\Formidable;

/**
 * Custom Delivery Mode Formidable field for AcademyXi.
 * 
 * @since 1.0.0
 */
class Field_Delivery_Mode
{
    /**
     * Field type
     *
     * @var string
     * @access private
     */
    protected const TYPE = 'axi_delivery_mode';

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
        $fields[ self::TYPE ] = array(
            'name' => esc_html__( 'AXi Delivery Mode', 'axi-system' ),
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
            $field['name'] = esc_html( 'Delivery Mode', 'axi-system' );
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
        if ( $posted_field->type !== self::TYPE )
        {
            return $errors;
        }
        $v = absint( $value );
        if ( $posted_field->required == '1' && $v <= 0 && class_exists( '\FrmFieldsHelper' ) )
        {
            $errors[ 'field' . $args['id'] ] = \FrmFieldsHelper::get_error_msg( $posted_field, 'blank' );
        }
        return $errors;
    }

    /**
     * Get choices in id => name paid
     *
     * @param  boolean $filtered Get single choice based on current post terms, default true
     * @return array
     */
    private function get_select_choices( $filtered = true )
    {
        global $post;
        if ( ! taxonomy_exists( 'axi_delivery_mode' ) )
        {
            return array();
        }

        $data   = [];
        $dmodes = get_terms( array(
            'taxonomy'   => 'axi_delivery_mode',
            'hide_empty' => false
        ));
        
        if ( is_wp_error( $dmodes ) )
        {
            return $data;
        }

        if ( ! $filtered )
        {
            foreach( $dmodes as $dmode )
            {
                $data[ $dmode->term_id ] = $dmode->name;
            }
        }
        else
        {
            $post_dmodes = wp_get_post_terms( $post->ID, 'axi_delivery_mode' );
            if ( is_wp_error( $post_dmodes ) || empty( $post_dmodes ) )
            {
                foreach( $dmodes as $dmode )
                {
                    $data[ $dmode->term_id ] = $dmode->name;
                }
            }
            else
            {
                $data[ $post_dmodes[0]->term_id ] = $post_dmodes[0]->name;
            }
        }
        return $data;
    }
}