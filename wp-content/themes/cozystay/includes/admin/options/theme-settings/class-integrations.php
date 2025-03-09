<?php
if ( ! class_exists( 'CozyStay_Theme_Settings_Panel_Integrations' ) && cozystay_is_mc4wp_activated() && cozystay_is_theme_core_activated() && cozystay_is_polylang_activated() ) {
    class CozyStay_Theme_Settings_Panel_Integrations extends CozyStay_Theme_Option_Section {
        /**
        * Default language
        */
        protected $default_language = false;
        /**
        * To store current language settings
        */
        protected $languages = false;
        /**
        * To store the mc4wp forms
        */
        protected $forms = false;
        /**
        * Setup environment
        */
        protected function setup_env() {
            $this->title = esc_html__( 'Integrations', 'cozystay' );
            $this->id = 'tab-integrations';

            $this->languages = $this->set_languages();
            $this->default_language = pll_default_language( 'slug' );

            $this->forms = cozystay_mc4wp_forms();

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        }
        /**
        * Render section content
        */
        public function render_tab_content() { ?>
            <h2><?php esc_html_e( 'MC4WP Multilingual Manager for Polylang', 'cozystay' ); ?></h2>
            <div class="cs-mc4wp-wrapper"><?php
            if ( empty( $this->languages ) || is_wp_error( $this->languages ) ) : ?>
                <h3><?php esc_html_e( 'Setup your polylang first.', 'cozystay' ); ?></h3><?php
            elseif ( ( ! is_array( $this->forms ) ) || ( count( $this->forms ) < 2 ) ) : ?>
                <h3>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=mailchimp-for-wp-forms&view=add-form' ) ); ?>"><?php esc_html_e( 'Create your form first.', 'cozystay' ); ?></a>
                <h3><?php
            else :
                $current_settings = (array) get_option( 'cozystay_polylang_mc4wp_settings', array() ); ?>
                <div class="default-language-form">
                    <?php esc_html_e( 'Set form for default langauage', 'cozystay' ); ?>(<?php echo esc_html( $this->languages[ $this->default_language ]['name'] ); ?>):
                    <?php $this->the_forms( 'default', ( isset( $current_settings[ 'default' ] ) ? $current_settings['default'] : '' ) ); ?>
                </div>
                <div class="other-language-forms">
                    <table><?php
                    unset( $this->languages[ $this->default_language ] );
                    foreach ( $this->languages as $lang ) : ?>
                        <tr>
                            <td><img width=16 height=11 src="<?php echo esc_url( $lang['flag'] ); ?>" alt="<?php echo esc_attr( $lang['name'] ); ?>" /> <?php echo esc_html( $lang['name'] ); ?></td>
                            <td><?php esc_html_e( 'Choose a form: ', 'cozystay' ); $this->the_forms( $lang['slug'], ( isset( $current_settings[ $lang['slug'] ] ) ? $current_settings[ $lang['slug'] ] : '' ) ); ?></td>
                            <td><?php
                                $slug = $lang['slug'];
                                $name = $lang['name'];
                                $form_id_set = ! empty( $current_settings[ $slug ] );
                                $url = $form_id_set
                                    ? admin_url( 'admin.php?page=mailchimp-for-wp-forms&view=edit-form&form_id=' . $current_settings[ $lang['slug'] ] )
                                        : admin_url( 'admin.php?page=mailchimp-for-wp-forms&view=edit-form' ); ?>

                                <a href="#" class="duplicate-form"<?php if ( $form_id_set ) : ?> style="display: none;"<?php endif; ?>" data-lang="<?php echo esc_attr( $slug ); ?>" data-name="<?php echo esc_attr( $name ); ?>">
                                    <?php esc_html_e( 'Or translate the form', 'cozystay' ); ?>
                                </a>
                                <a href="<?php echo esc_url( $url ); ?>" class="edit-form"<?php if ( ! $form_id_set ) : ?> style="display: none;"<?php endif; ?>">
                                    <?php esc_html_e( 'Edit the form', 'cozystay' ); ?>
                                </a>
                            </td>
                        </tr><?php
                    endforeach; ?>
                    </table>
                </div><?php
            endif; ?>
            </div><?php
        }
        /**
        * Set languages currently set
        */
        protected function set_languages() {
            $list = array();
            $args = array();
            $model = new PLL_Model( $args );
            $languages = $model->get_languages_list();
            foreach( $languages as $lang ) {
                $list[ $lang->slug ] = array(
                    'slug' => $lang->slug,
                    'name' => $lang->name,
                    'flag' => $lang->flag_url
                );
            }
            return $list;
        }
        /**
        * Output the forms
        */
        protected function the_forms( $slug, $value ) { ?>
            <select name="mc4wp-form-<?php echo esc_attr( $slug ); ?>" data-lang="<?php echo esc_attr( $slug ); ?>"><?php
            foreach ( $this->forms as $form_id => $title ) : ?>
                <option value="<?php echo esc_attr( $form_id ); ?>" <?php selected( $value, $form_id ) ?>><?php echo esc_html( $title ); ?></option><?php
            endforeach; ?>
            </select><?php
        }
        /**
        * Enqueue assets
        */
        public function enqueue_assets() {
            $suffix = cozystay_get_assets_suffix();
            wp_enqueue_script( 'cozystay-mc4wp-translation', COZYSTAY_ASSETS_URI . 'scripts/admin/mc4wp-translation' . $suffix . '.js', array( 'jquery' ), COZYSTAY_ASSETS_VERSION, true );
            wp_localize_script( 'cozystay-mc4wp-translation', 'cozystayMC4WPTranslation', array(
                'url' => admin_url( 'admin-ajax.php' ),
                'actionUpdate' => 'cozystay-polylang-update-form',
                'actionDuplicate' => 'cozystay-polylang-duplicate-form'
            ) );
        }
    }
    new CozyStay_Theme_Settings_Panel_Integrations();
}
