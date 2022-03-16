<?php

namespace Pivot_Multimedia\Settings;

use Pivot_Multimedia\Constants;

// Register configuration options
function init()
{
    register_setting(Constants::$snake, Constants::$settings, ['type' => 'object']);

    /*add_settings_section(
        'starter_section',
        __( 'Pivot Multimedia Section', 'pivot-multimedia' ),
        __NAMESPACE__ . '\\starter_section_callback',
        Constants::$snake
    );
    add_settings_field(
        'starter_field',
        __( 'Pivot Multimedia Field', 'pivot-multimedia' ),
        __NAMESPACE__ . '\\starter_field_callback',
        Constants::$snake,
        'section',
        ['label_for' => 'starter_field']
    );*/
}
//add_action( 'admin_init', __NAMESPACE__ . '\\init' );

/*function starter_section_callback()
{
    _e( '<p>Description for the Pivot Multimedia section.</p>', 'pivot-multimedia' );
}

function starter_field_callback( $args )
{
    $settings = get_option( Constants::$settings );
    ?>
    <input
        type="text"
        name="<?php echo Constants::$settings; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
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
            settings_fields(Constants::$snake);
            do_settings_sections(Constants::$snake);
            submit_button(__('Save', 'pivot-multimedia'));
            ?>
        </form>
    </div>
<?php
}

function page()
{
    add_menu_page(
        __('Pivot Multimedia', 'pivot-multimedia'),
        __('Pivot Multimedia', 'pivot-multimedia'),
        'manage_options',
        Constants::$snake,
        __NAMESPACE__ . '\\page_html',
        'dashicons-admin-settings' // For more Dashicons: https://developer.wordpress.org/resource/dashicons/
    );
}
//add_action( 'admin_menu', __NAMESPACE__ . '\\page' );