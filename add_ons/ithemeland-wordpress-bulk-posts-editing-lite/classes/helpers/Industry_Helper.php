<?php

namespace wpbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Industry_Helper
{
    public static function get_industries()
    {
        return [
            'Automotive and Transportation' => __('Automotive', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'AdTech and AdNetwork' => __('AdTech and AdNetwork', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Agency' => __('Agency', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'B2B Software' => __('B2B Software', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'B2C Internet Services' => __('B2C Internet Services', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Classifieds' => __('Classifieds', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Consulting and Market Research' => __('Consulting and Market Research', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'CPG, Food and Beverages' => __('CPG', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Education' => __('Education', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Education (student)' => __('Education (Student)', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Equity Research' => __('Equity Research', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Financial services' => __('Financial Services', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Gambling / Gaming' => __('Gambling and Gaming', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Hedge Funds and Asset Management' => __('Hedge Funds and Asset Management', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Investment Banking' => __('Investment Banking', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Logistics and Shipping' => __('Logistics and Shipping', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Payments' => __('Payments', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Pharma and Healthcare' => __('Pharma and Healthcare', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Private Equity and Venture Capital' => __('Private Equity and Venture Capital', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Media and Entertainment' => __('Publishers and Media', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Government Public Sector & Non Profit' => __('Public Sector, Non Profit, Fraud and Compliance', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Retail / eCommerce' => __('Retail and eCommerce', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Telecom and Hardware' => __('Telecom', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Travel and Hospitality' => __('Travel', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'Other' => __('Other', 'ithemeland-wordpress-bulk-posts-editing-lite'),
        ];
    }
}
