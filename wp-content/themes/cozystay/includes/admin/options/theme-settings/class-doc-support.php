<?php
if ( ! class_exists( 'CozyStay_Theme_Settings_Tab_Doc_Support' ) ) {
	class CozyStay_Theme_Settings_Tab_Doc_Support extends CozyStay_Theme_Option_Section {
		/**
		* Setup environment
		*/
		protected function setup_env() {
			$this->title = esc_html__( 'Docs & Support', 'cozystay' );
			$this->id = 'tab-support';
		}
		/**
		* Render tab content
		*/
		public function render_tab_content() { ?>
            <h2><?php esc_html_e( 'Theme Support', 'cozystay' ); ?></h2>
            <p><?php printf(
				/* translators: %1$s: html tag start. %2$s: html tag end */
				esc_html__( 'CozyStay theme comes with 6 months of free support for every license you purchase. Support can be extended through subscription via ThemeForest (%1$smore information on support extension%2$s).', 'cozystay' ),
			 	sprintf( '<a href="%s" target="_blank">', 'https://help.market.envato.com/hc/en-us/articles/207886473-Extending-and-Renewing-Item-Support' ),
				'</a>'
			); ?></p>
            <div class="support-channels">
                <div class="support-channel">
                    <h3><?php esc_html_e( 'Documentation', 'cozystay' ); ?></h3>
                    <p><?php esc_html_e( 'If you have any problems or questions while using this theme, please check our online documentation first. Your question may have been answered in the documentation.', 'cozystay' ); ?></p>
                    <a href="https://loftocean.com/doc/cozystay/" target="_blank" class="button"><?php esc_html_e( 'Documentation', 'cozystay' ); ?></a>
                </div>
                <div class="support-channel">
                    <h3><?php esc_html_e( 'F.A.Q', 'cozystay' ); ?></h3>
                    <p><?php esc_html_e( 'To make it easier and faster for you to find answers to your questions, we have made a list of frequently asked questions about using WordPress and this theme.', 'cozystay' ); ?></p>
                    <a href="https://loftocean.com/doc/cozystay/ptkb-category/how-tos-faqs/" target="_blank" class="button"><?php esc_html_e( 'Check FAQs', 'cozystay' ); ?></a>
                </div>

                <div class="support-channel">
                    <h3><?php esc_html_e( 'Submit a Ticket', 'cozystay' ); ?></h3>
                    <p><?php printf(
						/* translators: %1$s: html tag start. %2$s: html tag end */
						esc_html__( 'If you cannot find answers to your questions in the documentation and the FAQs, please submit a support request at Loft.Ocean Support Center (please follow %1$sthis guide%2$s).', 'cozystay' ),
						sprintf( '<a href="%s" target="_blank">', 'https://loftocean.com/doc/cozystay/ptkb-category/support/' ),
						'</a>'
					); ?></p>
                    <a href="https://www.loftocean.com/help-center/" class="button" target="_blank"><?php esc_html_e( 'Submit a Ticket', 'cozystay' ); ?></a>
                </div>
            </div><?php
		}
	}
	new CozyStay_Theme_Settings_Tab_Doc_Support();
}
