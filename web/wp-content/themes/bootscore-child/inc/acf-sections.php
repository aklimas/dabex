<?php

/**
 * Helpers for rendering reusable ACF Flexible Content sections.
 *
 * @package Bootscore Child
 */

defined('ABSPATH') || exit;

/**
 * Ensure ACF JSON is saved/loaded from the child theme so field groups live in Git.
 */
add_filter('acf/settings/save_json', function ($path) {
    $child_path = get_stylesheet_directory() . '/acf-json';
    if (!file_exists($child_path)) {
        wp_mkdir_p($child_path);
    }

    return $child_path;
});

add_filter('acf/settings/load_json', function ($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return array_unique($paths);
});

/**
 * Hook Flexible Content renderer into standard bootScore locations.
 */
add_action('bootscore_after_featured_image', 'dabex_render_acf_sections', 15, 1);

/**
 * Render the Flexible Content field `sections` as stacked <section> blocks.
 */
function dabex_render_acf_sections($context = '') {
    if (!function_exists('have_rows') || !is_singular()) {
        return;
    }

    $post_id = get_the_ID();

    if (!have_rows('sections', $post_id)) {
        return;
    }

    echo '<div class="dabex-acf-sections" data-context="' . esc_attr($context) . '">';

    while (have_rows('sections', $post_id)) {
        the_row();

        $layout      = get_row_layout();
        $raw_fields  = dabex_get_current_section_fields($post_id);
        $section_id  = !empty($raw_fields['section_id']) ? sanitize_title($raw_fields['section_id']) : dabex_generate_section_id($layout);
        $full_width  = !empty($raw_fields['section_full_width']);
        $spacing     = dabex_map_section_spacing($raw_fields['section_spacing'] ?? '');
        $theme_class = dabex_map_section_theme($raw_fields['section_theme'] ?? '');

        $args = [
            'section_id'      => $section_id,
            'layout'          => $layout,
            'fields'          => $raw_fields,
            'context'         => $context,
            'section_classes' => dabex_build_section_classes($layout, $spacing, $theme_class, $full_width),
            'container_class' => $full_width ? 'container-fluid px-0' : 'container',
        ];

        dabex_get_section_template($layout, $args);
    }

    echo '</div>';
}

/**
 * Load template part for the given layout.
 */
function dabex_get_section_template($layout, $args) {
    $slug      = 'template-parts/sections/' . sanitize_file_name($layout);
    $templates = [$slug];

    if ('section-generic' !== $layout) {
        $templates[] = 'template-parts/sections/section-generic';
    }

    foreach ($templates as $template) {
        $path = locate_template($template . '.php');
        if ($path) {
            get_template_part($template, null, $args);
            return;
        }
    }
}

/**
 * Retrieve the raw field data for the current row index.
 */
function dabex_get_current_section_fields($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $rows  = get_field('sections', $post_id);
    $index = get_row_index();

    if (!$rows || !isset($rows[$index - 1])) {
        return [];
    }

    $row = $rows[$index - 1];
    unset($row['acf_fc_layout']); // not needed in template context

    return $row;
}

/**
 * Build wrapper classes for a section.
 */
function dabex_build_section_classes($layout, $spacing, $theme_class, $full_width) {
    $classes   = ['dabex-section', 'dabex-section--' . sanitize_html_class($layout)];
    $classes[] = $spacing ?: 'py-5';

    if ($theme_class) {
        $classes[] = $theme_class;
    }

    if ($full_width) {
        $classes[] = 'dabex-section--full';
    }

    return implode(' ', array_filter($classes));
}

/**
 * Map ACF select choice to Bootstrap spacing utilities.
 */
function dabex_map_section_spacing($choice) {
    $map = [
        'none'    => 'py-0',
        'xs'      => 'py-2',
        'sm'      => 'py-3',
        'default' => 'py-5',
        'lg'      => 'py-5 py-lg-6',
        'xl'      => 'py-6',
    ];

    return $map[$choice] ?? $map['default'];
}

/**
 * Map theme choice into background/text utility classes.
 */
function dabex_map_section_theme($choice) {
    $map = [
        'light'    => 'bg-light text-body',
        'dark'     => 'bg-dark text-white',
        'primary'  => 'bg-primary text-white',
        'secondary'=> 'bg-secondary text-white',
        'muted'    => 'bg-body-secondary',
    ];

    return $map[$choice] ?? '';
}

/**
 * Generate unique fallback ID for anonymous sections.
 */
function dabex_generate_section_id($layout) {
    return sanitize_title($layout) . '-' . get_the_ID() . '-' . get_row_index();
}
