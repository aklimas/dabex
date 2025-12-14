<?php
/**
 * Catalog grid section (brands/categories).
 *
 * @package Bootscore Child
 */

defined('ABSPATH') || exit;

$defaults = [
    'section_id'      => '',
    'section_classes' => 'dabex-section dabex-section--section-catalog',
    'container_class' => 'container',
    'fields'          => [],
];

$args    = wp_parse_args($args ?? [], $defaults);
$fields  = is_array($args['fields']) ? $args['fields'] : [];
$title   = $fields['catalog_title'] ?? '';
$subtitle = $fields['catalog_subtitle'] ?? '';
$filters = $fields['catalog_filters'] ?? [];
$cards   = is_array($fields['catalog_cards'] ?? null) ? $fields['catalog_cards'] : [];
?>

<section id="<?= esc_attr($args['section_id']); ?>" class="<?= esc_attr($args['section_classes']); ?>">
  <div class="<?= esc_attr($args['container_class']); ?>">
    <div class="row justify-content-between align-items-center mb-4">
      <div class="col-12 col-lg-7">
        <?php if ($title) : ?>
          <h2 class="h2 mb-3"><?= esc_html($title); ?></h2>
        <?php endif; ?>
        <?php if ($subtitle) : ?>
          <p class="lead text-muted mb-0"><?= esc_html($subtitle); ?></p>
        <?php endif; ?>
      </div>
      <?php if (!empty($filters)) : ?>
        <div class="col-12 col-lg-5">
          <div class="dabex-catalog__filters d-flex flex-wrap gap-2 justify-content-lg-end mt-3 mt-lg-0">
            <?php foreach ($filters as $filter) :
              $label = $filter['label'] ?? '';
              $link  = $filter['link']['url'] ?? '';
              if (!$label) {
                  continue;
              }
            ?>
              <?php if ($link) : ?>
                <a href="<?= esc_url($link); ?>" class="badge rounded-pill text-bg-light px-3 py-2"><?= esc_html($label); ?></a>
              <?php else : ?>
                <span class="badge rounded-pill text-bg-light px-3 py-2"><?= esc_html($label); ?></span>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <?php if (!empty($cards)) : ?>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
        <?php foreach ($cards as $card) :
          $img   = $card['image'] ?? null;
          $label = $card['title'] ?? '';
          $desc  = $card['description'] ?? '';
          $link  = $card['link']['url'] ?? '';
          $target = $card['link']['target'] ?? '_self';
        ?>
          <div class="col">
            <div class="dabex-card h-100 p-4 border rounded-4 text-center">
              <?php if ($img && isset($img['ID'])) : ?>
                <div class="dabex-card__image mb-3">
                  <?= wp_get_attachment_image($img['ID'], 'medium', false, ['class' => 'img-fluid']); ?>
                </div>
              <?php endif; ?>
              <?php if ($label) : ?>
                <h3 class="h5 mb-2"><?= esc_html($label); ?></h3>
              <?php endif; ?>
              <?php if ($desc) : ?>
                <p class="text-muted mb-3"><?= esc_html($desc); ?></p>
              <?php endif; ?>
              <?php if ($link) : ?>
                <a class="stretched-link fw-semibold" href="<?= esc_url($link); ?>" target="<?= esc_attr($target); ?>">
                  <?= esc_html($card['link']['title'] ?? __('Zobacz', 'bootscore')); ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
