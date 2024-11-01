<?php

namespace iwbvel\classes\controllers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\bootstrap\IWBVEL_Verification;
use iwbvel\classes\repositories\Flush_Message;
use iwbvel\classes\repositories\ACF_Plugin_Fields;
use iwbvel\classes\providers\variation\VariationProvider;
use iwbvel\classes\repositories\Column;
use iwbvel\classes\repositories\History;
use iwbvel\classes\repositories\Meta_Field;
use iwbvel\classes\repositories\Product;
use iwbvel\classes\repositories\Search;
use iwbvel\classes\repositories\Setting;
use iwbvel\classes\repositories\Tab_Repository;

class IWBVEL_Bulk_Variations
{
    private static $instance;

    private $column_presets_fields;
    private $default_presets;
    private $plugin_data;
    private $flush_message_repository;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->flush_message_repository = new Flush_Message();

        if (!IWBVEL_Verification::is_active() && !defined('WBEBL_NAME')) {
            return $this->activation_page();
        }

        // "woocommerce currency switcher (woocs)" plugin compatibility
        $this->woocs_compatible();

        $this->set_plugin_data();

        add_filter('iwbvel_top_navigation_buttons', [$this, 'add_navigation_buttons']);

        $this->view();
    }

    private function activation_page()
    {
        $plugin_name = __('iThemeland Bulk Variation Editing Lite For WooCommerce', 'ithemelandco-woocommerce-bulk-variation-editing-lite');
        $plugin_description = IWBVEL_DESCRIPTION;
        $flush_message = $this->flush_message_repository->get();

        include_once IWBVEL_VIEWS_DIR . "activation/main.php";
    }

    public function print_script()
    {
        $id_in_url = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

        echo "
        <script>
            var itemIdInUrl = " . intval($id_in_url) . ";
            var defaultPresets = " . wp_json_encode($this->default_presets) . ";
            var columnPresetsFields = " . wp_json_encode($this->column_presets_fields) . ";
        </script>";
    }

    public function add_navigation_buttons($output)
    {
        if (empty($output)) {
            $output = '';
        }

        $last_filter_data = $this->plugin_data['last_filter_data'];
        $settings = $this->plugin_data['settings'];
        $current_settings = $this->plugin_data['current_settings'];

        ob_start();
        include IWBVEL_VIEWS_DIR . "navigation/buttons.php";
        $output .= ob_get_clean();

        return $output;
    }

    private function view()
    {
        $this->print_script();

        extract($this->plugin_data);
        include_once IWBVEL_VIEWS_DIR . "layouts/main.php";
    }

    private function set_plugin_data()
    {
        $column_repository = new Column();
        $search_repository = new Search();
        $setting_repository = new Setting();
        $product_repository = Product::get_instance();
        $history_repository = History::get_instance();
        $meta_field_repository = new Meta_Field();
        $tab_repository = new Tab_Repository();

        $settings = $setting_repository->get_settings();

        if (!isset($settings['close_popup_after_applying'])) {
            $settings['close_popup_after_applying'] = 'no';
            $settings = $setting_repository->update($settings);
        }

        if (!$column_repository->has_column_fields()) {
            $column_repository->set_default_columns();
        }

        if (!$search_repository->has_search_options()) {
            $search_repository->set_default_item();
        }

        $current_settings = $setting_repository->update_current_settings([
            'sort_by' => (isset($settings['default_sort_by'])) ? $settings['default_sort_by'] : '',
            'sort_type' => (isset($settings['default_sort'])) ? $settings['default_sort'] : ''
        ]);

        if (!isset($current_settings['count_per_page'])) {
            $current_settings = $setting_repository->update_current_settings([
                'count_per_page' => isset($settings['count_per_page']) ? $settings['count_per_page'] : 10,
            ]);
        }

        if (!$column_repository->get_active_columns()) {
            $column_repository->set_default_active_columns();
        }

        $this->column_presets_fields = $column_repository->get_presets_fields();
        $this->default_presets = $column_repository::get_default_columns_name();

        $active_columns_array = $column_repository->get_active_columns();
        if (isset($this->column_presets_fields[$active_columns_array['name']])) {
            $this->column_presets_fields[$active_columns_array['name']] = array_keys($active_columns_array['fields']);
        }

        $current_data = $search_repository->get_current_data();

        $acf = ACF_Plugin_Fields::get_instance('product');

        $this->plugin_data = [
            'plugin_key' => 'iwbvel',
            'version' => IWBVEL_VERSION,
            'title' => __('iThemeland Bulk Variation Editing Lite For WooCommerce', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'doc_link' => 'https://ithemelandco.com/Plugins/Documentations/Pro-Bulk-Editing/pro/woocommerce-bulk-variations-editing/documentation.pdf',
            'flush_message' => $this->flush_message_repository->get(),
            'settings' => $settings,
            'current_settings' => $current_settings,
            'columns' => $active_columns_array['fields'],
            'active_columns_key' => $active_columns_array['name'],
            'last_filter_data' => (isset($current_data['last_filter_data'])) ? $current_data['last_filter_data'] : null,
            'default_columns_name' => $column_repository::get_default_columns_name(),
            'items_loading' => true,
            'shipping_classes' => $product_repository->get_shipping_classes(),
            'count_per_page_items' => $setting_repository->get_count_per_page_items(),
            'sort_by' => $current_settings['sort_by'],
            'sort_type' => $current_settings['sort_type'],
            'sticky_first_columns' => $settings['sticky_first_columns'],
            'next_static_columns' => $column_repository::get_static_columns(),
            'item_provider' => VariationProvider::get_instance(),
            'show_id_column' => $column_repository::SHOW_ID_COLUMN,
            'filter_profile_use_always' => $search_repository->get_use_always(),
            'histories' => $history_repository->get_histories(),
            'history_count' => $history_repository->get_history_count(),
            'reverted' => $history_repository->get_latest_reverted(),
            'deactivated_columns' => $column_repository->get_deactivated_columns(),
            'meta_fields_main_types' => Meta_Field::get_main_types(),
            'meta_fields_sub_types' => Meta_Field::get_sub_types(),
            'meta_fields' => $meta_field_repository->get(),
            'grouped_fields' => $column_repository->get_grouped_fields(),
            'column_items' => $column_repository->get_fields(),
            'column_manager_presets' => $column_repository->get_presets(),
            'filters_preset' => $search_repository->get_presets(),
            'attributes' => $product_repository->get_attributes(),
            'acf_grouped_fields' => $acf->get_grouped_fields(),
            'acf_fields' => $acf->get_fields(),
            'bulk_edit_form_tabs_title' => $tab_repository->get_bulk_edit_form_tabs_title(),
            'bulk_edit_form_tabs_content' => $tab_repository->get_bulk_edit_form_tabs_content(),
            'filter_form_tabs_title' => $tab_repository->get_filter_form_tabs_title(),
            'filter_form_tabs_content' => $tab_repository->get_filter_form_tabs_content(),
        ];
    }

    private function woocs_compatible()
    {
        if (class_exists('WOOCS')) {
            global $WOOCS;
            $WOOCS->reset_currency();
            remove_filter('woocommerce_product_get_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 2);
            remove_filter('woocommerce_product_variation_get_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 2);
            remove_filter('woocommerce_product_variation_get_regular_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 2);
            remove_filter('woocommerce_product_variation_get_sale_price', array($WOOCS, 'raw_sale_price_filter'), 9999, 2);
            remove_filter('woocommerce_product_get_regular_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 2);
            remove_filter('woocommerce_product_get_sale_price', array($WOOCS, 'raw_woocommerce_price_sale'), 9999, 2);
            remove_filter('woocommerce_get_variation_regular_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 4);
            remove_filter('woocommerce_get_variation_sale_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 4);
            remove_filter('woocommerce_variation_prices', array($WOOCS, 'woocommerce_variation_prices'), 9999, 3);
        }
    }
}
