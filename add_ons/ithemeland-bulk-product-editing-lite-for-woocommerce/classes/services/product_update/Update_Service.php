<?php

namespace wcbel\classes\services\product_update;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\repositories\History;

class Update_Service
{
    private static $instance;
    private $product_ids;
    private $product_data;
    private $update_classes;
    private $save_history;
    private $operation_type;
    private $update_type;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->update_classes = $this->get_update_classes();
    }

    public function set_update_data($data)
    {
        if (!isset($data['product_ids']) || empty($data['product_data']) || !is_array($data['product_data']) || empty($data['update_type'])) {
            return false;
        }

        $this->product_ids = array_unique($data['product_ids']);
        $this->product_data = $data['product_data'];
        $this->save_history = (!empty($data['save_history']));
        $this->update_type = sanitize_text_field($data['update_type']);

        if (isset($data['operation_type'])) {
            $this->operation_type = sanitize_text_field($data['operation_type']);
        } else {
            $this->operation_type = (count($this->product_ids) > 1) ? History::BULK_OPERATION : History::INLINE_OPERATION;
        }

        return true;
    }

    public function perform()
    {
        if (!isset($this->update_classes[$this->update_type])) {
            return false;
        }

        $class = $this->update_classes[$this->update_type];
        $update_object = $class::get_instance();
        $update_object->set_update_data([
            'product_ids' => $this->product_ids,
            'product_data' => $this->product_data,
            'save_history' => $this->save_history,
            'operation_type' => $this->operation_type,
        ]);

        return $update_object->perform();
    }

    private function get_update_classes()
    {
        return [
            'product' => Product_Update::class,
            'variation' => Variation_Update::class,
        ];
    }
}
