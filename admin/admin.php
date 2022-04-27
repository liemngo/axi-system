<?php 
namespace AXi_System;

/**
 * Admin class for the plugin
 * 
 * @since 1.0.0
 */
class Admin
{
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

    protected $default_opts = [];
    
    /**
     * Ensures only one instance of class is loaded or can be loaded.
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
     * Admin constructor.
     *
     * @since 1.0.0
     * @access public
     */
    private function __construct()
    {
        Post_Meta_Boxes::instance();
        Term_Meta_Boxes::instance();

        add_action( 'admin_init', [ $this, 'admin_init' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
        add_action( 'admin_head', [ $this, 'admin_head' ] );
        add_action( 'wp_ajax_axisys_migrate_db', [ $this, 'axisys_migrate_db' ] );

        add_filter( 'parent_file', [ $this, 'parent_file' ] );
    }

    /**
     * admin_init hook
     *
     * @return void
     */
    function admin_init()
    {
        register_setting(
            AXISYS_OPT_GROUP,
            AXISYS_OPT_NAME,
            [
                'sanitize_callback' => [ $this, 'sanitize_opts' ],
                'show_in_rest'      => false,
                'default'           => $this->get_default_opts()
            ]
        );

        add_settings_section(
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_api',
            esc_html__( 'General API settings', 'axi-system'),
            [ $this, 'print_section_info' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID
        );

        add_settings_field(
            'api_base_url',
            esc_html__( 'API Base End Point', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_api',
            [
                'id'    => 'api_base_url',
                'type'  => 'url',
                'value' => axisys_get_opt( 'api_base_url' )
            ]
        );

        add_settings_field(
            'api_star_ratings_path',
            esc_html__( 'Star Ratings End Point Path', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_api',
            [
                'id'    => 'api_star_ratings_path',
                'type'  => 'text',
                'value' => axisys_get_opt( 'api_star_ratings_path' ),
                'description' => esc_html__( 'Enter path without leading and ending forward slashes. Eg: placeholders/test or placeholders.', 'axi-system' )
            ]
        );

        add_settings_field(
            'api_course_atts_path',
            esc_html__( 'Course Attributes End Point Path', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_api',
            [
                'id'    => 'api_course_atts_path',
                'type'  => 'text',
                'value' => axisys_get_opt( 'api_course_atts_path' ),
                'description' => esc_html__( 'Enter path without leading and ending forward slashes. Eg: placeholders/test or placeholders.', 'axi-system' )
            ]
        );

        add_settings_field(
            'api_webhook_path',
            esc_html__( 'Webhook End Point Path', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_api',
            [
                'id'    => 'api_webhook_path',
                'type'  => 'text',
                'value' => axisys_get_opt( 'api_webhook_path' ),
                'description' => esc_html__( 'Enter path without leading and ending forward slashes. Eg: placeholders/test or placeholders.', 'axi-system' )
            ]
        );

        add_settings_field(
            'api_webhook_secret',
            esc_html__( 'Webhook Secret', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_api',
            [
                'id'    => 'api_webhook_secret',
                'type'  => 'text',
                'value' => axisys_get_opt( 'api_webhook_secret' )
            ]
        );

        add_settings_field(
            'api_usr',
            esc_html__( 'API User Name', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_api',
            [
                'id'    => 'api_usr',
                'type'  => 'text',
                'value' => axisys_get_opt( 'api_usr' )
            ]
        );

        add_settings_field(
            'api_secret',
            esc_html__( 'API Secret', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_api',
            [
                'id'    => 'api_secret',
                'type'  => 'text',
                'value' => axisys_get_opt( 'api_secret' )
            ]
        );

        add_settings_section(
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_guide',
            esc_html__( 'Default Guide Settings', 'axi-system' ),
            [ $this, 'print_guide_section_info' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID
        );
        
        add_settings_field(
            'default_guide_name',
            esc_html__( 'Guide Name', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_guide',
            [
                'id'    => 'default_guide_name',
                'type'  => 'text',
                'value' => axisys_get_opt( 'default_guide_name' )
            ]
        );

        add_settings_field(
            'default_guide_url',
            esc_html__( 'Guide Link', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_guide',
            [
                'id'    => 'default_guide_url',
                'type'  => 'url',
                'value' => axisys_get_opt( 'default_guide_url' )
            ]
        );

        add_settings_section(
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_loc_mode',
            esc_html__( 'Default Location and Delivery Mode Setting', 'axi-system' ),
            [ $this, 'print_section_info' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID
        );

        $location_options = [
            0 => esc_html__( '- Select -', 'axi-system' )
        ];
        $loc_terms = get_terms([
            'taxonomy' => 'axi_location',
            'hide_empty' => false
        ]);
        if ( ! empty( $loc_terms ) && ! is_wp_error( $loc_terms ) )
        {
            foreach( $loc_terms as $loc_term )
            {
                $location_options[ $loc_term->term_id ] = $loc_term->name;
            }
        }

        add_settings_field(
            'default_location_id',
            esc_html__( 'Default Location', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_loc_mode',
            [
                'id'    => 'default_location_id',
                'type'  => 'select',
                'options' => $location_options,
                'value' => axisys_get_opt( 'default_location_id' )
            ]
        );

        $delivery_mode_options = [
            0 => esc_html__( '- Select -', 'axi-system' )
        ];
        $dmode_terms = get_terms([
            'taxonomy' => 'axi_delivery_mode',
            'hide_empty' => false
        ]);
        if ( ! empty( $dmode_terms ) && ! is_wp_error( $dmode_terms ) )
        {
            foreach( $dmode_terms as $dmode_term )
            {
                $delivery_mode_options[ $dmode_term->term_id ] = $dmode_term->name;
            }
        }

        add_settings_field(
            'default_delivery_mode_id',
            esc_html__( 'Default Delivery Mode', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_loc_mode',
            [
                'id'    => 'default_delivery_mode_id',
                'type'  => 'select',
                'options' => $delivery_mode_options,
                'value' => axisys_get_opt( 'default_delivery_mode_id' ),
                'description' => esc_html__( 'Delivery Mode will set to this value when specific location is selected.', 'axi-system' )
            ]
        );

        add_settings_section(
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_misc',
            esc_html__( 'Miscellaneous', 'axi-system' ),
            [ $this, 'print_section_info' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID
        );

        add_settings_field(
            'custom_404_page_id',
            esc_html__( 'Custom 404 Page', 'axi-system' ),
            [ $this, 'render_setting_field' ],
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            AXISYS_ADMIN_SETTINGS_PAGE_ID . '_section_misc',
            [
                'id'    => 'custom_404_page_id',
                'type'  => 'page_select',
                'value' => axisys_get_opt( 'custom_404_page_id' ),
                'description' => sprintf( esc_html__( 'Please put shortcode %s to your theme\'s 404.php.', 'axi-system' ), '<code>[axisys404]</code>' )
            ]
        );
		
		$old = get_option('menu_side_options');
		$new = array();
		if(isset($_POST['menu_name']) || isset($_POST['menu_id'])){
			$menu_name = $_POST['menu_name'];
			$menu_id = $_POST['menu_id'];
			$count = count( $menu_name );
			
			for ( $i = 0; $i < $count; $i++ ) {
				if ( $menu_name[$i] != '' ) :
					$new[$i]['menu_name'] = $menu_name[$i];
					if(!empty( $menu_id[$i] )){
						$new[$i]['menu_id'] = $menu_id[$i];
					}else{
						$new[$i]['menu_id'] = sanitize_title($menu_name[$i]);
					}
					
				endif;
			}

			if ( !empty( $new ) && $new != $old ){
				update_option('menu_side_options', $new);
			}
		}
    }

    /**
     * admin_menu hook
     *
     * @return void
     */
    function admin_menu()
    {
        add_menu_page(
            esc_html__( 'AXi System Settings', 'axi-system' ),
            esc_html__( 'AXi System', 'axi-system' ),
            'editor',
            AXISYS_ADMIN_PAGE_ID,
            '',
            'dashicons-dashboard',
            '58'
        );

        add_submenu_page(
            AXISYS_ADMIN_PAGE_ID,
            esc_html__( 'AXi System Settings', 'axi-system' ),
            esc_html__( 'Settings', 'axi-system' ),
            'manage_options',
            AXISYS_ADMIN_SETTINGS_PAGE_ID,
            [ $this, 'settings_page' ]
        );

        add_submenu_page(
            AXISYS_ADMIN_PAGE_ID,
            esc_html__( 'Disciplines', 'axi-system' ),
            esc_html__( 'Disciplines', 'axi-system' ),
            'manage_categories',
            'edit-tags.php?taxonomy=axi_discipline'
        );

        add_submenu_page(
            AXISYS_ADMIN_PAGE_ID,
            esc_html__( 'Discipline Guides', 'axi-system' ),
            esc_html__( 'Discipline Guides', 'axi-system' ),
            'manage_categories',
            'edit-tags.php?taxonomy=axi_discipline_guide'
        );

        add_submenu_page(
            AXISYS_ADMIN_PAGE_ID,
            esc_html__( 'Discipline Links', 'axi-system' ),
            esc_html__( 'Discipline Links', 'axi-system' ),
            'manage_categories',
            'edit-tags.php?taxonomy=axi_discipline_link'
        );

        add_submenu_page(
            AXISYS_ADMIN_PAGE_ID,
            esc_html__( 'Delivery Modes', 'axi-system' ),
            esc_html__( 'Delivery Modes', 'axi-system' ),
            'manage_categories',
            'edit-tags.php?taxonomy=axi_delivery_mode'
        );

        add_submenu_page(
            AXISYS_ADMIN_PAGE_ID,
            esc_html__( 'Locations', 'axi-system' ),
            esc_html__( 'Locations', 'axi-system' ),
            'manage_categories',
            'edit-tags.php?taxonomy=axi_location'
        );

        add_submenu_page(
            AXISYS_ADMIN_PAGE_ID,
            esc_html__( 'Tags', 'axi-system' ),
            esc_html__( 'Tags', 'axi-system' ),
            'manage_categories',
            'edit-tags.php?taxonomy=axi_tag'
        );

        add_submenu_page(
            AXISYS_ADMIN_PAGE_ID,
            esc_html__( 'Course Types', 'axi-system' ),
            esc_html__( 'Course Types', 'axi-system' ),
            'manage_categories',
            'edit-tags.php?taxonomy=axi_course_type'
        );

        add_submenu_page(
            AXISYS_ADMIN_PAGE_ID,
            esc_html__( 'Discount Codes', 'axi-system' ),
            esc_html__( 'Discount Codes', 'axi-system' ),
            'manage_categories',
            'edit-tags.php?taxonomy=axi_discount_code'
        );
    }

    /**
     * Enqueue scripts and styles
     *
     * @return void
     */
    function admin_scripts()
    {
        $current_screen = get_current_screen();
        
        wp_enqueue_media();

        if ( ! wp_style_is( 'select2', 'enqueued' ) && $current_screen->post_type === 'axi_promotion' ) {
            wp_enqueue_style( 'select2', AXISYS_URL . 'assets/select2/4/select2.min.css', [], AXISYS_VERSION );
        }
        wp_enqueue_style( 'axi-system-admin', AXISYS_URL . 'assets/css/admin.css', [], '1.0.2' );

        if ( ! wp_script_is( 'select2', 'enqueued' ) && $current_screen->post_type === 'axi_promotion' ) {
            wp_enqueue_script( 'select2', AXISYS_URL . 'assets/select2/4/select2.min.js', [ 'jquery' ], AXISYS_VERSION, true );
        }
        wp_enqueue_script( 'axi-system-admin', AXISYS_URL . 'assets/js/admin.js', [ 'jquery' ], '1.0.2', true );

        $localize = [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'pagenow' => $current_screen->id,
            'typenow' => $current_screen->post_type,
            'defaultDeliveryMode' => absint( axisys_get_opt( 'default_delivery_mode_id', 0 ) ),
            'canBeGenericPromotion' => true,
            'canBeGenericPromotionMsg' => '',
            'imgSliderWidget' => [
                'frame_title' => esc_html__( 'Select Image(s)', 'axi-system' ),
                'button_title' => esc_html__( 'Insert Into Widget', 'axi-system' ),
                'frame_edit_title' => esc_html__( 'Change Image', 'axi-system' ),
                'button_edit_title' => esc_html__( 'Change', 'axi-system' )
            ]
        ];

        if ( 'axi_promotion' == $current_screen->id && 'axi_promotion' == $current_screen->post_type )
        {
            $localize['canBeGenericPromotionMsg'] = esc_html__( 'You are already had a Generic Promotion. Please choose other promotion type since you can have only one Generic Promotion at the time.', 'axi-system' );
            
            $post = get_post();
            if ( $post )
            {
                $generic_promotions = get_posts([
                    'numberposts'  => 1,
                    'post_type'    => 'axi_promotion',
                    'post_status'  => 'publish',
                    'meta_key'     => '_promotion_type',
                    'meta_value'   => 'generic',
                    'meta_compare' => '=',
                    'exclude'      => [ $post->ID ]
                ]);

                if ( empty( $generic_promotions ) )
                {
                    $localize['canBeGenericPromotion'] = 1;
                }
                else
                {
                    $localize['canBeGenericPromotion'] = 0;
                }
            }
        }

        wp_localize_script( 'axi-system-admin', 'AXiAdminLocalize', $localize );
    }

    function admin_head()
    {
        $current_screen = get_current_screen();
        if ( empty( $current_screen ) )
        {
            return;
        }

        $css = '';
        if ( 'post' == $current_screen->base && 'axi_course' == $current_screen->id )
        {
            // $css .= '#submitdiv #delete-action{display:none}';
        }

        if ( 'term' == $current_screen->base )
        {
            $hide_delete_screen_ids = [
                // 'edit-axi_discipline',
                // 'edit-axi_course_type',
                'edit-axi_delivery_mode',
                'edit-axi_location'
            ];
            if ( in_array( $current_screen->id, $hide_delete_screen_ids ) )
            {
                $css .= '#edittag #delete-link{display:none}';
            }
        }
        
        printf( '<style type="text/css">%s</style>', $css );
    }

    /**
     * Settings page render
     *
     * @return void
     */
    function settings_page()
    {
        echo '<div class="wrap">';
        echo    '<h1>' . esc_html__( 'Settings', 'axi-system' ) . '</h1>';
        /**
         * @todo remove hook after migrated
         */
        do_action( 'axisys_admin_settings_page' );
        echo    '<form id="axisys-settings-form" method="post" action="options.php">';
        settings_fields( AXISYS_OPT_GROUP );
        do_settings_sections( AXISYS_ADMIN_SETTINGS_PAGE_ID );

        /*
		?>
			<script type="text/javascript">
			jQuery(document).ready(function( $ ){
				$( '#add-row' ).on('click', function() {
					var row = $( '.empty-row.screen-reader-text' ).clone(true);
					row.removeClass( 'empty-row screen-reader-text' );
					row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
					return false;
				});
			
				$( '.remove-row' ).on('click', function() {
					var remove_row = confirm("Are you sure you want to delete?");
					if( remove_row == true){
						$(this).parents('tr').remove();
					}else{
						return false;
					}
					return false;
				});
			});
			</script>
			<hr>
			<h2><?php echo esc_html__( 'Settings AXi Side NavMenu', 'axi-system' ); ?></h2>
			<p><?php echo _e('General settings for menu id of AXis Sidenav Menu', 'axi-system'); ?></p>
			<table id="repeatable-fieldset-one" width="100%">
			<tbody>
			<tr>
				<td style="width: 65%;">
					<p class="regular-text"><?php echo _e('Name', 'axi-system'); ?></p>
					<p class="regular-text"><?php echo _e('ID', 'axi-system'); ?></p>
				</td>
				<td><?php echo _e('Remove', 'axi-system'); ?></td>
			</tr>
			<?php
			$menu_side_options = get_option('menu_side_options');
			if ( $menu_side_options ) :
			
				foreach ( $menu_side_options as $field ) {
				?>
				<tr>
					<td style="width: 65%;">
						<input type="text" placeholder="Name" class="regular-text" name="menu_name[]" value="<?php if($field['menu_name'] != '') echo esc_attr( $field['menu_name'] ); ?>" />
						<input type="text" placeholder="ID" class="regular-text" name="menu_id[]" value="<?php if ($field['menu_id'] != '') echo esc_attr( $field['menu_id'] ); else echo ''; ?>" />
					</td>
					<td><a style="color:red;" class="button remove-row" href="#"><?php echo _e('Remove', 'axi-system'); ?></a></td>
				</tr>
				<?php
				}
			else :
				$blanks = array(
					'axi-feedbacks' => 'AXi Feedbacks',
					'axi-course-list' => 'AXi Course List',
					'axi-instructors' => 'AXi Instructors',
					'axi-accordion' => 'AXi Accordion',
					'axi-feedbacks' => 'AXi Comment Textx',
					'axi-comment-textx' => 'AXi Feedbacks',
					'axi-media-cards' => 'AXi Media Cards',
					'axi-comparison-table' => 'AXi Comparison Table',
					'axi-formidable-form' => 'AXi Formidable Form',
					'axi-media-cards-icon' => 'AXi Media Cards Icon',
					'axi-feedbacks-user' => 'AXi Feedbacks User',
					'axi-organisations' => 'AXi Organisations',
				);
			?>
				<?php foreach ( $blanks as $key => $value ) { ?>
				<tr>
					<td style="width: 65%;">
						<input type="text" placeholder="Name" class="regular-text" name="menu_name[]" value="<?php echo $value; ?>" />
						<input type="text" placeholder="ID" class="regular-text" name="menu_id[]" value="<?php echo $key; ?>" />
					</td>
					<td><a style="color:red;" class="button remove-row" href="#"><?php echo _e('Remove', 'axi-system'); ?></a></td>
				</tr>
				<?php } ?>
			<?php endif; ?>
			
			<!-- empty hidden one for jQuery -->
			<tr class="empty-row screen-reader-text">
				<td style="width: 65%;">
					<input type="text" placeholder="Name" class="regular-text" name="menu_name[]" />
					<input type="text" placeholder="ID" class="regular-text" name="menu_id[]" value="" />
				</td>
				  
				<td><a style="color:red;" class="button remove-row" href="#"><?php echo _e('Remove', 'axi-system'); ?></a></td>
			</tr>
			</tbody>
			</table>
			
			<p><a id="add-row" class="button" href="#"><?php echo _e('Add New', 'axi-system'); ?></a></p>
        <?php
        */

        submit_button();
        echo    '</form>';
        echo '</div><!-- /.wrap -->';
    }

    /**
     * Print general info after title on Guide setting sections.
     *
     * @return void
     */
    function print_section_info() {}

    /**
     * Print info after title on Guide setting section.
     *
     * @return void
     */
    function print_guide_section_info()
    {
        echo '<p>';
        esc_html_e( 'This guide info will be used for emails from Form Submission if no Discipline Guide match.', 'axi-system' );
        echo '</p>';
    }

    function render_setting_field( $args )
    {
        $args = wp_parse_args( $args, [
            'id'    => '',
            'type'  => 'text',
            'value' => '',
            'class' => '',
            'description' => '',
            'placeholder' => ''
        ]);
        if ( ! $args['id'] )
        {
            return;
        }
        $args['class'] = trim( $args['class'] );
        $class = 'regular-text';

        if ( $args['class'] )
        {
            $class .= ' ' . $args['class'];
        }
        $atts = [
            'id'   => AXISYS_OPT_NAME . '_' . $args['id'],
            'name' => AXISYS_OPT_NAME . '[' . $args['id'] . ']',
        ];
        switch( $args['type'] )
        {
            case 'select':
                $atts_str = $this->generate_field_atts( $atts );
                echo '<select ' . $atts_str . '>';
                foreach( $args['options'] as $key => $value )
                {
                    printf(
                        '<option value="%1$s"%2$s>%3$s</option>',
                        esc_attr( $key ),
                        selected( $key, $args['value'], false ),
                        esc_html( $value )
                    );
                }
                echo '</select>';
                break;
            
            case 'page_select':
                $dropdown_args = [
                    'id' => $atts['id'],
                    'name' => $atts['name'],
                    'show_option_none' => '&mdash; ' . esc_html__( 'Select', 'axi-system' ) . ' &mdash;'
                ];
                if ( $args['value'] ) {
                    $dropdown_args['selected'] = $args['value'];
                }
                wp_dropdown_pages( $dropdown_args );
                break;
                
            default:
                $atts['value'] = $args['value'];
                $atts['class'] = 'regular-text';
                $atts['type'] = $args['type'];
                if ( $args['class'] )
                {
                    $atts['class'] .= ' ' . $args['class'];
                }
                if ( $args['placeholder'] )
                {
                    $atts['placeholder'] = $args['placeholder'];
                }
                $atts_str = $this->generate_field_atts( $atts );
                echo '<input ' . $atts_str . '>';
                break;
        }
        if ( $args['description'] )
        {
            printf(
                '<p class="description">%s</p>',
                $args['description']
            );
        }
    }

    /**
     * Generate atributes string.
     *
     * @param  array $atts
     * @return string
     */
    protected function generate_field_atts( $atts )
    {
        if ( ! $atts )
        {
            return '';
        }
        $attr_str = '';
        foreach( $atts as $attr => $value )
        {
            $attr_str .= $attr . '="' . esc_attr( $value ) . '" ';
        }
        return trim( $attr_str );
    }

    /**
     * Get default options
     *
     * @return array
     */
    function get_default_opts()
    {
        if ( ! $this->default_opts )
        {
            $this->default_opts = maybe_serialize( [
                'api_base_url'          => '',
                'api_star_ratings_path' => '',
                'api_webhook_path'      => '',
                'api_webhook_secret'    => '',
                'api_usr'               => '',
                'api_secret'            => '',
                'default_guide_name'    => '',
                'default_guide_url'     => '',
                'default_location_id'   => '',
                'default_delivery_mode_id' => '',
                'custom_404_page_id' => ''
            ] );
        }
        return $this->default_opts;
    }

    /**
     * Sanitize settings before save
     *
     * @param  array $values
     * @return array
     */
    function sanitize_opts( $values )
    {
        $results = array();
        if ( is_array( $values ) )
        {
            foreach ( $values as $key => $value )
            {
                switch( $key )
                {
                    case 'api_base_url':
                    case 'default_guide_url':
                        $value = esc_url_raw( $value );
                        break;
                    case 'api_usr':
                    case 'api_secret':
                        $value = sanitize_text_field( $value );
                        break;
                    case 'custom_404_page_id':
                        $value = absint( $value );
                        break;
                    default:
                        $value = esc_html( $value );
                        break;
                }
                $results[ $key ] = $value;
            }
        }
        return maybe_serialize( $results );
    }

    /**
     * Filters the parent file of an admin menu sub-menu item.
     *
     * @param string $parent_file The parent file.
     */
    function parent_file( $parent_file )
    {
        $screen = get_current_screen();
        $axisys_screen_ids = [
            'edit-axi_discipline',
            'edit-axi_discipline_guide',
            'edit-axi_discipline_link',
            'edit-axi_delivery_mode',
            'edit-axi_location',
            'edit-axi_tag',
            'edit-axi_discount_code'
        ];

        if ( ( $screen->post_type == 'post' && in_array( $screen->id, $axisys_screen_ids ) )
            || ( $screen->id == 'axi_promotion' && $screen->post_type == 'axi_promotion' ) )
        {
            $parent_file = AXISYS_ADMIN_PAGE_ID;
        }
        return $parent_file;
    }
}