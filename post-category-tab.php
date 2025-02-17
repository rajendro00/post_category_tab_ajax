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

 class post_category_tab{
    function __construct(){
        add_shortcode( 'post_category_tab_shortcode', [$this, 'post_category_tab_display'] );
        add_action( 'wp_enqueue_scripts',  [$this, 'post_enqueue_script'] );
        define('PLUGIN_ASSETS__URL', plugin_dir_url(__FILE__). 'assets/');
        
    }

    function post_category_tab_display(){ 
        ob_start();
        ?>

        <div class="container">

            <ul class="tabs">
                <?php  
                $terms = get_terms( array(
                    'taxonomy'   => 'category',
                    'hide_empty' => false,
                ) );
                foreach($terms as $cat){ ?>
                    <li class="" data-target="<?php echo esc_attr($cat->term_id) ?>"><?php echo esc_attr($cat->name) ?></li>
               <?php }
                
                ?>  
            </ul>
            
            <div class="content-wrapper">
                <?php  

                $args =[
                    'post_type'  => 'post',
                ];

                $query = new \WP_Query($args);

                if(!empty($query->have_posts())){
                    while($query->have_posts()) : $query->the_post() ; 
                    $categories = get_the_category(); 
                    $category_id = (!empty($categories)) ? $categories[0]->term_id : ''; 
                    
                    
                    ?>
                    <div class="content" data-category="<?php echo esc_attr($category_id); ?>">
                        <h2><?php  the_title(); ?>  </h2>
                        <p> <?php  the_content(); ?>   </p>
                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title_attribute(); ?>">
                    </div>
                <?php endwhile;
                }else{ ?>
                    <p> Post Not found </p>
              <?php  }
                ?>  
            </div>
        </div>
        <?php return ob_get_clean();
    }

    function post_enqueue_script(){
        wp_enqueue_style('post-tab-css', PLUGIN_ASSETS__URL. 'css/style.css');
        wp_enqueue_script('post-tab-js', PLUGIN_ASSETS__URL. 'js/main.js', ['jquery'], '1.0.0', true);
        wp_localize_script( 'post-tab-js', 'post_tab_ajax', [
            'ajax_url' => admin_url('admin-ajax.php')
        ] );
    }

    



 }

 new post_category_tab();