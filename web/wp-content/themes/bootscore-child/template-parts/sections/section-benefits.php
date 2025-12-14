<?php
/**
 * Benefits/USP section.
 *
 * @package Bootscore Child
 */

defined('ABSPATH') || exit;

$defaults = [
    'section_id'      => '',
    'section_classes' => 'dabex-section dabex-section--section-benefits',
    'container_class' => 'container',
    'fields'          => [],
];

$args   = wp_parse_args($args ?? [], $defaults);
$fields = is_array($args['fields']) ? $args['fields'] : [];
$title  = $fields['benefits_title'] ?? '';
$intro  = $fields['benefits_intro'] ?? '';
$items  = $fields['benefits'] ?? [];
?>

<section id="<?= esc_attr($args['section_id']); ?>" class="<?= esc_attr($args['section_classes']); ?>">
  <div class="<?= esc_attr($args['container_class']); ?>">
    <div class="row mb-4">
      <div class="col-lg-8">
        <?php if ($title) : ?>
          <h2 class="h2 mb-3"><?= esc_html($title); ?></h2>
        <?php endif; ?>
        <?php if ($intro) : ?>
          <p class="lead text-muted mb-0"><?= esc_html($intro); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <?php if (!empty($items)) : ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
        <?php foreach ($items as $item) :
          $icon = $item['icon'] ?? '';
          $item_title = $item['title'] ?? '';
          $item_desc  = $item['description'] ?? '';
        ?>
          <div class="col">
            <div class="benefit-card h-100 p-4 border rounded-4 bg-white shadow-sm">
              <?php if ($icon) : ?>
                <div class="benefit-card__icon mb-3">
                  <?php
                  if (strpos($icon, '<svg') !== false) {
                      echo wp_kses_post($icon);
                  } elseif (strpos($icon, 'fa') !== false) {
                      echo '<i class="' . esc_attr($icon) . '"></i>';
                  } else {
                      echo '<span class="badge text-bg-primary">' . esc_html($icon) . '</span>';
                  }
                  ?>
                </div>
              <?php endif; ?>
              <?php if ($item_title) : ?>
                <h3 class="h5 mb-2"><?= esc_html($item_title); ?></h3>
              <?php endif; ?>
              <?php if ($item_desc) : ?>
                <p class="text-muted mb-0"><?= esc_html($item_desc); ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
