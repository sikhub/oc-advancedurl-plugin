<?php

namespace Sikhub\AdvancedUrl\Classes;

/**
 * LinkCleaner fixes two link mistakes in the rendered page, whatever template
 * printed them ({{ link.type }}{{ link.url }} is printed by the themes, not by
 * this plugin). Stored values are not changed.
 *
 * - tel: links get a dialable number: no spaces, dashes, dots, slashes or
 *   brackets, and no national "(0)" after the country code
 *   (tel:+386 (0)31 736 649 → tel:+38631736649).
 * - a link whose kind was chosen but whose address was left empty
 *   (href="https://", "mailto:", "tel:") loses its href, so it is no longer a
 *   link to nowhere; the element and its text stay.
 */
class LinkCleaner
{
    public static function clean(string $html): string
    {
        if (stripos($html, 'href') === false) {
            return $html;
        }

        $html = preg_replace_callback(
            '/\bhref\s*=\s*(["\'])tel:([^"\']*)\1/i',
            fn (array $match) => 'href=' . $match[1] . 'tel:' . self::phone($match[2]) . $match[1],
            $html
        );

        return preg_replace('/(<a\b[^>]*?)\s+href\s*=\s*(["\'])\s*(?:https?:\/\/\/?|mailto:|tel:)\s*\2/i', '$1', $html);
    }

    public static function phone(string $number): string
    {
        $number = html_entity_decode($number, ENT_QUOTES | ENT_HTML5);
        $number = preg_replace('/^(\+\d{1,3})\s*\(0\)/', '$1', trim($number));

        return (string) preg_replace('/[\s().\/-]+/u', '', $number);
    }
}
