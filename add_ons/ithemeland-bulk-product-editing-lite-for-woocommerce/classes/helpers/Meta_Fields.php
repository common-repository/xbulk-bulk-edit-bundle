<?php

namespace wcbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\repositories\Meta_Field;

class Meta_Fields
{
    public static function get_default_meta_key()
    {
        return [
            '_edit_lock',
            '_edit_last',
            '_visibility',
            '_sku',
            '_price',
            '_tax_status',
            '_regular_price',
            '_sale_price',
            '_button_text',
            '_sale_price_dates_from',
            '_sale_price_dates_to',
            'total_sales',
            '_manage_stock',
            '_stock',
            '_stock_status',
            '_tax_class',
            '_backorders',
            '_low_stock_amount',
            '_width',
            '_weight',
            '_length',
            '_height',
            '_sold_individually',
            '_upsell_ids',
            '_crosssell_ids',
            '_purchase_note',
            '_default_attributes',
            '_downloadable',
            '_download_limit',
            '_download_expiry',
            '_product_attributes',
            '_virtual',
            '_featured',
            '_downloadable_files',
            '_wc_rating_count',
            '_wc_average_rating',
            '_wc_review_count',
            '_variation_description',
            'image_id',
            '_file_paths',
            '_product_image_gallery',
            '_product_version',
            '_wp_old_slug',
        ];
    }

    public static function get_default_taxonomies()
    {
        return [
            'product_type',
            'product_visibility',
            'product_shipping_class',
        ];
    }

    public static function remove_default_meta_keys(array $meta_keys)
    {
        return array_diff($meta_keys, self::get_default_meta_key());
    }

    public static function get_meta_field_type($main_type, $sub_type)
    {
        $type = '';
        switch ($main_type) {
            case Meta_Field::TEXTINPUT:
                switch ($sub_type) {
                    case Meta_Field::STRING_TYPE:
                        $type = 'text';
                        break;
                    case Meta_Field::NUMBER:
                        $type = 'numeric';
                        break;
                }
                break;
            case Meta_Field::TEXTAREA:
                $type = 'textarea';
                break;
            case Meta_Field::ARRAY_TYPE:
                $type = 'text';
                break;
            case Meta_Field::CHECKBOX:
                $type = 'checkbox';
                break;
            case Meta_Field::CALENDAR:
                $type = 'date';
                break;
        }
        return $type;
    }

    public static function get_taxonomy_type($taxonomy_name)
    {
        return (strpos($taxonomy_name, 'pa_') !== false) ? 'attribute' : 'taxonomy';
    }
}
