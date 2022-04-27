<?php
namespace AXi_System\Elementor;

/**
 * options parameter needs to have 0 for adding new and reset things.
*/
class Control_Sortable_Select extends \Elementor\Base_Data_Control
{
    /**
     * Get control type.
     *
     * Retrieve the control type.
     *
     * @access public
     */
    public function get_type()
    {
        return 'axi_sortable_select';
    }

    /**
     * Control content template.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * Note that the content template is wrapped by \Elementor\Base_Control::print_template().
     *
     * @access public
     */
    public function content_template()
    {
        $control_uid = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
                <label for="<?php echo $control_uid; ?>-select" class="elementor-control-title">{{{ data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper">
                <#
                var values = data.controlValue.split( ',' ).filter( function( v ) {
                    const vint = parseInt( v, 10 );
                    if ( isNaN( vint ) ) {
                        return 0;
                    }
                    return vint;
                });
                if ( values.length > 0 ) { #>
                <ul class="axiec-sortable-select-display" data-axiec-sortable-select-role="display">
                    <# _.each( values, function( value ) { #>
                        <# if ( 'undefined' != typeof data.options[value] ) { #>
                        <li data-axiec-value="{{ value }}"><a href="javascript:void(0)" class="remove">×</a><span class="text">{{{ data.options[value] }}}</span></li>
                        <# } #>
                    <# } ); #>
                </ul>
                <# } #>
                <select id="<?php echo esc_attr( $control_uid ); ?>-select" data-axiec-sortable-select-role="select">
                    <# _.each( data.options, function( option_title, option_value ) {
                        var disabled = '';
                        if ( values.indexOf( option_value ) != -1 ) {
                            disabled = ' disabled="disabled"';
                        }
                        #>
                        <option value="{{ option_value }}"{{ disabled }}>{{{ option_title }}}</option>
                    <# } ); #>
                </select>
                <input id="<?php echo esc_attr( $control_uid ); ?>" type="hidden" data-setting="{{ data.name }}" data-axiec-sortable-select-role="value"/>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    /**
     * Enqueue control scripts and styles.
     *
     * Used to register and enqueue custom scripts and styles used by the control.
     *
     * @access public
     */
    public function enqueue()
    {
        wp_enqueue_style(
            'axie-sortable-select',
            AXISYS_URL . 'assets/css/axie-sortable-select.css',
            [],
            '0.0.1'
        );
        wp_register_script(
            'axie-sortable-select',
            AXISYS_URL . 'assets/js/axie-sortable-select.js',
            [ 'jquery', 'jquery-ui-sortable' ],
            '0.0.1',
            true
        );
        wp_enqueue_script( 'axie-sortable-select' );
    }

    /**
     * Get default control settings.
     *
     * Retrieve the default settings of the control. Used to return the default
     * settings while initializing the control.
     *
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function get_default_settings()
    {
        return [
            'options' => [
                0 => esc_html__( '- Add New -', 'axi-system' )
            ],
            'label_block' => true
        ];
    }
}