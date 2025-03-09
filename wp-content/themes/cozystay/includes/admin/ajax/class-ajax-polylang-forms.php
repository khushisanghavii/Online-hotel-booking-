<?php
if ( ! class_exists( 'CozyStay_Ajax_Polylang_Forms' ) ) {
    class CozyStay_Ajax_Polylang_Forms {
        /**
        * Construct function
        */
        public function __construct() {
            add_action( 'wp_ajax_nopriv_cozystay-polylang-update-form', array( $this, 'update_forms' ) );
            add_action( 'wp_ajax_cozystay-polylang-update-form', array( $this, 'update_forms' ) );

            add_action( 'wp_ajax_nopriv_cozystay-polylang-duplicate-form', array( $this, 'duplicate_form' ) );
            add_action( 'wp_ajax_cozystay-polylang-duplicate-form', array( $this, 'duplicate_form' ) );
        }
        /**
        * Action handler function to update forms
        */
        public function update_forms() {
            $data = array( 'status' => 'failed', 'message' => esc_html__( 'Request failed. Please try again later.', 'cozystay' ), 'editURL' => '' );
            if ( ( ! empty( $_REQUEST['language'] ) ) || isset( $_REQUEST['formID'] ) ) {
                $form_id = absint( wp_unslash( $_REQUEST['formID'] ) );
                $language = sanitize_text_field( wp_unslash( $_REQUEST['language'] ) );
                $this->update_settings( $language, $form_id );
                $data = array( 'status' => 'done', 'message' => esc_html__( 'Updated.', 'cozystay' ) );

                if ( ! empty( $form_id ) ) {
                    $data['editURL'] = admin_url( 'admin.php?page=mailchimp-for-wp-forms&view=edit-form&form_id=' . $form_id );
                }
            }
            wp_send_json_success( $data );
        }
        /**
        * Action handler function to duplicate form
        */
        public function duplicate_form() {
            $data = array( 'status' => 'failed', 'url' => '' );
            if ( ( ! empty( $_REQUEST['language'] ) ) || isset( $_REQUEST['formID'] ) ) {
            	$form_id = absint( wp_unslash( $_REQUEST['formID'] ) );
            	if ( cozystay_does_mc4wp_form_exist( $form_id ) ) {
            		$language = sanitize_text_field( wp_unslash( $_REQUEST['language'] ) );
					$form = get_post( $form_id, ARRAY_A );
					$metas = get_post_meta( $form_id );
                    $new_form_data = array_intersect_key( $form, array(
                        'post_content' => '',
                        'post_title' => '',
                        'post_status' => '',
                        'post_type' => ''
                    ) );
                    $title_prefix = empty( $_REQUEST['name'] ) ? $language : sanitize_text_field( wp_unslash( $_REQUEST['name'] ) );
                    $new_form_data['post_title'] = '[' . $title_prefix . ']' . $new_form_data['post_title'];
                    $new_form_id = wp_insert_post( $new_form_data );
                    foreach( $metas as $name => $value ) {
                        update_post_meta( $new_form_id, $name, maybe_unserialize( $value[0] ) );
                    }
                    $this->update_settings( $language, $new_form_id );
                    $data = array( 'status' => 'done', 'url' => admin_url( 'admin.php?page=mailchimp-for-wp-forms&view=edit-form&form_id=' . $new_form_id ) );
                }
            }
            wp_send_json_success( $data );
        }
        /**
		* Update settings
        */
        protected function update_settings( $language, $form_id ) {
        	$current_settings = (array) get_option( 'cozystay_polylang_mc4wp_settings', array() );
            $current_settings[ $language ] = absint( wp_unslash( $form_id ) );
            update_option( 'cozystay_polylang_mc4wp_settings', $current_settings );
        }
    }
    new CozyStay_Ajax_Polylang_Forms();
}
