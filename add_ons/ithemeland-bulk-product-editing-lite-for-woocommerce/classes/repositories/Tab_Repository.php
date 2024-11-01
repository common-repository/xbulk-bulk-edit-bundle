<?php

namespace wcbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Generator;
use wcbel\classes\helpers\Meta_Field as Meta_Field_Helper;
use wcbel\classes\helpers\Operator;
use wcbel\classes\repositories\ACF_Plugin_Fields;
use wcbel\classes\helpers\Product_Helper;

class Tab_Repository
{
    private $product_statuses;
    private $visibility_items;
    private $users;
    private $tax_classes;
    private $stock_statuses;
    private $product_types;
    private $backorders;
    private $shipping_classes;
    private $taxonomies;
    private $taxonomy_groups;
    private $meta_fields;
    private $acf_fields;
    private $meta_field_repository;

    public function __construct()
    {
        $this->meta_field_repository = new Meta_Field();

        $this->visibility_items = wc_get_product_visibility_options();
        $this->meta_fields = $this->meta_field_repository->get();
        $acf = ACF_Plugin_Fields::get_instance('product');
        $this->acf_fields = $acf->get_fields();

        $product_repository = Product::get_instance();
        $this->product_statuses = $product_repository->get_product_statuses();
        $this->tax_classes = ['' => esc_html__('Select', 'ithemeland-bulk-product-editing-lite-for-woocommerce')] + $product_repository->get_tax_classes();
        $this->taxonomies = $product_repository->get_grouped_taxonomies();
        $this->taxonomy_groups = $product_repository->get_taxonomy_groups();
        $this->shipping_classes = $product_repository->get_shipping_classes();
        $this->stock_statuses = wc_get_product_stock_status_options();
        $this->backorders = wc_get_product_backorder_options();
        $this->product_types = wc_get_product_types();

        // users
        $this->users = [];
        $users = get_users();
        if (!empty($users)) {
            foreach ($users as $user) {
                if ($user instanceof \WP_User) {
                    $this->users[$user->ID] = $user->display_name;
                }
            }
        }
    }

