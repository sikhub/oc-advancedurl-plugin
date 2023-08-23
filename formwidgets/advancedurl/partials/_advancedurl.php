<?php if ($this->previewMode): ?>

    <div class="form-control">
        <?= $this->getId('label') ?>
    </div>

<?php else: ?>

    <div class="form-preview">
        <div class="advanced-url">

            <label class="form-label" for="">Odpri povezavo v:</label>
            <div data-control="balloon-selector" class="control-balloon-selector">
                <ul>
                    <?php foreach ($targets as $key => $target): ?>
                        <li data-value="_self" value="<?= $target ?>" class=""><?= $key ?></li>
                    <?php endforeach; ?>
                </ul>

                <input type="hidden" name="<?= $name ?>[target]" value="<?= $value['target'] ?? '' ?>" />
            </div>

            <div class="flex">
                <div class="types" id="Relation-formRegion-region" data-select2-id="select2-data-Relation-form">
                    <label class="form-label">Vrsta povezave:</label>
                    <!-- Dropdown -->
                    <select id="<?= $this->getId('select') ?>" name="<?= $name ?>[type]" class="form-control custom-select select-no-search" data-select2-id="select2-data-Form-field-<?= $name ?>" tabindex="-1" aria-hidden="true" data-disposable="">
                        <?php foreach ($types as $key => $type): ?>
                            <option selected="selected" value="<?= $key ?>" data-select2-id="<?= "select2-data-" . $key . "-" . md5($name . $type) ?>"><?= $type ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="w-full">
                    <label class="form-label">Povezava:</label>
                    <input
                        type="text"
                        id="<?= $this->getId('input') ?>"
                        name="<?= $name ?>[url]"
                        value="<?= $value['url'] ?? '' ?>"
                        class="form-control"
                        autocomplete="off" />
                </div>
            </div>

            <div>
                <label class="form-label">Naslov povezave:</label>
                <input
                    type="text"
                    id="<?= $this->getId('label') ?>"
                    name="<?= $name ?>[label]"
                    value="<?= $value['label'] ?? '' ?>"
                    class="form-control"
                    autocomplete="off" />
            </div>

        </div>
    </div>
<?php endif ?>
