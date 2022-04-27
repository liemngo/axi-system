<?php
namespace AXi_System\Formidable;

/**
 * Custom Course Type Formidable field for AcademyXi.
 * 
 * @since 1.0.0
 */
class Field_Course_Type
{
    /**
     * Field type
     *
     * @var string
     * @access private
     */
    protected const TYPE = 'axi_course_type';

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
            'name' => esc_html__( 'AXi Course Type', 'axi-system' ),
            'icon' => 'frm_icon_font frm_check_square_icon',
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
            $field_values['name'] = esc_html( 'Course Type', 'axi-system' );
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

        $options = $this->get_select_choices();

        echo '<div class="axi-checkboxes">';
        foreach( $options as $key => $option )
        {
            printf(
                '<div class="axi-checkbox-ui">' .
                    '<input id="%1$s" name="%2$s" type="checkbox" value="%3$s" />' .
                    '<label style="color:inherit" for="%1$s">%4$s</label>' .
                '</div>',
                'axicoursetype-' . academyxi_guid(),
                esc_attr( $field_name . '[]' ),
                esc_attr( $key ),
                esc_html( $option )
            );
        }
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
     * @return array
     */
    private function get_select_choices()
    {
        if ( ! taxonomy_exists( 'axi_course_type' ) )
        {
            return array();
        }
        $data = [];
        $course_types = get_terms( array(
            'taxonomy'   => 'axi_course_type',
            'hide_empty' => false
        ));
        if ( is_wp_error( $course_types ) || empty( $course_types ) )
        {
            return $data;
        }
        foreach( $course_types as $course_type )
        {
            $data[ $course_type->term_id ] = $course_type->name;
        }
        return $data;
    }
}