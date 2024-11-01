<?php

namespace iwbvel\classes\services\product_update;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\repositories\History;
use iwbvel\classes\services\product_update\variation_handlers\Add_Variations_Handler;
use iwbvel\classes\services\product_update\variation_handlers\Attach_Variation_Handler;
use iwbvel\classes\services\product_update\variation_handlers\Attributes_Edit_Handler;
use iwbvel\classes\services\product_update\variation_handlers\Default_Attributes_Handler;
use iwbvel\classes\services\product_update\variation_handlers\Delete_Variation_Handler;
use iwbvel\classes\services\product_update\variation_handlers\Replace_Variation_Handler;
use iwbvel\classes\services\product_update\variation_handlers\Swap_Variation_Handler;

class Variation_Update implements Update_Interface
{
    private static $instance;
    private $product_ids;
    private $update_data;
    private $save_history;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function set_update_data($data)
    {
        if (!isset($data['product_ids']) || empty($data['product_data']) || !is_array($data['product_data'])) {
            return false;
        }

        $this->product_ids = array_unique($data['product_ids']);
        $this->update_data = $data['product_data'];
        $this->save_history = (!empty($data['save_history']));
    }

    public function perform()
    {
        if (empty($this->product_ids) || !is_array($this->product_ids) || empty($this->update_data) || !is_array($this->update_data)) {
            return false;
        }

        // save history
        if ($this->save_history) {
            $history_id = $this->save_history();
            if (empty($history_id)) {
                return false;
            }
        }

        foreach ($this->update_data as $update_data) {
            if (!isset($update_data['action'])) {
                continue;
            }

            if (!empty($history_id)) {
                $update_data['history_id'] = intval($history_id);
            }

            switch ($update_data['action']) {
                case 'add_variations':
                    $handler_class = Add_Variations_Handler::class;
                    break;
                case 'replace_variations':
                    $handler_class = Replace_Variation_Handler::class;
                    break;
                case 'attach_variations':
                    $handler_class = Attach_Variation_Handler::class;
                    break;
                case 'swap_variations':
                    $handler_class = Swap_Variation_Handler::class;
                    break;
                case 'delete_by_ids':
                case 'delete_by_term':
                case 'delete_all':
                    $handler_class = Delete_Variation_Handler::class;
                    break;
                case 'attributes_edit':
                    $handler_class = Attributes_Edit_Handler::class;
                    break;
                case 'default_attributes':
                    $handler_class = Default_Attributes_Handler::class;
                    break;
                default:
                    continue;
            }

            if (!class_exists($handler_class)) {
                continue;
            }

            $instance = $handler_class::get_instance();
            $instance->update($this->product_ids, $update_data);
        }

        return true;
    }

    private function save_history()
    {
        $history_repository = History::get_instance();
        $fields = array_column($this->update_data, 'name');
        $history_id = $history_repository->create_history([
            'user_id' => intval(get_current_user_id()),
            'fields' => serialize($fields),
            'operation_type' => History::BULK_OPERATION,
            'operation_date' => gmdate('Y-m-d H:i:s'),
        ]);

        return $history_id;
    }
}
