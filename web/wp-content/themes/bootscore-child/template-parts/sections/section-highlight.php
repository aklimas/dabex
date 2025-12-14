<?php
/**
 * Double CTA highlight section.
 *
 * @package Bootscore Child
 */

defined('ABSPATH') || exit;

$defaults = [
    'section_id'      => '',
    'section_classes' => 'dabex-section dabex-section--section-highlight',
    'container_class' => 'container',
    'fields'          => [],
];

$args   = wp_parse_args($args ?? [], $defaults);
$fields = is_array($args['fields']) ? $args['fields'] : [];
$title  = $fields['highlight_title'] ?? '';
$intro  = $fields['highlight_intro'] ?? '';
$cards  = $fields['highlight_cards'] ?? [];
?>

<section id="<?= esc_attr($args['section_id']); ?>" class="<?= esc_attr($args['section_classes']); ?>">
  <div class="<?= esc_attr($args['container_class']); ?>">
    <?php if ($title || $intro) : ?>
      <div class="text-center mb-5">
        <?php if ($title) : ?>
          <h2 class="h2 mb-3"><?= esc_html($title); ?></h2>
        <?php endif; ?>
        <?php if ($intro) : ?>
          <p class="lead text-muted"><?= esc_html($intro); ?></p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($cards)) : ?>
      <div class="row g-4">
        <?php foreach ($cards as $card) :
          $ctitle = $card['title'] ?? '';
          $cdesc  = $card['description'] ?? '';
          $clink  = $card['link']['url'] ?? '';
          $ctarget = $card['link']['target'] ?? '_self';
        ?>
          <div class="col-12 col-lg-6">
            <div class="cta-panel h-100 p-4 p-lg-5 rounded-4 bg-white shadow-sm position-relative">
              <?php if ($ctitle) : ?>
                <h3 class="h4 mb-3"><?= esc_html($ctitle); ?></h3>
              <?php endif; ?>
              <?php if ($cdesc) : ?>
                <p class="mb-4 text-muted"><?= esc_html($cdesc); ?></p>
              <?php endif; ?>
              <?php if ($clink) : ?>
                <a class="btn btn-outline-primary" href="<?= esc_url($clink); ?>" target="<?= esc_attr($ctarget); ?>">
                  <?= esc_html($card['link']['title'] ?? __('Dowiedz się więcej', 'bootscore')); ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
