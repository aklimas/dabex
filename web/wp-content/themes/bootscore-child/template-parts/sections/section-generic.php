<?php
/**
 * Generic fallback section template.
 *
 * Expected $args keys:
 * - section_id
 * - section_classes
 * - container_class
 * - fields (array of raw ACF values)
 *
 * @package Bootscore Child
 */

defined('ABSPATH') || exit;

$defaults = [
    'section_id'      => '',
    'section_classes' => 'dabex-section',
    'container_class' => 'container',
    'fields'          => [],
];

$args   = wp_parse_args($args ?? [], $defaults);
$fields = is_array($args['fields']) ? $args['fields'] : [];

$title   = $fields['section_title'] ?? ($fields['heading'] ?? '');
$content = $fields['section_content'] ?? ($fields['content'] ?? ($fields['section_text'] ?? ''));
$buttons = $fields['section_buttons'] ?? [];
$media   = $fields['section_media'] ?? null;
?>

<section id="<?= esc_attr($args['section_id']); ?>" class="<?= esc_attr($args['section_classes']); ?>">
  <div class="<?= esc_attr($args['container_class']); ?>">
    <div class="row g-5 align-items-center">
      <div class="col-12 col-lg-<?= esc_attr(!empty($media) ? '6' : '12'); ?>">
        <?php if ($title) : ?>
          <h2 class="dabex-section__title h2 mb-4"><?= esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($content) : ?>
          <div class="dabex-section__content fs-5">
            <?= wp_kses_post(wpautop($content)); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($buttons) && is_array($buttons)) : ?>
          <div class="dabex-section__buttons d-flex flex-wrap gap-3 mt-4">
            <?php foreach ($buttons as $button) :
                $label = $button['label'] ?? $button['text'] ?? '';
                $url   = $button['link']['url'] ?? $button['url'] ?? '';
                $style = $button['variant'] ?? 'primary';
                if (!$label || !$url) {
                    continue;
                }
            ?>
              <a class="btn btn-<?= esc_attr($style); ?>" href="<?= esc_url($url); ?>" target="<?= esc_attr($button['link']['target'] ?? '_self'); ?>">
                <?= esc_html($label); ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <?php if (!empty($media)) : ?>
        <div class="col-12 col-lg-6 dabex-section__media text-center text-lg-end">
          <?php
          if (is_array($media) && isset($media['url'])) {
              echo wp_get_attachment_image($media['ID'] ?? 0, 'large', false, ['class' => 'img-fluid rounded']);
          } elseif (is_string($media)) {
              echo wp_kses_post($media);
          }
          ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
