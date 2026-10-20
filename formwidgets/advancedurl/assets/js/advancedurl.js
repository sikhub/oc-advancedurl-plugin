+function ($) { "use strict";

    // files and external URLs open in a new tab by default, everything else in the same tab
    var NEW_TAB_KINDS = ['media', 'https://', 'http://'];

    function sectionFor(kind) {
        return kind === 'page' || kind === 'media' ? kind : 'url';
    }

    $(document).on('change', '[data-advanced-url-kind]', function () {
        var $root = $(this).closest('[data-advanced-url]'),
            kind = $(this).val() || '',
            target = NEW_TAB_KINDS.indexOf(kind) !== -1 ? '_blank' : '_self';

        $root.find('[data-advanced-url-section]').each(function () {
            $(this).toggle($(this).attr('data-advanced-url-section') === sectionFor(kind));
        });

        $root.find('[data-advanced-url-target]').val(target).trigger('change');
    });

    $(document).on('click', '[data-advanced-url-toggle]', function () {
        var $extra = $(this).closest('[data-advanced-url]').find('[data-advanced-url-extra]');

        $extra.toggle();

        if ($extra.is(':visible')) {
            $extra.find('input').trigger('focus');
        }
    });

}(window.jQuery);
