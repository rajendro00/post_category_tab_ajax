<?php 

/**
 * Plugin Name: Post Category Tab Ajax
 * Plugin URI: https://rajen.com
 * Description: This is a professional basement plugin
 * Version: 1.0.0
 * Author: Rajendro
 * Author URI: https://rajen.com
 * License: GPL2
 * Text Domain: post-category-tab-ajax
 */

class Post_Category_Tab {
    function __construct() {
        add_shortcode('post_category_tab_shortcode', [$this, 'post_category_tab_display']);
        add_action('wp_enqueue_scripts', [$this, 'post_enqueue_script']);
        add_action('wp_ajax_load_category_posts', [$this, 'load_category_posts_callback']);
        add_action('wp_ajax_nopriv_load_category_posts', [$this, 'load_category_posts_callback']);
        define('PLUGIN_ASSETS__URL', plugin_dir_url(__FILE__) . 'assets/');
    }

    function post_category_tab_display() { 
        ob_start();
        ?>

        <div class="container">
            <ul class="tabs">
                <?php  
                $terms = get_terms(array(
                    'taxonomy'   => 'category',
                    'hide_empty' => false,
                ));

                foreach ($terms as $cat) { ?>
                    <li class="tab-item" data-target="<?php echo esc_attr($cat->term_id) ?>">
                        <?php echo esc_html($cat->name) ?>
                    </li>
                <?php } ?>
            </ul>

            <div class="content-wrapper" id="post-content">
                <p>Select a category to load posts.</p>
            </div>
        </div>
        <?php return ob_get_clean();
    }

    function post_enqueue_script() {
        wp_enqueue_style('post-tab-css', PLUGIN_ASSETS__URL . 'css/style.css');
        wp_enqueue_script('post-tab-js', PLUGIN_ASSETS__URL . 'js/main.js', ['jquery'], '1.0.0', true);
        wp_localize_script('post-tab-js', 'post_tab_ajax', [
            'ajax_url' => admin_url('admin-ajax.php')
        ]);
    }

    function load_category_posts_callback() {
        $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;

        if ($category_id == 0) {
            echo '<p>No category selected.</p>';
            wp_die();
        }

        $args = [
            'post_type'      => 'post',
            'cat'            => $category_id,
            'posts_per_page' => 5,
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                ?>
                <div class="content">
                    <h2><?php the_title(); ?></h2>
                    <p><?php the_excerpt(); ?></p>
                    <?php if (has_post_thumbnail()) { ?>
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php } ?>
                </div>
                <?php
            }
            wp_reset_postdata();
        } else {
            echo '<p>No posts found.</p>';
        }

        wp_die();
    }
}

new Post_Category_Tab();
