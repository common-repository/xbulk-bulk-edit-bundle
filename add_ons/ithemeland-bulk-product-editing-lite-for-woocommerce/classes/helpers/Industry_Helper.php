<?php

namespace wcbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Industry_Helper
{
    public static function get_industries()
    {
        return [
            'Automotive and Transportation' => __('Automotive', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'AdTech and AdNetwork' => __('AdTech and AdNetwork', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Agency' => __('Agency', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'B2B Software' => __('B2B Software', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'B2C Internet Services' => __('B2C Internet Services', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Classifieds' => __('Classifieds', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Consulting and Market Research' => __('Consulting and Market Research', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'CPG, Food and Beverages' => __('CPG', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Education' => __('Education', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Education (student)' => __('Education (Student)', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Equity Research' => __('Equity Research', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Financial services' => __('Financial Services', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Gambling / Gaming' => __('Gambling and Gaming', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Hedge Funds and Asset Management' => __('Hedge Funds and Asset Management', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Investment Banking' => __('Investment Banking', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Logistics and Shipping' => __('Logistics and Shipping', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Payments' => __('Payments', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Pharma and Healthcare' => __('Pharma and Healthcare', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Private Equity and Venture Capital' => __('Private Equity and Venture Capital', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Media and Entertainment' => __('Publishers and Media', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Government Public Sector & Non Profit' => __('Public Sector, Non Profit, Fraud and Compliance', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Retail / eCommerce' => __('Retail and eCommerce', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Telecom and Hardware' => __('Telecom', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Travel and Hospitality' => __('Travel', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'Other' => __('Other', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
        ];
    }
}
