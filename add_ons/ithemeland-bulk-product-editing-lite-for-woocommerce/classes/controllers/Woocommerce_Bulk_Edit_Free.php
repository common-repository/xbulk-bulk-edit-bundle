<?php

namespace wcbef\classes\controllers;

use wcbef\classes\helpers\Operator;
use wcbef\classes\repositories\Flush_Message;
use wcbef\classes\providers\product\ProductProvider;
use wcbef\classes\repositories\Column;
use wcbef\classes\repositories\History;
use wcbef\classes\repositories\Meta_Field;
use wcbef\classes\repositories\Product;
use wcbef\classes\repositories\Search;
use wcbef\classes\repositories\Setting;

class Woocommerce_Bulk_Edit_Free
{
    private $product_repository;
    private $meta_field_repository;
    private $column_repository;
    private $history_repository;
    private $search_repository;
    private $setting_repository;
    private $flush_message_repository;

    public function __construct()
    {
        $this->product_repository = Product::get_instance();
        $this->history_repository = History::get_instance();
        $this->meta_field_repository = new Meta_Field();
        $this->column_repository = new Column();
        $this->search_repository = new Search();
        $this->setting_repository = new Setting();
        $this->flush_message_repository = new Flush_Message();
    }

    public function index()
    {
        // "woocommerce currency switcher (woocs)" plugin compatibility
        $this->woocs_compatible();

        $settings = $this->setting_repository->get_settings();

        if (!$this->column_repository->has_column_fields()) {
            $this->column_repository->set_default_columns();
        }
        
        if (!$this->search_repository->has_search_options()) {
            $this->search_repository->set_default_item();
        }

        $current_settings = $this->setting_repository->update_current_settings([
            'sort_by' => (isset($settings['default_sort_by'])) ? $settings['default_sort_by'] : '',
            'sort_type' => (isset($settings['default_sort'])) ? $settings['default_sort'] : ''
        ]);

        if (!isset($current_settings['count_per_page'])) {
            $current_settings = $this->setting_repository->update_current_settings([
                'count_per_page' => isset($settings['count_per_page']) ? $settings['count_per_page'] : 10,
            ]);
        }

        if (!$this->column_repository->get_active_columns()) {
            $this->column_repository->set_default_active_columns();
        }

        $plugin_key = "wcbef";
        $plugin_version = WCBEF_VERSION;
        $clear_all_history = "wcbef_clear_all_history";
        $history_action = "itbbc_history_action";
        $activation_plugin_action_name = "wcbef_activation_plugin";
        $deactivation_plugin_action_name = "wcbef_deactivation_plugin";
        $flush_message = $this->flush_message_repository->get();
        $get_active_columns = $this->column_repository->get_active_columns();
        $current_data = $this->search_repository->get_current_data();
        $last_filter_data = (isset($current_data['last_filter_data'])) ? $current_data['last_filter_data'] : null;
        $active_columns = $get_active_columns['fields'];
        $active_columns_key = $get_active_columns['name'];
        $default_columns_name = $this->column_repository::get_default_columns_name();
        $sort_by = $current_settings['sort_by'];
        $sort_type = $current_settings['sort_type'];
        $sticky_first_columns = $settings['sticky_first_columns'];
        $item_provider = ProductProvider::get_instance();
        $show_id_column = $this->column_repository::SHOW_ID_COLUMN;
        $next_static_columns = $this->column_repository::get_static_columns();
        $column_profile_action_form = "wcbef_load_column_profile";
        $count_per_page_items = $this->setting_repository->get_count_per_page_items();
        $items_loading = true;
        $filter_profile_use_always = $this->search_repository->get_use_always();
        $histories = $this->history_repository->get_histories();
        $history_count = $this->history_repository->get_history_count();
        $columns = $get_active_columns['fields'];
        $deactivated_columns = $this->column_repository->get_deactivated_columns();
        $meta_fields_main_types = Meta_Field::get_main_types();
        $meta_fields_sub_types = Meta_Field::get_sub_types();
        $meta_fields = $this->meta_field_repository->get();
        $grouped_fields = $this->column_repository->get_grouped_fields();
        $column_items = $this->column_repository->get_fields();
        $column_manager_presets = $this->column_repository->get_presets();
        $column_presets_fields = $this->column_repository->get_presets_fields();
        
        if (isset($column_presets_fields[$get_active_columns['name']])) {
            $column_presets_fields[$get_active_columns['name']] = array_keys($get_active_columns['fields']);
        }

        $shipping_classes = wc()->shipping()->get_shipping_classes();
        $edit_text_operators = Operator::edit_text();
        $edit_taxonomy_operators = Operator::edit_taxonomy();
        $edit_number_operators = Operator::edit_number();
        $edit_regular_price_operators = Operator::edit_regular_price();
        $edit_sale_price_operators = Operator::edit_sale_price();
        $product_types = wc_get_product_types();
        $product_statuses = get_post_statuses();
        $users = get_users();
        $filters_preset = $this->search_repository->get_presets();
        $attributes = $this->product_repository->get_attributes();
        $title = esc_html__('WooCommerce Bulk Product Editing', 'woocommerce-bulk-edit-free');
        $doc_link = "https://ithemelandco.com/Plugins/Documentations/Pro-Bulk-Editing/woocommerce-bulk-product-editing/documentation.pdf";

        $defaultPresets = $this->column_repository::get_default_columns_name();
        $id_in_url = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
        echo "
        <script> 
            var itemIdInUrl = " . $id_in_url . "; 
            var defaultPresets = " . json_encode($defaultPresets) . ";
            var columnPresetsFields = " . json_encode($column_presets_fields) . ";
        </script>";
        include_once WCBEF_VIEWS_DIR . "layouts/main.php";
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
