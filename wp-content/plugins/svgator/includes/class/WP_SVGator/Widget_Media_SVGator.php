<?php

namespace WP_SVGator;

/**
 * Widget API: WP_Widget_Media_Image class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.8.0
 */

/**
 * Core class that implements an image widget.
 *
 * @since 4.8.0
 *
 * @see WP_Widget_Media
 * @see WP_Widget
 */
class Widget_Media_SVGator extends \WP_Widget {

	public function __construct() {
	    add_action('admin_enqueue_scripts', Main::run()->enqueueScripts('WP_SVGatorWidget'));
		add_action('elementor/editor/before_enqueue_scripts', Main::run()->enqueueScripts('WP_SVGatorWidget'));

		parent::__construct(
			'media_svgator',
			'SVGator',
			array(
				'description' => __( 'Displays an animated SVG.' ),
				'mime_type'   => 'svgator',
			)
		);
	}

	private function makeResponsive(&$svg){
	    $dimensionRemoved = false;
	    $svg = preg_replace_callback('/<svg.*?>/', function($tag) use (&$dimensionRemoved){
		    $tag = $tag[0];
		    $tag = preg_replace('/(?:width|height)=["\'].*?["\']/i', '', $tag, -1, $count);
		    $dimensionRemoved = $count > 0;
		    $tag = preg_replace('/(?:width|height)=[0-9]+/i', '', $tag, -1, $count);
		    $dimensionRemoved = $dimensionRemoved || $count > 0;
		    return $tag;
        }, $svg);

	    return $dimensionRemoved;
    }

    private function getSvg($attachmentId){
	    $svgPath = $attachmentId ? get_attached_file($attachmentId) : false;
	    return $svgPath ? @file_get_contents($svgPath) : false;
    }

    // Creating widget front-end
	public function widget( $args, $instance ) {
		$svg = !empty($instance['attachment_id']) ? $this->getSvg($instance['attachment_id']) : false;
		$svg = Svg_Support::fixScript($svg);
		if (1 || !empty($instance['responsive']) && $instance['responsive']) {
		    $this->makeResponsive($svg);
        }
        if (!$svg) {
		    return;
        }
		print $args['before_widget'];
		if (!empty($instance['title'])) {
			print $args['before_title'];
			print htmlspecialchars( $instance['title'] );
			print $args['after_title'];
		}
		print $svg;
		print $args['after_widget'];

	}

	public function form( $instance ) {
		$id = function($name){
			return $this->get_field_id($name);
        };
		$name = function($name){
			return $this->get_field_name($name);
		};

		$svg = !empty($instance['attachment_id']) ? $this->getSvg($instance['attachment_id']) : false;
		$svgUrl = !empty($instance['attachment_id']) ? wp_get_attachment_url($instance['attachment_id']) : 'about:blank';
		if (!$svg) {
			$hasDimension = false;
			$responsive = '';
			$attachmentId = '';
			$title = '';
		} else {
			$hasDimension = $this->makeResponsive($svg);
			$responsive = !empty($instance['responsive']) && $instance['responsive'] ? ' checked="true"' : '';
			$attachmentId = (int)$instance['attachment_id'];
			$title = !empty($instance['title']) ? $instance['title'] : '';
		}

		$classes = [];

		if (!$svg) {
		    $classes[] = 'empty';
        }

        if ($hasDimension) {
	        $classes[] = 'has-dimension';
        }

		print '<div class="media-widget-control svgator-widget-control ' . implode(' ', $classes) . '">';

		print '<input type="hidden" id="' . $id('attachment_id') . '"'
              .' name="' . $name('attachment_id') . '"'
              .' value="' . $attachmentId . '"'
              .' class="attachment_id">';

	    print '<p>';
	    print '<label for="' . $id('title') . '">Title:</label>';
		print '<input type="text"'
              .' id="' . $id('title') . '"'
              .' name="' . $name('title') . '"'
              .' class="widefat"'
              .' value="' . htmlspecialchars($title) . '">';
		print '</p>';

		?>
        <div class="media-widget-preview media_image media_svgator">
            <div class="attachment-media-view">
                <button type="button" class="select-svgator-media button-add-media">Select animated SVG</button>
            </div>
            <div class="block-edit-media">
                <div class="media-widget-preview media_image media_svgator">
                    <object type="image/svg+xml" data="<?php echo $svgUrl; ?>" class="media_svgator_svg"></object>
                </div>
                <p class="toggle-responsive">
                    <input type="checkbox" id="<?php echo $id('responsive');?>" class="responsive" name=<?php echo '"' . $name('responsive') . '"' . $responsive ?>>
                    <label for="<?php echo $id('responsive');?>">Make responsive</label>
                </p>
                <p class="no-dimension">This SVG is already responsive</p>
                <p class="media-widget-buttons">
                    <button type="button" class="button select-svgator-media">Change animated SVG</button>
                </p>
            </div>
        </div>
        <?php

	    if ($svg) {
		    ?>

		    <?php
        }
		print '</div>';
	}

	public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
		$instance['attachment_id'] = (int)$new_instance['attachment_id'];
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['responsive'] = !empty($new_instance['responsive']) && $new_instance['responsive'] ? true : false;
        return $instance;
	}
}
