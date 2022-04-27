<?php
namespace AXi_System;

/**
 * AcademyXi API request
 *
 * @since 1.0.0
 */
class API_Request
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
     * API base url
     *
     * @var string
     * @access private
     */
    private $api_url;

    /**
     * API Username
     *
     * @var string
     * @access private
     */
    private $api_usr;

    /**
     * API Secret
     *
     * @var string
     * @access private
     */
    private $api_secret;

    /**
     * Star rating path
     *
     * @var string
     * @access private
     */
    private $rating_path;

    /**
     * Course attributes path
     *
     * @var string
     * @access private
     */
    private $course_atts_path;

    /**
     * Webhook path
     *
     * @var string
     * @access private
     */
    private $webhook_path;

    /**
     * Webhook secret
     *
     * @var string
     * @access private
     */
    private $webhook_secret;

    /**
     * Supported post types
     *
     * @var array
     * @access private
     */
    private $post_types;

    /**
     * Supported taxonomies
     *
     * @var array
     * @access private
     */
    private $taxonomies;

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
     * Class constructor
     *
     * @since 1.0.0
     * @access public
     */
    private function __construct()
    {
        $this->post_types = [
            'axi_course',
            'axi_feedback',
            'axi_instructor',
            'axi_promotion'
        ];

        $this->taxonomies = [
            'axi_course_type',
            'axi_organisation',
            'axi_discipline',
            'axi_discipline_guide',
            'axi_discipline_link',
            'axi_delivery_mode',
            'axi_location',
            'axi_tag',
            'axi_discount_code'
        ];

        add_action( 'save_post', [ $this, 'add_update_post_webhook' ], 100, 2 );
        add_action( 'before_delete_post', [ $this, 'delete_post_webhook' ], 100 );

        add_action( 'created_term', [ $this, 'add_term_webhook' ], 100, 3 );
        add_action( 'edited_term', [ $this, 'update_term_webhook' ], 100, 3 );
        add_action( 'pre_delete_term', [ $this, 'delete_term_webhook' ], 100, 3 );
    }

    /**
     * Send webhook API request once a post has been saved.
     *
     * @param int      $post_ID Post ID.
     * @param \WP_Post $post    Post object.
     */
    function add_update_post_webhook( $post_ID, $post )
    {
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        {
            return;
        }

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_ID ) )
        {
            return;
        }

        if ( ! isset( $_POST['axisys-pagenow'] ) )
        {
            return;
        }

        if ( 'post-new.php' == $_POST['axisys-pagenow'] )
        {
            $this->do_post_webhook( 'ADD', $post );
        }
        elseif ( 'post.php' == $_POST['axisys-pagenow'] )
        {
            $this->do_post_webhook( 'UPDATE', $post );
        }
    }

    /**
     * Send webhook API before a post is deleted, at the start of wp_delete_post()
     * so all post meta is available. Moving post to thrash is not count.
     *
     * @see wp_delete_post()
     *
     * @param int $postid Post ID.
     */
    function delete_post_webhook( $postid )
    {
        // Check the user's permissions.
        if ( ! current_user_can( 'delete_post', $postid ) )
        {
            return $postid;
        }

        $post = get_post( $postid );
        if ( ! $post )
        {
            return $postid;
        }

        $this->do_post_webhook( 'DELETE', $post );
    }

    /**
     * Perform webhook request for posts
     *
     * @param string $action 'ADD', 'UPDATE' or 'DELETE'
     * @param \WP_Post $post
     * @return void
     */
    protected function do_post_webhook( $action, $post )
    {
        if ( ! in_array( $post->post_type, $this->post_types ) )
        {
            return;
        }

        $data = [
            'action' => $action,
            'placeholder' => '',
            'placeholder_id' => $post->ID,
            'data' => []
        ];

        switch( $post->post_type )
        {
            case 'axi_course':
                $data['placeholder'] = 'Course';
                $data['data'] = $this->get_course_data_for_webhook( $post );
                break;

            case 'axi_feedback':
                $data['placeholder'] = 'Feedback';
                $data['data'] = $this->get_feedback_data_for_webhook( $post );
                break;

            case 'axi_instructor':
                $data['placeholder'] = 'Instructor';
                $data['data'] = $this->get_instructor_data_for_webhook( $post );
                break;

            case 'axi_promotion':
                $data['placeholder'] = 'Promotion';
                $data['data'] = $this->get_promotion_data_for_webhook( $post );
                break;

            default:
                break;
        }

        $url = untrailingslashit( $this->get_api_url() ) . '/' . $this->get_webhook_path();
        $curl_response = $this->push_api_response( $url, $data );
    }

    /**
     * Fetch course data and format it to match with course placeholder
     *
     * @param \WP_Post $post
     * @return array
     */
    protected function get_course_data_for_webhook( $post )
    {
        $data = [
            'name'             => get_the_title( $post ),
            'course_guide'     => '',
            'discipline_id'    => '',
            'course_type_id'   => '',
            'delivery_mode_id' => '',
            'location_id'      => '',
            'course_logo'      => '',
            'course_cost'      => ''
        ];

        $course_guide_id = absint( get_post_meta( $post->ID, '_course_guide', true ) );
        $course_guide = wp_get_attachment_url( $course_guide_id );

        if ( $course_guide )
        {
            $data['course_guide'] = esc_url_raw( $course_guide );
        }

        $disciplines = wp_get_post_terms( $post->ID, 'axi_discipline' );
        if ( ! empty( $disciplines ) && ! is_wp_error( $disciplines ) )
        {
            $data['discipline_id'] = $disciplines[0]->term_id;
        }

        $course_types = wp_get_post_terms( $post->ID, 'axi_course_type' );
        if ( ! empty( $course_types ) && ! is_wp_error( $course_types ) )
        {
            $data['course_type_id'] = $course_types[0]->term_id;
        }

        $delivery_modes = wp_get_post_terms( $post->ID, 'axi_delivery_mode' );
        if ( ! empty( $delivery_modes ) && ! is_wp_error( $delivery_modes ) )
        {
            $data['delivery_mode_id'] = $delivery_modes[0]->term_id;
        }

        $locations = wp_get_post_terms( $post->ID, 'axi_location' );
        if ( ! empty( $locations ) && ! is_wp_error( $locations ) )
        {
            $data['location_id'] = $locations[0]->term_id;
        }

        $course_logo_id = absint( get_post_meta( $post->ID, '_logo', true ) );
        $course_logo = wp_get_attachment_url( $course_logo_id );
        if ( $course_logo )
        {
            $data['course_logo'] = esc_url_raw( $course_logo );
        }

        $course_cost = floatval( get_post_meta( $post->ID, '_cost', true ) );
        $data['course_cost'] = round( $course_cost, 2 );

        return $data;
    }

    /**
     * Fetch feedback data and format it to match with feedback placeholder
     *
     * @param \WP_Post $post
     * @return array
     */
    protected function get_feedback_data_for_webhook( $post )
    {
        $data = [
            'name'            => get_the_title( $post ),
            'type'            => '',
            'label'           => '',
            'designation'     => '',
            'discipline_ids'  => '',
            'organisation_id' => '',
            'feedback'        => '',
            'highlight'       => '',
            'rating'          => ''
        ];
        
        $type = get_post_meta( $post->ID, '_type', true );
        if ( $type )
        {
            $data['type'] = $type;
        }

        $label = get_post_meta( $post->ID, '_label', true );
        if ( $label )
        {
            $data['label'] = $label;
        }

        $designation = get_post_meta( $post->ID, '_designation', true );
        if ( $designation )
        {
            $data['designation'] = $designation;
        }

        $disciplines = wp_get_post_terms( $post->ID, 'axi_discipline', [ 'fields' => 'ids' ] );
        if ( ! empty( $disciplines ) && ! is_wp_error( $disciplines ) )
        {
            $data['discipline_ids'] = implode( ',', $disciplines );
        }

        $organisations = wp_get_post_terms( $post->ID, 'axi_organisation', [ 'fields' => 'ids' ] );
        if ( ! empty( $organisations ) && ! is_wp_error( $organisations ) )
        {
            $data['organisation_id'] = $organisations[0];
        }

        $feedback = get_the_content( null, false, $post );
        $feedback = strip_shortcodes( $feedback );
        $feedback = excerpt_remove_blocks( $feedback );
        $feedback = apply_filters( 'the_content', $feedback );
        $feedback = str_replace( ']]>', ']]&gt;', $feedback );
        $data['feedback'] = $feedback;
        
        $highlight = get_post_meta( $post->ID, '_highlight', true );
        if ( $highlight )
        {
            $data['highlight'] = $highlight;
        }

        $rating = floatval( get_post_meta( $post->ID, '_rating', true ) );
        $data['rating'] = $rating;

        return $data;
    }

    /**
     * Fetch instructor data and format it to match with instructor placeholder
     *
     * @param \WP_Post $post
     * @return array
     */
    protected function get_instructor_data_for_webhook( $post )
    {
        $data = [
            'instructor_email'   => '',
            'instructor_name'    => '',
            'instructor_summary' => '',
            'instructor_image'   => '',
            'instructor_page'    => ''
        ];

        $email = get_post_meta( $post->ID, '_email', true );
        if ( $email && is_email( $email ) )
        {
            $data['instructor_email'] = $email;
        }

        $data['instructor_name'] = get_the_title( $post );
        
        $summary = get_the_content( null, false, $post );
        $summary = strip_shortcodes( $summary );
        $summary = excerpt_remove_blocks( $summary );
        $summary = apply_filters( 'the_content', $summary );
        $summary = str_replace( ']]>', ']]&gt;', $summary );
        $data['instructor_summary'] = $summary;

        $image = get_the_post_thumbnail_url( $post, 'full' );
        if ( $image )
        {
            $data['instructor_image'] = $image;
        }

        $page_link = get_post_meta( $post->ID, '_page_link', true );
        if ( $page_link )
        {
            $data['instructor_page'] = $page_link;
        }

        return $data;
    }

    /**
     * Fetch promotion data and format it to match with promotion placeholder
     *
     * @param \WP_Post $post
     * @return array
     */
    protected function get_promotion_data_for_webhook( $post )
    {
        $data = [
            'promotion_type'    => '',
            'promotion_message' => '',
            'promotion_percent' => '',
            'promotion_amount'  => '',
            'promotion_enabled' => '',
            'course_id'         => '',
            'discipline_id'     => '',
            'location_id'       => '',
            'delivery_mode_id'  => '',
        ];

        $promotion_type = get_post_meta( $post->ID, '_promotion_type', true );
        if ( $promotion_type )
        {
            $data['promotion_type'] = $promotion_type;
        }

        $promotion_message = get_the_content( null, false, $post );
        $promotion_message = strip_shortcodes( $promotion_message );
		$promotion_message = excerpt_remove_blocks( $promotion_message );
        $promotion_message = apply_filters( 'the_content', $promotion_message );
        $promotion_message = str_replace( ']]>', ']]&gt;', $promotion_message );
        $data['promotion_message'] = $promotion_message;

        $promotion_amount_type = get_post_meta( $post->ID, '_promotion_amount_type', true );
        if ( 'percent' == $promotion_amount_type )
        {
            $data['promotion_percent'] = absint( get_post_meta( $post->ID, '_promotion_percent', true ) );
        }
        else
        {
            $data['promotion_amount'] = absint( get_post_meta( $post->ID, '_promotion_amount', true ) );
        }

        $promotion_enabled = get_post_meta( $post->ID, '_promotion_status', true );
        $data['promotion_enabled'] = 'enabled' === $promotion_enabled;

        $course_id = get_post_meta( $post->ID, '_course_id', true );
        if ( is_array( $course_id ) && ! empty( $course_id ) )
        {
            $data['course_id'] = implode( ',', $course_id );
        }

        $disciplines = wp_get_post_terms( $post->ID, 'axi_discipline', [ 'fields' => 'ids' ] );
        if ( ! empty( $disciplines ) && ! is_wp_error( $disciplines ) )
        {
            $data['discipline_id'] = implode( ',', $disciplines );
        }

        $delivery_modes = wp_get_post_terms( $post->ID, 'axi_delivery_mode', [ 'fields' => 'ids' ] );
        if ( ! empty( $delivery_modes ) && ! is_wp_error( $delivery_modes ) )
        {
            $data['delivery_mode_id'] = $delivery_modes[0];
        }

        $locations = wp_get_post_terms( $post->ID, 'axi_location', [ 'fields' => 'ids' ] );
        if ( ! empty( $locations ) && ! is_wp_error( $locations ) )
        {
            $data['location_id'] = implode( ',', $locations );
        }

        return $data;
    }

    /**
     * Send webhook API after a new term is created, and after the term cache has been cleaned.
     *
     * @param int    $term_id  Term ID.
     * @param int    $tt_id    Term taxonomy ID.
     * @param string $taxonomy Taxonomy slug.
     */
    function add_term_webhook( $term_id, $tt_id, $taxonomy )
    {
        $this->do_term_webhook( 'ADD', $term_id, $taxonomy );
    }

    /**
     * Send webhook API after a term has been updated, and the term cache has been cleaned.
     *
     * @param int    $term_id  Term ID.
     * @param int    $tt_id    Term taxonomy ID.
     * @param string $taxonomy Taxonomy slug.
     */
    function update_term_webhook( $term_id, $tt_id, $taxonomy )
    {
        $this->do_term_webhook( 'UPDATE', $term_id, $taxonomy );
    }

    /**
     * Send webhook API when deleting a term, before any modifications are made to posts or terms.
     *
     * @param int    $term     Term ID.
     * @param string $taxonomy Taxonomy Name.
     */
    function delete_term_webhook( $term, $taxonomy )
    {
        $this->do_term_webhook( 'DELETE', $term, $taxonomy );
    }

    /**
     * Perform webhook request for terms
     *
     * @param string $action 'ADD', 'UPDATE' or 'DELETE'
     * @param int $term_id
     * @param string $taxonomy
     * @return void
     */
    protected function do_term_webhook( $action, $term_id, $taxonomy )
    {
        if ( ! in_array( $taxonomy, $this->taxonomies ) )
        {
            return $term_id;
        }

        $data = [
            'action' => $action,
            'placeholder' => '',
            'placeholder_id' => $term_id,
            'data' => []
        ];

        switch( $taxonomy )
        {
            case 'axi_course_type':
                $data['placeholder'] = 'Course_Type';
                $data['data'] = $this->get_course_type_data_for_webhook( $term_id );
                break;

            case 'axi_organisation':
                $data['placeholder'] = 'Organisation';
                $data['data'] = $this->get_organisation_data_for_webhook( $term_id );
                break;

            case 'axi_discipline':
                $data['placeholder'] = 'Discipline';
                $data['data'] = $this->get_discipline_data_for_webhook( $term_id );
                break;

            case 'axi_discipline_guide':
                $data['placeholder'] = 'Discipline_Guide';
                $data['data'] = $this->get_discipline_guide_data_for_webhook( $term_id );
                break;

            case 'axi_discipline_link':
                $data['placeholder'] = 'Discipline_Link';
                $data['data'] = $this->get_discipline_link_data_for_webhook( $term_id );
                break;

            case 'axi_delivery_mode':
                $data['placeholder'] = 'Delivery_Mode';
                $data['data'] = $this->get_delivery_mode_data_for_webhook( $term_id );
                break;

            case 'axi_location':
                $data['placeholder'] = 'Location';
                $data['data'] = $this->get_location_data_for_webhook( $term_id );
                break;

            case 'axi_tag':
                $data['placeholder'] = 'Tag';
                $data['data'] = $this->get_tag_data_for_webhook( $term_id );
                break;

            case 'axi_discount_code':
                $data['placeholder'] = 'Discount_Code';
                $data['data'] = $this->get_discount_code_data_for_webhook( $term_id );
                break;

            default:
                break;
        }

        $url = untrailingslashit( $this->get_api_url() ) . '/' . $this->get_webhook_path();
        $curl_response = $this->push_api_response( $url, $data );
    }

    /**
     * Fetch course type data and format it to match with course type placeholder
     * 
     * @param int $term_id
     * @return array
     */
    function get_course_type_data_for_webhook( $term_id )
    {
        $data = [
            'course_type' => ''
        ];

        $course_type = get_term( $term_id, 'axi_course_type' );
        if ( ! $course_type || is_wp_error( $course_type ) )
        {
            return $data;
        }

        $data['course_type'] = $course_type->name;
        return $data;
    }

    /**
     * Fetch organisation data and format it to match with organisation placeholder
     * 
     * @param int $term_id
     * @return array
     */
    function get_organisation_data_for_webhook( $term_id )
    {
        $data = [
            'name' => '',
            'logo' => ''
        ];

        $term = get_term( $term_id, 'axi_organisation' );
        if ( ! $term || is_wp_error( $term ) )
        {
            return $data;
        }

        $data['name'] = $term->name;

        $logo_id = get_term_meta( $term_id, '_image', true );
        $logo = wp_get_attachment_url( $logo_id );
        if ( $logo )
        {
            $data['logo'] = $logo;
        }

        return $data;
    }

    /**
     * Fetch discipline data and format it to match with discipline placeholder
     * 
     * @param int $term_id
     * @return array
     */
    function get_discipline_data_for_webhook( $term_id )
    {
        $data = [
            'discipline_name' => '',
            'discipline_code' => ''
        ];

        $term = get_term( $term_id, 'axi_discipline' );
        if ( ! $term || is_wp_error( $term ) )
        {
            return $data;
        }

        $data['discipline_name'] = $term->name;
        $data['discipline_code'] = $term->slug;
        
        return $data;
    }

    /**
     * Fetch discipline guide data and format it to match with discipline guide placeholder
     * 
     * @param int $term_id
     * @return array
     */
    function get_discipline_guide_data_for_webhook( $term_id )
    {
        $data = [
            'discipline_id'    => '',
            'delivery_mode_id' => '',
            'location_id'      => ''
        ];

        $term = get_term( $term_id, 'axi_discipline_guide' );
        if ( ! $term || is_wp_error( $term ) )
        {
            return $data;
        }
        
        $discipline = get_term_meta( $term_id, '_discipline', true );
        if ( $discipline )
        {
            $data['discipline_id'] = $discipline;
        }

        $location = get_term_meta( $term_id, '_location', true );
        if ( $location )
        {
            $data['location_id'] = $location;
        }

        $delivery_mode = get_term_meta( $term_id, '_delivery_mode', true );
        if ( $delivery_mode )
        {
            $data['delivery_mode_id'] = $delivery_mode;
        }

        $discipline_guide_id = get_term_meta( $term_id, '_discipline_guide', true );
        $discipline_guide = wp_get_attachment_url( $discipline_guide_id );
        if ( $discipline_guide )
        {
            $data['discipline_guide'] = $discipline_guide;
        }

        return $data;
    }

    /**
     * Fetch discipline link data and format it to match with discipline link placeholder
     * 
     * @param int $term_id
     * @return array
     */
    function get_discipline_link_data_for_webhook( $term_id )
    {
        $data = [
            'discipline_id'        => '',
            'delivery_mode_id'     => '',
            'location_id'          => '',
            'discipline_logo'      => '',
            'discipline_text'       => '',
            'discipline_page_link' => ''
        ];

        $term = get_term( $term_id, 'axi_discipline_link' );
        if ( ! $term || is_wp_error( $term ) )
        {
            return $data;
        }

        $discipline_id = absint( get_term_meta( $term_id, '_discipline', true ) );
        if ( $discipline_id )
        {
            $data['discipline_id'] = $discipline_id;
        }

        $delivery_mode_id = absint( get_term_meta( $term_id, '_delivery_mode', true ) );
        if ( $delivery_mode_id )
        {
            $data['delivery_mode_id'] = $delivery_mode_id;
        }

        $location_id = absint( get_term_meta( $term_id, '_location', true ) );
        if ( $location_id )
        {
            $data['location_id'] = $location_id;
        }

        $logo_id = absint( get_term_meta( $term_id, '_discipline_logo', true ) );
        $logo = wp_get_attachment_url( $logo_id );
        if ( $logo )
        {
            $data['discipline_logo'] = $logo;
        }

        $data['discipline_text'] = $term->description;
        
        $page_id = absint( get_term_meta( $term_id, '_page_id', true ) );
        if ( $page_id )
        {
            $data['discipline_page_link'] = esc_url( get_permalink( $page_id ) );
        }

        return $data;
    }

    /**
     * Fetch delivery mode data and format it to match with delivery mode placeholder
     * 
     * @param int $term_id
     * @return array
     */
    function get_delivery_mode_data_for_webhook( $term_id )
    {
        $data = [
            'delivery_mode' => ''
        ];

        $term = get_term( $term_id, 'axi_delivery_mode' );
        if ( ! $term || is_wp_error( $term ) )
        {
            return $data;
        }

        $data['delivery_mode'] = $term->name;
        return $data;
    }

    /**
     * Fetch location data and format it to match with location placeholder
     * 
     * @param int $term_id
     * @return array
     */
    function get_location_data_for_webhook( $term_id )
    {
        $data = [
            'country_name' => '',
            'city_name'    => '',
            'address'      => '',
            'show'         => ''
        ];

        $term = get_term( $term_id, 'axi_location' );
        if ( ! $term || is_wp_error( $term ) )
        {
            return $data;
        }

        if ( function_exists( 'get_field' ) )
        {
            $country = get_field( '_country', 'axi_location_' . $term_id );
            if ( isset( $country['label'] ) )
            {
                $data['country_name'] = esc_html( $country['label'] );
            }
            else
            {
                $data['country_name'] = esc_html( $country );
            }
        }
        else
        {
            $country = get_term_meta( $term_id, '_country', true );
            if ( $country )
            {
                $data['country_name'] = esc_html( $country );
            }
        }

        $city = get_term_meta( $term_id, '_city', true );
        if ( $city )
        {
            $data['city_name'] = esc_html( $city );
        }

        $address = get_term_meta( $term_id, '_address', true );
        if ( $address )
        {
            $data['address'] = esc_html( $address );
        }

        $show = get_term_meta( $term_id, '_show', true );
        if ( $show )
        {
            $data['show'] = true;
        }
        else
        {
            $data['show'] = false;
        }

        return $data;
    }

    /**
     * Fetch tag data and format it to match with tag placeholder
     * 
     * @param int $term_id
     * @return array
     */
    function get_tag_data_for_webhook( $term_id )
    {
        $data = [
            'tag_name' => '',
            'discipline_link_id' => ''
        ];

        $term = get_term( $term_id, 'axi_tag' );
        if ( ! $term || is_wp_error( $term ) )
        {
            return $data;
        }

        $data['tag_name'] = esc_html( $term->name );

        $discipline_links = maybe_unserialize( get_term_meta( $term_id, '_discipline_links', true ) );
        if ( $discipline_links )
        {
            $data['discipline_link_id'] = implode( ',', $discipline_links );
        }

        return $data;
    }

    /**
     * Fetch discount code data and format it to match with discount code placeholder
     * 
     * @param int $term_id
     * @return array
     */
    function get_discount_code_data_for_webhook( $term_id )
    {
        $data = [
            'discount_code'   => '',
            'discount_type'   => '',
            'discount_amount' => '',
            'discount_expiry' => '',
            'discount_limit'  => '',
            'course_id'       => ''
        ];

        $term = get_term( $term_id, 'axi_discount_code' );
        if ( ! $term || is_wp_error( $term ) )
        {
            return $data;
        }

        $discount_code = get_term_meta( $term_id, '_discount_code', true );
        if ( $discount_code )
        {
            $data['discount_code'] = '';
        }

        $discount_type = get_term_meta( $term_id, '_discount_type', true );
        if ( $discount_type )
        {
            $data['discount_type'] = $discount_type;
        }

        if ( 'percent' == $discount_type )
        {
            $discount_percent = absint( get_term_meta( $term_id, '_discount_percent', true ) );
            if ( $discount_percent )
            {
                $data['discount_amount'] = $discount_percent;
            }
        }
        elseif ( 'flat' == $discount_type )
        {
            $discount_amount  = absint( get_term_meta( $term_id, '_discount_amount', true ) );
            if ( $discount_amount )
            {
                $data['discount_amount'] = $discount_amount;
            }
        }
        
        $discount_expiry = get_term_meta( $term_id, '_discount_expiry', true );
        if ( $discount_expiry )
        {
            $data['discount_expiry'] = $discount_expiry;
        }

        $discount_limit = absint( get_term_meta( $term_id, '_discount_limit', true ) );
        if ( $discount_limit )
        {
            $data['discount_limit'] = $discount_limit;
        }

        $course_id = maybe_unserialize( get_term_meta( $term_id, '_course_id', true ) );
        if ( $course_id )
        {
            $data['course_id'] = $course_id;
        }

        return $data;
    }

    /**
     * Get course intakes from API
     *
     * @param array $input_params {
     *     Array of required data used for url request build.
     * 
     *     @type string $action
     *     @type string $id
     * }
     * 
     * @return array|false Array contains course attributes. False otherwise
     */
    function get_course_atts( $input_params )
    {
        $api_url = esc_url_raw( untrailingslashit( $this->get_api_url() ) . '/' . $this->get_course_atts_path() );
        $params = [
            'action' => isset( $input_params['action'] ) ? $input_params['action'] : 'intakes',
            'id' => isset( $input_params['id'] ) ? $input_params['id'] : ''
        ];

        $curl_result = $this->pull_api_response( $api_url, $params );

        $result = [];
        $response_data = null;

        if ( ! empty( $curl_result ) )
        {
            $response_data = json_decode( trim( utf8_encode( $curl_result ) ), true );
        }
        if ( ! empty( $response_data ) && is_array( $response_data ) )
        {
            foreach ( $response_data as $rdata )
            {
                if ( empty( $rdata['intakeId'] ) || ! isset( $rdata['start'] ) || ! isset( $rdata['end'] ) )
                {
                    continue;
                }
                $result_item = [
                    'intakeId' => $rdata['intakeId'],
                    'start'    => $rdata['start'],
                    'end'      => $rdata['end']
                ];

                $result[] = $result_item;
            }
	}

        return $response_data;
    }
        
    /**
     * Get star rating from API
     *
     * @param array $input_params {
     *     Array of required data used for url request build.
     * 
     *     @type string $discipline Discipline names, separated by ","
     *     @type string $location Location names, separated by ","
     * }
     * 
     * @return array|false Array contains rating and total. False otherwise
     */
    function get_star_ratings( $input_params = array() )
    {
        if ( ! $this->dependencies_enabled() )
        {
            return false;
        }

        $api_url = esc_url_raw( untrailingslashit( $this->get_api_url() ) . '/' . $this->get_rating_path() );
        $params = [
            'discipline' => isset( $input_params['discipline'] ) ? $input_params['discipline'] : '',
            'location'   => isset( $input_params['location'] ) ? $input_params['location'] : '',
        ];

        $curl_result = $this->pull_api_response( $api_url, $params );

        $result = [
            'rating' => 0,
            'total'  => 0
        ];
        $response_data = null;

        if ( ! empty( $curl_result ) )
        {
            $response_data = json_decode( trim( utf8_encode( $curl_result ) ), true );
        }
        if ( ! empty( $response_data ) )
        {
            if ( ! empty( $response_data['data']['data'] ) )
            {
                $result['rating'] = floatval( $response_data['data']['data'] );
            }
            if ( ! empty( $response_data['data']['total'] ) )
            {
                $result['total'] = intval( $response_data['data']['total'] );
            }
        }
        return $result;
    }

    /**
     * GET request to api
     *
     * @param string $url
     * @param array $params
     * @return string|bool
     */
    function pull_api_response( $url, $params )
    {
        if ( empty( $params['username'] ) )
        {
            $params['username'] = $this->get_api_user();
        }
        $headers = [
            'Accept: application/json; charset=utf-8',
            'Content-Type: application/json',
            'API-KEY: ' . base64_encode( hash_hmac( 'sha256', json_encode( $params ), $this->get_api_secret(), true ) )
        ];

        $url = $this->build_query_url( $url, $params );

        $ch = curl_init();
        curl_setopt_array( $ch, [
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_URL            => $url,
            CURLOPT_HEADER         => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => 'curl'
        ] );
        $curl_result = curl_exec( $ch );
        curl_close( $ch );

        return $curl_result;
    }

    /**
     * POST request to api
     *
     * @param string $url
     * @param array  $params
     * @return string|bool curl_exec result
     */
    function push_api_response( $url, $params )
    {
        $api_webhook_secret = axisys_get_opt( 'api_webhook_secret' );
        $headers = [
            'Accept: application/json; charset=utf-8',
            'Content-Type: application/json; charset=utf-8',
            'API-KEY: ' . trim( esc_html( $api_webhook_secret ) ) // 5UN8y69Gmbf4FzP2aTxPVFK8'
        ];

        $ch = curl_init();
        curl_setopt_array( $ch, [
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode( $params ),
            CURLOPT_HEADER         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => 'curl'
        ] );

        $curl_result = curl_exec( $ch );
        curl_close( $ch );

        return $curl_result;
    }

    /**
     * Build query url based on params
     *
     * @param string $input_url
     * @param string|array $params
     * @return string
     */
    protected function build_query_url( $input_url, $params )
    {
        $input_url = untrailingslashit( $input_url );
        $lastc     = substr( $input_url, -1, 1 );
        $url       = '';
        if ( false !== strpos( $input_url, '&' ) )
        {
            if ( $lastc != '&' )
            {
                $url = $input_url . '&';
            }
            else
            {
                $url = $input_url;
            }
        }
        elseif ( false !== strpos( $input_url, '?' ) )
        {
            if ( $lastc != '?' )
            {
                $url = $input_url . '?';
            }
            else
            {
                $url = $input_url;
            }
        }
        else
        {
            $url = $input_url . '?';
        }

        if ( is_array( $params ) )
        {
            $query = http_build_query( $params, '', '&', PHP_QUERY_RFC3986 );
        }
        else
        {
            $query = $params;
        }

        return $url . $query;
    }

    /**
     * Check if we can do curl request
     *
     * @return boolean
     */
    protected function dependencies_enabled()
    {
        return function_exists( 'curl_init' )
            && function_exists( 'curl_setopt' )
            && function_exists( 'curl_exec' )
            && function_exists( 'curl_close' )
            && function_exists( 'hash_hmac' );
    }

    /**
     * Get API user from options.
     *
     * @return string
     */
    protected function get_api_user()
    {
        if ( $this->api_usr )
        {
            return $this->api_usr;
        }
        $this->api_usr = axisys_get_opt( 'api_usr' );
        return $this->api_usr;
    }

    /**
     * Get API url from Options.
     *
     * @return string
     */
    protected function get_api_url()
    {
        if ( $this->api_url )
        {
            return $this->api_url;
        }
        $this->api_url = axisys_get_opt( 'api_base_url' );
	return $this->api_url;
    }

    /**
     * Get API secret from options.
     *
     * @return string
     */
    protected function get_api_secret()
    {
        if ( $this->api_secret )
        {
            return $this->api_secret;
        }
        $this->api_secret = axisys_get_opt( 'api_secret' );
        return $this->api_secret;
    }

    /**
     * Get rating path from Options
     *
     * @return string
     */
    protected function get_rating_path()
    {
        if ( $this->rating_path )
        {
            return $this->rating_path;
        }
        $this->rating_path = axisys_get_opt( 'api_star_ratings_path' );
        return $this->rating_path;
    }

    /**
     * Get course attributes path from Options
     *
     * @return string
     */
    protected function get_course_atts_path()
    {
        if ( $this->course_atts_path )
        {
            return $this->course_atts_path;
        }
        $this->course_atts_path = axisys_get_opt( 'api_course_atts_path' );
        return $this->course_atts_path;
    }
    
    /**
     * Get webhook path from Options
     *
     * @return string
     */
    protected function get_webhook_path()
    {
        if ( $this->webhook_path )
        {
            return $this->webhook_path;
        }
        $this->webhook_path = axisys_get_opt( 'api_webhook_path' );
        return $this->webhook_path;
    }

    /**
     * Get webhook secret from Options
     *
     * @return string
     */
    protected function get_webhook_secret()
    {
        if ( $this->webhook_secret )
        {
            return $this->webhook_secret;
        }
        $this->webhook_secret = axisys_get_opt( 'api_webhook_secret' );
        return $this->webhook_secret;
    }
}
