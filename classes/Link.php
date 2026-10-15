<?php

namespace Sikhub\AdvancedUrl\Classes;

use Throwable;

/**
 * Link converts an advanced URL value between its stored form, the form
 * widget and the template output. The stored keys stay target, type, url,
 * label and css_classes; `reference` (october:// page link) and `media`
 * (media library path) are added for those kinds, and `url` always holds the
 * resolved address, so {{ link.type }}{{ link.url }} works everywhere.
 */
class Link
{
    public const KIND_PAGE = 'page';

    public const KIND_MEDIA = 'media';

    public const TYPES = ['https://', 'http://', 'mailto:', 'tel:'];

    /**
     * fromValue normalizes a stored value: an array, a legacy plain URL string,
     * or nothing (a new link keeps the old defaults: https:// in a new tab).
     */
    public static function fromValue($value, bool $legacy = false): array
    {
        if (is_string($value)) {
            return self::fromString($value);
        }

        $link = is_array($value) ? $value : [];

        if ($legacy && ! $link) {
            return self::fromString('');
        }

        $link['type'] ??= 'https://';
        $link['target'] ??= '_blank';

        return $link;
    }

    /**
     * fromString reads a URL that was stored as plain text. It has no target,
     * which is the same as opening in the same tab.
     */
    public static function fromString(string $url): array
    {
        $url = trim($url);
        $link = ['target' => '_self', 'type' => '', 'url' => $url];

        if (str_starts_with($url, 'october://')) {
            return ['target' => '_self', 'type' => '', 'url' => self::pageUrl($url) ?? '', 'reference' => $url];
        }

        foreach (self::TYPES as $type) {
            if (stripos($url, $type) === 0) {
                $link['type'] = $type;
                $link['url'] = substr($url, strlen($type));
                break;
            }
        }

        return $link;
    }

    public static function kindOf(array $link): string
    {
        if (! empty($link['reference'])) {
            return self::KIND_PAGE;
        }

        if (! empty($link['media'])) {
            return self::KIND_MEDIA;
        }

        return (string) ($link['type'] ?? '');
    }

    /**
     * fromPost builds the value to store from the posted widget fields.
     */
    public static function fromPost(array $value): array
    {
        $kind = (string) ($value['kind'] ?? ($value['type'] ?? ''));

        $link = [
            'target' => (string) ($value['target'] ?? ''),
            'type' => '',
            'url' => '',
            'label' => (string) ($value['label'] ?? ''),
            'css_classes' => (string) ($value['css_classes'] ?? ''),
        ];

        if ($kind === self::KIND_PAGE) {
            $reference = trim((string) ($value['reference'] ?? ''));

            if ($reference !== '') {
                $link['url'] = self::pageUrl($reference) ?? '';
                $link['reference'] = $reference;
            }

            return $link;
        }

        if ($kind === self::KIND_MEDIA) {
            $media = trim((string) ($value['media'] ?? ''));

            if ($media !== '') {
                $link['url'] = self::mediaUrl($media);
                $link['media'] = $media;
            }

            return $link;
        }

        $url = trim((string) ($value['url'] ?? ''));

        // a scheme typed into the field as well would be doubled on output
        if ($kind !== '' && stripos($url, $kind) === 0) {
            $url = substr($url, strlen($kind));
        }

        $link['type'] = $kind;
        $link['url'] = $url;

        return $link;
    }

    /**
     * refresh re-resolves a page link, so it follows a changed slug.
     */
    public static function refresh(array $link): array
    {
        if (! empty($link['reference']) && ($url = self::pageUrl((string) $link['reference'])) !== null) {
            $link['url'] = $url;
        }

        return $link;
    }

    public static function toString(array $link): string
    {
        return (string) ($link['type'] ?? '') . (string) ($link['url'] ?? '');
    }

    public static function pageUrl(string $reference): ?string
    {
        if (! class_exists(\Cms\Classes\PageManager::class)) {
            return null;
        }

        try {
            $url = (string) \Cms\Classes\PageManager::url($reference);
        } catch (Throwable) {
            return null;
        }

        if ($url === '' || str_starts_with($url, 'october://')) {
            return null;
        }

        return self::relative($url);
    }

    public static function mediaUrl(string $path): string
    {
        try {
            return self::relative((string) \Media\Classes\MediaLibrary::url($path));
        } catch (Throwable) {
            return $path;
        }
    }

    /**
     * relative strips this site's own host, so a link keeps working when the
     * site moves (staging, production); URLs on other hosts (S3) stay absolute.
     */
    private static function relative(string $url): string
    {
        foreach ([config('app.url'), request()->root()] as $base) {
            $base = rtrim((string) $base, '/');

            if ($base !== '' && str_starts_with($url, $base)) {
                $path = substr($url, strlen($base));

                return $path === '' ? '/' : $path;
            }
        }

        return $url;
    }
}
