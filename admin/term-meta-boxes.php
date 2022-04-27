<?php
namespace AXi_System;

/**
 * Metabox for all supported taxonomies and additional hooks
 * 
 * @since 1.0.0
 */
class Term_Meta_Boxes
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
        add_action( 'axi_discipline_link_add_form_fields', [ $this, 'new_term_form_fields' ] );
        add_action( 'axi_discipline_link_edit_form_fields', [ $this, 'edit_term_form_fields' ] );
        add_action( 'axi_discipline_link_edit_form', [ $this, 'show_term_edit_messages' ] );

        add_action( 'created_term', [ $this, 'update_term_form_fields' ], 10, 3 );
        add_action( 'edited_term', [ $this, 'update_term_form_fields' ], 10, 3 );

        add_filter( 'wp_insert_term_data', [ $this, 'insert_term_data' ], 10, 3 );
        add_filter( 'wp_update_term_data', [ $this, 'update_term_data' ], 10, 4 );

        add_action( 'axi_discipline_add_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_discipline_edit_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_delivery_mode_add_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_delivery_mode_edit_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_location_add_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_location_edit_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_course_type_add_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_course_type_edit_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_organisation_add_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_organisation_edit_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_discipline_guide_add_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_discipline_guide_edit_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_discipline_link_add_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_discipline_link_edit_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_tag_add_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_tag_edit_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_discount_code_add_form', [ $this, 'hide_tax_stuffs' ] );
        add_action( 'axi_discount_code_edit_form', [ $this, 'hide_tax_stuffs' ] );

        add_filter( 'manage_edit-axi_discipline_columns', [ $this, 'tax_discipline_edit_columns' ] );
        add_filter( 'manage_edit-axi_delivery_mode_columns', [ $this, 'tax_edit_columns' ] );
        add_filter( 'manage_edit-axi_location_columns', [ $this, 'tax_edit_columns' ] );
        add_filter( 'manage_edit-axi_course_type_columns', [ $this, 'tax_edit_columns' ] );
        add_filter( 'manage_edit-axi_organisation_columns', [ $this, 'tax_edit_columns' ] );
        add_filter( 'manage_edit-axi_discipline_guide_columns', [ $this, 'tax_edit_columns' ] );
        add_filter( 'manage_edit-axi_discipline_link_columns', [ $this, 'tax_edit_columns' ] );
        add_filter( 'manage_edit-axi_discount_code_columns', [ $this, 'tax_edit_columns' ] );

        add_filter( 'manage_edit-axi_discipline_link_columns', [ $this, 'discipline_link_edit_columns' ] );
        add_filter( 'manage_axi_discipline_link_custom_column', [ $this, 'discipline_link_column_content' ], 10, 3 );
        
        add_filter( 'manage_edit-axi_discipline_guide_columns', [ $this, 'discipline_guide_edit_columns' ] );
        add_filter( 'manage_axi_discipline_guide_custom_column', [ $this, 'discipline_guide_column_content' ], 10, 3 );

        add_filter( 'manage_edit-axi_location_columns', [ $this, 'location_edit_columns' ] );
        add_filter( 'manage_axi_location_custom_column', [ $this, 'location_column_content' ], 10, 3 );

        add_filter( 'manage_edit-axi_tag_columns', [ $this, 'tag_edit_columns' ] );
        add_filter( 'manage_axi_tag_custom_column', [ $this, 'tag_column_content' ], 10, 3 );

        add_filter( 'manage_edit-axi_discount_code_columns', [ $this, 'discount_code_edit_columns' ] );
        add_filter( 'manage_axi_discount_code_custom_column', [ $this, 'discount_code_column_content' ], 10, 3 );

        add_filter( 'acf/load_value', [ $this, 'acf_load_value' ], 10, 3 );
        add_filter( 'acf/update_value', [ $this, 'acf_update_value' ], 10, 3 );
    }

    /**
     * Show fields on new term screen
     *
     * @param \WP_Taxonomy $taxonomy
     * @return void
     */
    function new_term_form_fields( $taxonomy )
    {
        echo '<div class="axifield-wrap axifield-discipline-wrap">';
        printf(
            '<label class="post-attributes-label" for="axifield-discipline">%s <span class="axifield-required">*</span></label>',
            esc_html__( 'Discipline', 'axi-system' )
        );
        wp_dropdown_categories( [
            'taxonomy'          => 'axi_discipline',
            'hide_empty'        => false,
            'name'              => 'axifield[discipline]',
            'id'                => 'axifield-discipline',
            'class'             => 'widefat',
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0
        ] );
        echo '</div>';

        echo '<div class="axifield-wrap axifield-location-wrap">';
        printf(
            '<label class="post-attributes-label" for="axifield-location">%s</label>',
            esc_html__( 'Location', 'axi-system' )
        );
        wp_dropdown_categories( [
            'taxonomy'          => 'axi_location',
            'hide_empty'        => false,
            'name'              => '',
            'id'                => 'axifield-location',
            'class'             => 'widefat',
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0
        ] );
        echo '<input type="hidden" name="axifield[location]" data-axi-sfield="axifield-location" value="" />';
        echo '</div>';

        echo '<div class="axifield-wrap axifield-delivery-mode-wrap">';
        printf(
            '<label class="post-attributes-label" for="axifield-delivery-mode">%s</label>',
            esc_html__( 'Delivery Mode', 'axi-system' )
        );
        wp_dropdown_categories( [
            'taxonomy'          => 'axi_delivery_mode',
            'hide_empty'        => false,
            'name'              => '',
            'id'                => 'axifield-delivery-mode',
            'class'             => 'widefat',
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0
        ] );
        echo '<input type="hidden" name="axifield[delivery_mode]" data-axi-sfield="axifield-delivery-mode" value="" />';
        echo '</div>';

        echo '<p id="axifield-locmode-msg" class="axifield-locmode-msg" style="display:none">';
        esc_html_e( 'Please select combination of Location and Delivery Mode. This message will dissapear if the combination is correct.', 'axi-system' );
        echo '</p>';

        echo '<div class="axifield-wrap axifield-page-id-wrap">';
        printf(
            '<label class="post-attributes-label" for="axifield-page-id">%s</label>',
            esc_html__( 'Discipline Page Link', 'axi-system' )
        );
        echo '<input type="text" name="axifield[page_url]" class="widefat" value="" />';
        /* wp_dropdown_pages( [
            'name'              => 'axifield[page_id]',
            'id'                => 'axifield-page-id',
            'class'             => 'widefat',
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0,
        ] ); */
        echo '</div>';
    }

    /**
     * Show fields on term edit screen
     *
     * @param \WP_Term $term
     * @return void
     */
    function edit_term_form_fields( $term )
    {
        $dis     = absint( get_term_meta( $term->term_id, '_discipline', true ) );
        $loc     = absint( get_term_meta( $term->term_id, '_location', true ) );
        $mode    = absint( get_term_meta( $term->term_id, '_delivery_mode', true ) );
        $page_id = absint( get_term_meta( $term->term_id, '_page_id', true ) );
        $page_url = get_term_meta( $term->term_id, '_page_url', true );

        echo '<tr class="form-field form-required term-discipline-wrap">';
        printf(
                '<th scope="row">' .
                    '<label for="axifield-discipline">%s</label>' .
                '</th>',
                esc_html__( 'Discipline', 'axi-system' )
        );
        echo    '<td>';
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
        echo    '</td>';
        echo '</tr>';

        echo '<tr class="form-field form-required term-location-wrap">';
        printf(
                '<th scope="row">' .
                    '<label for="axifield-location">%s</label>' .
                '</th>',
                esc_html__( 'Location', 'axi-system' )
        );
        echo    '<td>';
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
        echo    '</td>';
        echo '</tr>';

        echo '<tr class="form-field form-required term-delivery-mode-wrap">';
        printf(
                '<th scope="row">' .
                    '<label for="axifield-delivery-mode">%s</label>' .
                '</th>',
                esc_html__( 'Delivery Mode', 'axi-system' )
        );
        echo    '<td>';
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
        echo    '</td>';
        echo '</tr>';

        echo '<tr class="form-field form-required term-page-link-wrap">';
        printf(
                '<th scope="row">' .
                    '<label for="axifield-page">%s</label>' .
                '</th>',
                esc_html__( 'Discipline Page Link', 'axi-system' )
        );
        echo    '<td>';
        printf(     '<input type="text" name="axifield[page_url]" value="%s" />', esc_url( $page_url ) );
        /* wp_dropdown_pages( [
            'selected'          => $page_id,
            'name'              => 'axifield[page_id]',
            'id'                => 'axifield-page-id',
            'class'             => 'widefat',
            'show_option_none'  => esc_html__( '- None -', 'axi-system' ),
            'option_none_value' => 0,
        ] ); */
        echo    '</td>';
        echo '</tr>';
    }

    /**
     * Show message on term new/edit screen
     *
     * @return void
     */
    function show_term_edit_messages()
    {
        printf(
            '<p id="axifield-locmode-msg" class="axifield-locmode-msg" style="display:none">%s</p>',
            esc_html__( 'Please select combination of Location and Delivery Mode. This message will dissapear if the combination is correct.', 'axi-system' )
        );
    }

    /**
     * Save meta boxes
     *
     * @param int    $term_id  Term ID.
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
     * @return void
     */
    function update_term_form_fields( $term_id, $tt_id, $taxonomy )
    {
        if ( 'axi_discipline_link' == $taxonomy )
        {
            $dis     = isset( $_POST['axifield']['discipline'] ) ? absint( $_POST['axifield']['discipline'] ) : 0;
            $loc     = isset( $_POST['axifield']['location'] ) ? absint( $_POST['axifield']['location'] ) : 0;
            $mode    = isset( $_POST['axifield']['delivery_mode'] ) ? absint( $_POST['axifield']['delivery_mode'] ) : 0;
            // $page_id = isset( $_POST['axifield']['page_id'] ) ? absint( $_POST['axifield']['page_id'] ) : 0;
            $page_url = isset( $_POST['axifield']['page_url'] ) ? esc_url_raw( $_POST['axifield']['page_url'] ) : '';

            update_term_meta( $term_id, '_discipline', $dis );
            update_term_meta( $term_id, '_location', $loc );
            update_term_meta( $term_id, '_delivery_mode', $mode );
            // update_term_meta( $term_id, '_page_id', $page_id );
            update_term_meta( $term_id, '_page_url', $page_url );
        }
    }

    /**
     * Generate discipline code (slug)
     *
     * @param integer $term_id
     * @param string  $taxonomy
     * @return string
     */
    private function generate_discipline_code( $term_id = 0, $taxonomy )
    {
        $desired = '';
        while ( true )
        {
            $desired = axisys_guid( 3 );
            $maybe_term = get_term_by( 'slug', $desired, $taxonomy );
            if ( ! $maybe_term )
            {
                break;
            }
        }
        return $desired;
    }

    /**
     * Filters axi_discipline term data before it is inserted into the database.
     *
     * @param array  $data     Term data to be inserted.
     * @param string $taxonomy Taxonomy slug.
     * @param array  $args     Arguments passed to wp_insert_term().
     */
    function insert_term_data( $data, $taxonomy, $args )
    {
        switch( $taxonomy )
        {
            case 'axi_discipline':
                $data['slug'] = $this->generate_discipline_code( 0, $taxonomy );
                break;

            case 'axi_location':
                $data['slug'] = str_replace( '-', '', $data['slug'] );
                $maybe_term = null;
                while ( true )
                {
                    $maybe_term = get_term_by( 'slug', $data['slug'], $taxonomy );
                    if ( ! $maybe_term || is_wp_error( $maybe_term ) )
                    {
                        break;
                    }
                    else
                    {
                        $data['slug'] .= axisys_guid();
                    }
                }
                break;

            default:
                break;
        }
        
        return $data;
    }

    /**
     * Filters axi_discipline term data before it is updated in the database.
     *
     * @param array  $data     Term data to be updated.
     * @param int    $term_id  Term ID.
     * @param string $taxonomy Taxonomy slug.
     * @param array  $args     Arguments passed to wp_update_term().
     */
    function update_term_data( $data, $term_id, $taxonomy, $args )
    {
        switch( $taxonomy )
        {
            case 'axi_discipline':
                if ( strlen( $data['slug'] ) !== 3 )
                {
                    $data['slug'] = $this->generate_discipline_code( $term_id, $taxonomy );
                }
                break;

            case 'axi_location':
                $data['slug'] = str_replace( '-', '', $data['slug'] );
                $maybe_term = null;
                while ( true )
                {
                    $maybe_term = get_term_by( 'slug', $data['slug'], $taxonomy );
                    if ( ! $maybe_term || is_wp_error( $maybe_term ) )
                    {
                        break;
                    }
                    else
                    {
                        $data['slug'] .= axisys_guid();
                    }
                }
                break;

            default:
                break;
        }

        return $data;
    }

    /**
     * Hide the term description in the edit form.
     * Hooked both 'taxonomy_add_form' and 'taxonomy_edit_form'
     * 
     * @param string|WP_Term $param Depends on hooks, it can be string or WP_Term object.
     */
    function hide_tax_stuffs( $param )
    {
        $selectors = [];
        $taxonomy = '';
        if ( $param instanceof \WP_Term )
        {
            $taxonomy = $param->taxonomy;
        }
        else
        {
            $taxonomy = $param;
        }
        switch( $taxonomy )
        {
            case 'axi_discipline':
            case 'axi_discipline_guide':
            case 'axi_delivery_mode':
            case 'axi_location':
            case 'axi_course_type':
            case 'axi_organisation':
            case 'axi_tag':
            case 'axi_discount_code':
                $selectors = [
                    '.term-slug-wrap',
                    '.term-description-wrap',
                    '.term-parent-wrap'
                ];
                break;
            case 'axi_discipline_link':
                $selectors = [
                    '.term-slug-wrap',
                    '.term-parent-wrap'
                ];
                break;
            default:
                break;
        }
        if ( $selectors )
        {
            echo '<style>';
            echo implode( ',', $selectors );
            echo '{display:none}';
            echo '</style>';
        }
    }

    /**
     * Add/Remove columns at discipline taxonomy term add/edit screen
     *
     * @param array $columns
     * @return void
     */
    function tax_discipline_edit_columns( $columns )
    {
        if ( isset( $columns['posts'] ) )
        {
            unset( $columns['posts'] );
        }
        if ( isset( $columns['description'] ) )
        {
            unset( $columns['description'] );
        }
        if ( isset( $columns['slug'] ) )
        {
            $columns['slug'] = esc_html__( 'Code', 'axi-system' );
        }
        return $columns;
    }

    /**
     * Add/Remove columns at taxonomy term add/edit screen
     *
     * @param array $columns
     * @return void
     */
    function tax_edit_columns( $columns )
    {
        if ( isset( $columns['posts'] ) )
        {
            unset( $columns['posts'] );
        }
        if ( isset( $columns['description'] ) )
        {
            unset( $columns['description'] );
        }
        return $columns;
    }

    /**
     * Add/Remove columns at discipline_link taxonomy term add screen
     *
     * @param array $columns
     * @return void
     */
    function discipline_link_edit_columns( $columns )
    {
        // $slug = $columns['slug'];
        unset( $columns['slug'] );
        $columns['discipline'] = esc_html__( 'Discipline', 'axi-system' );
        $columns['location'] = esc_html__( 'Location', 'axi-system' );
        $columns['delivery_mode'] = esc_html__( 'Delivery Mode', 'axi-system' );
        // $columns['page_id'] = esc_html__( 'Linked Page', 'axi-system' );
        $columns['page_url'] = esc_html__( 'Linked Page', 'axi-system' );
        $columns['logo'] = esc_html__( 'Logo', 'axi-system' );
        // $columns['slug'] = $slug;
        return $columns;
    }

    /**
     * Fill column content on discipline_link taxonomy term add screen
     *
     * @param string $value
     * @param string $column_name
     * @param int $term_id
     * @return void
     */
    function discipline_link_column_content( $value, $column_name, $term_id )
    {
        if ( $column_name == 'discipline' || $column_name == 'location' || $column_name == 'delivery_mode' || $column_name == 'page_url' || $column_name == 'logo' )
        {
            $value = '-';
            if ( $column_name == 'discipline' )
            {
                $di_id = absint( get_term_meta( $term_id, '_discipline', true ) );
                if ( $di_id )
                {
                    $discipline = get_term( $di_id );
                    if ( ! is_wp_error( $discipline ) && ! empty( $discipline ) )
                    {
                        $value = esc_html( $discipline->name );
                    }
                }
            }
            if ( $column_name == 'location' )
            {
                $di_id = absint( get_term_meta( $term_id, '_location', true ) );
                if ( $di_id )
                {
                    $location = get_term( $di_id );
                    if ( ! is_wp_error( $location ) && ! empty( $location ) )
                    {
                        $value = esc_html( $location->name );
                    }
                }
            }
            if ( $column_name == 'delivery_mode' )
            {
                $dm_id = absint( get_term_meta( $term_id, '_delivery_mode', true ) );
                if ( $dm_id )
                {
                    $delivery_mode = get_term( $dm_id );
                    if ( ! is_wp_error( $delivery_mode ) && ! empty( $delivery_mode ) )
                    {
                        $value = esc_html( $delivery_mode->name );
                    }
                }
            }
            if ( $column_name == 'page_id' )
            {
                $p_id = absint( get_term_meta( $term_id, '_page_id', true ) );
                if ( $p_id )
                {
                    $page_title = get_the_title( $p_id );
                    if ( $page_title )
                    {
                        $value = sprintf(
                            '<a href="%1$s">%2$s</a>',
                            esc_url( admin_url( 'post.php?post=' . $p_id . '&action=edit' ) ),
                            $page_title
                        );
                    }
                }
            }
            if ( $column_name == 'page_url' )
            {
                $p_url = get_term_meta( $term_id, '_page_url', true );
                if ( $p_url )
                {
                    $value = sprintf( '<a href="%1$s" target="blank">%2$s</a>', esc_url( $p_url ), esc_html__( 'View', 'axi-system' ) );
                }
            }
            if ( $column_name == 'logo' )
            {
                $logo_id = absint( get_term_meta( $term_id, '_discipline_logo', true ) );
                if ( $logo_id )
                {
                    $value = wp_get_attachment_image( $logo_id, 'thumbnail', false, [
                        'style' => 'width:48px;max-width:100%;height:auto'
                    ] );
                }
            }
        }
        return $value;
    }

    /**
     * Add/Remove columns at discipline_guide taxonomy term add screen
     *
     * @param array $columns
     * @return void
     */
    function discipline_guide_edit_columns( $columns )
    {
        // $slug = $columns['slug'];
        if ( isset( $columns['slug'] ) )
        {
            unset( $columns['slug'] );
        }
        $columns['discipline'] = esc_html__( 'Disciplines', 'axi-system' );
        $columns['delivery_mode'] = esc_html__( 'Delivery Mode', 'axi-system' );
        $columns['location'] = esc_html__( 'Location', 'axi-system' );
        // $columns['slug'] = $slug;
        return $columns;
    }

    /**
     * Fill column content on discipline_guide taxonomy term add screen
     *
     * @param string $value
     * @param string $column_name
     * @param int $term_id
     * @return void
     */
    function discipline_guide_column_content( $value, $column_name, $term_id )
    {
        if ( $column_name == 'discipline' || $column_name == 'delivery_mode' || $column_name == 'location' )
        {
            $value = '-';
            if ( $column_name == 'discipline' )
            {
                $di_id = absint( get_term_meta( $term_id, '_discipline', true ) );
                if ( $di_id )
                {
                    $discipline = get_term( $di_id );
                    if ( ! is_wp_error( $discipline ) && ! empty( $discipline ) )
                    {
                        $value = esc_html( $discipline->name );
                    }
                }
            }
            if ( $column_name == 'delivery_mode' )
            {
                $dm_id = absint( get_term_meta( $term_id, '_delivery_mode', true ) );
                if ( $dm_id )
                {
                    $delivery_mode = get_term( $dm_id );
                    if ( ! is_wp_error( $delivery_mode ) && ! empty( $delivery_mode ) )
                    {
                        $value = esc_html( $delivery_mode->name );
                    }
                }
            }
            if ( $column_name == 'location' )
            {
                $loc_id = absint( get_term_meta( $term_id, '_location', true ) );
                if ( $loc_id )
                {
                    $location = get_term( $loc_id );
                    if ( ! is_wp_error( $location ) && ! empty( $location ) )
                    {
                        $value = esc_html( $location->name );
                    }
                }
            }
        }
        return $value;
    }

    /**
     * Add/Remove columns at location taxonomy term add screen
     *
     * @param array $columns
     * @return void
     */
    function location_edit_columns( $columns )
    {
        if ( isset( $columns['slug'] ) )
        {
            unset( $columns['slug'] );
        }
        $columns['country'] = esc_html__( 'Country', 'axi-system' );
        $columns['city'] = esc_html__( 'City', 'axi-system' );
        $columns['address'] = esc_html__( 'Address', 'axi-system' );
        $columns['show_hide'] = esc_html__( 'Show/Hide', 'axi-system' );
        return $columns;
    }

    /**
     * Fill column content on location taxonomy term add screen
     *
     * @param string $value
     * @param string $column_name
     * @param int $term_id
     * @return void
     */
    function location_column_content( $value, $column_name, $term_id )
    {
        if ( $column_name == 'country' || $column_name == 'city' || $column_name == 'address' || $column_name == 'show_hide' )
        {
            $value = '-';
            if ( $column_name == 'country' )
            {
                if ( function_exists( 'get_field' ) )
                {
                    $country = get_field( '_country', 'axi_location_' . $term_id );
                    if ( isset( $country['label'] ) )
                    {
                        $value = esc_html( $country['label'] );
                    }
                    else
                    {
                        $value = esc_html( $country );
                    }
                }
                else
                {
                    $country = get_term_meta( $term_id, '_country', true );
                    if ( $country )
                    {
                        $value = esc_html( $country );
                    }
                }
            }
            if ( $column_name == 'city' )
            {
                $city = get_term_meta( $term_id, '_city', true );
                if ( $city )
                {
                    $value = esc_html( $city );
                }
            }
            if ( $column_name == 'address' )
            {
                $address = get_term_meta( $term_id, '_address', true );
                if ( $address )
                {
                    $value = esc_html( $address );
                }
            }
            if ( $column_name == 'show_hide' )
            {
                $show = get_term_meta( $term_id, '_show', true );
                if ( $show )
                {
                    $value = esc_html__( 'Show', 'axi-system' );
                }
                else
                {
                    $value = esc_html__( 'Hide', 'axi-system' );
                }
            }
        }
        return $value;
    }

    /**
     * Add/Remove columns at discount_code taxonomy term add screen
     *
     * @param array $columns
     * @return void
     */
    function tag_edit_columns( $columns )
    {
        if ( isset( $columns['description'] ) )
        {
            unset( $columns['description'] );
        }
        if ( isset( $columns['slug'] ) )
        {
            unset( $columns['slug'] );
        }
        if ( isset( $columns['posts'] ) )
        {
            unset( $columns['posts'] );
        }
        $columns['discipline'] = esc_html__( 'Disciplines', 'axi-system' );
        return $columns;
    }

    /**
     * Fill column content on discount_code taxonomy term add screen
     *
     * @param string $value
     * @param string $column_name
     * @param int $term_id
     * @return void
     */
    function tag_column_content( $value, $column_name, $term_id )
    {
        if ( $column_name == 'discipline' )
        {
            $diss = maybe_unserialize( get_term_meta( $term_id, '_discipline_links', true ) );
            $dissv = [];
            foreach( $diss as $dis )
            {
                $term = get_term( $dis, 'axi_discipline_link' );
                if ( $term )
                {
                    $dissv[] = $term->name;
                }
            }
            if ( $dissv )
            {
                $value = '<ol style="margin:0">';
                foreach( $dissv as $disv )
                {
                    $value .= '<li>' . $disv . '</li>';
                }
                $value .= '</ol>';
            }
            else
            {
                $value = '-';
            }
        }
        return $value;
    }

    /**
     * Add/Remove columns at discount_code taxonomy term add screen
     *
     * @param array $columns
     * @return void
     */
    function discount_code_edit_columns( $columns )
    {
        if ( isset( $columns['slug'] ) )
        {
            unset( $columns['slug'] );
        }
        $columns['code'] = esc_html__( 'Code', 'axi-system' );
        $columns['type'] = esc_html__( 'Type', 'axi-system' );
        $columns['amount'] = esc_html__( 'Amount', 'axi-system' );
        $columns['courses'] = esc_html__( 'Courses', 'axi-system' );
        $columns['expiry'] = esc_html__( 'Expiry', 'axi-system' );
        return $columns;
    }

    /**
     * Fill column content on discount_code taxonomy term add screen
     *
     * @param string $value
     * @param string $column_name
     * @param int $term_id
     * @return void
     */
    function discount_code_column_content( $value, $column_name, $term_id )
    {
        if ( $column_name == 'code' || $column_name == 'type' || $column_name == 'amount' || $column_name == 'courses' || $column_name == 'expiry' )
        {
            $value = '-';
            if ( $column_name == 'code' )
            {
                $discount_code = get_term_meta( $term_id, '_discount_code', true );
                if ( $discount_code )
                {
                    $value = esc_html( $discount_code );
                }
            }
            if ( $column_name == 'type' )
            {
                if ( function_exists( 'get_field' ) )
                {
                    $discount_type = get_field( '_discount_type', 'axi_discount_code_' . $term_id );
                    if ( isset( $discount_type['label'] ) )
                    {
                        $value = esc_html( $discount_type['label'] );
                    }
                    else
                    {
                        $value = esc_html( $discount_type );
                    }
                }
                else
                {
                    $discount_type = get_term_meta( $term_id, '_discount_type', true );
                    if ( $discount_type )
                    {
                        $value = esc_html( $discount_type );
                    }
                }
            }
            if ( $column_name == 'amount' )
            {
                $discount_type = get_term_meta( $term_id, '_discount_type', true );
                $discount_amount = 0;
                if ( 'percent' == $discount_type )
                {
                    $discount_amount = absint( get_term_meta( $term_id, '_discount_percent', true ) );
                }
                elseif ( 'flat' == $discount_type )
                {
                    $discount_amount = absint( get_term_meta( $term_id, '_discount_amount', true ) );
                }
                if ( $discount_amount >= 0 )
                {
                    $value = esc_html( round( (float)$discount_amount/100, 2 ) );
                }
            }
            if ( $column_name == 'courses' )
            {
                $courses = maybe_unserialize( get_term_meta( $term_id, '_course_id', true ) );
                $course_names = [];
                foreach( $courses as $course )
                {
                    $course_names[] = get_the_title( $course );
                }
                if ( $course_names )
                {
                    $value = '<ol style="margin:0">';
                    foreach( $course_names as $course_name )
                    {
                        $value .= '<li>' . esc_html( $course_name ) . '</li>';
                    }
                    $value .= '</ol>';
                }
            }
            if ( $column_name == 'expiry' )
            {
                if ( function_exists( 'get_field' ) )
                {
                    $_discount_expiry = get_field( '_discount_expiry', 'axi_discount_code_' . $term_id );
                }
                else
                {
                    $_discount_expiry = get_term_meta( $term_id, '_discount_expiry', true );
                }
                if ( $_discount_expiry )
                {
                    $date = strtotime( $_discount_expiry );
                    $value = esc_html( date( 'M d, Y', $date ) );
                }
            }
        }
        return $value;
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
            if ( $field['name'] == '_discount_percent' || $field['name'] == '_discount_amount' )
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
            if ( $field['name'] == '_discount_percent' || $field['name'] == '_discount_amount' )
            {
                $value = round( $value * 100 );
            }
        }
        return $value;
    }
}