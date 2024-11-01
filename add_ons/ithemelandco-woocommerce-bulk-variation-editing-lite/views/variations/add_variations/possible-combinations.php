<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<form id="iwbvel-variations-possible-combinations-form" class="iwbvel-variations-possible-combinations-rows">
    <?php
    if (!empty($combinations)) :
        $i = 0;
        foreach ($combinations as $combination) :
            if (!empty($combination)) :
    ?>
                <div class="iwbvel-variations-possible-combinations-row">
                    <input type="checkbox" data-name="enable" value="yes" checked="checked">
                    <?php
                    $j = 0;
                    foreach ($combination as $attribute_name => $term_slug) :
                        $term = get_term_by('slug', $term_slug, $attribute_name);
                        if (!($term instanceof \WP_Term)) {
                            continue;
                        }
                    ?>
                        <input type="hidden" class="iwbvel-variations-possible-combination-item" data-attribute="<?php echo esc_attr($attribute_name); ?>" data-term-slug="<?php echo esc_attr($term_slug); ?>" data-term-id="<?php echo intval($term->term_id); ?>">
                        <span>
                            <?php
                            echo esc_html($term->name);
                            if (($j + 1) < count($combination)) {
                                echo " | ";
                            }
                            $j++
                            ?>
                        </span>
                    <?php endforeach; ?>
                    <button type="button" class="iwbvel-button-flat iwbvel-variations-possible-combination-sort-button"><i class="iwbvel-icon-move"></i></button>
                </div>
    <?php
            endif;
            $i++;
        endforeach;
    endif;
    ?>
</form>