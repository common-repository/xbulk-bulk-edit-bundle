<?php

namespace wbebl\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Plugin
{
    public static function is_installed($plugin_file)
    {
        return file_exists(WBEBL_PLUGINS_DIR . $plugin_file);
    }

    public static function get_status($plugin_file)
    {
        if (self::is_installed($plugin_file)) {
            if (is_plugin_active($plugin_file)) {
                $output = '<span class="active">' . esc_html__("Active Module", 'xbulk-bulk-edit-bundle') . '</span>';
            } else {
                $output = '<span class="inactive">' . esc_html__("Inactive Module", 'xbulk-bulk-edit-bundle') . '</span>';
            }
        } else {
            $output = '<span class="not-installed">' . esc_html__("Not Installed", 'xbulk-bulk-edit-bundle') . '</span>';
        }

        return $output;
    }

    public static function get_action_button($data)
    {
        if (self::is_installed($data['plugin'])) {
            if (is_plugin_active($data['plugin'])) {
                $output = '<button type="submit" class="deactivate" name="deactivate" value="' . sanitize_text_field($data['plugin']) . '">' . esc_html__("Deactivate", 'xbulk-bulk-edit-bundle') . '</button>';
            } else {
                $output = '<button type="submit" class="activate" name="activate" value="' . sanitize_text_field($data['plugin']) . '">' . esc_html__("Activate", 'xbulk-bulk-edit-bundle') . '</button>';
            }
        } else {
            $buy_link = (!empty($data['buy_link'])) ? esc_url($data['buy_link']) : 'javascript:;';
            $output = '<a href="' . $buy_link . '">' . esc_html__("Buy Add-On", 'xbulk-bulk-edit-bundle') . '</a>';
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
                'plugin' => 'ithemeland-woocommerce-bulk-product-editing-pro/ithemeland-woocommerce-bulk-product-editing-pro.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/products.jpg"),
                'label' => 'WooCommerce Products',
                'download_link' => '#',
                'version' => '1.0.3',
                'license' => true,
                'buy_link' => "https://ithemelandco.com/plugins/woocommerce-bulk-product-editing",
                'landing_page' => 'https://ithemelandco.com/plugins/woocommerce-bulk-product-editing',
            ],
            [
                'plugin' => 'ithemeland-woocommerce-bulk-variations-editing-pro/ithemeland-woocommerce-bulk-variations-editing-pro.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/products.jpg"),
                'label' => 'WooCommerce Variations',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => true,
                'buy_link' => "https://ithemelandco.com/plugins/woocommerce-bulk-variation-editing",
                'landing_page' => 'https://ithemelandco.com/plugins/woocommerce-bulk-variation-editing',
            ],
            [
                'plugin' => 'ithemeland-wordpress-bulk-posts-editing-pro/ithemeland-wordpress-bulk-posts-editing-pro.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/posts.jpg"),
                'label' => 'Posts,Pages and Custom Post Types',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => true,
                'buy_link' => "https://ithemelandco.com/plugins/wordpress-bulk-posts-editing",
                'landing_page' => 'https://ithemelandco.com/plugins/wordpress-bulk-posts-editing',
            ],
            [
                'plugin' => 'ithemeland-woocommerce-bulk-orders-editing-pro/ithemeland-woocommerce-bulk-orders-editing-pro.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/orders.jpg"),
                'label' => 'WooCommerce Orders',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => true,
                'buy_link' => "https://ithemelandco.com/plugins/woocommerce-bulk-orders-editing",
                'landing_page' => 'https://ithemelandco.com/plugins/woocommerce-bulk-orders-editing',
            ],
            [
                'plugin' => 'ithemeland-woocommerce-bulk-coupons-editing-pro/ithemeland-woocommerce-bulk-coupons-editing-pro.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/coupons.jpg"),
                'label' => 'WooCommerce Coupons',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => true,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/customers.jpg"),
                'label' => 'WooCommerce Customers',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/users.jpg"),
                'label' => 'WordPress Users',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/edd.jpg"),
                'label' => 'Easy Digital Downloads',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/categories.jpg"),
                'label' => 'Categories / Tags / Taxonomies',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/media.jpg"),
                'label' => 'Media Library',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/comments.jpg"),
                'label' => 'Comments and Reviews',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
            [
                'plugin' => 'post-page-bulk-edit/post-page-bulk-edit.php',
                'image_link' => esc_url(WBEBL_IMAGES_URL . "sub_system/frontend.jpg"),
                'label' => 'Universal Frontend',
                'download_link' => '#',
                'version' => '1.0.0',
                'license' => false,
                'landing_page' => '#',
            ],
        ];
    }
}
