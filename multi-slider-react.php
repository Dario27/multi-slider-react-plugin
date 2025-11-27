<?php

/**
 * Plugin Name: Multi Slider React
 * Plugin URI: https://github.com/Dario27/multi-slider-react-plugin
 * Description: Interactive multi-slider block for WordPress Gutenberg with React
 * Version: 1.2.2
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
define('MULTI_SLIDER_VERSION', '1.2.2');
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
            ),
            'primaryColor' => array(
                'type' => 'string',
                'default' => '#ff6b35'
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
    $primary_color = isset($attributes['primaryColor']) ? $attributes['primaryColor'] : '#ff6b35';
    $total_items = count($items);
    $show_navigation_desktop = $total_items > 6; // Flechas en desktop solo si >6

    if (empty($items)) {
        return '';
    }

    // Enqueue Font Awesome for frontend (solo para iconos de categorías)
    wp_enqueue_style('font-awesome');

    // Generate unique ID for this slider instance
    $slider_id = 'multi-slider-' . uniqid();

    // SVG para flechas (puedes personalizar estos SVGs)
    $arrow_prev_svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300" width="16" height="16">
        <defs>
            <style>
            .cls-1 {
                fill: #ff6b35;
                stroke-width: 0px;
            }
            </style>
        </defs>
        <circle class="cls-1" cx="86.24" cy="150.01" r="11.81"/>
        <circle class="cls-1" cx="117.43" cy="118.96" r="11.81"/>
        <circle class="cls-1" cx="148.62" cy="87.91" r="11.81"/>
        <circle class="cls-1" cx="179.81" cy="56.86" r="11.81"/>
        <circle class="cls-1" cx="211" cy="25.81" r="11.81"/>
        <circle class="cls-1" cx="213.78" cy="273.24" r="11.81"/>
        <circle class="cls-1" cx="182.59" cy="242.19" r="11.81"/>
        <circle class="cls-1" cx="151.4" cy="211.14" r="11.81"/>
        <circle class="cls-1" cx="120.21" cy="180.09" r="11.81"/>
        </svg>';
    $arrow_next_svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300" width="16" height="16">
        <defs>
            <style>
            .cls-1 {
                fill: #ff6b35;
                stroke-width: 0px;
            }
            </style>
        </defs>
        <circle class="cls-1" cx="213.78" cy="149.04" r="11.81"/>
        <circle class="cls-1" cx="182.59" cy="180.09" r="11.81"/>
        <circle class="cls-1" cx="151.4" cy="211.14" r="11.81"/>
        <circle class="cls-1" cx="120.21" cy="242.19" r="11.81"/>
        <circle class="cls-1" cx="89.02" cy="273.24" r="11.81"/>
        <circle class="cls-1" cx="86.24" cy="25.81" r="11.81"/>
        <circle class="cls-1" cx="117.43" cy="56.86" r="11.81"/>
        <circle class="cls-1" cx="148.62" cy="87.91" r="11.81"/>
        <circle class="cls-1" cx="179.81" cy="118.96" r="11.81"/>
        </svg>';

    ob_start();
?>
    <style>
        #<?php echo $slider_id; ?>.multi-slider-icon-wrapper {
            color: <?php echo esc_attr($primary_color); ?>;
        }

        #<?php echo $slider_id; ?>.multi-slider-link:hover .multi-slider-icon-wrapper {
            background: <?php echo esc_attr($primary_color); ?>;
            color: white;
        }

        #<?php echo $slider_id; ?>.multi-slider-item-title {
            color: <?php echo esc_attr($primary_color); ?>;
        }

        #<?php echo $slider_id; ?>.multi-slider-nav {
            color: <?php echo esc_attr($primary_color); ?>;
        }

        #<?php echo $slider_id; ?>.multi-slider-nav svg {
            width: 20px;
            height: 20px;
        }

        /*#<?php echo $slider_id; ?>.multi-slider-nav:hover:not(:disabled) {
            background: <?php echo esc_attr($primary_color); ?>;
            border-color: <?php echo esc_attr($primary_color); ?>;
            color: white;
        }*/

        #<?php echo $slider_id; ?>.multi-slider-dot:hover {
            background: <?php echo esc_attr($primary_color); ?>;
        }

        #<?php echo $slider_id; ?>.multi-slider-dot.active {
            background: <?php echo esc_attr($primary_color); ?>;
        }

        /* Desktop: ocultar flechas si hay <=6 items */
        <?php if (!$show_navigation_desktop) : ?>@media (min-width: 1025px) {
            #<?php echo $slider_id; ?>.multi-slider-nav {
                display: none;
            }
        }

        <?php endif; ?>
        /* Responsive: siempre mostrar flechas */
        @media (max-width: 1024px) {
            #<?php echo $slider_id; ?>.multi-slider-nav {
                display: flex !important;
            }
        }
    </style>
    <div id="<?php echo $slider_id; ?>" class="multi-slider-container">
        <h2 class="multi-slider-title">Nuestras Categorías</h2>
        <div class="multi-slider-wrapper">
            <button class="multi-slider-nav multi-slider-nav-prev" aria-label="Previous">
                <?php echo $arrow_prev_svg; ?>
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
                <?php echo $arrow_next_svg; ?>
            </button>
        </div>
        <div class="multi-slider-dots"></div>
    </div>
    <script>
        (function() {
            const sliderContainer = document.querySelector('#<?php echo $slider_id; ?> .multi-slider-track-container');
            const sliderTrack = document.querySelector('#<?php echo $slider_id; ?> .multi-slider-track');
            const prevBtn = document.querySelector('#<?php echo $slider_id; ?> .multi-slider-nav-prev');
            const nextBtn = document.querySelector('#<?php echo $slider_id; ?> .multi-slider-nav-next');
            const dotsContainer = document.querySelector('#<?php echo $slider_id; ?> .multi-slider-dots');
            const items = document.querySelectorAll('#<?php echo $slider_id; ?> .multi-slider-item');

            let currentIndex = 0;
            let itemsPerView = getItemsPerView();
            const totalItems = items.length;
            let totalPages = Math.ceil(totalItems / itemsPerView);
            let isResponsive = window.innerWidth < 1024;

            function getItemsPerView() {
                if (window.innerWidth < 768) return 2;
                if (window.innerWidth < 1024) return 4;
                return 6;
            }

            function isResponsiveMode() {
                return window.innerWidth < 1024;
            }

            function shouldShowDots() {
                // Desktop: mostrar dots solo si >6 items
                // Responsive: siempre mostrar dots
                if (isResponsiveMode()) {
                    return true;
                }
                return totalItems > 6;
            }

            function createDots() {
                if (!dotsContainer) return;

                // Ocultar dots si no deben mostrarse
                if (!shouldShowDots()) {
                    dotsContainer.style.display = 'none';
                    return;
                }

                dotsContainer.style.display = 'flex';
                dotsContainer.innerHTML = '';

                const dotsCount = isResponsiveMode() ? totalItems : totalPages;

                for (let i = 0; i < dotsCount; i++) {
                    const dot = document.createElement('span');
                    dot.classList.add('multi-slider-dot');
                    if (i === 0) dot.classList.add('active');
                    dot.addEventListener('click', () => goToPage(i));
                    dotsContainer.appendChild(dot);
                }
            }

            function updateDots() {
                if (!dotsContainer || !shouldShowDots()) return;
                const dots = document.querySelectorAll('#<?php echo $slider_id; ?> .multi-slider-dot');
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }

            function updateSlider() {
                const itemWidth = items[0].offsetWidth;
                const gap = 20;

                // En responsive: mover de 1 en 1 item
                // En desktop: mover por páginas
                let offset;
                if (isResponsiveMode()) {
                    offset = -(currentIndex * (itemWidth + gap));
                } else {
                    offset = -(currentIndex * itemsPerView * (itemWidth + gap));
                }

                sliderTrack.style.transform = `translateX(${offset}px)`;
                updateDots();

                if (prevBtn && nextBtn) {
                    const maxIndex = isResponsiveMode() ? totalItems - itemsPerView : totalPages - 1;
                    prevBtn.disabled = currentIndex === 0;
                    nextBtn.disabled = currentIndex >= maxIndex;
                }
            }

            function goToPage(index) {
                const maxIndex = isResponsiveMode() ? totalItems - itemsPerView : totalPages - 1;
                currentIndex = Math.max(0, Math.min(index, maxIndex));
                updateSlider();
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (currentIndex > 0) {
                        currentIndex--;
                        updateSlider();
                    }
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    const maxIndex = isResponsiveMode() ? totalItems - itemsPerView : totalPages - 1;
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                        updateSlider();
                    }
                });
            }

            window.addEventListener('resize', () => {
                const newItemsPerView = getItemsPerView();
                const wasResponsive = isResponsive;
                isResponsive = isResponsiveMode();

                if (newItemsPerView !== itemsPerView || wasResponsive !== isResponsive) {
                    itemsPerView = newItemsPerView;
                    totalPages = Math.ceil(totalItems / itemsPerView);
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
