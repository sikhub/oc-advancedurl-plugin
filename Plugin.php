<?php namespace sikhub\AdvancedUrl;

use Sikhub\AdvancedUrl\FormWidgets\AdvancedUrl;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerFormWidgets()
    {
        return [
            AdvancedUrl::class => [
                'label' => 'Advanced URL',
                'code' => 'advancedurl',
            ],
        ];
    }
}
