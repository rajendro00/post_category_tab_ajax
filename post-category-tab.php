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
        add_action( 'wp_ajax_post_tab', [$this, 'post_tab'] );
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
                'hide_empty' => true
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
            <div class="post-preloader">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><circle fill="#FF156D" stroke="#FF156D" stroke-width="15" r="15" cx="40" cy="65"><animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.4"></animate></circle><circle fill="#FF156D" stroke="#FF156D" stroke-width="15" r="15" cx="100" cy="65"><animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.2"></animate></circle><circle fill="#FF156D" stroke="#FF156D" stroke-width="15" r="15" cx="160" cy="65"><animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0"></animate></circle></svg>
            </div>
           <div class="tab-content-item active">
             
           </div>
        </div>
    </div>
</div>
        
        <?php return ob_get_clean();
    }

    function post_enqueue_script() {
        wp_enqueue_style('post-tab-css', PLUGIN_ASSETS__URL . 'css/style.css');
        wp_enqueue_script('post-tab-js', PLUGIN_ASSETS__URL . 'js/main.js', ['jquery'], '1.0.0', true);
        wp_localize_script('post-tab-js', 'post_tab_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('post_tab_nonce'),
        ]);
    }
  public function post_tab() {
    check_ajax_referer('post_tab_nonce', '_nonce');
    $args = [
        'post_type'      => 'post',
        'tax_query'      => [
            [
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $_POST['cat'],
            ],
        ],
    ];
    $post = new \WP_Query($args);
    if ($post->have_posts()) {
        while ($post->have_posts()) {
            $post->the_post();
            ?>
            <h1><?php the_title(); ?></h1>
            <p><?php the_excerpt(); ?></p>
            <?php if (has_post_thumbnail()) { 
                the_post_thumbnail();
                 
             }
        }
    }
    wp_die( );
  }
}

new Post_Category_Tab();
