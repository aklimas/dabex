<?php
/**
 * Brand/story spotlight section.
 *
 * @package Bootscore Child
 */

defined('ABSPATH') || exit;

$defaults = [
    'section_id'      => '',
    'section_classes' => 'dabex-section dabex-section--section-brand',
    'container_class' => 'container',
    'fields'          => [],
];

$args    = wp_parse_args($args ?? [], $defaults);
$fields  = is_array($args['fields']) ? $args['fields'] : [];
$logo    = $fields['brand_logo'] ?? null;
$name    = $fields['brand_name'] ?? '';
$heading = $fields['brand_heading'] ?? '';
$desc    = $fields['brand_description'] ?? '';
$link    = $fields['brand_link'] ?? [];
$cards   = $fields['brand_cards'] ?? [];
?>

<section id="<?= esc_attr($args['section_id']); ?>" class="<?= esc_attr($args['section_classes']); ?>">
  <div class="<?= esc_attr($args['container_class']); ?>">
    <div class="row g-5 align-items-center">
      <div class="col-12 col-lg-5">
        <?php if ($logo && isset($logo['ID'])) : ?>
          <div class="mb-3">
            <?= wp_get_attachment_image($logo['ID'], 'medium', false, ['class' => 'img-fluid brand-logo']); ?>
          </div>
        <?php endif; ?>
        <?php if ($name || $heading) : ?>
          <h2 class="h3 mb-3">
            <?= esc_html($heading ?: $name); ?>
          </h2>
        <?php endif; ?>
        <?php if ($desc) : ?>
          <div class="mb-4 text-body">
            <?= wp_kses_post($desc); ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($link['url'])) : ?>
          <a class="btn btn-primary" href="<?= esc_url($link['url']); ?>" target="<?= esc_attr($link['target'] ?? '_self'); ?>">
            <?= esc_html($link['title'] ?? __('Zobacz ofertę', 'bootscore')); ?>
          </a>
        <?php endif; ?>
      </div>
      <div class="col-12 col-lg-7">
        <?php if (!empty($cards)) : ?>
          <div class="row row-cols-1 row-cols-sm-2 g-4">
            <?php foreach ($cards as $card) :
              $cimg = $card['image'] ?? null;
              $ctitle = $card['title'] ?? '';
              $cdesc  = $card['description'] ?? '';
              $clink  = $card['link']['url'] ?? '';
              $ctarget = $card['link']['target'] ?? '_self';
            ?>
              <div class="col">
                <div class="card h-100 shadow-sm border-0">
                  <?php if ($cimg && isset($cimg['ID'])) : ?>
                    <?= wp_get_attachment_image($cimg['ID'], 'medium', false, ['class' => 'card-img-top']); ?>
                  <?php endif; ?>
                  <div class="card-body">
                    <?php if ($ctitle) : ?>
                      <h3 class="h5 card-title"><?= esc_html($ctitle); ?></h3>
                    <?php endif; ?>
                    <?php if ($cdesc) : ?>
                      <p class="card-text text-muted"><?= esc_html($cdesc); ?></p>
                    <?php endif; ?>
                    <?php if ($clink) : ?>
                      <a class="stretched-link fw-semibold" href="<?= esc_url($clink); ?>" target="<?= esc_attr($ctarget); ?>">
                        <?= esc_html($card['link']['title'] ?? __('Dowiedz się więcej', 'bootscore')); ?>
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
