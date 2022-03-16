<?php

namespace Pivot_Multimedia\Taxonomies;

function taxonomy_types()
{
    register_taxonomy('pivot_multimedia_format', ['pivot_multimedia'], [
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'labels' => [
            'name' => __('Formats', 'pivot-multimedia'),
            'singular_name' => __('Format', 'pivot-multimedia'),
        ],
    ]);
}
add_action('init', __NAMESPACE__ . '\\taxonomy_types');

// From: https://rudrastyh.com/wordpress/add-custom-fields-to-taxonomy-terms.html
function add_icon_field($taxonomy)
{
?>
    <div class="form-field">
        <label for="pivot-multimedia-format-icon"><?php _e('Icône Bootstrap', 'pivot-multimedia'); ?></label>
        <input type="text" name="pivot-multimedia-format-icon" id="pivot-multimedia-format-icon" />
        <p><?php _e('Classe de l\'icône affichée', 'pivot-multimedia'); ?></p>
    </div>
<?php
}
add_action('pivot_multimedia_format_add_form_fields', __NAMESPACE__ . '\\add_icon_field');

function edit_icon_field($term, $taxonomy)
{
    $value = get_term_meta($term->term_id, 'pivot-multimedia-format-icon', true);
?>
    <tr class="form-field">
        <th>
            <label for="pivot-multimedia-format-icon"><?php _e('Icône Bootstrap', 'pivot-multimedia'); ?></label>
        </th>
        <td>
            <input name="pivot-multimedia-format-icon" id="pivot-multimedia-format-icon" type="text" value="<?php echo esc_attr($value); ?>" />
            <p class="description"><?php _e('Classe de l\'icône affichée', 'pivot-multimedia'); ?></p>
        </td>
    </tr>
<?php
}
add_action('pivot_multimedia_format_edit_form_fields', __NAMESPACE__ . '\\edit_icon_field', 10, 2);

function save_icon_field($term_id)
{
    update_term_meta(
        $term_id,
        'pivot-multimedia-format-icon',
        sanitize_text_field($_POST['pivot-multimedia-format-icon'])
    );
}
add_action('created_pivot_multimedia_format', __NAMESPACE__ . '\\save_icon_field');
add_action('edited_pivot_multimedia_format', __NAMESPACE__ . '\\save_icon_field');
