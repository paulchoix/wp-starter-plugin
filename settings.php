<?php
namespace Starter_Plugin\Settings;


// Register configuration options
function init()
{
    register_setting( \Starter_Plugin\Constants::$snake, \Starter_Plugin\Constants::$settings . '_settings', ['type' => 'object']);

    /*add_settings_section(
        'starter_section',
        __( 'Starter Plugin Section', \Starter_Plugin\Constants::$text_domain ),
        __NAMESPACE__ . '\\starter_section_callback',
        \Starter_Plugin\Constants::$snake
    );
    add_settings_field(
        'starter_field',
        __( 'Starter Plugin Field', \Starter_Plugin\Constants::$text_domain ),
        __NAMESPACE__ . '\\starter_field_callback',
        \Starter_Plugin\Constants::$snake,
        'section',
        ['label_for' => 'starter_field']
    );*/
}
add_action( 'admin_init', __NAMESPACE__ . '\\init' );

/*function starter_section_callback()
{
    _e( '<p>Description for the starter plugin section.</p>', \Starter_Plugin\Constants::$text_domain );
}

function starter_field_callback( $args )
{
    $options = get_option( \Starter_Plugin\Constants::$settings );
    ?>
    <input
        type="text"
        name="<?php echo \Starter_Plugin\Constants::$settings; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
        value="<?php echo isset( $options[$args['label_for']] ) ? esc_attr( $options[$args['label_for']] ) : ''; ?>"
    ></input>
    <?php
}*/

// Settings Page
function page_html()
{
    if ( !current_user_can( 'manage_options' ) ) return;
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( \Starter_Plugin\Constants::$snake );
            do_settings_sections( \Starter_Plugin\Constants::$snake );
            submit_button( __( 'Save', \Starter_Plugin\Constants::$text_domain ) );
            ?>
        </form>
    </div>
    <?php
}

function page()
{
    add_menu_page(
        __( 'Starter Plugin', \Starter_Plugin\Constants::$text_domain ),
        __( 'Starter Plugin', \Starter_Plugin\Constants::$text_domain ),
        'manage_options',
        \Starter_Plugin\Constants::$snake,
        __NAMESPACE__ . '\\page_html',
        'dashicons-admin-settings' // For more Dashicons: https://developer.wordpress.org/resource/dashicons/
    );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\page' );