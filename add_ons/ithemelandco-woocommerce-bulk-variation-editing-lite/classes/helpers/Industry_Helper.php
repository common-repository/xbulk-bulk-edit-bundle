<?php

namespace iwbvel\classes\helpers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Industry_Helper
{
    public static function get_industries()
    {
        return [
            'Automotive and Transportation' => __('Automotive', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'AdTech and AdNetwork' => __('AdTech and AdNetwork', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Agency' => __('Agency', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'B2B Software' => __('B2B Software', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'B2C Internet Services' => __('B2C Internet Services', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Classifieds' => __('Classifieds', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Consulting and Market Research' => __('Consulting and Market Research', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'CPG, Food and Beverages' => __('CPG', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Education' => __('Education', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Education (student)' => __('Education (Student)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Equity Research' => __('Equity Research', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Financial services' => __('Financial Services', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Gambling / Gaming' => __('Gambling and Gaming', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Hedge Funds and Asset Management' => __('Hedge Funds and Asset Management', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Investment Banking' => __('Investment Banking', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Logistics and Shipping' => __('Logistics and Shipping', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Payments' => __('Payments', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Pharma and Healthcare' => __('Pharma and Healthcare', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Private Equity and Venture Capital' => __('Private Equity and Venture Capital', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Media and Entertainment' => __('Publishers and Media', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Government Public Sector & Non Profit' => __('Public Sector, Non Profit, Fraud and Compliance', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Retail / eCommerce' => __('Retail and eCommerce', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Telecom and Hardware' => __('Telecom', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Travel and Hospitality' => __('Travel', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'Other' => __('Other', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
        ];
    }
}
