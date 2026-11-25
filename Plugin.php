<?php

namespace sikhub\AdvancedUrl;

use Event;
use Sikhub\AdvancedUrl\Classes\LinkCleaner;
use Sikhub\AdvancedUrl\FormWidgets\AdvancedUrl;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function boot()
    {
        // cms.page.postprocess exists in October 3 and 4 (postProcessContent only in 4)
        Event::listen('cms.page.postprocess', function ($controller, $url, $page, $dataHolder) {
            if (is_string($dataHolder->content ?? null)) {
                $dataHolder->content = LinkCleaner::clean($dataHolder->content);
            }
        });
    }

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
