<?php namespace Sikhub\AdvancedUrl\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * AdvancedUrl Form Widget
 *
 * @link https://docs.octobercms.com/3.x/extend/forms/form-widgets.html
 */
class AdvancedUrl extends FormWidgetBase
{
    protected $defaultAlias = 'advancedurl_advanced_url';

    public function init()
    {
    }

    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('advancedurl');
    }

    public function prepareVars()
    {
        $this->vars['targets'] = [
            'blank' => '_blank',
            'parent' => '_parent',
            'self' => '_self',
            'top' => '_top',
        ];

        $this->vars['types'] = [
            'http' => 'http://',
            'https' => 'https://',
            'mailto' => 'mailto:',
            'tel' => 'tel:',
        ];

        $this->vars['download'] = false;

        $this->vars['id'] = $this->model->id;
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['value']['type'] ??= null;
        $this->vars['model'] = $this->model;
    }

    public function loadAssets()
    {
        $this->addCss('css/advancedurl.css');
        $this->addJs('js/advancedurl.js');
    }

    public function getSaveValue($value)
    {
        return $value;
    }
}
