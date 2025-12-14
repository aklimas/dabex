<?php
/**
 * Brand modal grid section.
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

$args   = wp_parse_args($args ?? [], $defaults);
$fields = is_array($args['fields']) ? $args['fields'] : [];
$title  = $fields['brand_section_title'] ?? '';
$intro  = $fields['brand_section_intro'] ?? '';
$brands = is_array($fields['brand_list'] ?? null) ? $fields['brand_list'] : [];

if (empty($brands)) {
    return;
}

$section_unique = uniqid('brand-grid-');
?>

<section id="<?= esc_attr($args['section_id']); ?>" class="<?= esc_attr($args['section_classes']); ?>">
  <div class="<?= esc_attr($args['container_class']); ?>">
    <div class="text-center mb-5">
      <?php if ($title) : ?>
        <h2 class="h2 mb-3"><?= esc_html($title); ?></h2>
      <?php endif; ?>
      <?php if ($intro) : ?>
        <p class="lead text-muted mb-0"><?= esc_html($intro); ?></p>
      <?php endif; ?>
    </div>

    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3 justify-content-center brand-logo-grid">
      <?php foreach ($brands as $index => $brand) :
        $logo = $brand['logo'] ?? null;
        $name = $brand['name'] ?? '';
        $modal_id = $section_unique . '-brand-' . $index;
      ?>
        <div class="col">
          <button class="brand-logo-tile w-100 border rounded-4 bg-white p-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#<?= esc_attr($modal_id); ?>">
            <?php if ($logo && isset($logo['ID'])) : ?>
              <?= wp_get_attachment_image($logo['ID'], 'medium', false, ['class' => 'img-fluid']); ?>
            <?php endif; ?>
            <?php if ($name) : ?>
              <span class="d-block fw-semibold mt-2 small text-uppercase text-primary"><?= esc_html($name); ?></span>
            <?php endif; ?>
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php foreach ($brands as $index => $brand) :
    $logo   = $brand['logo'] ?? null;
    $name   = $brand['name'] ?? '';
    $header = $brand['header'] ?? '';
    $desc   = $brand['description'] ?? '';
    $features = is_array($brand['features'] ?? null) ? $brand['features'] : [];
    $categories = is_array($brand['categories'] ?? null) ? $brand['categories'] : [];
    $modal_id = $section_unique . '-brand-' . $index;
?>
<div class="modal fade brand-modal" id="<?= esc_attr($modal_id); ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <div class="d-flex align-items-center gap-3">
          <?php if ($logo && isset($logo['ID'])) : ?>
            <?= wp_get_attachment_image($logo['ID'], 'medium', false, ['class' => 'brand-modal__logo']); ?>
          <?php endif; ?>
          <?php if ($name) : ?>
            <h3 class="h4 mb-0"><?= esc_html($name); ?></h3>
          <?php endif; ?>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= esc_attr__('Zamknij', 'bootscore'); ?>"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4 mb-4">
          <div class="col-lg-5">
            <?php if ($header) : ?>
              <h4><?= esc_html($header); ?></h4>
            <?php endif; ?>
          </div>
          <div class="col-lg-7">
            <?php if ($desc) : ?>
              <div class="text-muted">
                <?= wp_kses_post($desc); ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <?php if (!empty($features)) : ?>
          <div class="row g-3 mb-4">
            <?php foreach ($features as $f_index => $feature) :
              $feature_modal_id = $modal_id . '-feature-' . $f_index;
              $icon = $feature['icon'] ?? '';
              $ftitle = $feature['title'] ?? '';
              $fdesc  = $feature['description'] ?? '';
            ?>
              <div class="col-12 col-lg-6">
                <button class="feature-card d-flex align-items-start gap-3 w-100 p-3 border rounded-4 text-start"
                        data-bs-toggle="modal"
                        data-bs-target="#<?= esc_attr($feature_modal_id); ?>">
                  <?php if ($icon) : ?>
                    <span class="feature-card__icon">
                      <?php
                      if (strpos($icon, '<svg') !== false) {
                          echo wp_kses_post($icon);
                      } else {
                          echo '<i class="' . esc_attr($icon) . '"></i>';
                      }
                      ?>
                    </span>
                  <?php endif; ?>
                  <span>
                    <strong class="d-block mb-1"><?= esc_html($ftitle); ?></strong>
                    <span class="text-muted small"><?= esc_html($fdesc); ?></span>
                  </span>
                </button>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($categories)) : ?>
          <div class="brand-categories">
            <h5 class="mb-3"><?= esc_html__('Kategorie produktów', 'bootscore'); ?></h5>
            <div class="d-flex flex-wrap gap-2">
              <?php foreach ($categories as $cat) :
                $label = $cat['label'] ?? '';
                $link  = $cat['link']['url'] ?? '';
                $target = $cat['link']['target'] ?? '_self';
                if (!$label || !$link) {
                    continue;
                }
              ?>
                <a class="btn btn-outline-primary" href="<?= esc_url($link); ?>" target="<?= esc_attr($target); ?>">
                  <?= esc_html($label); ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

  <?php if (!empty($features)) :
    foreach ($features as $f_index => $feature) :
      $feature_modal_id = $modal_id . '-feature-' . $f_index;
      $ftitle = $feature['modal_title'] ?? ($feature['title'] ?? '');
      $fcontent = $feature['modal_content'] ?? '';
  ?>
    <div class="modal fade feature-modal" id="<?= esc_attr($feature_modal_id); ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?= esc_html($ftitle); ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= esc_attr__('Zamknij', 'bootscore'); ?>"></button>
          </div>
          <div class="modal-body">
            <?= wp_kses_post($fcontent ?: $feature['description']); ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; endif; ?>
<?php endforeach; ?>
