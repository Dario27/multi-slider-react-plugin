<?php

/**
 * Plugin Name: Multi Slider React
 * Plugin URI: https://github.com/Dario27/multi-slider-react-plugin
 * Description: Interactive multi-slider block for WordPress Gutenberg with React
 * Version: 1.0.6
 * Requires at least: WordPress 5.0 o superior
 * Requires PHP: 7.4 o superior
 * Author: Steven Chilan Bito
 * License: MIT
 * Text Domain: multi-slider-react
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MULTI_SLIDER_VERSION', '1.0.6');
define('MULTI_SLIDER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MULTI_SLIDER_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Register the block
 */
function multi_slider_register_block()
{
    // Register block editor script
    wp_register_script(
        'multi-slider-block-editor',
        MULTI_SLIDER_PLUGIN_URL . 'build/index.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        MULTI_SLIDER_VERSION,
        true
    );

    // Register block editor styles
    wp_register_style(
        'multi-slider-block-editor-style',
        MULTI_SLIDER_PLUGIN_URL . 'build/editor.css',
        array('wp-edit-blocks'),
        MULTI_SLIDER_VERSION
    );

    // Register front-end styles
    wp_register_style(
        'multi-slider-block-style',
        MULTI_SLIDER_PLUGIN_URL . 'build/style.css',
        array(),
        MULTI_SLIDER_VERSION
    );

    // Register Font Awesome
    wp_register_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css',
        array(),
        '7.0.0'
    );

    // Register the block
    register_block_type('multi-slider/slider-block', array(
        'editor_script' => 'multi-slider-block-editor',
        'editor_style' => 'multi-slider-block-editor-style',
        'style' => 'multi-slider-block-style',
        'render_callback' => 'multi_slider_render_block',
        'attributes' => array(
            'items' => array(
                'type' => 'array',
                'default' => array()
            )
        )
    ));
}
add_action('init', 'multi_slider_register_block');

/**
 * Render callback for the block
 */
function multi_slider_render_block($attributes)
{
    $items = isset($attributes['items']) ? $attributes['items'] : array();

    if (empty($items)) {
        return '';
    }

    // Enqueue Font Awesome for frontend
    wp_enqueue_style('font-awesome');

    ob_start();
?>
    <div class="multi-slider-container">
        <h2 class="multi-slider-title">Nuestras Categorías</h2>
        <div class="multi-slider-wrapper">
            <button class="multi-slider-nav multi-slider-nav-prev" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="multi-slider-track-container">
                <div class="multi-slider-track">
                    <?php foreach ($items as $item) : ?>
                        <div class="multi-slider-item">
                            <a href="<?php echo esc_url($item['link']); ?>" class="multi-slider-link">
                                <div class="multi-slider-icon-wrapper">
                                    <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                                </div>
                                <h3 class="multi-slider-item-title"><?php echo esc_html($item['name']); ?></h3>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="multi-slider-nav multi-slider-nav-next" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="multi-slider-dots"></div>
    </div>
    <script>
        (function() {
            const sliderContainer = document.querySelector('.multi-slider-track-container');
            const sliderTrack = document.querySelector('.multi-slider-track');
            const prevBtn = document.querySelector('.multi-slider-nav-prev');
            const nextBtn = document.querySelector('.multi-slider-nav-next');
            const dotsContainer = document.querySelector('.multi-slider-dots');
            const items = document.querySelectorAll('.multi-slider-item');

            let currentIndex = 0;
            let itemsPerView = getItemsPerView();
            const totalItems = items.length;
            const totalPages = Math.ceil(totalItems / itemsPerView);

            function getItemsPerView() {
                if (window.innerWidth < 768) return 2;
                if (window.innerWidth < 1024) return 4;
                return 6;
            }

            function createDots() {
                dotsContainer.innerHTML = '';
                for (let i = 0; i < totalPages; i++) {
                    const dot = document.createElement('span');
                    dot.classList.add('multi-slider-dot');
                    if (i === 0) dot.classList.add('active');
                    dot.addEventListener('click', () => goToPage(i));
                    dotsContainer.appendChild(dot);
                }
            }

            function updateDots() {
                const dots = document.querySelectorAll('.multi-slider-dot');
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }

            function updateSlider() {
                const itemWidth = items[0].offsetWidth;
                const gap = 20;
                const offset = -(currentIndex * itemsPerView * (itemWidth + gap));
                sliderTrack.style.transform = `translateX(${offset}px)`;
                updateDots();

                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= totalPages - 1;
            }

            function goToPage(index) {
                currentIndex = Math.max(0, Math.min(index, totalPages - 1));
                updateSlider();
            }

            prevBtn.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            });

            nextBtn.addEventListener('click', () => {
                if (currentIndex < totalPages - 1) {
                    currentIndex++;
                    updateSlider();
                }
            });

            window.addEventListener('resize', () => {
                const newItemsPerView = getItemsPerView();
                if (newItemsPerView !== itemsPerView) {
                    itemsPerView = newItemsPerView;
                    currentIndex = 0;
                    createDots();
                    updateSlider();
                }
            });

            createDots();
            updateSlider();
        })();
    </script>
<?php
    return ob_get_clean();
}

/**
 * Enqueue Font Awesome in admin
 */
function multi_slider_enqueue_admin_assets()
{
    wp_enqueue_style('font-awesome');
}
add_action('enqueue_block_editor_assets', 'multi_slider_enqueue_admin_assets');
