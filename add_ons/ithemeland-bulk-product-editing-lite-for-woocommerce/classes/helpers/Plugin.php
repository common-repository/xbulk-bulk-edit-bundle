<?php

namespace wcbef\classes\helpers;

use wcbef\classes\bootstrap\WCBEF_EL;

class Plugin
{
    public static function is_installed(string $plugin_file)
    {
        return file_exists(WCBEF_PLUGINS_DIR . $plugin_file);
    }

    public static function get_status(string $plugin_file)
    {
        if (self::is_installed($plugin_file)) {
            if (is_plugin_active($plugin_file)) {
                $output = '<span class="active">' . __("Active Module", 'woocommerce-bulk-edit-free') . '</span>';
            } else {
                $output = '<span class="inactive">' . __("Inactive Module", 'woocommerce-bulk-edit-free') . '</span>';
            }
        } else {
            $output = '<span class="not-installed">' . __("Not Installed", 'woocommerce-bulk-edit-free') . '</span>';
        }

        return $output;
    }

    public static function get_action_button(array $data)
    {
        if (self::is_installed($data['plugin'])) {
            if (is_plugin_active($data['plugin'])) {
                $output = '<button type="submit" class="deactivate" name="deactivate" value="' . sanitize_text_field($data['plugin']) . '">' . __("Deactivate", 'woocommerce-bulk-edit-free') . '</button>';
            } else {
                $output = '<button type="submit" class="activate" name="activate" value="' . sanitize_text_field($data['plugin']) . '">' . __("Activate", 'woocommerce-bulk-edit-free') . '</button>';
            }
        } else {
            $buy_link = (!empty($data['buy_link'])) ? esc_url($data['buy_link']) : 'javascript:;';
            $output = '<a href="' . $buy_link . '">' . __("Buy Add-On", 'woocommerce-bulk-edit-free') . '</a>';
        }

        return $output;
    }

    public static function has_active_plugin($plugin_Key)
    {
        return (defined(strtoupper($plugin_Key) . '_NAME'));
    }

    public static function get_add_ons()
    {
        return [
            [
                'plugin' => 'woocommerce-bulk-edit-free/woocommerce-bulk-edit-free.php',
                'image_link' => esc_url(WCBEF_IMAGES_URL . "sub_system/products.jpg"),
                'label' => 'WooCommerce Products',
                'download_link' => '#',
                'version' => '1.0.3',
                'license' => WCBEF_EL::woo_products(),
                'buy_link' => "https://ithemelandco.com/plugins/woocommerce-bulk-product-editing",
                'landing_page' => 'https://ithemelandco.com/plugins/woocommerce-bulk-product-editing',
            ],
            [
                'plugin' => 'ithemeland-wordpress-bulk-posts-editing/ithemeland-wordpress-bulk-posts-editing.php',
                'image_link' => esc_url(WCBEF_IMAGES_URL . "sub_system/posts.jpg"),
                'label' => 'Posts,Pages and Custom Post Types',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => WCBEF_EL::wp_posts(),
                'buy_link' => "https://ithemelandco.com/plugins/wordpress-bulk-posts-editing",
                'landing_page' => 'https://ithemelandco.com/plugins/wordpress-bulk-posts-editing',
            ],
            [
                'plugin' => 'ithemeland-woocommerce-bulk-orders-editing/ithemeland-woocommerce-bulk-orders-editing.php',
                'image_link' => esc_url(WCBEF_IMAGES_URL . "sub_system/orders.jpg"),
                'label' => 'WooCommerce Orders',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => WCBEF_EL::woo_orders(),
                'buy_link' => "https://ithemelandco.com/plugins/woocommerce-bulk-orders-editing",
                'landing_page' => 'https://ithemelandco.com/plugins/woocommerce-bulk-orders-editing',
            ],
            [
                'plugin' => 'ithemeland-woocommerce-bulk-coupons-editing/ithemeland-woocommerce-bulk-coupons-editing.php',
                'image_link' => esc_url(WCBEF_IMAGES_URL . "sub_system/coupons.jpg"),
                'label' => 'WooCommerce Coupons',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => WCBEF_EL::woo_coupons(),
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WCBEF_IMAGES_URL . "sub_system/customers.jpg"),
                'label' => 'WooCommerce Customers',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WCBEF_IMAGES_URL . "sub_system/users.jpg"),
                'label' => 'WordPress Users',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WCBEF_IMAGES_URL . "sub_system/edd.jpg"),
                'label' => 'Easy Digital Downloads',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WCBEF_IMAGES_URL . "sub_system/categories.jpg"),
                'label' => 'Categories / Tags / Taxonomies',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WCBEF_IMAGES_URL . "sub_system/media.jpg"),
                'label' => 'Media Library',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WCBEF_IMAGES_URL . "sub_system/comments.jpg"),
                'label' => 'Comments and Reviews',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WCBEF_IMAGES_URL . "sub_system/frontend.jpg"),
                'label' => 'Universal Frontend',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
        ];
    }
}
