<?php

namespace wcbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Lang_Helper
{
    public static function get_js_strings()
    {
        return [
            'selectProduct' => __('Please Select Product !', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'areYouSure' => __('Are you sure?', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'iAmSure' => __("Yes, I'm sure !", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'areYouSureForEditAllFilteredProducts' => __("Your changes will be applied to all of filtered products. Are you sure?", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'duplicateVariationsDisabled' => __("Duplicate for variations product is disabled!", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'setAsDefault' => __("Set as default", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'selectedProductsIsNotVariable' => __("Some of selected products are not 'Variable' product. Do you want to change the product type?", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'drag' => __("Drag", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'variationRequired' => __("variation is required !", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'productRequired' => __("Select product is required !", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'variableProductRequired' => __("Select variable product is required !", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'taxonomyNameRequired' => __("Taxonomy Name is required !", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'attributeNameRequired' => __("Attribute Name is required !", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'sameCase' => __("Same Case", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'ignoreCase' => __("Ignore Case", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'enterText' => __("Text ...", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'selectVariable' => __("Select Variable", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'variable' => __("Variable", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'title' => __("Title", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'id' => __("ID", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'sku' => __("SKU", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'parentId' => __("Parent ID", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'parentTitle' => __("Parent Title", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'parentSku' => __("Parent SKU", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'regularPrice' => __("Regular Price", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'salePrice' => __("Sale Price", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'plzAddColumns' => __("Please Add Columns !", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'presetNameRequired' => __("Preset name is required !", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'newProduct' => __("New Product", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'newProductNumber' => __("Enter how many new product(s) to create!", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'enterProductName' => __("Product Name ...", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'loading' => __("Loading", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'success' => __("Success !", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'productsFound' => __("Products Found", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'productHasNoVariations' => __("The product has no variations !", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'selectAttribute' => __("Select attribute", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'notFound' => __("Not Found!", 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
        ];
    }
}
