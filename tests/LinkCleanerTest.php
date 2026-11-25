<?php

declare(strict_types=1);

namespace Sikhub\AdvancedUrl\Tests;

use PHPUnit\Framework\TestCase;
use Sikhub\AdvancedUrl\Classes\Link;
use Sikhub\AdvancedUrl\Classes\LinkCleaner;

final class LinkCleanerTest extends TestCase
{
    /**
     * @dataProvider phones
     */
    public function test_phone_numbers_become_dialable(string $printed, string $expected): void
    {
        $this->assertSame($expected, LinkCleaner::phone($printed));
    }

    public static function phones(): array
    {
        return [
            'spaces (Lancom)' => ['+386 2 33 00 100', '+38623300100'],
            'national zero (CBT)' => ['+386 (0)31 736 649', '+38631736649'],
            'dashes and slashes' => ['031/736-649', '031736649'],
            'already clean' => ['+38623300100', '+38623300100'],
        ];
    }

    public function test_tel_links_are_cleaned_in_the_page(): void
    {
        $html = '<a href="tel:+386 1 24 27 350" class="x">+386 1 24 27 350</a><a href=\'tel:+386 (0)31 736 649\'>CBT</a>';

        $this->assertSame(
            '<a href="tel:+38612427350" class="x">+386 1 24 27 350</a><a href=\'tel:+38631736649\'>CBT</a>',
            LinkCleaner::clean($html)
        );
    }

    public function test_links_without_an_address_lose_their_href(): void
    {
        $html = '<a class="btn" href="https://">Več</a> <a href="mailto:" target="_blank">E-pošta</a> <a href="tel:">Klic</a>';

        $this->assertSame('<a class="btn">Več</a> <a target="_blank">E-pošta</a> <a>Klic</a>', LinkCleaner::clean($html));
    }

    public function test_real_links_stay_as_they_are(): void
    {
        $html = '<a href="https://example.com">x</a><a href="/kontakt">y</a><a href="#">z</a><a href="mailto:info@example.com">m</a><link href="https://fonts.googleapis.com">';

        $this->assertSame($html, LinkCleaner::clean($html));
    }

    public function test_a_saved_phone_link_is_stored_dialable(): void
    {
        $link = Link::fromPost(['kind' => 'tel:', 'url' => '+386 2 33 00 100', 'target' => '_self']);

        $this->assertSame('tel:', $link['type']);
        $this->assertSame('+38623300100', $link['url']);
    }
}
