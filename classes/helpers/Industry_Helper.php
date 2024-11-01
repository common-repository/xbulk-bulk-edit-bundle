<?php

namespace wbebl\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Industry_Helper
{
    public static function get_industries()
    {
        return [
            'Automotive and Transportation' => __('Automotive', 'xbulk-bulk-edit-bundle'),
            'AdTech and AdNetwork' => __('AdTech and AdNetwork', 'xbulk-bulk-edit-bundle'),
            'Agency' => __('Agency', 'xbulk-bulk-edit-bundle'),
            'B2B Software' => __('B2B Software', 'xbulk-bulk-edit-bundle'),
            'B2C Internet Services' => __('B2C Internet Services', 'xbulk-bulk-edit-bundle'),
            'Classifieds' => __('Classifieds', 'xbulk-bulk-edit-bundle'),
            'Consulting and Market Research' => __('Consulting and Market Research', 'xbulk-bulk-edit-bundle'),
            'CPG, Food and Beverages' => __('CPG', 'xbulk-bulk-edit-bundle'),
            'Education' => __('Education', 'xbulk-bulk-edit-bundle'),
            'Education (student)' => __('Education (Student)', 'xbulk-bulk-edit-bundle'),
            'Equity Research' => __('Equity Research', 'xbulk-bulk-edit-bundle'),
            'Financial services' => __('Financial Services', 'xbulk-bulk-edit-bundle'),
            'Gambling / Gaming' => __('Gambling and Gaming', 'xbulk-bulk-edit-bundle'),
            'Hedge Funds and Asset Management' => __('Hedge Funds and Asset Management', 'xbulk-bulk-edit-bundle'),
            'Investment Banking' => __('Investment Banking', 'xbulk-bulk-edit-bundle'),
            'Logistics and Shipping' => __('Logistics and Shipping', 'xbulk-bulk-edit-bundle'),
            'Payments' => __('Payments', 'xbulk-bulk-edit-bundle'),
            'Pharma and Healthcare' => __('Pharma and Healthcare', 'xbulk-bulk-edit-bundle'),
            'Private Equity and Venture Capital' => __('Private Equity and Venture Capital', 'xbulk-bulk-edit-bundle'),
            'Media and Entertainment' => __('Publishers and Media', 'xbulk-bulk-edit-bundle'),
            'Government Public Sector & Non Profit' => __('Public Sector, Non Profit, Fraud and Compliance', 'xbulk-bulk-edit-bundle'),
            'Retail / eCommerce' => __('Retail and eCommerce', 'xbulk-bulk-edit-bundle'),
            'Telecom and Hardware' => __('Telecom', 'xbulk-bulk-edit-bundle'),
            'Travel and Hospitality' => __('Travel', 'xbulk-bulk-edit-bundle'),
            'Other' => __('Other', 'xbulk-bulk-edit-bundle'),
        ];
    }
}
