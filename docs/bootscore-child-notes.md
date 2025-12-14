# Bootscore Child Theme – Internal Notes

Dokument ma służyć jako punkt wyjścia przy każdej nowej funkcjonalności w motywie `bootscore-child`. Opisuje strukturę motywu, przepływ pracy z CSS/JS oraz rekomendowany sposób budowania wielokrotnie używalnych sekcji z ACF.

## 1. Przegląd motywu
- **Motyw bazowy**: `bootscore` (Bootstrap 5, WooCommerce-ready). Wszystkie funkcje ładowane są w `wp-content/themes/bootscore/functions.php`, a większość logiki żyje w `inc/*`.
- **Motyw potomny**: `wp-content/themes/bootscore-child`. To tutaj dodajemy własne style, skrypty, sekcje i hooki. `functions.php` ogranicza się do podpięcia CSS/JS, więc nowy kod PHP dopisujemy właśnie w tym pliku.
- **Key directories**:
  - `assets/scss` → źródła SCSS (edytujemy głównie `_bootscore-custom.scss`; pozostałe pliki odzwierciedlają strukturę Bootstrapa).
  - `assets/js/custom.js` → miejsce na JS (domyślnie wrap w jQuery ready).
  - `assets/css/main.css` → wynik kompilacji SCSS (nie ruszamy ręcznie).
  - `template-parts/` (utworzyć w child-theme jeśli potrzeba) → tam trzymamy niestandardowe sekcje, moduły i partiale.

## 2. Stylowanie i bundle
- Bootscore posiada własny SCSS compiler (`inc/scss-compiler.php`). W środowisku lokalnym kompilacja odpali się automatycznie przy zmianie plików w `assets/scss`.
- **Workflow**:
  1. Edytuj `assets/scss/_bootscore-custom.scss` (lub dodaj nowe partiale i zaciągnij je w `main.scss`).
  2. Odśwież stronę → SCSS kompiluje się do `assets/css/main.css`. W repo commitujemy tylko SCSS; CSS jest generowany.
  3. Jeżeli trzeba wyczyścić cache, usuń `assets/css/*.css` i odśwież – plik utworzy się na nowo.
- Możemy korzystać z pełnego systemu zmiennych Bootstrapa. Własne kolory/spacing ustawiamy w `_bootscore-variables.scss` (przed importem `bootstrap/variables`).

## 3. JavaScript
- `assets/js/custom.js` ładowany jest automatycznie (patrz `functions.php`). Każdorazowa zmiana wymaga jedynie odświeżenia – wersjonowanie odbywa się przez `filemtime`.
- Unikamy inline scriptów; wszystko pakujemy do modułów/funcji w `custom.js` i targetujemy elementy poprzez klasy BEM.

## 4. Hooki i template hierarchy
- `page.php` z motywu rodzica korzysta z hooków `do_action('bootscore_*')`. Najwygodniejsze miejsca na wstrzyknięcie sekcji:
  - `bootscore_after_featured_image` (tuż pod nagłówkiem strony),
  - `bootscore_before_entry_footer` (przed komentarzami),
  - `bootscore_after_primary_open` (zaraz wewnątrz `.content-area`).
- Do WooCommerce przydatne są pliki `woocommerce/wc-functions.php` i `template-parts/header/actions-woocommerce.php` – można tam podhaczyć własne filtry.

## 5. Workflow dla sekcji opartych na ACF
ACF (najlepiej wersja PRO) pozwala nam zbudować elastyczne „klocki”, które można układać na różnych stronach.

### 5.1. Struktura plików
```
wp-content/themes/bootscore-child/
├─ template-parts/
│  └─ sections/
│     ├─ hero.php
│     ├─ features.php
│     └─ ...
└─ inc/
   └─ acf-sections.php   (opcjonalnie – logika renderująca)
```
Utwórz katalog `template-parts/sections` i trzymaj tam czyste partiale. Każdy plik powinien przyjmować dane przez `$args`, np. `['section_id' => '', 'fields' => []]`, aby pozbyć się zależności od `get_sub_field()` w środku.

### 5.2. Pola ACF
1. Dodaj grupę „Sekcje strony” (`Location: Page equals <wybrane strony>`).
2. We wnętrzu ustaw **Flexible Content** o nazwie `sections`.
3. Każdy layout = jedna sekcja. Pola nazywamy z prefiksem `section_` (`section_title`, `section_buttons` itp.).
4. Dodaj pole `section_id` (text) i `section_spacing` (select), by wymusić spójne ID i marginesy pomiędzy sekcjami.
5. Włącz local JSON (ACF → Tools → Local JSON) i wskaż repo (np. dopisując w `functions.php` filtr `acf/settings/save_json`). Dzięki temu konfiguracja pól jest wersjonowana.

