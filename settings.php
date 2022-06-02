<?php

namespace Starter_Plugin\Settings;

use Starter_Plugin\Constants;

// Register configuration options
function init()
{
    register_setting(Constants::$SNAKE, Constants::$SETTINGS, ['type' => 'object']);

    /*add_settings_section(
        'starter_section',
        __( 'Starter Plugin Section', 'starter-plugin' ),
        __NAMESPACE__ . '\\starter_section_callback',
        Constants::$SNAKE
    );
    add_settings_field(
        'starter_field',
        __( 'Starter Plugin Field', 'starter-plugin' ),
        __NAMESPACE__ . '\\starter_field_callback',
        Constants::$SNAKE,
        'starter_section',
        ['label_for' => 'starter_field']
    );*/
}
//add_action( 'admin_init', __NAMESPACE__ . '\\init' );

/*function starter_section_callback()
{
    _e( '<p>Description for the starter plugin section.</p>', 'starter-plugin' );
}

function starter_field_callback( $args )
{
    $settings = get_option( Constants::$SETTINGS );
    ?>
    <input
        type="text"
        name="<?php echo Constants::$SETTINGS; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
        value="<?php echo isset( $settings[$args['label_for']] ) ? esc_attr( $settings[$args['label_for']] ) : ''; ?>"
    ></input>
    <?php
}*/

// Settings Page
function page_html()
{
    if (!current_user_can('manage_options')) return;
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields(Constants::$SNAKE);
            do_settings_sections(Constants::$SNAKE);
            submit_button(__('Save', 'starter-plugin'));
            ?>
        </form>
    </div>
<?php
}

function page()
{
    add_menu_page(
        __('Starter Plugin', 'starter-plugin'),
        __('Starter Plugin', 'starter-plugin'),
        'manage_options',
        Constants::$SNAKE,
        __NAMESPACE__ . '\\page_html',
        'dashicons-admin-settings' // For more Dashicons: https://developer.wordpress.org/resource/dashicons/
    );
}
//add_action( 'admin_menu', __NAMESPACE__ . '\\page' );