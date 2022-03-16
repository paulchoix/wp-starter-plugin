<?php

namespace Pivot_Multimedia\Types;

class Multimedia
{

    function __construct()
    {
        add_action('init', [$this, 'create_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta']);
        add_action('save_post', [$this, 'save_post']);
    }

    function create_post_type()
    {
        register_post_type(
            'pivot_multimedia',
            [
                'supports' => ['title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes'],
                'public' => true,
                'has_archive' => true,
                'hierarchical' => true,
                'rewrite'     => ['slug' => 'multimedia'],
                'menu_icon' => 'dashicons-admin-media',
                'labels' => [
                    'name' => __('Multimédias', 'pivot-multimedia'),
                    'singular_name' => __('Multimédia', 'pivot-multimedia'),
                ],
            ]
        );
    }

    function add_meta()
    {
        $screens = ['pivot_multimedia'];
        foreach ($screens as $screen) {
            add_meta_box(
                'pivot_multimedia_credits',
                __('Crédits', 'pivot-multimedia'),
                [$this, 'wysiwyg_callback'], // [TODO] Fix this as it doesn't provide post data
                $screen,
                'normal',
                'high',
            );
        }
    }

    function save_post($post_id)
    {
        if (!empty($_POST['pivot_multimedia_credits'])) {
            update_post_meta($post_id, 'pivot_multimedia_credits', $_POST['pivot_multimedia_credits']);
        }
    }

    function wysiwyg_callback($post)
    {
        $text = get_post_meta($post->ID, 'pivot_multimedia_credits', true);
        wp_editor($text, 'pivot_multimedia_credits_wp', [
            'textarea_name' => 'pivot_multimedia_credits',
            'textarea_rows' => 8,
        ]);
    }
}

$multimedia = new Multimedia();
