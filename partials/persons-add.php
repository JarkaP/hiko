<?php
$pods_types = get_hiko_post_types_by_url();
$entity_type = $pods_types['person'];
$professions = get_professions($pods_types['profession'], $pods_types['default_lang']);
$action = array_key_exists('edit', $_GET) ? 'edit' : 'new';
$entity = isset($_GET['edit']) ? get_entity($entity_type, (int) $_GET['edit'], $professions) : [];
if (array_key_exists('save_post', $_POST)) {
    echo save_entity($entity_type, $action);
}
show_alerts(); ?>

<?php if (isset($_GET['edit']) && empty($entity['name'])) : ?>
    <div class="alert alert-warning">
        Požadovaná položka nebyla nalezena. Pro vytvoření nové osoby / instituce použijte <a href="?">tento odkaz</a>.
    </div>
<?php else : ?>
    <script id="entity-data" type="application/json">
        <?= json_encode($entity, JSON_UNESCAPED_UNICODE) ?>
    </script>
    <div class="card bg-light" x-data="entityForm()" x-init="fetch()" x-cloak>
        <div class="card-body">
            <form id="entity-form" action="#" method="post" x-on:keydown.enter.prevent x-on:submit="handleSubmit(event)" autocomplete="off">
                <fieldset>
                    <div class="form-group required">
                        <label for="type">Type</label>
                        <select x-model="type" class="form-control form-control-sm" name="type" required>
                            <option value="person">Person</option>
                            <option value="institution">Institution</option>
                        </select>
                    </div>
                </fieldset>
                <fieldset x-show="type === 'institution'">
                    <div class="form-group required">
                        <label for="surname">Institution name</label>
                        <input x-model="surname" id="surname" type="text" class="form-control form-control-sm" name="surname" required>
                    </div>
                </fieldset>
                <div x-show="type === 'person'">
                    <fieldset>
                        <div class="form-group required">
                            <label for="surname">Surname</label>
                            <input x-model="surname" id="surname" type="text" class="form-control form-control-sm" name="surname" required>
                        </div>
                        <div class="form-group">
                            <label for="forename">Forename</label>
                            <input x-model="forename" type="text" class="form-control form-control-sm" id="forename" name="forename">
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="d-flex justify-content-between">
                            <div class="pr-3 form-group w-50">
                                <label for="birth_year">Birth year</label>
                                <input value="<?= input_value($entity, 'birth_year') ?>" id="birth_year" type="number" name="birth_year" class="form-control form-control-sm" max="<?= date('Y'); ?>">
                                <small class="form-text text-muted">
                                    format YYYY, e.g. 1660
                                </small>
                            </div>
                            <div class="pl-3 form-group w-50">
                                <label for="death_year">Death year</label>
                                <input value="<?= input_value($entity, 'death_year') ?>" type="number" id="death_year" name="death_year" class="form-control form-control-sm" max="<?= date('Y'); ?>">
                                <small class="form-text text-muted">
                                    format YYYY, e.g. 1660
                                </small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nationality">Nationality</label>
                            <input value="<?= input_value($entity, 'nationality') ?>" type="text" class="form-control form-control-sm" id="nationality" name="nationality">
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <input value="<?= input_value($entity, 'gender') ?>" type="text" class="form-control form-control-sm" id="gender" name="gender">
                        </div>
                        <div class="form-group">
                            <label for="profession_short" class="d-flex justify-content-between">
                                Palladio Profession <button type="button" class="p-0 pointer oi oi-reload btn btn-sm" @click="regenerateProfessions($event)" aria-label="Reload professions" title="Reload professions"></button>
                            </label>
                            <input type="text" value="<?= input_value($entity, 'profession_short') ?>" id="profession_short" name="profession_short" class="tagify-select">
                            <small class="form-text text-muted">
                                reorder professions by dragging
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="profession_detailed" class="d-flex justify-content-between">
                                Professions <button type="button" class="p-0 pointer oi oi-reload btn btn-sm" @click="regenerateProfessions($event)" aria-label="Reload professions" title="Reload professions"></button>
                            </label>
                            <input type="text" value="<?= input_value($entity, 'profession_detailed') ?>" id="profession_detailed" name="profession_detailed" class="tagify-select">
                            <small class="form-text text-muted">
                                reorder professions by dragging
                            </small>
                        </div>
                        <?php if ($action === 'edit' && (!empty($entity['profession']) && empty($entity['profession_detailed']))) : ?>
                            <div class="form-group">
                                <label for="professions_deprecated">
                                    Deprecated professions
                                </label>
                                <input type="text" id="professions_deprecated" class="form-control form-control-sm" value="<?= $entity['profession'] ?>" readonly>
                                <small class="form-text text-muted">
                                    use professions field
                                </small>
                            </div>
                        <?php endif; ?>
                    </fieldset>
                </div>
                <fieldset>
                    <div class="form-group">
                        <label for="viaf">VIAF ID</label>
                        <input x-model="viaf" type="text" class="form-control form-control-sm" name="viaf" id="viaf" <?= input_value($entity, 'viaf') ?>>
                    </div>
                    <div class="form-group">
                        <label for="note">Note on person / institution</label>
                        <textarea class="form-control form-control-sm" name="note" id="note"><?= input_value($entity, 'note') ?></textarea>
                    </div>
                </fieldset>
                <?php if (isset($entity['names'])) : ?>
                    <strong>Name as marked</strong>
                    <ul class="list-unstyled">
                        <?php foreach ($entity['names'] as $name) : ?>
                            <li>
                                <?= $name ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <div class="form-group">
                    <input type="hidden" :value="type">
                    <input type="hidden" name="save_post" value="<?= $action ?>">
                    <div class="mb-3 input-group">
                        <input x-bind:value="fullName()" name="fullname" type="text" class="form-control form-control-sm not-allowed" readonly>
                        <div class="input-group-append">
                            <input class="btn btn-primary btn-sm" type="submit" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
