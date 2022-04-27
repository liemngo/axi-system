<?php
namespace AXi_System;

/**
 * Metabox for all supported post types
 * 
 * @since 1.0.0
 */
class Post_Meta_Boxes
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
    function __construct()
    {
        add_action( 'post_submitbox_misc_actions', [ $this, 'post_submitbox_misc_actions' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_course' ] );
        add_action( 'save_post', [ $this, 'save_page' ] );
        add_action( 'save_post', [ $this, 'save_promotion' ], 50, 2 );

        add_filter( 'acf/load_value', [ $this, 'acf_load_value' ], 10, 3 );
        add_filter( 'acf/update_value', [ $this, 'acf_update_value' ], 10, 3 );

        add_filter( 'manage_edit-page_columns', [ $this, 'page_columns' ] );
        add_action( 'manage_page_posts_custom_column', [ $this, 'page_column_content' ], 10, 2 );

        add_filter( 'manage_edit-axi_feedback_columns', [ $this, 'feedback_columns' ] );
        add_action( 'manage_axi_feedback_posts_custom_column', [ $this, 'feedback_column_content' ], 10, 2 );

        add_filter( 'manage_edit-axi_instructor_columns', [ $this, 'instructor_columns' ] );
        add_action( 'manage_axi_instructor_posts_custom_column', [ $this, 'instructor_column_content' ], 10, 2 );

        add_filter( 'manage_edit-axi_promotion_columns', [ $this, 'promotion_columns' ] );
        add_action( 'manage_axi_promotion_posts_custom_column', [ $this, 'promotion_column_content' ], 10, 2 );
    }

    /**
     * Add a hidden field to include pagenow along with $_POST
     *
     * @param \WP_Post $post
     * @return void
     */
    function post_submitbox_misc_actions( $post )
    {
        global $pagenow;
        printf(
            '<input type="hidden" name="axisys-pagenow" value="%s" />',
            esc_attr( $pagenow )
        );
    }

    /**
     * Add meta boxes
     *
     * @return void
     */
    function add_meta_boxes()
    {
        add_meta_box(
            'axi_course_meta_box',
            esc_html__( 'Additional Attributes', 'axi-system' ),
            [ $this, 'course_meta_box' ],
            'axi_course',
            'side'
        );

        add_meta_box(
            'axi_page_meta_box',
            esc_html__( 'Additional Attributes', 'axi-system' ),
            [ $this, 'page_meta_box' ],
            'page',
            'normal'
        );
    }

    /**
     * Additional info for courses
     *
     * @param \WP_Post $post
     * @return void
     */
    function course_meta_box( $post )
    {
        wp_nonce_field( 'axi_course_meta_box', 'axi_course_meta_box_nonce' );

        $diss = wp_get_post_terms( $post->ID, 'axi_discipline' );
        $dis  = 0;
        if ( ! empty( $diss ) && ! is_wp_error( $diss ) )
        {
            $dis = $diss[0]->term_id;
        }
        echo '<div class="axifield-wrap axifield-discipline-wrap">';
        printf(
            '<p class="axifield-label-wrap axifield-discipline-label-wrap">' .
                '<label class="post-attributes-label" for="axifield-discipline">%s <span class="axifield-required">*</span></label>' .
            '</p>',
            esc_html__( 'Discipline', 'axi-system' )
        );
        wp_dropdown_categories( [
            'taxonomy'          => 'axi_discipline',
            'hide_empty'        => false,
            'name'              => 'axifield[discipline]',
            'id'                => 'axifield-discipline',
            'class'             => 'widefat',
            'selected'          => $dis,
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0,
            'required'          => true
        ] );
        echo '</div>';

        $ctypes = wp_get_post_terms( $post->ID, 'axi_course_type' );
        $ctype  = 0;
        if ( ! empty( $ctypes ) && ! is_wp_error( $ctypes ) )
        {
            $ctype = $ctypes[0]->term_id;
        }
        echo '<div class="axifield-wrap axifield-course-type-wrap">';
        printf(
            '<p class="axifield-label-wrap axifield-course-type-label-wrap">' .
                '<label class="post-attributes-label" for="axifield-course-type">%s <span class="axifield-required">*</span></label>' .
            '</p>',
            esc_html__( 'Course Type', 'axi-system' )
        );
        wp_dropdown_categories( [
            'taxonomy'          => 'axi_course_type',
            'hide_empty'        => false,
            'name'              => 'axifield[course-type]',
            'id'                => 'axifield-course-type',
            'class'             => 'widefat',
            'selected'          => $ctype,
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0,
            'required'          => true
        ] );
        echo '</div>';

        printf(
            '<p id="axifield-locmode-msg" class="axifield-locmode-msg" style="display:none">%s</p>',
            esc_html__( 'Please select combination of Location and Delivery Mode. This message will dissapear if the combination is correct.', 'axi-system' )
        );

        $locs = wp_get_post_terms( $post->ID, 'axi_location' );
        $loc  = 0;
        if ( ! empty( $locs ) && ! is_wp_error( $locs ) )
        {
            $loc = $locs[0]->term_id;
        }
        echo '<div class="axifield-wrap axifield-location-wrap">';
        printf(
            '<p class="axifield-label-wrap axifield-location-label-wrap">' .
                '<label class="post-attributes-label" for="axifield-location">%s</label>' .
            '</p>',
            esc_html__( 'Location', 'axi-system' )
        );
        wp_dropdown_categories( [
            'taxonomy'          => 'axi_location',
            'hide_empty'        => false,
            'name'              => '',
            'id'                => 'axifield-location',
            'class'             => 'widefat',
            'selected'          => $loc,
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0
        ] );
        printf( '<input type="hidden" name="axifield[location]" data-axi-sfield="axifield-location" value="%s" />', esc_attr( $loc ) );
        echo '</div>';

        $modes = wp_get_post_terms( $post->ID, 'axi_delivery_mode' );
        $mode  = 0;

        if ( ! empty( $modes ) && ! is_wp_error( $modes ) )
        {
            $mode = $modes[0]->term_id;
        }
        echo '<div class="axifield-wrap axifield-delivery-mode-wrap">';
        printf(
            '<p class="axifield-label-wrap axifield-delivery-mode-label-wrap">' .
                '<label class="post-attributes-label" for="axifield-delivery-mode">%s</label>' .
            '</p>',
            esc_html__( 'Delivery Mode', 'axi-system' )
        );
        wp_dropdown_categories( [
            'taxonomy'          => 'axi_delivery_mode',
            'hide_empty'        => false,
            'name'              => '',
            'id'                => 'axifield-delivery-mode',
            'class'             => 'widefat',
            'selected'          => $mode,
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0
        ] );
        printf( '<input type="hidden" name="axifield[delivery_mode]" data-axi-sfield="axifield-delivery-mode" value="%s" />', esc_attr( $mode ) );
        echo '</div>';
    }

    /**
     * Additional info for pages
     *
     * @param \WP_Post $post
     * @return void
     */
    function page_meta_box( $post )
    {
        wp_nonce_field( 'axi_page_meta_box', 'axi_page_meta_box_nonce' );

        $diss = wp_get_post_terms( $post->ID, 'axi_discipline' );
        $dis  = 0;
        if ( ! empty( $diss ) && ! is_wp_error( $diss ) )
        {
            $dis = $diss[0]->term_id;
        }
        echo '<div class="axifield-wrap axifield-discipline-wrap">';
        printf(
            '<p class="axifield-label-wrap axifield-discipline-label-wrap">' .
                '<label class="post-attributes-label" for="axifield-discipline">%s</label>' .
            '</p>',
            esc_html__( 'Discipline', 'axi-system' )
        );
        wp_dropdown_categories( [
            'taxonomy'          => 'axi_discipline',
            'hide_empty'        => false,
            'name'              => 'axifield[discipline]',
            'id'                => 'axifield-discipline',
            'class'             => 'widefat',
            'selected'          => $dis,
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0
        ] );
        echo '</div>';

        $locs = wp_get_post_terms( $post->ID, 'axi_location' );
        $loc  = 0;
        if ( ! empty( $locs ) && ! is_wp_error( $locs ) )
        {
            $loc = $locs[0]->term_id;
        }
        echo '<div class="axifield-wrap axifield-location-wrap">';
        printf(
            '<p class="axifield-label-wrap axifield-location-label-wrap">' .
                '<label class="post-attributes-label" for="axifield-location">%s</label>' .
            '</p>',
            esc_html__( 'Location', 'axi-system' )
        );
        wp_dropdown_categories( [
            'taxonomy'          => 'axi_location',
            'hide_empty'        => false,
            'name'              => 'axifield[location]',
            'id'                => 'axifield-location',
            'class'             => 'widefat',
            'selected'          => $loc,
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0
        ] );
        echo '</div>';

        $modes = wp_get_post_terms( $post->ID, 'axi_delivery_mode' );
        $mode  = 0;

        echo '<div class="axifield-wrap axifield-delivery-mode-wrap">';
        if ( ! empty( $modes ) && ! is_wp_error( $modes ) )
        {
            $mode = $modes[0]->term_id;
        }
        printf(
            '<p class="axifield-label-wrap axifield-delivery-mode-label-wrap">' .
                '<label class="post-attributes-label" for="axifield-delivery-mode">%s</label>' .
            '</p>',
            esc_html__( 'Delivery Mode', 'axi-system' )
        );
        wp_dropdown_categories( [
            'taxonomy'          => 'axi_delivery_mode',
            'hide_empty'        => false,
            'name'              => 'axifield[delivery_mode]',
            'id'                => 'axifield-delivery-mode',
            'class'             => 'widefat',
            'selected'          => $mode,
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0
        ] );
        echo '</div>';
    }

    /**
     * Save course meta boxes
     *
     * @param int $post_id
     * @return void
     */
    function save_course( $post_id )
    {
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        {
            return;
        }

        // Nonce field hasn't comfirmed.
        if ( isset( $_POST['post_type'] ) && ( 'axi_course' == $_POST['post_type'] ) )
        {
            if ( ! isset( $_POST['axi_course_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['axi_course_meta_box_nonce'], 'axi_course_meta_box' ) )
            {
                return;
            }
        }
        else
        {
            return;
        }

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) )
        {
            return;
        }

        $dis   = isset( $_POST['axifield']['discipline'] ) ? absint( $_POST['axifield']['discipline'] ) : 0;
        $ctype = isset( $_POST['axifield']['course-type'] ) ? absint( $_POST['axifield']['course-type'] ) : 0;
        $loc   = isset( $_POST['axifield']['location'] ) ? absint( $_POST['axifield']['location'] ) : 0;
        $mode  = isset( $_POST['axifield']['delivery_mode'] ) ? absint( $_POST['axifield']['delivery_mode'] ) : 0;

        wp_set_post_terms( $post_id, [ $dis ], 'axi_discipline' );
        wp_set_post_terms( $post_id, [ $ctype ], 'axi_course_type' );
        wp_set_post_terms( $post_id, [ $loc ], 'axi_location' );
        wp_set_post_terms( $post_id, [ $mode ], 'axi_delivery_mode' );
    }

    /**
     * Save page meta boxes
     *
     * @param int $post_id
     * @return void
     */
    function save_page( $post_id )
    {
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        {
            return;
        }

        // Nonce field hasn't comfirmed.
        if ( isset( $_POST['post_type'] ) && ( 'page' == $_POST['post_type'] ) )
        {
            if ( ! isset( $_POST['axi_page_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['axi_page_meta_box_nonce'], 'axi_page_meta_box' ) )
            {
                return;
            }
        }
        else
        {
            return;
        }

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) )
        {
            return;
        }

        switch( $_POST['page_template'] )
        {
            case AXISYS_TMPL_LANDING:
                $dis  = isset( $_POST['axifield']['discipline'] ) ? absint( $_POST['axifield']['discipline'] ) : 0;
                $loc  = isset( $_POST['axifield']['location'] ) ? absint( $_POST['axifield']['location'] ) : 0;
                $mode = isset( $_POST['axifield']['delivery_mode'] ) ? absint( $_POST['axifield']['delivery_mode'] ) : 0;

                wp_set_post_terms( $post_id, [ $dis ], 'axi_discipline' );
                wp_set_post_terms( $post_id, [ $loc ], 'axi_location' );
                wp_set_post_terms( $post_id, [ $mode ], 'axi_delivery_mode' );
                break;

            case AXISYS_TMPL_DISCIPLINE:
                wp_set_post_terms( $post_id, [], 'axi_location' );
                wp_set_post_terms( $post_id, [], 'axi_delivery_mode' );
                break;
            
            case AXISYS_TMPL_HOME:
            default:
                wp_set_post_terms( $post_id, [], 'axi_discipline' );
                wp_set_post_terms( $post_id, [], 'axi_location' );
                wp_set_post_terms( $post_id, [], 'axi_delivery_mode' );
                break;
        }
    }

    /**
     * Save page meta boxes
     *
     * @param int $post_id
     * @return void
     */
    function save_promotion( $post_id, $post )
    {
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        {
            return;
        }

        // Nonce field hasn't comfirmed.
        if ( ! isset( $_POST['post_type'] ) || 'axi_promotion' !== $_POST['post_type'] )
        {
            return;
        }

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) )
        {
            return;
        }

        $generic_promotions = get_posts([
            'numberposts'  => 1,
            'post_type'    => 'axi_promotion',
            'post_status'  => 'publish',
            'meta_key'     => '_promotion_type',
            'meta_value'   => 'generic',
            'meta_compare' => '=',
            'exclude'      => [ $post_id ]
        ]);

        if ( ! empty( $generic_promotions ) )
        {
            if ( function_exists( 'get_field_object' ) )
            {
                $ptype_acf = get_field_object( '_promotion_type' );

                if ( $ptype_acf && isset( $_POST['acf'][ $ptype_acf['key'] ] ) && 'generic' == $_POST['acf'][ $ptype_acf['key'] ] )
                {
                    remove_action( 'save_post', [ $this, 'save_promotion' ], 50, 2 );
                    $post->post_status = 'draft';
                    wp_update_post( $post );
                    add_action( 'save_post', [ $this, 'save_promotion' ], 50, 2 );
                    update_post_meta( $post_id, '_promotion_type', 'course' );
                }
            }
        }
    }

    /**
     * Filter ACF value when loaded
     *
     * @param mixed $value
     * @param int $id
     * @param array $field
     * @return void
     */
    function acf_load_value( $value, $id, $field )
    {
        if ( $field['type'] === 'number' )
        {
            if ( $field['name'] == '_promotion_percent' || $field['name'] == '_promotion_amount' )
            {
                $nv = absint( $value );
                $value = round( (float)$nv/100, 2 );
            }
        }
        return $value;
    }
    
    /**
     * Filter ACF value before saved
     *
     * @param mixed $value
     * @param int $id
     * @param array $field
     * @return void
     */
    function acf_update_value( $value, $id, $field )
    {
        if ( $field['type'] === 'number' )
        {
            if ( $field['name'] == '_promotion_percent' || $field['name'] == '_promotion_amount' )
            {
                $value = round( $value * 100 );
            }
        }
        return $value;
    }

    /**
     * Custom columns for feedbacks
     *
     * @param array $columns
     * @return array
     */
    function page_columns( $columns )
    {
        if ( isset( $columns['date'] ) )
        {
            $date = $columns['date'];
            unset( $columns['date'] );
        }

        if ( isset( $columns['comments'] ) )
        {
            unset( $columns['comments'] );
        }

        $columns['page_template'] = esc_html__( 'Template', 'axi-system' );

        if ( isset( $date ) )
        {
            $columns['date'] = $date;
        }
        return $columns;
    }

    /**
     * Custom column content for feedbacks
     *
     * @param string $column_name The name of the column to display.
     * @param int    $post_id     The current post ID.
     */
    function page_column_content( $column_name, $post_id )
    {
        if ( $column_name == 'page_template' )
        {
            $page = get_post( $post_id );
            $templates = wp_get_theme()->get_page_templates( $page );
            $cur_template = get_post_meta( $post_id, '_wp_page_template', true );
            if ( isset( $templates[ $cur_template ] ) )
            {
                echo $templates[ $cur_template ];
            }
            else
            {
                esc_html_e( 'Default', 'axi-system' );
            }
        }
    }

    /**
     * Custom columns for feedbacks
     *
     * @param array $columns
     * @return array
     */
    function feedback_columns( $columns )
    {
        if ( isset( $columns['date'] ) )
        {
            $date = $columns['date'];
            unset( $columns['date'] );
        }
        $columns['type'] = esc_html__( 'Type', 'axi-system' );
        if ( isset( $date ) )
        {
            $columns['date'] = $date;
        }
        return $columns;
    }

    /**
     * Custom column content for feedbacks
     *
     * @param string $column_name The name of the column to display.
     * @param int    $post_id     The current post ID.
     */
    function feedback_column_content( $column_name, $post_id )
    {
        if ( $column_name == 'type' )
        {
            if ( function_exists( 'get_field' ) )
            {
                $type = get_field( '_type', $post_id );
                if ( isset( $type['label'] ) )
                {
                    echo esc_html( $type['label'] );
                }
                else
                {
                    echo esc_html( $type );
                }
            }
            else
            {
                $type = get_post_meta( $post_id, '_type', true );
                echo esc_html( $type );
            }
        }
    }

    /**
     * Custom columns for instructors
     *
     * @param array $columns
     * @return void
     */
    function instructor_columns( $columns )
    {
        if ( isset( $columns['date'] ) )
        {
            $date = $columns['date'];
            unset( $columns['date'] );
        }
        $columns['name'] = esc_html__( 'Name', 'axi-system' );
        $columns['email'] = esc_html__( 'Email', 'axi-system' );
        $columns['link'] = esc_html__( 'Link', 'axi-system' );
        if ( isset( $date ) )
        {
            $columns['date'] = $date;
        }
        return $columns;
    }

    /**
     * Custom column content for instructors
     *
     * @param string $column_name The name of the column to display.
     * @param int    $post_id     The current post ID.
     */
    function instructor_column_content( $column_name, $post_id )
    {
        if ( $column_name == 'name' )
        {
            $name = get_post_meta( $post_id, '_name', true );
            if ( $name )
            {
                echo esc_html( $name );
            }
            else
            {
                echo '-';
            }
        }
        if ( $column_name == 'email' )
        {
            $email = get_post_meta( $post_id, '_email', true );
            if ( is_email( $email ) )
            {
                echo esc_html( $email );
            }
            else
            {
                echo '-';
            }
        }
        if ( $column_name == 'link' )
        {
            $link = get_post_meta( $post_id, '_page_link', true );
            if ( $link )
            {
                printf(
                    '<a href="%1$s" target="_blank">%2$s</a>',
                    esc_url( $link ),
                    esc_html__( 'Visit', 'axi-system' )
                );
            }
            else
            {
                echo '-';
            }
        }
    }

    /**
     * Custom columns for promotions
     *
     * @param array $columns
     * @return void
     */
    function promotion_columns( $columns )
    {
        if ( isset( $columns['date'] ) )
        {
            $date = $columns['date'];
            unset( $columns['date'] );
        }
        
        if ( isset( $columns['taxonomy-axi_discipline'] ) )
        {
            unset( $columns['taxonomy-axi_discipline'] );
        }

        if ( isset( $columns['taxonomy-axi_delivery_mode'] ) )
        {
            unset( $columns['taxonomy-axi_delivery_mode'] );
        }

        if ( isset( $columns['taxonomy-axi_location'] ) )
        {
            unset( $columns['taxonomy-axi_location'] );
        }

        $columns['type'] = esc_html__( 'Type', 'axi-system' );
        $columns['typeval'] = esc_html__( 'Type Values', 'axi-system' );
        $columns['amount'] = esc_html__( 'Amount', 'axi-system' );
        $columns['status'] = esc_html__( 'Status', 'axi-system' );

        return $columns;
    }

    /**
     * Custom column content for promotions
     *
     * @param string $column_name The name of the column to display.
     * @param int    $post_id     The current post ID.
     */
    function promotion_column_content( $column_name, $post_id )
    {
        if ( $column_name == 'type' )
        {
            $before = $after = '';
            if ( function_exists( 'get_field' ) )
            {
                $type = get_field( '_promotion_type', $post_id );
                if ( isset( $type['value'] ) && 'generic' == $type['value'] )
                {
                    $before = '<strong style="color:#dc3545">';
                    $after = '</strong>';
                }
                if ( isset( $type['label'] ) )
                {
                    echo $before . esc_html( $type['label'] ) . $after;
                }
                else
                {
                    echo $before . esc_html( $type ) . $after;
                }
            }
            else
            {
                $type = get_post_meta( $post_id, '_promotion_type', true );
                echo $before . esc_html( $type ) . $after;
            }
        }

        if ( $column_name == 'typeval' )
        {
            $type = get_post_meta( $post_id, '_promotion_type', true );
            if ( 'discipline' == $type )
            {
                $disciplines = wp_get_post_terms( $post_id, 'axi_discipline' );
                $diss = '';
                if ( $disciplines && ! is_wp_error( $disciplines ) )
                {
                    foreach( $disciplines as $discipline )
                    {
                        $diss .= sprintf( '<li>%s</li>', $discipline->name );
                    }
                }
                if ( $diss )
                {
                    printf( '<ol style="margin:0">%s</ol>', $diss );
                }
            }
            elseif ( 'course' == $type )
            {
                $course_ids = maybe_unserialize( get_post_meta( $post_id, '_course_id', true ) );
                $courses = '';
                if ( is_array( $course_ids ) && ! empty( $course_ids ) )
                {
                    foreach( $course_ids as $course_id )
                    {
                        $courses .= sprintf( '<li>%s</li>', get_the_title( $course_id ) );
                    }
                }
                if ( $courses )
                {
                    printf( '<ol style="margin:0">%s</ol>', $courses );
                }
            }
        }

        if ( $column_name == 'amount' )
        {
            $type = get_post_meta( $post_id, '_promotion_amount_type', true );
            if ( $type == 'percent' )
            {
                $amount = get_post_meta( $post_id, '_promotion_percent', true );
                echo round( (float)$amount/100, 2 ) . '%';
            }
            elseif ( $type == 'flat' )
            {
                $amount = get_post_meta( $post_id, '_promotion_amount', true );
                echo round( (float)$amount/100, 2 ) . ' ' . esc_html__( '[Flat Rate]', 'axi-system' );
            }
        }

        if ( $column_name == 'status' )
        {
            if ( function_exists( 'get_field' ) )
            {
                $status = get_field( '_promotion_status', $post_id );
                if ( isset( $status['label'] ) && isset( $status['value'] ) )
                {
                    printf( '<span class="status-%1$s">%2$s</span>', esc_attr( $status['value'] ), esc_html( $status['label'] ) );
                }
                else
                {
                    echo esc_html( $status );
                }
            }
            else
            {
                $status = get_post_meta( $post_id, '_promotion_status', true );
                echo esc_html( $status );
            }
        }
    }
}