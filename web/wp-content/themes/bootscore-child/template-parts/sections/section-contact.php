<?php
/**
 * Contact / CTA section.
 *
 * @package Bootscore Child
 */

defined('ABSPATH') || exit;

$defaults = [
    'section_id'      => '',
    'section_classes' => 'dabex-section dabex-section--section-contact',
    'container_class' => 'container',
    'fields'          => [],
];

$args   = wp_parse_args($args ?? [], $defaults);
$fields = is_array($args['fields']) ? $args['fields'] : [];
$title  = $fields['contact_title'] ?? '';
$text   = $fields['contact_text'] ?? '';
$button = $fields['contact_button'] ?? [];
$form   = $fields['contact_form'] ?? '';
?>

<section id="<?= esc_attr($args['section_id']); ?>" class="<?= esc_attr($args['section_classes']); ?>">
  <div class="<?= esc_attr($args['container_class']); ?>">
    <div class="row align-items-center g-5">
      <div class="col-12 col-lg-6">
        <?php if ($title) : ?>
          <h2 class="h2 text-white mb-3"><?= esc_html($title); ?></h2>
        <?php endif; ?>
        <?php if ($text) : ?>
          <p class="text-white-50 lead mb-4"><?= esc_html($text); ?></p>
        <?php endif; ?>
        <?php if (!empty($button['url'])) : ?>
          <a class="btn btn-light btn-lg" href="<?= esc_url($button['url']); ?>" target="<?= esc_attr($button['target'] ?? '_self'); ?>">
            <?= esc_html($button['title'] ?? __('Napisz do nas', 'bootscore')); ?>
          </a>
        <?php endif; ?>
      </div>
      <div class="col-12 col-lg-6">
        <?php if ($form) : ?>
          <div class="bg-white rounded-4 p-4 shadow">
            <?= do_shortcode($form); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
