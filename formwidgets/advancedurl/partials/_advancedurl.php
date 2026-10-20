<?php
use Sikhub\AdvancedUrl\Classes\Link;

$isPage = $kind === Link::KIND_PAGE;
$isMedia = $kind === Link::KIND_MEDIA;
$cssClasses = (string) ($value['css_classes'] ?? '');
?>
<?php if ($this->previewMode) { ?>

    <div class="form-control">
        <?= e($value['label'] ?? '') ?>
    </div>

<?php } else { ?>

    <div class="form-preview">
        <div class="advanced-url" data-advanced-url>

            <div class="advanced-url-row">
                <div class="advanced-url-kind">
                    <select
                        id="<?= $this->getId('select') ?>"
                        name="<?= $name ?>[kind]"
                        class="form-control custom-select select-no-search"
                        title="Vrsta povezave"
                        data-advanced-url-kind>
                        <?php foreach ($kinds as $key => $label) { ?>
                            <option value="<?= e($key) ?>" <?= ((string) $key === $kind) ? 'selected="selected"' : '' ?>><?= e($label) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="advanced-url-value" data-advanced-url-section="url" <?= ($isPage || $isMedia) ? 'style="display:none"' : '' ?>>
                    <input
                        type="text"
                        id="<?= $this->getId('input') ?>"
                        name="<?= $name ?>[url]"
                        value="<?= e(($isPage || $isMedia) ? '' : ($value['url'] ?? '')) ?>"
                        class="form-control"
                        placeholder="Povezava"
                        autocomplete="off" />
                </div>

                <?php if ($pageFinder) { ?>
                    <div class="advanced-url-value" data-advanced-url-section="page" <?= $isPage ? '' : 'style="display:none"' ?>>
                        <?= $pageFinder->render() ?>
                    </div>
                <?php } ?>

                <?php if ($mediaFinder) { ?>
                    <div class="advanced-url-value" data-advanced-url-section="media" <?= $isMedia ? '' : 'style="display:none"' ?>>
                        <?= $mediaFinder->render() ?>
                    </div>
                <?php } ?>

                <div class="advanced-url-target">
                    <select
                        id="<?= $this->getId('target') ?>"
                        name="<?= $name ?>[target]"
                        class="form-control custom-select select-no-search"
                        title="Odpri povezavo v"
                        data-advanced-url-target>
                        <?php foreach ($targets as $target => $label) { ?>
                            <option value="<?= e($target) ?>" <?= ($target === ($value['target'] ?? '')) ? 'selected="selected"' : '' ?>><?= e($label) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <?php // legacy text fields: the template prints its own classes, so they have no effect
                if (! $legacyString) { ?>
                    <button
                        type="button"
                        class="btn btn-default advanced-url-toggle <?= $cssClasses !== '' ? 'active' : '' ?>"
                        title="CSS razredi"
                        data-advanced-url-toggle>
                        <i class="icon-cog"></i>
                    </button>
                <?php } ?>
            </div>

            <?php if (! $legacyString) { ?>
                <div class="advanced-url-extra" data-advanced-url-extra <?= $cssClasses === '' ? 'style="display:none"' : '' ?>>
                    <input
                        type="text"
                        id="<?= $this->getId('css_classes') ?>"
                        name="<?= $name ?>[css_classes]"
                        value="<?= e($cssClasses) ?>"
                        class="form-control"
                        placeholder="CSS razredi, npr. btn btn-primary"
                        autocomplete="off" />
                </div>

                <div class="advanced-url-label">
                    <input
                        type="text"
                        id="<?= $this->getId('label') ?>"
                        name="<?= $name ?>[label]"
                        value="<?= e($value['label'] ?? '') ?>"
                        class="form-control"
                        placeholder="Naslov povezave"
                        autocomplete="off" />
                </div>
            <?php } ?>

        </div>
    </div>
<?php } ?>
