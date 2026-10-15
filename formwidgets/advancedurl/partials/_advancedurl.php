<?php
use Sikhub\AdvancedUrl\Classes\Link;

$isPage = $kind === Link::KIND_PAGE;
$isMedia = $kind === Link::KIND_MEDIA;
?>
<?php if ($this->previewMode) { ?>

    <div class="form-control">
        <?= e($value['label'] ?? '') ?>
    </div>

<?php } else { ?>

    <div class="form-preview">
        <div class="advanced-url" data-advanced-url>

            <label class="form-label">Odpri povezavo v:</label>
            <div data-control="balloon-selector" class="control-balloon-selector">
                <ul>
                    <?php foreach ($targets as $target => $label) { ?>
                        <li data-value="<?= e($target) ?>" class="<?= ($target === ($value['target'] ?? '')) ? 'active' : '' ?>"><?= e($label) ?></li>
                    <?php } ?>
                </ul>

                <input type="hidden" name="<?= $name ?>[target]" value="<?= e($value['target'] ?? '') ?>" />
            </div>

            <div class="flex">
                <div class="types">
                    <label class="form-label">Vrsta povezave:</label>
                    <select
                        id="<?= $this->getId('select') ?>"
                        name="<?= $name ?>[kind]"
                        class="form-control custom-select select-no-search"
                        data-advanced-url-kind>
                        <?php foreach ($kinds as $key => $label) { ?>
                            <option value="<?= e($key) ?>" <?= ((string) $key === $kind) ? 'selected="selected"' : '' ?>><?= e($label) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="w-full" data-advanced-url-section="url" <?= ($isPage || $isMedia) ? 'style="display:none"' : '' ?>>
                    <label class="form-label">Povezava:</label>
                    <input
                        type="text"
                        id="<?= $this->getId('input') ?>"
                        name="<?= $name ?>[url]"
                        value="<?= e(($isPage || $isMedia) ? '' : ($value['url'] ?? '')) ?>"
                        class="form-control"
                        autocomplete="off" />
                </div>

                <?php if ($pageFinder) { ?>
                    <div class="w-full" data-advanced-url-section="page" <?= $isPage ? '' : 'style="display:none"' ?>>
                        <label class="form-label">Stran:</label>
                        <?= $pageFinder->render() ?>
                    </div>
                <?php } ?>

                <?php if ($mediaFinder) { ?>
                    <div class="w-full" data-advanced-url-section="media" <?= $isMedia ? '' : 'style="display:none"' ?>>
                        <label class="form-label">Datoteka:</label>
                        <?= $mediaFinder->render() ?>
                    </div>
                <?php } ?>
            </div>

            <?php // legacy text fields: the template prints its own text and classes, so these have no effect
            if (! $legacyString) { ?>
            <div>
                <label class="form-label">Naslov povezave:</label>
                <input
                    type="text"
                    id="<?= $this->getId('label') ?>"
                    name="<?= $name ?>[label]"
                    value="<?= e($value['label'] ?? '') ?>"
                    class="form-control"
                    autocomplete="off" />
            </div>

            <div>
                <label class="form-label">Css classes:</label>
                <input
                    type="text"
                    id="<?= $this->getId('css_classes') ?>"
                    name="<?= $name ?>[css_classes]"
                    value="<?= e($value['css_classes'] ?? '') ?>"
                    class="form-control"
                    autocomplete="off" />
            </div>

            <?php } ?>

        </div>
    </div>
<?php } ?>
