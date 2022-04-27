<?php
namespace AXi_System\Elementor\Conditions;

use \ElementorPro\Modules\ThemeBuilder as ThemeBuilder;

class Page_Template_Modality extends ThemeBuilder\Conditions\Condition_Base
{
    public static function get_type()
    {
        return 'page-template';
    }

    public function get_name()
    {
        return 'page-template-axisys-tmpl-modality';
    }

    public function get_label()
    {
        return esc_html__( 'AXi System - Modality Pages', 'elementor-pro' );
    }

    public function check( $args )
    {
        return is_page() && is_page_template( AXISYS_TMPL_MODALITY );
    }
}