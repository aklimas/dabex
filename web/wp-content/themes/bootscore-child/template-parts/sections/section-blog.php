<?php
/**
 * Blog/latest posts section.
 *
 * @package Bootscore Child
 */

defined('ABSPATH') || exit;

$defaults = [
    'section_id'      => '',
    'section_classes' => 'dabex-section dabex-section--section-blog',
    'container_class' => 'container',
    'fields'          => [],
];

$args   = wp_parse_args($args ?? [], $defaults);
$fields = is_array($args['fields']) ? $args['fields'] : [];
$title  = $fields['blog_title'] ?? '';
$intro  = $fields['blog_intro'] ?? '';
$count  = max(1, min(6, intval($fields['blog_count'] ?? 3)));
$category = intval($fields['blog_category'] ?? 0);
$button = $fields['blog_button'] ?? [];

$query_args = [
    'post_type'      => 'post',
    'posts_per_page' => $count,
    'post_status'    => 'publish',
];
if ($category) {
    $query_args['cat'] = $category;
}
$query = new WP_Query($query_args);
?>

<section id="<?= esc_attr($args['section_id']); ?>" class="<?= esc_attr($args['section_classes']); ?>">
  <div class="<?= esc_attr($args['container_class']); ?>">
    <div class="row align-items-end mb-4">
      <div class="col-md-8">
        <?php if ($title) : ?>
          <h2 class="h2 mb-3"><?= esc_html($title); ?></h2>
        <?php endif; ?>
        <?php if ($intro) : ?>
          <p class="text-muted mb-md-0"><?= esc_html($intro); ?></p>
        <?php endif; ?>
      </div>
      <?php if (!empty($button['url'])) : ?>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
          <a class="btn btn-outline-primary" href="<?= esc_url($button['url']); ?>" target="<?= esc_attr($button['target'] ?? '_self'); ?>">
            <?= esc_html($button['title'] ?? __('Zobacz wszystkie', 'bootscore')); ?>
          </a>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($query->have_posts()) : ?>
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php while ($query->have_posts()) :
          $query->the_post(); ?>
          <div class="col">
            <article class="card h-100 shadow-sm border-0 dabex-blog-card">
              <?php if (has_post_thumbnail()) : ?>
                <a href="<?= esc_url(get_permalink()); ?>" class="card-img-top ratio ratio-4x3 overflow-hidden">
                  <?php the_post_thumbnail('medium_large', ['class' => 'object-fit-cover w-100 h-100']); ?>
                </a>
              <?php endif; ?>
              <div class="card-body d-flex flex-column">
                <div class="small text-uppercase text-primary fw-semibold mb-2">
                  <?php $cats = get_the_category();
                  echo esc_html($cats ? $cats[0]->name : __('Artykuł', 'bootscore')); ?>
                </div>
                <h3 class="h5 card-title">
                  <a href="<?= esc_url(get_permalink()); ?>" class="stretched-link text-decoration-none text-reset">
                    <?= esc_html(get_the_title()); ?>
                  </a>
                </h3>
                <p class="card-text text-muted mt-2 mb-4"><?= esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                <span class="mt-auto text-primary fw-semibold"><?= esc_html__('Czytaj więcej', 'bootscore'); ?></span>
              </div>
            </article>
          </div>
        <?php endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p class="text-muted"><?= esc_html__('Brak wpisów do wyświetlenia.', 'bootscore'); ?></p>
    <?php endif; ?>
  </div>
</section>
