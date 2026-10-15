<?php

namespace Sikhub\AdvancedUrl\FormWidgets;

use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;
use Sikhub\AdvancedUrl\Classes\Link;

/**
 * AdvancedUrl Form Widget: one field for every kind of link. A page of the
 * site (October page finder), an own URL, an e-mail address, a phone number
 * or a file from the media library, plus where it opens.
 *
 * The stored value keeps its shape (target, type, url, label, css_classes),
 * so templates printing {{ cta.type }}{{ cta.url }} keep working; a page link
 * adds `reference`, a media file adds `media`. With `legacyString: true` the
 * widget edits a field that used to hold a plain URL string.
 *
 * @link https://docs.octobercms.com/3.x/extend/forms/form-widgets.html
 */
class AdvancedUrl extends FormWidgetBase
{
    protected $defaultAlias = 'advancedurl_advanced_url';

    /**
     * @var bool legacyString the field held a plain URL string before
     */
    public $legacyString = false;

    protected $pageFinder;

    protected $mediaFinder;

    public function init()
    {
        $this->fillFromConfig(['legacyString']);

        $link = Link::fromValue($this->getLoadValue());

        if (class_exists(\Cms\FormWidgets\PageFinder::class)) {
            $this->pageFinder = $this->makeSubWidget(\Cms\FormWidgets\PageFinder::class, 'reference', $link['reference'] ?? null, [
                'singleMode' => true,
                'allowCustomUrl' => false,
            ]);
        }

        if (class_exists(\Media\FormWidgets\MediaFinder::class)) {
            $this->mediaFinder = $this->makeSubWidget(\Media\FormWidgets\MediaFinder::class, 'media', $link['media'] ?? null, [
                'mode' => 'file',
            ]);
        }
    }

    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('advancedurl');
    }

    public function prepareVars()
    {
        $this->vars['targets'] = [
            '_self' => 'Isti zavihek',
            '_blank' => 'Nov zavihek',
            '_parent' => '_parent',
            '_top' => '_top',
        ];

        $this->vars['kinds'] = array_filter([
            Link::KIND_PAGE => $this->pageFinder ? 'Stran na spletnem mestu' : null,
            'https://' => 'https://',
            'http://' => 'http://',
            '' => 'Relativni URL',
            'mailto:' => 'E-pošta (mailto:)',
            'tel:' => 'Telefon (tel:)',
            Link::KIND_MEDIA => $this->mediaFinder ? 'Datoteka iz knjižnice medijev' : null,
        ], fn ($label) => $label !== null);

        $link = Link::fromValue($this->getLoadValue(), $this->legacyString);

        $this->vars['id'] = $this->model->id;
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $link;
        $this->vars['kind'] = Link::kindOf($link);
        $this->vars['model'] = $this->model;
        $this->vars['pageFinder'] = $this->pageFinder;
        $this->vars['mediaFinder'] = $this->mediaFinder;
        $this->vars['legacyString'] = $this->legacyString;
    }

    public function loadAssets()
    {
        $this->addCss('css/advancedurl.css');
        $this->addJs('js/advancedurl.js');
    }

    public function getSaveValue($value)
    {
        if (! is_array($value)) {
            return $value;
        }

        $link = Link::fromPost($value);

        if ($this->legacyString) {
            $link['legacy'] = true;
        }

        return $link;
    }

    /**
     * makeSubWidget builds a nested form widget that posts into a key of this
     * field, e.g. cta[reference], so its AJAX handlers work inside repeaters.
     */
    protected function makeSubWidget(string $class, string $key, $value, array $config)
    {
        $field = new FormField($this->formField->fieldName . '[' . $key . ']', $key);
        $field->arrayName = $this->formField->arrayName;
        $field->idPrefix = $this->formField->idPrefix;
        $field->value = $value;

        $widget = new $class($this->controller, $field, $config + ['alias' => $this->alias . ucfirst($key)]);
        $widget->bindToController();

        return $widget;
    }
}
