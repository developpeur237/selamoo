<?php
// add_action('widgets_init', 'nasa_posts_widget');

function nasa_posts_widget() {
    register_widget('Nasa_Post_Widget');
}

class Nasa_Post_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'nasa_posts', 'description' => esc_html__('A widget that displays recent posts ', 'nasa-core'));

        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'nasa_posts');

        parent::__construct('nasa_posts', esc_html__('Nasa Posts', 'nasa-core'), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        $cache = wp_cache_get('widget_posts', 'widget');

        if (!is_array($cache))
            $cache = array();

        if (!isset($args['widget_id']))
            $args['widget_id'] = $this->id;

        if (isset($cache[$args['widget_id']])) {
            echo $cache[$args['widget_id']];
            return;
        }

        ob_start();
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Recent Posts', 'nasa-core') : $instance['title'], $instance, $this->id_base);
        if (empty($instance['number']) || !($number = absint($instance['number'])))
            $number = 10;

        $r = new WP_Query(apply_filters('widget_posts_args', array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true)));

        if ($r->have_posts()) :
            ?>
            <?php echo $before_widget; ?>
            <?php if ($title) echo $before_title . $title . $after_title; ?>

            <ul>
                <?php while ($r->have_posts()) :
                    $r->the_post(); ?>
                    <li>
                        <div class="post-date">
                            <span class="post-date-day">
                                <?php echo get_the_time('M d ,Y'); ?>
                            </span>
                        </div>
                        <div class="post-excerpt">
                            <?php
                            $excerpt = get_the_excerpt();
                            echo string_limit_words($excerpt, 10) . '<a class="read-more" href="' . get_permalink() . '">' . esc_html__('&nbsp;&nbsp;[more]', 'nasa-core') . '</a>';
                            ?>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php echo $after_widget; ?>
            <?php
            wp_reset_postdata();
        endif;

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_posts', $cache, 'widget');
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];

        $alloptions = wp_cache_get('alloptions', 'options');
        if (isset($alloptions['widget_entries']))
            delete_option('widget_entries');

        return $instance;
    }

    function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'nasa-core'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of posts to show:', 'nasa-core'); ?></label>
            <input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>
        <?php
    }

}
