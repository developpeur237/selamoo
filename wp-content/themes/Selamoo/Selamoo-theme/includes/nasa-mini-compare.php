<?php $count_compare = count($nasa_compare_list); ?>
<div class="nasa-compare-list">
    <div class="row">
        <div class="large-12 columns">
            <table>
                <tr>
                    <td class="nasa-td-30">
                        <h5 class="clearfix text-center nasa-compare-label"><span class="nasa-block"><?php esc_html_e('Compare Products', 'digi-theme'); ?></span><span class="color-gray nasa-block"> (<?php echo $count_compare; ?> <?php esc_html_e('Products', 'digi-theme'); ?>)</span></h5>
                    </td>
                    <td class="nasa-td-40 nasa-td-products-compare">
                        <div class="row padding-side-15">
                            <?php 
                            $k = 0;
                            $class_item = $max_compare == 4 ? 'large-3 small-3 columns' : 'large-4 small-4 columns';
                            if($nasa_compare_list) :
                                foreach ($nasa_compare_list as $product) :
                                    if($k > $max_compare - 1):
                                        break;
                                    endif;
                                    $productId = $product->get_id();
                                    $nasa_title = $product->get_name();
                                    $nasa_href = get_permalink($productId);
                                    ?>
                                    <div class="<?php echo esc_attr($class_item); ?>">
                                        <div class="nasa-compare-wrap-item">
                                            <div class="nasa-compare-item-hover">
                                                <div class="nasa-compare-item-hover-wraper">
                                                    <a href="<?php echo esc_url($nasa_href); ?>" title="<?php echo esc_attr($nasa_title); ?>">
                                                        <?php echo $product->get_image('shop_catalog', array('alt' => esc_attr($nasa_title))); ?>
                                                        <h5><?php echo $nasa_title; ?></h5>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="nasa-compare-item">
                                                <a href="javascript:void(0);" class="nasa-remove-compare" data-prod="<?php echo esc_attr($productId); ?>"><i class="pe-7s-close"></i></a>
                                                <a href="<?php echo esc_url($nasa_href); ?>" class="nasa-img-compare" title="<?php echo esc_attr($nasa_title); ?>">
                                                    <?php echo $product->get_image('shop_thumbnail', array('alt' => esc_attr($nasa_title))); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php 
                                $k++;
                                endforeach; ?>
                            <?php endif; ?>

                            <?php if ($k < $max_compare) :
                                $nasa_src_no_image = wc_placeholder_img_src();
                                for($i=$k; $i<$max_compare; $i++): ?>
                                    <div class="<?php echo esc_attr($class_item); ?>">
                                        <div class="nasa-compare-wrap-item">
                                            <div class="nasa-compare-item">
                                                <span class="nasa-no-image">
                                                <img src="<?php echo esc_url($nasa_src_no_image); ?>" />
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="nasa-td-30">
                        <div class="nasa-compare-label<?php echo !$count_compare ? ' hidden-tag' : ''; ?>">
                            <a class="nasa-compare-clear-all nasa-hover-underline color-gray" href="javascript:void(0);" title="<?php esc_html_e('Clear All', 'digi-theme'); ?>"><?php esc_html_e('Clear All', 'digi-theme'); ?></a>
                            <a class="nasa-compare-view btn button small" href="<?php echo esc_url($view_href); ?>" title="<?php esc_html_e("Let's Compare !", 'digi-theme'); ?>"><?php esc_html_e("Let's Compare !", 'digi-theme'); ?></a>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<a class="nasa-close-mini-compare" href="javascript:void(0)"><i class="pe-7s-angle-down"></i></a>