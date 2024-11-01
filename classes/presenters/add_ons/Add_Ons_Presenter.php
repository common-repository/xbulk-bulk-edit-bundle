<?php

namespace wbebl\classes\presenters\add_ons;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wbebl\classes\helpers\Plugin;

class Add_Ons_Presenter
{
    private static $instance;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function get_add_on_status_icon($add_on)
    {
        $output = '';
        $is_installed = Plugin::is_installed($add_on['plugin']);

        if ($add_on['license'] === true && $is_installed) {
            $output .= '<div class="license_status active_license">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                <g>
                    <path d="M32,0C14.4,0,0,14.4,0,32s14.4,32,32,32s32-14.4,32-32S49.6,0,32,0z M32,58.7c-14.7,0-26.7-12-26.7-26.7S17.3,5.3,32,5.3s26.7,12,26.7,26.7S46.7,58.7,32,58.7z" />
                    <path d="M40.8,22.1l-12.3,12l-5.6-5.3c-1.1-1.1-2.7-1.1-3.7,0s-1.1,2.7,0,3.7l6.7,6.4c0.8,0.8,1.6,1.1,2.4,1.1c0.8,0,1.9-0.3,2.4-1.1l13.6-13.1c1.1-1.1,1.1-2.7,0-3.7C43.5,21.1,41.9,21.1,40.8,22.1z" />
                </g>
            </svg>
        </div>';
        } elseif ($is_installed) {
            $output .= '<div class="license_status wbebl-inactive-license">
            <a href="javascript:;" class="wbebl-inactive-license-toggle"><i class="wbebl-icon-warning"></i></a>
            </div>';
        } else {
            if (!empty($add_on['buy_link'])) {
                $output .= '<div class="license_status wbebl-coming-soon-license" title="Buy">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="510px" height="510px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve">
                    <g>
                        <g id="shopping-cart">
                            <path d="M153,408c-28.05,0-51,22.95-51,51s22.95,51,51,51s51-22.95,51-51S181.05,408,153,408z M0,0v51h51l91.8,193.8L107.1,306
                                c-2.55,7.65-5.1,17.85-5.1,25.5c0,28.05,22.95,51,51,51h306v-51H163.2c-2.55,0-5.1-2.55-5.1-5.1v-2.551l22.95-43.35h188.7
                                c20.4,0,35.7-10.2,43.35-25.5L504.9,89.25c5.1-5.1,5.1-7.65,5.1-12.75c0-15.3-10.2-25.5-25.5-25.5H107.1L84.15,0H0z M408,408
                                c-28.05,0-51,22.95-51,51s22.95,51,51,51s51-22.95,51-51S436.05,408,408,408z" />
                        </g>
                    </g>
                </svg>
            </div>';
            } else {
                $output .= '<div class="license_status wbebl-coming-soon-license" title="Coming Soon">
                <svg height="384pt" viewBox="0 0 384 384" width="384pt" xmlns="http://www.w3.org/2000/svg">
                    <path d="m343.59375 101.039062c-7.953125 3.847657-11.28125 13.417969-7.433594 21.367188 10.511719 21.714844 15.839844 45.121094 15.839844 69.59375 0 88.222656-71.777344 160-160 160s-160-71.777344-160-160 71.777344-160 160-160c36.558594 0 70.902344 11.9375 99.328125 34.519531 6.894531 5.503907 16.976563 4.351563 22.480469-2.566406 5.503906-6.914063 4.351562-16.984375-2.570313-22.480469-33.652343-26.746094-76-41.472656-119.238281-41.472656-105.863281 0-192 86.136719-192 192s86.136719 192 192 192 192-86.136719 192-192c0-29.335938-6.40625-57.449219-19.039062-83.527344-3.839844-7.96875-13.441407-11.289062-21.367188-7.433594zm0 0" />
                    <path d="m192 64c-8.832031 0-16 7.167969-16 16v112c0 8.832031 7.167969 16 16 16h80c8.832031 0 16-7.167969 16-16s-7.167969-16-16-16h-64v-96c0-8.832031-7.167969-16-16-16zm0 0" />
                </svg>
            </div>';
            }
        }

        return $output;
    }
}
