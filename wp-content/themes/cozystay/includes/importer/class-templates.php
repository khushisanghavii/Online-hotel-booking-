<?php
class CozyStay_Importer_Template {
    /**
    * Construct function
    */
    public function __construct() {
        add_action( 'cozystay_the_demo_list', array( $this, 'the_template' ) );
    }
    /**
    * Template to show demos
    */
    public function the_template() {
        $configs = apply_filters( 'cozystay_get_demo_configs', array() );
        foreach( $configs as $id => $config ) : ?>
            <div class="cs-demo-box" data-imported="0" data-tags="<?php echo esc_attr( implode( ',', $config[ 'tag'] ) ); ?>" data-title="<?php echo esc_attr( $config[ 'title' ] ); ?>" style="float: left; margin: 10px;">
    			<div id="cs-demo-<?php echo esc_attr( $id ); ?>" class="cs-demo-preview">
    				<div class="cs-preview-wrapper">
    					<div class="cs-preview-screenshot">
    						<img src="<?php echo esc_attr( $config[ 'screenshot' ] ); ?>" width="325">
    					</div>
    					<h3 class="cs-preview-title"><?php echo esc_html( $config[ 'title' ] ); ?></h3>
    					<div class="cs-preview-actions">
    						<a class="button button-primary button-install-open-modal" data-demo-id="<?php echo esc_attr( $id ); ?>" href="#"><?php esc_html_e( 'Import', 'cozystay' ); ?></a>
    						<a class="button button-primary" target="_blank" href="<?php echo esc_attr( $config[ 'preview' ] ); ?>"><?php esc_html_e( 'Preview', 'cozystay' ); ?></a>
    					</div>
    				</div>
    			</div>
                <div id="cs-demo-popup-<?php echo esc_attr( $id ); ?>" class="cs-demo-popup-wrap" style="display: none; width: 600px; position: fixed; top: 100px; background-color: grey; left: 33%; padding-left: 100px;">
                    <div class="cs-demo-popup-inner">
                        <div class="cs-demo-popup-thumbnail" style="background-image:url(<?php echo esc_attr( $config[ 'preview' ] ); ?>);">
                            <a class="cs-demo-popup-preview" target="_blank" href="<?php echo esc_attr( $config[ 'preview' ] ); ?>"><?php esc_html_e( 'Live Preview', 'cozystay' ); ?></a>
                        </div>
                        <div class="cs-demo-popup-content">
                            <div class="cs-demo-popup-required-plugins">
                                <h3><?php esc_html_e( 'Required Plugins To Import Content', 'cozystay' ); ?></h3>
                                <ul class="required-plugins-list">
                                    <li>
                                        <span class="required-plugin-name">Avada Core</span>
                                        <span class="required-plugin-status active ">Active</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="cs-demo-popup-form-wrap">
                                <div class="cs-demo-popup-import-form" style="display: inline-block; margin-right: 50px;">
                                    <h4 class="cs-demo-popup-form-title"><?php esc_html_e( 'Import Content', 'cozystay' ); ?> <span><?php esc_html_e( '(menus only import with "All")', 'cozystay' ); ?></span></h4>
                                    <p><input type="checkbox" value="all" id="import-all-classic"> <label for="import-all-classic">All</label></p>
                                    <p><input type="checkbox" value="post" data-type="content" id="import-post-classic"> <label for="import-post-classic">Posts</label></p>
                                    <p><input type="checkbox" value="page" data-type="content" id="import-page-classic"> <label for="import-page-classic">Pages</label></p>
                                    <p><input type="checkbox" value="avada_faq" data-type="content" id="import-avada_faq-classic"> <label for="import-avada_faq-classic">FAQs</label></p>
                                    <p><input type="checkbox" value="avada_layout" data-type="content" id="import-avada_layout-classic"> <label for="import-avada_layout-classic">Layouts</label></p>
                                    <p><input type="checkbox" value="fusion_icons" data-type="content" id="import-fusion_icons-classic"> <label for="import-fusion_icons-classic">Icons</label></p>
                                    <p><input type="checkbox" value="fusion_form" data-type="content" id="import-fusion_form-classic"> <label for="import-fusion_form-classic">Forms</label></p>
                                    <p><input type="checkbox" value="attachment" data-type="content" id="import-attachment-classic"> <label for="import-attachment-classic">Images</label></p>
                                    <p><input type="checkbox" value="sliders" id="import-sliders-classic"> <label for="import-sliders-classic">Sliders</label></p>
                                    <p><input type="checkbox" value="theme_options" id="import-theme_options-classic"> <label for="import-theme_options-classic">Options</label></p>
                                    <p><input type="checkbox" value="widgets" id="import-widgets-classic"> <label for="import-widgets-classic">Widgets</label></p>
                                </div>
                                <div class="cs-demo-popup-remove-form" style="display: inline-block;">
                                    <h4 class="cs-demo-popup-form-title">Remove Content</h4>
                                    <p><input type="checkbox" value="uninstall" id="uninstall-classic" disabled=""> <label for="uninstall-classic">Remove</label></p>
                                    <p><input type="checkbox" value="post" disabled="" data-type="content" id="remove-post-classic"> <label for="remove-post-classic">Posts</label></p>
                                    <p><input type="checkbox" value="page" disabled="" data-type="content" id="remove-page-classic"> <label for="remove-page-classic">Pages</label></p>
                                    <p><input type="checkbox" value="avada_faq" disabled="" data-type="content" id="remove-avada_faq-classic"> <label for="remove-avada_faq-classic">FAQs</label></p>
                                    <p><input type="checkbox" value="avada_layout" disabled="" data-type="content" id="remove-avada_layout-classic"> <label for="remove-avada_layout-classic">Layouts</label></p>
                                    <p><input type="checkbox" value="fusion_icons" disabled="" data-type="content" id="remove-fusion_icons-classic"> <label for="remove-fusion_icons-classic">Icons</label></p>
                                    <p><input type="checkbox" value="fusion_form" disabled="" data-type="content" id="remove-fusion_form-classic"> <label for="remove-fusion_form-classic">Forms</label></p>
                                    <p><input type="checkbox" value="attachment" disabled="" data-type="content" id="remove-attachment-classic"> <label for="remove-attachment-classic">Images</label></p>
                                    <p><input type="checkbox" value="sliders" disabled="" id="remove-sliders-classic"> <label for="remove-sliders-classic">Sliders</label></p>
                                    <p><input type="checkbox" value="theme_options" disabled="" id="remove-theme_options-classic"> <label for="remove-theme_options-classic">Options</label></p>
                                    <p><input type="checkbox" value="widgets" disabled="" id="remove-widgets-classic"> <label for="remove-widgets-classic">Widgets</label></p>
                                </div>
                            </div>
                        </div>
                        <div class="cs-demo-popup-status-bar">
                            <div class="cs-demo-popup-status-bar-label"><span></span></div>
                            <div class="cs-demo-popup-status-bar-progress-bar"></div>
                            <a class="button-install-demo" data-demo-id="<?php echo esc_attr( $id ); ?>" href="#"><?php esc_html_e( 'Import', 'cozystay' ); ?></a>
                            <a class="button-uninstall-demo" data-demo-id="<?php echo esc_attr( $id ); ?>" href="#"><?php esc_html_e( 'Remove', 'cozystay' ); ?></a>
                            <a class="button-done-demo demo-update-modal-close" href="#"><?php esc_html_e( 'Done', 'cozystay' ); ?></a>
                        </div>
                    </div>
                    <a href="#" class="cs-demo-popup-corner-close cs-demo-popup-update-modal-close"><span class="dashicons dashicons-no-alt"></span></a>
                </div> <!-- .cs-demo-popup-wrap -->
    		</div><?php
        endforeach;
    }
}
new CozyStay_Importer_Template();
