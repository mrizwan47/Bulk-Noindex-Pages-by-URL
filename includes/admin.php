<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */
 
/**
 * custom option and settings
 */
function bulk_nibu_settings_init() {
    // Register a new setting for "bulk_nibu" page.
    register_setting( 'bulk_nibu', 'bulk_nibu_options' );
 
    // Register a new section in the "bulk_nibu" page.
    add_settings_section(
        'bulk_nibu_urls_list',
        __( 'Bulk Noindex by URL.', 'bulk_nibu' ), 'bulk_nibu_urls_list_callback',
        'bulk_nibu'
    );
 
    // Register a new field in the "bulk_nibu_urls_list" section, inside the "bulk_nibu" page.
    add_settings_field(
        'bulk_nibu_field_urls_list',
        __( 'URLs List', 'bulk_nibu' ),
        'bulk_nibu_field_urls_list_cb',
        'bulk_nibu',
        'bulk_nibu_urls_list',
        array(
            'label_for'         => 'bulk_nibu_field_urls_list',
            'class'             => 'bulk_nibu_row',
            'bulk_nibu_custom_data' => 'custom',
        )
    );
	
}
 
/**
 * Register our bulk_nibu_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'bulk_nibu_settings_init' );
 
 
/**
 * Custom option and settings:
 *  - callback functions
 */
 
 
/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function bulk_nibu_urls_list_callback( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Enter each link on a new line in the field below.', 'bulk_nibu' ); ?></p>
    <?php
}
 
/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function bulk_nibu_field_urls_list_cb( $args ) {

    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'bulk_nibu_options' ); ?>

    <textarea
		id="<?php echo esc_attr( $args['label_for'] ); ?>"
		data-custom="<?php echo esc_attr( $args['bulk_nibu_custom_data'] ); ?>"
		name="bulk_nibu_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
		rows="12" cols="100"><?php 
			
			echo $options[ $args['label_for'] ]; 
	
	?></textarea>

    <p class="description">
        <?php esc_html_e( 'Enter the URLs here. Be sure to match the domain/https status etc with the one setup in admin settings', 'bulk_nibu' ); ?>
    </p>
    <?php
}
 
/**
 * Add the top level menu page.
 */
function bulk_nibu_options_page() {
    add_menu_page(
        'Bulk Noindex Settings',
        'Bulk Noindex',
        'manage_options',
        'bulk_nibu',
        'bulk_nibu_options_page_html'
    );
}
 
 
/**
 * Register our bulk_nibu_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'bulk_nibu_options_page' );
 
 
/**
 * Top level menu callback function
 */
function bulk_nibu_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
 
    // add error/update messages
 
    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'bulk_nibu_messages', 'bulk_nibu_message', __( 'Settings Saved', 'bulk_nibu' ), 'updated' );
    }
 
    // show error/update messages
    settings_errors( 'bulk_nibu_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "bulk_nibu"
            settings_fields( 'bulk_nibu' );
            // output setting sections and their fields
            // (sections are registered for "bulk_nibu", each field is registered to a specific section)
            do_settings_sections( 'bulk_nibu' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}