### 5.3. Renderowanie sekcji
W `functions.php` (lub osobnym pliku ładowanym w `functions.php`) dodaj helper:

```php
add_action('bootscore_after_featured_image', 'dabex_render_flexible_sections', 10, 1);

function dabex_render_flexible_sections($context) {
    if (!function_exists('have_rows') || !is_singular(['page', 'product']) || !have_rows('sections')) {
        return;
    }

    echo '<div class="acf-sections">';
    while (have_rows('sections')) {
        the_row();
        $layout = get_row_layout();
        $data   = [
            'section_id' => get_sub_field('section_id') ?: uniqid($layout . '-'),
            'fields'     => get_sub_field(null, false, false),
            'layout'     => $layout,
        ];
        get_template_part('template-parts/sections/' . $layout, null, $data);
    }
    echo '</div>';
}
```

Każdy `template-parts/sections/<layout>.php` zaczyna od pobrania `$args`:

```php
<?php
$defaults = ['section_id' => '', 'fields' => [], 'layout' => ''];
$args     = wp_parse_args($args, $defaults);
$fields   = $args['fields'];
?>
<section id="<?= esc_attr($args['section_id']); ?>" class="section section-<?= esc_attr($args['layout']); ?>">
  <div class="container py-5">
    <div class="row">
      <div class="col">
        <?= wp_kses_post($fields['section_title'] ?? ''); ?>
      </div>
    </div>
  </div>
</section>
```

### 5.4. Reużywalność
- Każda sekcja powinna posiadać:
  - **ID** (a11y + linki w nawigacji wewnętrznej),
  - opcję odstępów (`section_spacing`) i kolorystyki (`section_theme`) mapowaną na klasy Utility (np. `bg-light`, `py-5`),
  - możliwość wyboru szerokości (full/container) – zrób to w ACF jako `true/false`.
- Styl sekcji dopisujemy w SCSS: `assets/scss/sections/_hero.scss` i importujemy w `_bootscore-custom.scss`.
- Jeżeli sekcja korzysta z slidera/JS, inicjalizację dajemy w `custom.js`, w funkcji `initHeroSlider()` wywoływanej na `domready`.

## 6. Dodatkowe dobre praktyki
- **Nazewnictwo**: prefiks `dabex_` dla funkcji, `dabex-` dla klas CSS (np. `.dabex-section-hero`).
- **Tłumaczenia**: używamy `__()` / `esc_html__()` z tekst-domeną `bootscore`.
- **WooCommerce**: jeśli sekcja ma działać także na stronach sklepu, sprawdzaj `is_shop()`, `is_product()`, itd.
- **Performance**: unikaj ciężkich zapytań w sekcjach. Cache’uj przez `wp transient` jeśli łączysz wiele produktów/postów.
- **DCLI skróty**:
  - `ddev wp plugin activate advanced-custom-fields-pro`
  - `ddev wp acf export --slug=sections` (jeśli wtyczka CLI jest dostępna)
  - `ddev wp theme status` – kontrola czy child jest aktywny.

Tę dokumentację aktualizujemy za każdym razem, gdy wypracujemy nowy pattern (np. slider produktów, CTA, integracje JS). Dzięki temu kolejne wdrożenia zachowają spójność techniczną i wizualną.

### 5.4. Dostępne layouty (Flexible Content)
- `section-hero` – nagłówek strony z animacją/obrazem, CTA i opcjonalną nakładką.
- `section-generic` – uniwersalny blok tekst + media.
- `section-catalog` – siatka kategorii/marek z filtrami (2×4 kafle).
- `section-brand` – spotlight jednej marki z opisem i kaflami produktów.
- `section-benefits` – lista korzyści/USP w układzie 3 kolumn.
- `section-highlight` – podwójne CTA (np. dopasowane rozwiązania + szkolenia).
- `section-blog` – teaser bazy wiedzy (WP_Query).
- `section-contact` – ostatni call-to-action + formularz/shortcode.

W razie potrzeby tworzymy kolejne partiale w `template-parts/sections/` i dopisujemy layout do `acf-json/group_dabex_sections.json`, pamiętając o unikalnych `field_` kluczach.
