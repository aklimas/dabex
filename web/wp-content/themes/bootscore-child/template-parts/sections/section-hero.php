<?php
/**
 * Hero section with kicker, heading, description, CTAs and optional background/media.
 *
 * @package Bootscore Child
 */

defined('ABSPATH') || exit;

$defaults = [
    'section_id'      => '',
    'section_classes' => 'dabex-section dabex-section--section-hero',
    'container_class' => 'container',
    'fields'          => [],
];

$args   = wp_parse_args($args ?? [], $defaults);
$fields = is_array($args['fields']) ? $args['fields'] : [];

$kicker      = $fields['hero_kicker'] ?? '';
$title       = $fields['section_title'] ?? '';
$content     = $fields['section_content'] ?? '';
$buttons     = $fields['section_buttons'] ?? [];
$media       = $fields['section_media'] ?? null;
$bg_image    = $fields['hero_background_image'] ?? null;
$overlay     = $fields['hero_overlay'] ?? '';
$overlay_hex = $fields['hero_overlay_color'] ?? '#000000';
$overlay_op  = isset($fields['hero_overlay_opacity']) ? floatval($fields['hero_overlay_opacity']) : 0.6;

$style_attr = [];
if (is_array($bg_image) && !empty($bg_image['url'])) {
    $style_attr[] = sprintf('background-image: url(%s);', esc_url($bg_image['url']));
}
$style_attr = $style_attr ? ' style="' . esc_attr(implode(' ', $style_attr)) . '"' : '';

$overlay_class = '';
if ($overlay === 'dark') {
    $overlay_class = 'bg-dark';
} elseif ($overlay === 'primary') {
    $overlay_class = 'bg-primary';
}

$overlay_styles = [];
if ($overlay_op > 0) {
    $overlay_styles[] = sprintf('--dabex-overlay-opacity:%s;', $overlay_op);
}
if ($overlay === 'custom' && $overlay_hex) {
    $overlay_styles[] = sprintf('background-color:%s;', esc_attr($overlay_hex));
}
$overlay_style_attr = $overlay_styles ? ' style="' . esc_attr(implode(' ', $overlay_styles)) . '"' : '';
?>

<section id="<?= esc_attr($args['section_id']); ?>" class="<?= esc_attr($args['section_classes'] . ' dabex-hero'); ?>"<?= $style_attr; ?>>
  <?php if ($overlay && $overlay !== '') : ?>
    <div class="dabex-hero__overlay <?= esc_attr($overlay_class); ?>"<?= $overlay_style_attr; ?>></div>
  <?php endif; ?>
  <div class="<?= esc_attr($args['container_class']); ?>">
    <div class="row align-items-center gy-5">
      <div class="col-12 col-lg-6">
        <?php if ($kicker) : ?>
          <div class="text-uppercase fw-semibold letter-spacing-sm text-primary mb-3"><?= esc_html($kicker); ?></div>
        <?php endif; ?>

        <?php if ($title) : ?>
          <h1 class="display-4 fw-bold mb-4"><?= esc_html($title); ?></h1>
        <?php endif; ?>

        <?php if ($content) : ?>
          <div class="lead mb-4"><?= wp_kses_post(nl2br($content)); ?></div>
        <?php endif; ?>

        <?php if (!empty($buttons) && is_array($buttons)) : ?>
          <div class="d-flex flex-wrap gap-3">
            <?php foreach ($buttons as $button) :
              $label = $button['label'] ?? '';
              $link  = $button['link']['url'] ?? '';
              $target = $button['link']['target'] ?? '_self';
              $variant = $button['variant'] ?? 'primary';
              if (!$label || !$link) {
                  continue;
              }
            ?>
              <a class="btn btn-<?= esc_attr($variant); ?> btn-lg" href="<?= esc_url($link); ?>" target="<?= esc_attr($target); ?>">
                <?= esc_html($label); ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <?php if (!empty($media)) : ?>
        <div class="col-12 col-lg-6 text-center text-lg-end">
          <?php
          if (isset($media['ID'])) {
              echo wp_get_attachment_image($media['ID'], 'large', false, ['class' => 'img-fluid shadow-lg dabex-hero__media']);
          } elseif (is_string($media)) {
              echo wp_kses_post($media);
          }
          ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