    // bulk edit form
    public function get_bulk_edit_form_tabs_title()
    {
        return [
            'general' => esc_html__("General", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'categories_tags_taxonomies' => esc_html__("Categories/Tags/Taxonomies", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'pricing' => esc_html__("Pricing", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'shipping' => esc_html__("Shipping", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'stock' => esc_html__("Stock", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'type' => esc_html__("Type", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'custom_fields' => esc_html__("Custom Fields", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
        ];
    }

    public function get_bulk_edit_form_tabs_content()
    {
        $custom_fields = $this->get_bulk_edit_custom_fields();
        $taxonomies = $this->get_bulk_edit_taxonomies_fields();

        $regular_price_operator = Operator::edit_number() + Operator::edit_regular_price();
        $sale_price_operator = Operator::edit_number() + Operator::edit_sale_price();
        $round_item_options = ['' => esc_html__('Round item', 'ithemeland-bulk-product-editing-lite-for-woocommerce')] + Operator::round_items();

        return [
            'general' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'selected wcbel-tab-content-item',
                    'data-content' => 'general'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'title' => [
                        'wrap_attributes' => 'data-name="title" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-title'], esc_html__('Product title', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-product-title-operator',
                                'data-field' => 'operator',
                                'title' => esc_html__('Select Operator', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ], Operator::edit_text([
                                'text_remove_duplicate' => esc_html__('Remove Duplicate', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ])),
                            Generator::input_field([
                                'type' => 'text',
                                'id' => 'wcbel-bulk-edit-form-product-title',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Title ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::select_field([
                                'data-field' => 'variable',
                                'class' => 'wcbel-bulk-edit-form-variable',
                            ], Product_Helper::get_text_variable_options())
                        ]
                    ],
                    'slug' => [
                        'wrap_attributes' => 'data-name="slug" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-slug'], esc_html__('Product slug', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Product slug ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'sku' => [
                        'wrap_attributes' => 'data-name="sku" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-sku'], esc_html__('Product SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Product SKU ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'description' => [
                        'wrap_attributes' => 'data-name="description" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-description'], esc_html__('Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Description ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'short_description' => [
                        'wrap_attributes' => 'data-name="short_description" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-short-description'], esc_html__('Short description', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::textarea_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Short description ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'purchase_note' => [
                        'wrap_attributes' => 'data-name="purchase_note" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-purchase-note'], esc_html__('Purchase note', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Purchase note ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'menu_order' => [
                        'wrap_attributes' => 'data-name="menu_order" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-menu-order'], esc_html__('Menu order', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::input_field([
                                'type' => 'number',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Menu order ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'sold_individually' => [
                        'wrap_attributes' => 'data-name="sold_individually" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-sold-individually'], esc_html__('Menu order', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                            ], [
                                'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            ], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'reviews_allowed' => [
                        'wrap_attributes' => 'data-name="reviews_allowed" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-enable-reviews'], esc_html__('Enable reviews', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                            ], [
                                'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            ], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'status' => [
                        'wrap_attributes' => 'data-name="status" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-product-status'], esc_html__('Product status', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'id' => 'wcbel-bulk-edit-form-product-product-status',
                                'data-field' => 'value',
                            ], $this->product_statuses, true),
                        ]
                    ],
                    'catalog_visibility' => [
                        'wrap_attributes' => 'data-name="catalog_visibility" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-catalog-visibility'], esc_html__('Catalog visibility', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                            ], $this->visibility_items, true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'date_created' => [
                        'wrap_attributes' => 'data-name="date_created" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-date-created'], esc_html__('Date', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::input_field([
                                'class' => 'wcbel-input-md',
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Date ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'post_author' => [
                        'wrap_attributes' => 'data-name="post_author" data-type="wp_posts_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-author'], esc_html__('Author', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                            ], $this->users, true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'image_id' => [
                        'wrap_attributes' => 'data-name="image_id" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field([], esc_html__('Image', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::button(esc_html__('Choose image', 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'type' => 'button',
                                'disabled' => 'disabled',
                                'class' => 'wcbel-button wcbel-button-blue wcbel-float-left',
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'gallery_image_ids' => [
                        'wrap_attributes' => 'data-name="gallery_image_ids" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field([], esc_html__('Gallery', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::button(esc_html__('Choose images', 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'type' => 'button',
                                'disabled' => 'disabled',
                                'class' => 'wcbel-button wcbel-button-blue wcbel-float-left',
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                ],
            ],
            'categories_tags_taxonomies' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'categories_tags_taxonomies'
                ]),
                'fields_top' => (!empty($taxonomies['top_alert'])) ? $taxonomies['top_alert'] : '',
                'wrapper_end' => Generator::div_field_end(),
                'fields' => (!empty($taxonomies['fields'])) ? $taxonomies['fields'] : []
            ],
            'pricing' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'pricing'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'regular_price' => [
                        'wrap_attributes' => 'data-name="regular_price" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-regular-price'], esc_html__('Regular price', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-regular-price-operator',
                                'data-field' => 'operator',
                            ], $regular_price_operator),
                            Generator::input_field([
                                'type' => 'number',
                                'id' => 'wcbel-bulk-edit-form-regular-price',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Regular price ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::select_field([
                                'data-field' => 'round',
                                'id' => 'wcbel-bulk-edit-form-regular-price-round-item',
                                'title' => esc_html__('Select round item', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ], $round_item_options),
                            Generator::span_field(esc_html__('Note: In formula, the current value known as X. Ex: (X+10)*10% :: (The current value+10) * 10%', 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-description-full-width',
                            ])
                        ]
                    ],
                    'sale_price' => [
                        'wrap_attributes' => 'data-name="sale_price" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-sale-price'], esc_html__('Sale price', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-sale-price-operator',
                                'data-field' => 'operator',
                            ], $sale_price_operator),
                            Generator::input_field([
                                'type' => 'number',
                                'id' => 'wcbel-bulk-edit-form-sale-price',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Sale price ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::select_field([
                                'data-field' => 'round',
                                'id' => 'wcbel-bulk-edit-form-sale-price-round-item',
                                'title' => esc_html__('Select round item', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ], $round_item_options),
                            Generator::span_field(esc_html__('Note: In formula, the current value known as X. Ex: (X+10)*10% :: (The current value+10) * 10%', 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-description-full-width',
                            ])
                        ]
                    ],
                    'date_on_sale_from' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-sale-date-from'], esc_html__('Sale date from', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::input_field([
                                'type' => 'text',
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Sale date from ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'date_on_sale_to' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-sale-date-to'], esc_html__('Sale date to', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::input_field([
                                'type' => 'text',
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Sale date to ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'tax_status' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-tax-status'], esc_html__('Tax status', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                            ], [
                                '' => esc_html__('Select', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'tax_class' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-tax-class'], esc_html__('Tax class', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                            ], $this->tax_classes),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                ]
            ],
            'shipping' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'shipping'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'shipping_class' => [
                        'wrap_attributes' => 'data-name="shipping_class" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-shipping-class'], esc_html__('Shipping class', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-shipping-class',
                                'data-field' => 'value',
                                'class' => 'wcbel-input-md'
                            ], $this->shipping_classes, true)
                        ]
                    ],
                    'width' => [
                        'wrap_attributes' => 'data-name="width" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-width'], esc_html__('Width', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-width-operator',
                                'data-field' => 'operator',
                            ], Operator::edit_number()),
                            Generator::input_field([
                                'type' => 'number',
                                'id' => 'wcbel-bulk-edit-form-width',
                                'data-field' => 'value',
                            ]),
                            Generator::span_field(esc_html__('Note: In formula, the current value known as X. Ex: (X+10)*10% :: (The current value+10) * 10%', 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-description-full-width',
                            ])
                        ]
                    ],
                    'height' => [
                        'wrap_attributes' => 'data-name="height" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-height'], esc_html__('Height', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-height-operator',
                                'data-field' => 'operator',
                            ], Operator::edit_number()),
                            Generator::input_field([
                                'type' => 'number',
                                'id' => 'wcbel-bulk-edit-form-height',
                                'data-field' => 'value',
                            ]),
                            Generator::span_field(esc_html__('Note: In formula, the current value known as X. Ex: (X+10)*10% :: (The current value+10) * 10%', 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-description-full-width',
                            ])
                        ]
                    ],
                    'length' => [
                        'wrap_attributes' => 'data-name="length" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-length'], esc_html__('Length', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-length-operator',
                                'data-field' => 'operator',
                            ], Operator::edit_number()),
                            Generator::input_field([
                                'type' => 'number',
                                'id' => 'wcbel-bulk-edit-form-length',
                                'data-field' => 'value',
                            ]),
                            Generator::span_field(esc_html__('Note: In formula, the current value known as X. Ex: (X+10)*10% :: (The current value+10) * 10%', 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-description-full-width',
                            ])
                        ]
                    ],
                    'weight' => [
                        'wrap_attributes' => 'data-name="weight" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-weight'], esc_html__('Weight', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-weight-operator',
                                'data-field' => 'operator',
                            ], Operator::edit_number()),
                            Generator::input_field([
                                'type' => 'number',
                                'id' => 'wcbel-bulk-edit-form-weight',
                                'data-field' => 'value',
                            ]),
                            Generator::span_field(esc_html__('Note: In formula, the current value known as X. Ex: (X+10)*10% :: (The current value+10) * 10%', 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-description-full-width',
                            ])
                        ]
                    ],
                ]
            ],
            'stock' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'stock'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'manage_stock' => [
                        'wrap_attributes' => 'data-name="manage_stock" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-manage-stock'], esc_html__('Manage stock', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'id' => 'wcbel-bulk-edit-form-manage-stock',
                                'data-field' => 'value',
                            ], [
                                'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            ], true),
                        ]
                    ],
                    'stock_status' => [
                        'wrap_attributes' => 'data-name="stock_status" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-stock-status'], esc_html__('Stock status', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                            ], $this->stock_statuses, true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'stock_quantity' => [
                        'wrap_attributes' => 'data-name="stock_quantity" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-stock-quantity'], esc_html__('Stock quantity', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-stock-quantity-operator',
                                'data-field' => 'operator',
                            ], Operator::edit_number()),
                            Generator::input_field([
                                'type' => 'number',
                                'id' => 'wcbel-bulk-edit-form-stock-quantity',
                                'data-field' => 'value',
                            ]),
                        ]
                    ],
                    'backorders' => [
                        'wrap_attributes' => 'data-name="backorders" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-backorders'], esc_html__('Allow backorders', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                            ], $this->backorders, true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                ]
            ],
            'type' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'type'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'product_type' => [
                        'wrap_attributes' => 'data-name="product_type" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-type'], esc_html__('Product type', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                            ], $this->product_types, true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'featured' => [
                        'wrap_attributes' => 'data-name="featured" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-featured'], esc_html__('Featured', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'id' => 'wcbel-bulk-edit-form-featured',
                                'data-field' => 'value',
                            ], [
                                'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            ], true),
                        ]
                    ],
                    'virtual' => [
                        'wrap_attributes' => 'data-name="virtual" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-virtual'], esc_html__('Virtual', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                            ], [
                                'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            ], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'downloadable' => [
                        'wrap_attributes' => 'data-name="downloadable" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-downloadable'], esc_html__('Downloadable', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                            ], [
                                'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            ], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'download_limit' => [
                        'wrap_attributes' => 'data-name="download_limit" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-download-limit'], esc_html__('Download limit', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::input_field([
                                'type' => 'number',
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Download limit ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'download_expiry' => [
                        'wrap_attributes' => 'data-name="download_expiry" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-download-expiry'], esc_html__('Download expiry', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::input_field([
                                'type' => 'number',
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Download expiry ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    '_product_url' => [
                        'wrap_attributes' => 'data-name="_product_url" data-type="meta_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-product-url'], esc_html__('Product url', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::input_field([
                                'type' => 'text',
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Product url ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    '_button_text' => [
                        'wrap_attributes' => 'data-name="_button_text" data-type="meta_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-button-text'], esc_html__('Button text', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::input_field([
                                'type' => 'text',
                                'class' => 'wcbel-input-md',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Button text ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ])
                        ]
                    ],
                    'upsell_ids' => [
                        'wrap_attributes' => 'data-name="upsell_ids" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-upsells'], esc_html__('Upsells', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-upsells-operator',
                                'data-field' => 'operator',
                            ], Operator::edit_taxonomy()),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-upsells',
                                'data-field' => 'value',
                                'multiple' => 'multiple',
                                'class' => 'wcbel-get-products-ajax wcbel-select2'
                            ], []),
                        ]
                    ],
                    'cross_sell_ids' => [
                        'wrap_attributes' => 'data-name="cross_sell_ids" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wcbel-bulk-edit-form-cross-sells'], esc_html__('Cross-Sells', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-cross-sells-operator',
                                'data-field' => 'operator',
                            ], Operator::edit_taxonomy()),
                            Generator::select_field([
                                'id' => 'wcbel-bulk-edit-form-cross-sells',
                                'data-field' => 'value',
                                'multiple' => 'multiple',
                                'class' => 'wcbel-get-products-ajax wcbel-select2'
                            ], []),
                        ]
                    ],
                ]
            ],
            'custom_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'custom_fields'
                ]),
                'fields_top' => (!empty($custom_fields['top_alert'])) ? $custom_fields['top_alert'] : '',
                'wrapper_end' => Generator::div_field_end(),
                'fields' => (!empty($custom_fields['fields'])) ? $custom_fields['fields'] : []
            ],
        ];
    }

    private function get_bulk_edit_custom_fields()
    {
        $output['top_alert'] = [];
        $output['fields'] = [];

        if (!empty($this->meta_fields) && is_array($this->meta_fields)) {
            foreach ($this->meta_fields as $meta_field) {
                $field_id = 'wcbel-bulk-edit-form-custom-field-' . $meta_field['key'];
                $output['fields'][$meta_field['key']]['wrap_attributes'] = "data-name='{$meta_field['key']}' data-type='meta_field'";
                $output['fields'][$meta_field['key']]['html'][] = Generator::label_field(['for' => $field_id], $meta_field['title']);
                $meta_field_rendered = $this->bulk_edit_meta_field_render($meta_field, $field_id);

                if (!empty($meta_field_rendered['operator_field'])) {
                    $output['fields'][$meta_field['key']]['html'][] = $meta_field_rendered['operator_field'];
                }
                $output['fields'][$meta_field['key']]['html'][] = $meta_field_rendered['value_field'];
            }
        } else {
            $output['top_alert'] = [
                Generator::div_field_start([
                    'class' => 'wcbel-alert wcbel-alert-warning',
                ]),
                Generator::span_field(esc_html__('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                Generator::div_field_end()
            ];
        }

        return $output;
    }

    private function get_bulk_edit_taxonomies_fields()
    {
        $output['top_alert'] = [];
        $output['fields'] = [];

        if (!empty($this->taxonomies) && !empty($this->taxonomy_groups)) {
            foreach ($this->taxonomy_groups as $group_key => $group_label) {
                $output['fields'][$group_key]['header'] = [
                    Generator::strong_field($group_label),
                    Generator::hr()
                ];
                if (!empty($this->taxonomies[$group_key])) {
                    foreach ($this->taxonomies[$group_key] as $name => $taxonomy) {
                        $taxonomy_terms = [];
                        if (!empty($taxonomy['terms'])) {
                            foreach ($taxonomy['terms'] as $term) {
                                $term_key = ($name == 'product_tag') ? urldecode($term->slug) : $term->term_id;
                                $taxonomy_terms[$term_key] = $term->name;
                            }
                        }
                        $tax_type = esc_html(\wcbel\classes\helpers\Meta_Fields::get_taxonomy_type($name));
                        if (in_array($name, ['product_cat', 'product_tag'])) {
                            $output['fields'][$name]['wrap_attributes'] = "data-type='taxonomy' data-name='" . esc_attr($name) . "'";
                            $output['fields'][$name]['html'][] = Generator::label_field(['for' => "wcbel-bulk-edit-form-product-attr-{$name}"], esc_html($taxonomy['label']));
                            $output['fields'][$name]['html'][] = Generator::select_field([
                                'data-field' => 'operator',
                                'id' => "wcbel-bulk-edit-form-product-attr-operator-{$name}"
                            ], Operator::edit_taxonomy());
                            $output['fields'][$name]['html'][] = Generator::select_field([
                                'class' => 'wcbel-select2',
                                'multiple' => 'multiple',
                                'data-field' => 'value',
                                'data-placeholder' => esc_html__('Select ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                'id' => "wcbel-bulk-edit-form-product-attr-{$name}"
                            ], $taxonomy_terms);
                        } else {
                            $output['fields'][$name]['wrap_attributes'] = "data-type='taxonomy'";
                            $output['fields'][$name]['html'][] = Generator::label_field(['for' => ""], esc_html($taxonomy['label']));
                            $output['fields'][$name]['html'][] = Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_taxonomy());
                            $output['fields'][$name]['html'][] = Generator::select_field([
                                'class' => 'wcbel-select2',
                                'disabled' => 'disabled',
                            ], ['' => 'Select']);
                            $output['fields'][$name]['html'][] = Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ]);
                        }

                        if ($tax_type == 'attribute') {
                            $output['fields'][$name]['html'][] = Generator::div_field_start([
                                'style' => 'width: 100%; float: left; padding: 8px 0 10px 180px; box-sizing: border-box;'
                            ]);

                            $output['fields'][$name]['html'][] = Generator::label_field([
                                'for' => "wcbel-bulk-edit-form-product-attr-is-visible-{$name}",
                                'style' => 'width: auto; padding-right: 8px; line-height: 28px; font-size: 13px;'
                            ], __('Visible on Product Page', 'ithemeland-bulk-product-editing-lite-for-woocommerce'));
                            $output['fields'][$name]['html'][] = Generator::select_field([
                                'disabled' => 'disabled',
                                'style' => 'width: auto; height: 28px; font-size: 13px;'
                            ], [
                                '' => __('Current Value', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                'yes' => __('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                'no' => __('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]);
                            $output['fields'][$name]['html'][] = Generator::label_field([
                                'for' => "wcbel-bulk-edit-form-product-attr-for-variations-{$name}",
                                'style' => 'width: auto; padding-right: 8px; line-height: 28px; font-size: 13px;'
                            ], __('Used for variations', 'ithemeland-bulk-product-editing-lite-for-woocommerce'));
                            $output['fields'][$name]['html'][] = Generator::select_field([
                                'disabled' => 'disabled',
                                'style' => 'width: auto; height: 28px; font-size: 13px;'
                            ], [
                                '' => __('Current Value', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                'yes' => __('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                'no' => __('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                            ]);
                            $output['fields'][$name]['html'][] = Generator::div_field_end();
                        }
                    }

                    $output['fields'][$group_key]['footer'] = [
                        Generator::div_field_start(['class' => 'wcbel-mb20']),
                        Generator::div_field_end()
                    ];
                }
            }
        } else {
            $output['top_alert'] = [
                Generator::div_field_start([
                    'class' => 'wcbel-alert wcbel-alert-warning',
                ]),
                Generator::span_field(esc_html__('Not found !', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                Generator::div_field_end()
            ];
        }

        return $output;
    }

    // Filter From
    public function get_filter_form_tabs_title()
    {
        return [
            'filter_general' => esc_html__("General", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'filter_categories_tags_taxonomies' => esc_html__("Categories/Tags/Taxonomies", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'filter_pricing' => esc_html__("Pricing", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'filter_shipping' => esc_html__("Shipping", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'filter_stock' => esc_html__("Stock", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'filter_type' => esc_html__("Type", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'filter_custom_fields' => esc_html__("Custom Fields", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
        ];
    }

    public function get_filter_form_tabs_content()
    {
        $custom_fields = $this->get_filter_custom_fields();
        $taxonomies = $this->get_filter_taxonomies_fields();

        return [
            'filter_general' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'selected wcbel-tab-content-item',
                    'data-content' => 'filter_general'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'product_ids' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-ids'], esc_html__('Product ID(s)', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'id' => 'wcbel-filter-form-product-ids-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ], [
                            'exact' => esc_html__('Exact', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wcbel-filter-form-product-ids',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('for example: 1,2,3 or 1-10 or 1,2,3|10-20', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                    ],
                    'product_title' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-title'], esc_html__('Product title', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'id' => 'wcbel-filter-form-product-title-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wcbel-filter-form-product-title',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Title ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                    ],
                    'product_content' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-content'], esc_html__('Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'id' => 'wcbel-filter-form-product-content-operator',
                            'data-field' => 'operator',
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wcbel-filter-form-product-content',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Description ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                    ],
                    'product_excerpt' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-excerpt'], esc_html__('Short description', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_text()),
                        Generator::textarea_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Short description ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                    'product_slug' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-slug'], esc_html__('Product slug', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Product slug ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                    'product_sku' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-sku'], esc_html__('Product SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Product SKU ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                    'product_url' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-url'], esc_html__('Product url', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Product url ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                    'date_created' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-date-created-from'], esc_html__('Product date', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft wcbel-datepicker wcbel-date-from',
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft wcbel-datepicker',
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                ],
            ],
            'filter_categories_tags_taxonomies' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'filter_categories_tags_taxonomies'
                ]),
                'fields_top' => (!empty($taxonomies['top_alert'])) ? $taxonomies['top_alert'] : '',
                'wrapper_end' => Generator::div_field_end(),
                'fields' => (!empty($taxonomies['fields'])) ? $taxonomies['fields'] : []
            ],
            'filter_pricing' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'filter_pricing'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'regular_price' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-regular-price-from'], esc_html__('Regular price', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'from',
                            'id' => 'wcbel-filter-form-product-regular-price-from',
                            'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'to',
                            'id' => 'wcbel-filter-form-product-regular-price-to',
                            'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                    ],
                    'sale_price' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-sale-price-from'], esc_html__('Sale price', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'from',
                            'id' => 'wcbel-filter-form-product-sale-price-from',
                            'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'to',
                            'id' => 'wcbel-filter-form-product-sale-price-to',
                            'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                    ],
                    'date_on_sale_from' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-sale-price-date-from'], esc_html__('Sale date from', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::input_field([
                            'type' => 'text',
                            'class' => 'wcbel-input-md wcbel-datepicker wcbel-date-from',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Sale date from ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                    'date_on_sale_to' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-sale-price-date-to'], esc_html__('Sale date to', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::input_field([
                            'type' => 'text',
                            'class' => 'wcbel-input-md',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Sale date to ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                ]
            ],
            'filter_shipping' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'filter_shipping'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'width' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-width-from'], esc_html__('Width', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'from',
                            'id' => 'wcbel-filter-form-product-width-from',
                            'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'to',
                            'id' => 'wcbel-filter-form-product-width-to',
                            'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                    ],
                    'height' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-height-from'], esc_html__('Height', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'from',
                            'id' => 'wcbel-filter-form-product-height-from',
                            'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'to',
                            'id' => 'wcbel-filter-form-product-height-to',
                            'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                    ],
                    'length' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-length-from'], esc_html__('Length', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'from',
                            'id' => 'wcbel-filter-form-product-length-from',
                            'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'to',
                            'id' => 'wcbel-filter-form-product-length-to',
                            'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                    ],
                    'weight' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-weight-from'], esc_html__('Weight', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'from',
                            'id' => 'wcbel-filter-form-product-weight-from',
                            'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'to',
                            'id' => 'wcbel-filter-form-product-weight-to',
                            'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                    ],
                ]
            ],
            'filter_stock' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'filter_stock'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'manage_stock' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-manage-stock'], esc_html__('Manage stock', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'class' => 'wcbel-input-md',
                            'id' => 'wcbel-filter-form-manage-stock',
                            'data-field' => 'value',
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                        ], true),
                    ],
                    'stock_quantity' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-stock-quantity-from'], esc_html__('Stock quantity', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'from',
                            'id' => 'wcbel-filter-form-stock-quantity-from',
                            'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'data-field' => 'to',
                            'id' => 'wcbel-filter-form-stock-quantity-to',
                            'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                    ],
                    'stock_status' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-stock-status'], esc_html__('Stock status', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'class' => 'wcbel-input-md',
                            'disabled' => 'disabled',
                        ], $this->stock_statuses, true),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                    'backorders' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-backorders'], esc_html__('Allow backorders', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'class' => 'wcbel-input-md',
                            'disabled' => 'disabled',
                        ], $this->backorders, true),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                ]
            ],
            'filter_type' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'filter_type'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'product_type' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-type'], esc_html__('Product type', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'class' => 'wcbel-input-md',
                            'id' => 'wcbel-filter-form-product-type',
                            'data-field' => 'value',
                        ], $this->product_types, true),
                    ],
                    'product_status' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-status'], esc_html__('Product status', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'class' => 'wcbel-input-md',
                            'id' => 'wcbel-filter-form-product-status',
                            'data-field' => 'value',
                        ], $this->product_statuses, true),
                    ],
                    'featured' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-featured'], esc_html__('Featured', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'class' => 'wcbel-input-md',
                            'disabled' => 'disabled',
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                        ], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                    'downloadable' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-downloadable'], esc_html__('Downloadable', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'class' => 'wcbel-input-md',
                            'disabled' => 'disabled',
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                        ], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                    'sold_individually' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-sold-individually'], esc_html__('Sold individually', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'class' => 'wcbel-input-md',
                            'disabled' => 'disabled',
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                        ], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                    'author' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-author'], esc_html__('By author', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'class' => 'wcbel-input-md',
                            'id' => 'wcbel-filter-form-author',
                            'data-field' => 'value',
                        ], $this->users, true),
                    ],
                    'visibility' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-visibility'], esc_html__('Catalog visibility', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::select_field([
                            'class' => 'wcbel-input-md',
                            'disabled' => 'disabled',
                        ], $this->visibility_items, true),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                    'product_menu_order' => [
                        Generator::label_field(['for' => 'wcbel-filter-form-product-menu-order-from'], esc_html__('Menu order', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::input_field([
                            'class' => 'wcbel-input-ft',
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                            'class' => 'wcbel-short-description'
                        ])
                    ],
                ]
            ],
            'filter_custom_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wcbel-tab-content-item',
                    'data-content' => 'filter_custom_fields'
                ]),
                'fields_top' => (!empty($custom_fields['top_alert'])) ? $custom_fields['top_alert'] : '',
                'wrapper_end' => Generator::div_field_end(),
                'fields' => (!empty($custom_fields['fields'])) ? $custom_fields['fields'] : []
            ],
        ];
    }

    private function get_filter_taxonomies_fields()
    {
        $output['top_alert'] = [];
        $output['fields'] = [];

        if (!empty($this->taxonomies) && !empty($this->taxonomy_groups)) {
            foreach ($this->taxonomy_groups as $group_key => $group_label) {
                $output['fields'][$group_key] = [
                    Generator::strong_field($group_label),
                    Generator::hr()
                ];
                if (!empty($this->taxonomies[$group_key])) {
                    foreach ($this->taxonomies[$group_key] as $name => $taxonomy) {
                        $tax_type = sanitize_text_field(\wcbel\classes\helpers\Meta_Fields::get_taxonomy_type($name));
                        $taxonomy_terms = [];
                        if (!empty($taxonomy['terms'])) {
                            foreach ($taxonomy['terms'] as $term) {
                                if ($tax_type == 'taxonomy') {
                                    $taxonomy_terms[$term->term_id] = $term->name;
                                } else {
                                    $taxonomy_terms[$term->slug] = $term->name;
                                }
                            }
                        }
                        if (in_array($name, ['product_cat', 'product_tag'])) {
                            $output['fields'][$name]['wrap_attributes'] = 'data-type="' . $tax_type . '"';
                            $output['fields'][$name][] = Generator::label_field(['for' => "wcbel-filter-form-product-attr-{$name}"], esc_html($taxonomy['label']));
                            $output['fields'][$name][] = Generator::select_field([
                                'data-field' => 'operator',
                                'id' => "wcbel-filter-form-product-attr-operator-{$name}"
                            ], Operator::filter_multi_select());
                            $output['fields'][$name][] = Generator::select_field([
                                'class' => 'wcbel-select2',
                                'multiple' => 'multiple',
                                'data-field' => 'value',
                                'id' => "wcbel-filter-form-product-attr-{$name}"
                            ], $taxonomy_terms);
                        } else {
                            $output['fields'][$name]['wrap_attributes'] = 'data-type="' . $tax_type . '"';
                            $output['fields'][$name][] = Generator::label_field(['for' => ""], esc_html($taxonomy['label']));
                            $output['fields'][$name][] = Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::filter_multi_select());
                            $output['fields'][$name][] = Generator::select_field([
                                'class' => 'wcbel-select2',
                                'disabled' => 'disabled',
                            ], ['' => 'Select']);
                            $output['fields'][$name][] = Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-bulk-product-editing-lite-for-woocommerce'), [
                                'class' => 'wcbel-short-description'
                            ]);
                        }
                    }

                    $output['fields'][] = [
                        Generator::div_field_start(['class' => 'wcbel-mb20']),
                        Generator::div_field_end()
                    ];
                }
            }
        } else {
            $output['top_alert'] = [
                Generator::div_field_start([
                    'class' => 'wcbel-alert wcbel-alert-warning',
                ]),
                Generator::span_field(esc_html__('Not found !', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                Generator::div_field_end()
            ];
        }

        return $output;
    }

    private function get_filter_custom_fields()
    {
        $output['top_alert'] = [];
        $output['fields'] = [];

        if (!empty($this->meta_fields) && is_array($this->meta_fields)) {
            foreach ($this->meta_fields as $meta_field) {
                $field_id = 'wcbel-filter-form-custom-field-' . $meta_field['key'];
                $output['fields'][$meta_field['key']][] = Generator::label_field(['for' => $field_id], $meta_field['title']);

                $meta_field_rendered = $this->filter_meta_field_render($meta_field, $field_id);
                if (!empty($meta_field_rendered['operator_field'])) {
                    $output['fields'][$meta_field['key']][] = $meta_field_rendered['operator_field'];
                }
                if (is_array($meta_field_rendered['value_field'])) {
                    foreach ($meta_field_rendered['value_field'] as $value_item) {
                        $output['fields'][$meta_field['key']][] = $value_item;
                    }
                } else {
                    $output['fields'][$meta_field['key']][] = $meta_field_rendered['value_field'];
                }
            }
        } else {
            $output['top_alert'] = [
                Generator::div_field_start([
                    'class' => 'wcbel-alert wcbel-alert-warning',
                ]),
                Generator::span_field(esc_html__('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', 'ithemeland-bulk-product-editing-lite-for-woocommerce')),
                Generator::div_field_end()
            ];
        }

        return $output;
    }

    private function get_taxonomy_terms($taxonomy)
    {
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);

        $options = [];
        if (!empty($terms) && count($terms)) {
            foreach ($terms as $term) {
                if ($term instanceof \WP_Term) {
                    $options[$term->term_id] = $term->name;
                }
            }
        }

        return $options;
    }

    private function bulk_edit_meta_field_render($meta_field, $field_id)
    {
        $output = [
            'operator_field' => '',
            'value_field' => ''
        ];

        if (!empty($this->acf_fields[$meta_field['key']])) {
            $meta_field['main_type'] = (!empty($this->acf_fields[$meta_field['key']]['field_type'])) ? $this->acf_fields[$meta_field['key']]['field_type'] : $this->acf_fields[$meta_field['key']]['type'];
        } else {
            $meta_field['main_type'] = $meta_field['main_type'];
        }

        if (!empty($meta_field['main_type'])) {
            switch ($meta_field['main_type']) {
                case $this->meta_field_repository::TEXTINPUT:
                    if (!empty($meta_field['sub_type'])) {
                        switch ($meta_field['sub_type']) {
                            case $this->meta_field_repository::STRING_TYPE:
                                $output['operator_field'] = Generator::select_field([
                                    'data-field' => 'operator',
                                    'id' => $field_id . '-operator'
                                ], Operator::edit_text());
                                $output['value_field'] = Generator::input_field([
                                    'type' => 'text',
                                    'data-field' => 'value',
                                    'id' => $field_id,
                                    'placeholder' => $meta_field['title'] . ' ...',
                                    'class' => ($meta_field['main_type'] == $this->meta_field_repository::CALENDAR) ? 'wcbel-datepicker' : ''
                                ]);
                                break;
                            case $this->meta_field_repository::NUMBER:
                                $output['operator_field'] = Generator::select_field([
                                    'data-field' => 'operator',
                                    'for' => $field_id
                                ], Operator::edit_number());
                                $output['value_field'] = Generator::input_field([
                                    'type' => 'number',
                                    'data-field' => 'value',
                                    'id' => $field_id,
                                    'placeholder' => $meta_field['title'] . ' ...',
                                ]);
                                break;
                        }
                    }
                    break;
                case $this->meta_field_repository::CHECKBOX:
                    if (!empty($this->acf_fields[$meta_field['key']])) {
                        if ($this->acf_fields[$meta_field['key']]['type'] == 'taxonomy') {
                            $options = $this->get_taxonomy_terms($this->acf_fields[$meta_field['key']]['taxonomy']);
                        } else {
                            $options = (!empty($this->acf_fields[$meta_field['key']]['choices'])) ? $this->acf_fields[$meta_field['key']]['choices'] : [];
                        }
                        $output['value_field'] = Generator::select_field([
                            'id' => $field_id,
                            'class' => 'wcbel-select2',
                            'multiple' => 'multiple',
                            'data-field' => 'value',
                        ], $options);
                    } else {
                        $output['value_field'] = Generator::select_field([
                            'id' => $field_id,
                            'data-field' => 'value',
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                        ], true);
                    }
                    break;
                case $this->meta_field_repository::MULTI_SELECT:
                    if (!empty($this->acf_fields[$meta_field['key']]) && $this->acf_fields[$meta_field['key']]['type'] == 'taxonomy' && !empty($this->acf_fields[$meta_field['key']]['taxonomy'])) {
                        $options = $this->get_taxonomy_terms($this->acf_fields[$meta_field['key']]['taxonomy']);
                        $output['value_field'] = Generator::select_field([
                            'id' => $field_id,
                            'class' => 'wcbel-select2',
                            'multiple' => 'multiple',
                            'data-field' => 'value',
                        ], $options);
                    }
                    break;
                case $this->meta_field_repository::ARRAY_TYPE:
                case $this->meta_field_repository::SELECT:
                case $this->meta_field_repository::RADIO:
                    if (!empty($this->acf_fields[$meta_field['key']]) && $this->acf_fields[$meta_field['key']]['type'] == 'taxonomy' && !empty($this->acf_fields[$meta_field['key']]['taxonomy'])) {
                        $options = $this->get_taxonomy_terms($this->acf_fields[$meta_field['key']]['taxonomy']);
                    } else if (!empty($meta_field['key_value'])) {
                        $options = Meta_Field_Helper::key_value_field_to_array($meta_field['key_value']);
                    } else {
                        $options = [];
                    }

                    $output['value_field'] = Generator::select_field([
                        'id' => $field_id,
                        'class' => 'wcbel-input-md',
                        'data-field' => 'value',
                    ], $options, true);
                    break;
                case $this->meta_field_repository::CALENDAR:
                case $this->meta_field_repository::DATE:
                    $output['value_field'] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wcbel-input-md wcbel-datepicker',
                        'data-field' => 'value',
                        'data-field-type' => 'date',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                    break;
                case $this->meta_field_repository::DATE_TIME:
                    $output['value_field'] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wcbel-input-md wcbel-datetimepicker',
                        'data-field' => 'value',
                        'data-field-type' => 'date',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                    break;
                case $this->meta_field_repository::TIME:
                    $output['value_field'] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wcbel-input-md wcbel-timepicker',
                        'data-field' => 'value',
                        'data-field-type' => 'date',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                    break;
            }
        }

        return $output;
    }

    private function filter_meta_field_render($meta_field, $field_id)
    {
        $output = [
            'operator_field' => '',
            'value_field' => ''
        ];

        if (!empty($this->acf_fields[$meta_field['key']])) {
            $meta_field['main_type'] = (!empty($this->acf_fields[$meta_field['key']]['field_type'])) ? $this->acf_fields[$meta_field['key']]['field_type'] : $this->acf_fields[$meta_field['key']]['type'];
        } else {
            $meta_field['main_type'] = $meta_field['main_type'];
        }

        if (!empty($meta_field['main_type'])) {
            switch ($meta_field['main_type']) {
                case $this->meta_field_repository::TEXTINPUT:
                    if (!empty($meta_field['sub_type'])) {
                        switch ($meta_field['sub_type']) {
                            case $this->meta_field_repository::STRING_TYPE:
                                $output['operator_field'] = Generator::select_field([
                                    'data-field' => 'operator',
                                    'id' => $field_id . '-operator'
                                ], Operator::filter_text());
                                $output['value_field'] = Generator::input_field([
                                    'type' => 'text',
                                    'data-field' => 'value',
                                    'id' => $field_id,
                                    'placeholder' => $meta_field['title'] . ' ...',
                                    'class' => ($meta_field['main_type'] == $this->meta_field_repository::CALENDAR) ? 'wcbel-datepicker' : ''
                                ]);
                                break;
                            case $this->meta_field_repository::NUMBER:
                                $output['value_field'] = [];
                                $output['value_field'][] = Generator::input_field([
                                    'type' => 'number',
                                    'class' => 'wcbel-input-ft',
                                    'data-field-type' => 'number',
                                    'data-field' => 'from',
                                    'id' => $field_id . '-from',
                                    'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                ]);
                                $output['value_field'][] = Generator::input_field([
                                    'type' => 'number',
                                    'class' => 'wcbel-input-ft',
                                    'data-field' => 'to',
                                    'data-field-type' => 'number',
                                    'id' => $field_id . '-to',
                                    'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                                ]);
                                break;
                        }
                    }
                    break;
                case $this->meta_field_repository::CHECKBOX:
                    if (!empty($this->acf_fields[$meta_field['key']])) {
                        if ($this->acf_fields[$meta_field['key']]['type'] == 'taxonomy') {
                            $options = $this->get_taxonomy_terms($this->acf_fields[$meta_field['key']]['taxonomy']);
                        } else {
                            $options = (!empty($this->acf_fields[$meta_field['key']]['choices'])) ? $this->acf_fields[$meta_field['key']]['choices'] : [];
                        }

                        $output['value_field'] = Generator::select_field([
                            'id' => $field_id,
                            'class' => 'wcbel-select2',
                            'multiple' => 'multiple',
                            'data-field' => 'value',
                        ], $options);
                    } else {
                        $output['value_field'] = Generator::select_field([
                            'id' => $field_id,
                            'data-field' => 'value',
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                            'no' => esc_html__('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                        ], true);
                    }
                    break;
                case $this->meta_field_repository::MULTI_SELECT:
                    if (!empty($this->acf_fields[$meta_field['key']]) && $this->acf_fields[$meta_field['key']]['type'] == 'taxonomy' && !empty($this->acf_fields[$meta_field['key']]['taxonomy'])) {
                        $options = $this->get_taxonomy_terms($this->acf_fields[$meta_field['key']]['taxonomy']);
                        $output['value_field'] = Generator::select_field([
                            'id' => $field_id,
                            'class' => 'wcbel-select2',
                            'multiple' => 'multiple',
                            'data-field' => 'value',
                        ], $options);
                    }
                    break;
                case $this->meta_field_repository::ARRAY_TYPE:
                case $this->meta_field_repository::SELECT:
                case $this->meta_field_repository::RADIO:
                    if (!empty($this->acf_fields[$meta_field['key']]) && $this->acf_fields[$meta_field['key']]['type'] == 'taxonomy' && !empty($this->acf_fields[$meta_field['key']]['taxonomy'])) {
                        $options = $this->get_taxonomy_terms($this->acf_fields[$meta_field['key']]['taxonomy']);
                    } else if (!empty($meta_field['key_value'])) {
                        $options = Meta_Field_Helper::key_value_field_to_array($meta_field['key_value']);
                    } else {
                        $options = [];
                    }
                    $output['value_field'] = Generator::select_field([
                        'id' => $field_id,
                        'class' => 'wcbel-input-md',
                        'data-field' => 'value',
                    ], $options, true);
                    break;
                case $this->meta_field_repository::CALENDAR:
                case $this->meta_field_repository::DATE:
                    $output['value_field'] = [];
                    $output['value_field'][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wcbel-input-md wcbel-datepicker wcbel-date-from',
                        'data-field' => 'from',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-from',
                        'data-to-id' => $field_id . '-to',
                        'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                    ]);
                    $output['value_field'][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wcbel-input-md wcbel-datepicker',
                        'data-field' => 'to',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-to',
                        'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                    ]);
                    break;
                case $this->meta_field_repository::DATE_TIME:
                    $output['value_field'] = [];
                    $output['value_field'][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wcbel-input-md wcbel-datetimepicker wcbel-date-from',
                        'data-field' => 'from',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-from',
                        'data-to-id' => $field_id . '-to',
                        'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                    ]);
                    $output['value_field'][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wcbel-input-md wcbel-datetimepicker',
                        'data-field' => 'to',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-to',
                        'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                    ]);
                    break;
                case $this->meta_field_repository::TIME:
                    $output['value_field'] = [];
                    $output['value_field'][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wcbel-input-md wcbel-timepicker',
                        'data-field' => 'from',
                        'data-field-type' => 'time',
                        'id' => $field_id . '-from',
                        'placeholder' => esc_html__('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                    ]);
                    $output['value_field'][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wcbel-input-md wcbel-timepicker',
                        'data-field' => 'to',
                        'data-field-type' => 'time',
                        'id' => $field_id . '-to',
                        'placeholder' => esc_html__('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                    ]);
                    break;
            }
        }

        return $output;
    }
}
