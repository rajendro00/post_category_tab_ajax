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
        define('PLUGIN_ASSETS__URL', plugin_dir_url(__FILE__) . 'assets/');
    }

    function post_category_tab_display() { 
        ob_start();
        ?>

        <div class="container">
    <div class="tabs">
        <ul class="tab-items">
            <?php  
            $terms = get_terms(array(
                'taxonomy'   => 'category',
                'hide_empty' => false,
            ));
            
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $category) {
                    $term_id = $category->term_id;
                    $term_name = $category->name; ?>
                    <li class="tab-item" data-target="<?php echo esc_attr($term_id); ?>">
                        <?php echo esc_html($term_name); ?>
                    </li>
                <?php }
            }
            ?>
        </ul>

        <div class="tab-content-items">
            <?php  
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $category) {
                    $term_id = $category->term_id;
                    
                    $args = array(
                        'post_type'      => 'post',
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'category',
                                'field'    => 'term_id',
                                'terms'    => $term_id,
                            ),
                        ),
                        'posts_per_page' => 5,
                    );

                    $query = new WP_Query($args);

                    if ($query->have_posts()) { ?>
                        <div class="tab-content-item" data-category="<?php echo esc_attr($term_id); ?>">
                            <?php
                            while ($query->have_posts()) {
                                $query->the_post(); ?>
                                <h1><?php the_title(); ?></h1>
                                <p><?php the_excerpt(); ?></p>
                                <?php if (has_post_thumbnail()) { ?>
                                    <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="">
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php }
                    wp_reset_postdata();
                }
            }
            ?>
        </div>
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

    
}

new Post_Category_Tab();
