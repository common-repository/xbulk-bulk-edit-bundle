<?php

namespace iwbvel\classes\helpers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Lang_Helper
{
    public static function get_js_strings()
    {
        return [
            'selectProduct' => __('Please Select Product !', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'areYouSure' => __('Are you sure?', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'iAmSure' => __("Yes, I'm sure !", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'areYouSureForEditAllFilteredProducts' => __("Your changes will be applied to all of filtered products. Are you sure?", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'duplicateVariationsDisabled' => __("Duplicate for variations product is disabled!", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'setAsDefault' => __("Set as default", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'selectedProductsIsNotVariable' => __("Some of selected products are not 'Variable' product. Do you want to change the product type?", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'drag' => __("Drag", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'variationRequired' => __("variation is required !", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'productRequired' => __("Select product is required !", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'variableProductRequired' => __("Select variable product is required !", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'taxonomyNameRequired' => __("Taxonomy Name is required !", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'attributeNameRequired' => __("Attribute Name is required !", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'sameCase' => __("Same Case", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'ignoreCase' => __("Ignore Case", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'enterText' => __("Text ...", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'selectVariable' => __("Select Variable", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'variable' => __("Variable", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'title' => __("Title", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'id' => __("ID", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'sku' => __("SKU", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'parentId' => __("Parent ID", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'parentTitle' => __("Parent Title", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'parentSku' => __("Parent SKU", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'regularPrice' => __("Regular Price", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'salePrice' => __("Sale Price", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'plzAddColumns' => __("Please Add Columns !", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'presetNameRequired' => __("Preset name is required !", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'newProduct' => __("New Product", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'newProductNumber' => __("Enter how many new product(s) to create!", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'enterProductName' => __("Product Name ...", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'loading' => __("Loading", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'success' => __("Success !", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'productsFound' => __("Products Found", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'productHasNoVariations' => __("The product has no variations !", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'selectAttribute' => __("Select attribute", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'notFound' => __("Not Found!", 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
        ];
    }
}
