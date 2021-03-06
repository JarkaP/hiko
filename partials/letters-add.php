<?php
$pods_types = get_hiko_post_types_by_url();
$action = array_key_exists('edit', $_GET) ? 'edit' : 'new';
if (array_key_exists('save_post', $_POST)) {
    echo save_letter($pods_types['letter'], $action, $pods_types['path']);
}
$letter = isset($_GET['edit']) ? get_letter($pods_types, (int) $_GET['edit'], '', true) : [];
$locations = list_locations(); ?>
<style>
    /* TODO: fix regenerate buttons */
    .oi-reload {
        display: none;
    }
</style>
<div class="list-group list-group-sm mw-200 sticky-content">
    <a class="list-group-item list-group-item-action" href="#a-dates">Dates</a>
    <a class="list-group-item list-group-item-action" href="#a-author">Author</a>
    <a class="list-group-item list-group-item-action" href="#a-recipient">Recipient</a>
    <a class="list-group-item list-group-item-action" href="#a-origin">Origin</a>
    <a class="list-group-item list-group-item-action" href="#a-destination">Destination</a>
    <a class="list-group-item list-group-item-action" href="#a-content">Content</a>
    <a class="list-group-item list-group-item-action" href="#a-related-resource">Related resource</a>
    <a class="list-group-item list-group-item-action" href="#a-copies">Manifestations and repositories</a>
    <a class="list-group-item list-group-item-action" href="#a-description">Copyright</a>
    <a class="list-group-item list-group-item-action" href="#a-description">Description</a>
    <a class="list-group-item list-group-item-action" href="#a-status">Status</a>
</div>

<?php if (isset($_GET['edit']) && empty($letter['id'])) : ?>
    <div class="alert alert-warning">
        Požadovaná položka nebyla nalezena. Pro vytvoření nového dopisu použijte <a href="?">tento odkaz</a>.
    </div>
<?php else : ?>
    <script id="letter-data" type="application/json">
        <?= json_encode($letter, JSON_UNESCAPED_UNICODE) ?>
    </script>
    <div class="card bg-light" x-data="letterForm()" x-init="getInitData(), fetch(), window.addEventListener('beforeunload', (e) => { preventExit(e) });" x-cloak>
        <div class="card-body">
            <form method="post" id="letter-form" x-on:keydown.enter.prevent x-on:submit="handleSubmit(event)" autocomplete="off">
                <fieldset id="a-dates">
                    <legend>Dates of letter</legend>
                    <div class="d-flex justify-content-between">
                        <div class="pr-4 form-group">
                            <label for="date_year">Year</label>
                            <input x-model="year" type="number" name="date_year" id="date_year" class="form-control form-control-sm" min="0" max="<?= date('Y') ?>" value="<?= input_value($letter, 'date_year') ?>">
                            <small class="form-text text-muted">
                                format YYYY, e.g. 1660
                            </small>
                        </div>
                        <div class="pr-4 form-group">
                            <label for="date_month">Month</label>
                            <input x-model="month" id="date_month" type="number" name="date_month" class="form-control form-control-sm" min="0" max="12" value="<?= input_value($letter, 'date_month') ?>">
                            <small class="form-text text-muted">
                                format MM, e.g. 1
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="date_day">Day</label>
                            <input x-model="day" id="date_day" type="number" name="date_day" class="form-control form-control-sm" min="0" max="31" value="<?= input_value($letter, 'date_day') ?>">
                            <small class="form-text text-muted">
                                format DD, e.g. 8
                            </small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_marked">Date as marked on letter</label>
                        <input value="<?= input_value($letter, 'date_marked') ?>" id="date_marked" type="text" name="date_marked" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="date_uncertain" name="date_uncertain" <?= input_bool($letter, 'date_uncertain') ?>>
                        <label class="form-check-label" for="date_uncertain">
                            Date uncertain
                        </label>
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="date_approximate" name="date_approximate" <?= input_bool($letter, 'date_approximate') ?>>
                        <label class="form-check-label" for="date_approximate">
                            Date approximate
                        </label>
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="date_inferred" name="date_inferred" <?= input_bool($letter, 'date_inferred') ?>>
                        <label class="form-check-label" for="date_inferred">
                            Date inferred
                        </label>
                    </div>
                    <div class="mb-3 form-check">
                        <input x-model="dateIsRange" class="form-check-input" type="checkbox" id="date_is_range" name="date_is_range" <?= input_bool($letter, 'date_is_range') ?>>
                        <label class="form-check-label" for="date_is_range">
                            Date is range
                        </label>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div x-show="dateIsRange" class="pr-4 form-group">
                            <label for="range_year">Year 2</label>
                            <input id="range_year" type="number" name="range_year" class="form-control form-control-sm" min="0" max="<?= date('Y') ?>" value="<?= input_value($letter, 'range_year') ?>">
                            <small class="form-text text-muted">
                                2nd date, if range
                            </small>
                        </div>
                        <div x-show="dateIsRange === true" class="pr-4 form-group">
                            <label for="range_month">Month 2</label>
                            <input id="range_month" type="number" name="range_month" class="form-control form-control-sm" min="0" max="12" value="<?= input_value($letter, 'range_month') ?>">
                            <small class="form-text text-muted">
                                2nd date, if range
                            </small>
                        </div>
                        <div x-show="dateIsRange === true" class="form-group">
                            <label for="range_day">Day 2</label>
                            <input id="range_day" type="number" name="range_day" class="form-control form-control-sm" min="0" max="31" value="<?= input_value($letter, 'range_day') ?>">
                            <small class="form-text text-muted">
                                2nd date, if range
                            </small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_note">Notes on date</label>
                        <textarea id="date_note" name="date_note" class="form-control form-control-sm"><?= input_value($letter, 'date_note') ?></textarea>
                    </div>
                </fieldset>
                <fieldset id="a-author">
                    <legend>Author</legend>
                    <template x-for="(author, index) in authors" :key="author.id && author.id != '' ? author.id : author.key">
                        <div class="px-2 py-3 my-2 border rounded bg-light-50">
                            <button @click="removeAuthor(index)" type="button" class="close text-danger" aria-label="Remove author" title="Remove author">
                                &times;
                            </button>
                            <div class="form-group required">
                                <label x-bind:for="'author-' + index">Author</label>
                                <button type="button" class="p-0 ml-1 align-baseline btn btn-sm oi oi-reload" @click="regenerateSelectData('entities', $event)" title="Update persons"></button>
                                <input type="text" x-bind:value="JSON.stringify([{value: author.name, id: author.id}])" x-bind:id="'author-' + index" class="related-tagify hidden-tagify-remove" data-type="entitiesList" data-mode="select" data-target="authors" x-bind:data-index="index" required>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'marked-' + index">Author as marked</label>
                                <input x-bind:value="decodeHTML(authors[index]['marked'])" @input="authors[index]['marked'] = $event.target.value" x-bind:id="'marked-' + index" type="text" class="form-control form-control-sm">
                                <small class="form-text text-muted">
                                    author's name as written in letter
                                </small>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="addNewAuthor()" class="mt-2 mb-4 btn btn-sm btn-outline-info">
                        <span class="oi oi-plus"></span> Add author
                    </button>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="author_inferred" name="author_inferred" <?= input_bool($letter, 'author_inferred'); ?>>
                        <label class="form-check-label" for="author_inferred">
                            Author inferred
                        </label>
                        <small class="form-text text-muted">
                            author name not specified but can be deduced from the content of letter or related materials
                        </small>
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="author_uncertain" name="author_uncertain" <?= input_bool($letter, 'author_uncertain'); ?>>
                        <label class="form-check-label" for="author_uncertain">
                            Author uncertain
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="author_note">Notes on author</label>
                        <textarea name="author_note" id="author_note" class="form-control form-control-sm"><?= input_value($letter, 'author_note'); ?></textarea>
                    </div>
                    <input type="hidden" name="authors" x-bind:value="JSON.stringify(authors)">
                </fieldset>
                <fieldset id="a-recipient">
                    <legend>Recipient</legend>
                    <template x-for="(recipient, index) in recipients" :key="recipient.id && recipient.id != '' ? recipient.id : recipient.key">
                        <div class="px-2 py-3 my-2 border rounded bg-light-50">
                            <button @click="removeRecipient(index)" type="button" class="close text-danger" aria-label="Remove author" title="Remove recipient">
                                &times;
                            </button>
                            <div class="form-group required">
                                <label x-bind:for="'recipient-' + index">Recipient</label>
                                <button type="button" class="p-0 ml-1 align-baseline btn btn-sm oi oi-reload" @click="regenerateSelectData('entities', $event)" title="Update persons"></button>
                                <input type="text" x-bind:value="JSON.stringify([{value: recipient.name, id: recipient.id}])" x-bind:id="'recipient-' + index" class="related-tagify hidden-tagify-remove" data-type="entitiesList" data-mode="select" data-target="recipients" x-bind:data-index="index" required>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'recipient-marked-' + index">Recipient as marked</label>
                                <input x-bind:value="decodeHTML(recipients[index]['marked'])" @input="recipients[index]['marked'] = $event.target.value" x-bind:id="'recipient-marked-' + index" type="text" class="form-control form-control-sm">
                                <small class="form-text text-muted">
                                    recipient's name as written in letter
                                </small>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'salutation-' + index">Salutation</label>
                                <input x-bind:value="decodeHTML(recipients[index]['salutation'])" @input="recipients[index]['salutation'] = $event.target.value" x-bind:id="'salutation-' + index" type="text" class="form-control form-control-sm">
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="addNewRecipient()" class="mt-2 mb-4 btn btn-sm btn-outline-info">
                        <span class="oi oi-plus"></span>
                        Add recipient
                    </button>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="recipient_inferred" name="recipient_inferred" <?= input_bool($letter, 'recipient_inferred'); ?>>
                        <label class="form-check-label" for="recipient_inferred">
                            Recipient inferred
                        </label>
                        <small class="form-text text-muted">
                            recipient not specified but deduced from content of letter or related materials
                        </small>
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="recipient_uncertain" name="recipient_uncertain" <?= input_bool($letter, 'recipient_uncertain'); ?>>
                        <label class="form-check-label" for="recipient_uncertain">
                            Recipient uncertain
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="recipient_notes">Notes on recipient</label>
                        <textarea name="recipient_notes" id="recipient_notes" class="form-control form-control-sm"><?= input_value($letter, 'recipient_notes'); ?></textarea>
                    </div>
                    <input type="hidden" name="recipients" x-bind:value="JSON.stringify(recipients)">
                </fieldset>
                <fieldset id="a-origin">
                    <legend>Origin</legend>
                    <template x-for="(origin, index) in origins" :key="origin.id && origin.id != '' ? origin.id : origin.key">
                        <div class="px-2 py-3 my-2 border rounded bg-light-50">
                            <button @click="removeOrigin(index)" type="button" class="close text-danger" aria-label="Remove origin" title="Remove origin">
                                &times;
                            </button>
                            <div class="form-group required">
                                <label x-bind:for="'origin-' + index">Origin</label>
                                <button type="button" class="p-0 ml-1 align-baseline btn btn-sm oi oi-reload" @click="regenerateSelectData('places', $event)" title="Update places"></button>
                                <input type="text" x-bind:value="JSON.stringify([{value: origin.name, id: origin.id}])" x-bind:id="'origin-' + index" class="related-tagify hidden-tagify-remove" data-type="placesList" data-mode="select" data-target="origins" x-bind:data-index="index" required>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'origin-marked-' + index">Origin as marked</label>
                                <input x-bind:value="decodeHTML(origins[index]['marked'])" @input="origins[index]['marked'] = $event.target.value" x-bind:id="'origin-marked-' + index" type="text" class="form-control form-control-sm">
                                <small class="form-text text-muted">
                                    origin name as written in letter
                                </small>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="addNewOrigin()" class="my-2 btn btn-sm btn-outline-info">
                        <span class="oi oi-plus"></span>
                        Add origin
                    </button>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="origin_inferred" name="origin_inferred" <?= input_bool($letter, 'origin_inferred'); ?>>
                        <label class="form-check-label" for="origin_inferred">
                            Origin inferred
                        </label>
                        <small class="form-text text-muted">
                            origin not specified but deduced from letter content or related materials
                        </small>
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="origin_uncertain" name="origin_uncertain" <?= input_bool($letter, 'origin_uncertain'); ?>>
                        <label class="form-check-label" for="origin_uncertain">
                            Origin uncertain
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="origin_note">Notes on origin</label>
                        <textarea name="origin_note" id="origin_note" class="form-control form-control-sm"><?= input_value($letter, 'origin_note'); ?></textarea>
                    </div>
                    <input type="hidden" name="origin" x-bind:value="JSON.stringify(origins)">
                </fieldset>
                <fieldset id="a-destination">
                    <legend>Destination</legend>
                    <template x-for="(destination, index) in destinations" :key="destination.id && destination.id != '' ? destination.id : destination.key">
                        <div class="px-2 py-3 my-2 border rounded bg-light-50">
                            <button @click="removeDestination(index)" type="button" class="close text-danger" aria-label="Remove destination" title="Remove destination">
                                &times;
                            </button>
                            <div class="form-group required">
                                <label x-bind:for="'destination-' + index">Destination</label>
                                <button type="button" class="p-0 ml-1 align-baseline btn btn-sm oi oi-reload" @click="regenerateSelectData('places', $event)" title="Update places"></button>
                                <input type="text" x-bind:value="JSON.stringify([{value: destination.name, id: destination.id}])" x-bind:id="'destination-' + index" class="related-tagify hidden-tagify-remove" data-type="placesList" data-mode="select" data-target="destinations" x-bind:data-index="index" required>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'destination-marked-' + index">Destination as marked</label>
                                <input x-bind:value="decodeHTML(destinations[index]['marked'])" @input="destinations[index]['marked'] = $event.target.value" x-bind:id="'destination-marked-' + index" type="text" class="form-control form-control-sm">
                                <small class="form-text text-muted">
                                    destination name as written in letter
                                </small>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="addNewDestination" class="my-2 btn btn-sm btn-outline-info">
                        <span class="oi oi-plus"></span>
                        Add destination
                    </button>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="dest_inferred" name="dest_inferred" <?= input_bool($letter, 'dest_inferred'); ?>>
                        <label class="form-check-label" for="dest_inferred">
                            Destination inferred
                        </label>
                        <small class="form-text text-muted">
                            destination not specified but deduced from letter content or related materials
                        </small>
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="dest_uncertain" name="dest_uncertain" <?= input_bool($letter, 'dest_uncertain'); ?>>
                        <label class="form-check-label" for="dest_uncertain">
                            Destination uncertain
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="dest_note">Notes on destination</label>
                        <textarea name="dest_note" id="dest_note" class="form-control form-control-sm"><?= input_value($letter, 'dest_note'); ?></textarea>
                    </div>
                    <input type="hidden" name="dest" x-bind:value="JSON.stringify(destinations)">
                </fieldset>
                <fieldset id="a-content">
                    <legend>Content</legend>
                    <div class="form-group">
                        <label for="languages">Languages</label>
                        <input type="text" id="languages" name="languages" class="simple-tagify" data-type="languagesList" value="<?= input_value_list($letter, 'languages'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="keywords">
                            Keywords <button type="button" class="p-0 ml-1 align-baseline btn btn-sm oi oi-reload" @click="regenerateSelectData('keywords', $event)"></button>
                        </label>
                        <input type="text" value="<?= input_json_value($letter, 'keywords') ?>" id="keywords" name="keywords" class="related-tagify" data-type="keywordsList">
                    </div>
                    <div class="form-group">
                        <label for="abstract">Abstract</label>
                        <textarea id="abstract" name="abstract" class="form-control form-control-sm"><?= input_value($letter, 'abstract') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="incipit">Incipit</label>
                        <textarea id="incipit" name="incipit" class="form-control form-control-sm"><?= input_value($letter, 'incipit') ?></textarea>
                        <small class="form-text text-muted">
                            exact words opening body of letter, e.g. opening sentence or first 10 words, but not opening salutation
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="explicit">Explicit</label>
                        <textarea id="explicit" name="explicit" class="form-control form-control-sm"><?= input_value($letter, 'explicit') ?></textarea>
                        <small class="form-text text-muted">
                            exact words which close the body of the letter, e.g. closing sentence or closing 10 words, but not closing salutation
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="people_mentioned">
                            People mentioned <button type="button" class="p-0 ml-1 align-baseline btn btn-sm oi oi-reload" @click="regenerateSelectData('entities', $event)"></button>
                        </label>
                        <input type="text" name="people_mentioned" id="people_mentioned" class="related-tagify" data-type="entitiesList" value="<?= input_json_value($letter, 'people_mentioned') ?>">
                    </div>
                    <div class="form-group">
                        <label for="people_mentioned_notes">Notes on people mentioned</label>
                        <textarea id="people_mentioned_notes" name="people_mentioned_notes" class="form-control form-control-sm"><?= input_value($letter, 'people_mentioned_notes') ?></textarea>
                        <small class="form-text text-muted">
                            exact words which close the body of the letter, e.g. closing sentence or closing 10 words, but not closing salutation
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="notes_public">Notes on letter for public display</label>
                        <textarea id="notes_public" name="notes_public" class="form-control form-control-sm"><?= input_value($letter, 'notes_public') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="notes_private">Editor' notes </label>
                        <textarea id="notes_private" name="notes_private" class="form-control form-control-sm"><?= input_value($letter, 'notes_private') ?></textarea>
                        <small class="form-text text-muted">
                            internal, hidden editors' notes for EMLO back-end use only. Preface each note with a ‘q’ followed by the initials of the person the note is intended for, e.g. ‘qml’. End each note with your own initials.
                        </small>
                    </div>
                </fieldset>
                <fieldset id="a-related-resource">
                    <legend>Related resource</legend>
                    <template x-for="rr, index in relatedResources" :key="index">
                        <div class="px-2 py-3 my-2 border rounded bg-light-50">
                            <button @click="removeRelatedResource(index)" type="button" class="close text-danger" aria-label="Remove related resource" title="Remove related resource">
                                &times;
                            </button>
                            <div class="form-group">
                                <label x-bind:for="'rel_rec_name' + index">Related resource name</label>
                                <input type="text" class="form-control form-control-sm" x-model="relatedResources[index]['title']" x-bind:id="'rel_rec_name' + index">
                                <small class="form-text text-muted">
                                    descriptor
                                </small>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'rel_rec_url' + index">Related resource url</label>
                                <input type="url" class="form-control form-control-sm" x-model="relatedResources[index]['link']" x-bind:id="'rel_rec_url' + index">
                                <small class="form-text text-muted">
                                    permanent URL to online resource
                                </small>
                            </div>
                        </div>
                    </template>
                    <button type="button" class="mt-2 mb-4 btn btn-sm btn-outline-info" @click="addNewRelatedResource()">
                        <span class="oi oi-plus"></span> Add
                    </button>
                    <input type="hidden" name="related_resources" x-bind:value="JSON.stringify(relatedResources)">
                </fieldset>
                <fieldset id="a-copies">
                    <legend>Manifestations and repositories</legend>
                    <template x-for="(c, index) in copies" :key="index">
                        <div class="px-2 py-1 mt-2 mb-4 border rounded bg-light-50">
                            <button @click="removeCopy(index)" type="button" class="close text-danger" aria-label="Remove copy" title="Remove copy">
                                &times;
                            </button>
                            <div class="form-group">
                                <label x-bind:for="'ms_manifestation' + index">MS manifestation (EMLO)</label>
                                <select x-model="copies[index]['ms_manifestation']" x-bind:id="'ms_manifestation' + index" class="form-control form-control-sm">
                                    <option value>---</option>
                                    <option x-bind:value="'E'" :selected="copies[index]['ms_manifestation'] === 'E'">Extract</option>
                                    <option x-bind:value="'S'" :selected="copies[index]['ms_manifestation'] === 'S'">MS Copy</option>
                                    <option x-bind:value="'D'" :selected="copies[index]['ms_manifestation'] === 'D'">MS Draft</option>
                                    <option x-bind:value="'ALS'" :selected="copies[index]['ms_manifestation'] === 'ALS'">MS Letter</option>
                                    <option x-bind:value="'O'" :selected="copies[index]['ms_manifestation'] === 'O'">Other</option>
                                    <option x-bind:value="'P'" :selected="copies[index]['ms_manifestation'] === 'P'">Printed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'type' + index">Document type</label>
                                <select x-model="copies[index]['type']" x-bind:id="'type' + index" class="form-control form-control-sm">
                                    <option value>---</option>
                                    <option x-bind:value="'calling card'" :selected="copies[index]['type'] === 'calling card'">calling card</option>
                                    <option x-bind:value="'greeting card'" :selected="copies[index]['type'] === 'greeting card'">greeting card</option>
                                    <option x-bind:value="'invitation card'" :selected="copies[index]['type'] === 'invitation card'">invitation card</option>
                                    <option x-bind:value="'letter'" :selected="copies[index]['type'] === 'letter'">letter</option>
                                    <option x-bind:value="'picture postcard'" :selected="copies[index]['type'] === 'picture postcard'">picture postcard</option>
                                    <option x-bind:value="'postcard'" :selected="copies[index]['type'] === 'postcard'">postcard</option>
                                    <option x-bind:value="'telegram'" :selected="copies[index]['type'] === 'telegram'">telegram</option>
                                    <option x-bind:value="'visiting card'" :selected="copies[index]['type'] === 'visiting card'">visiting card</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'preservation' + index">Preservation</label>
                                <select x-model="copies[index]['preservation']" x-bind:id="'preservation' + index" class="form-control form-control-sm">
                                    <option value>---</option>
                                    <option x-bind:value="'carbon copy'" :selected="copies[index]['preservation'] === 'carbon copy'">carbon copy</option>
                                    <option x-bind:value="'copy'" :selected="copies[index]['preservation'] === 'copy'">copy</option>
                                    <option x-bind:value="'draft'" :selected="copies[index]['preservation'] === 'draft'">draft</option>
                                    <option x-bind:value="'original'" :selected="copies[index]['preservation'] === 'original'">original</option>
                                    <option x-bind:value="'photocopy'" :selected="copies[index]['preservation'] === 'photocopy'">photocopy</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'copy' + index">Type of copy</label>
                                <select x-model="copies[index]['copy']" x-bind:id="'copy' + index" class="form-control form-control-sm">
                                    <option value>---</option>
                                    <option x-bind:value="'handwritten'" :selected="copies[index]['copy'] === 'handwritten'">handwritten</option>
                                    <option x-bind:value="'typewritten'" :selected="copies[index]['copy'] === 'typewritten'">typewritten</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'manifestation_notes' + index">Notes on manifestation</label>
                                <textarea x-bind:value="decodeHTML(copies[index]['manifestation_notes'])" @input="copies[index]['manifestation_notes'] = $event.target.value" x-bind:id="'manifestation_notes' + index" class="form-control form-control-sm"></textarea>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'lnumber' + index">Letter number</label>
                                <input x-bind:value="decodeHTML(copies[index]['l_number'])" @input="copies[index]['l_number'] = $event.target.value" x-id:for="'lnumber' + index" type="text" class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'repository' + index">Repository</label>
                                <input x-bind:value="decodeHTML(copies[index]['repository'])" @input="copies[index]['repository'] = $event.target.value" list="repositories" type="text" x-bind:id="'repository' + index" class="form-control form-control-sm">
                                <datalist id="repositories">
                                    <?php foreach ($locations as $loc) : ?>
                                        <?php if ($loc['type'] === 'repository') : ?>
                                            <option>
                                                <?= $loc['name'] ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'archive' + index">Archive</label>
                                <input x-bind:value="decodeHTML(copies[index]['archive'])" @input="copies[index]['archive'] = $event.target.value" list="archives" type="text" x-bind:id="'archive' + index" class="form-control form-control-sm">
                                <datalist id="archives">
                                    <?php foreach ($locations as $loc) : ?>
                                        <?php if ($loc['type'] === 'archive') : ?>
                                            <option>
                                                <?= $loc['name'] ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'collection' + index">Collection</label>
                                <input x-bind:value="decodeHTML(copies[index]['collection'])" @input="copies[index]['collection'] = $event.target.value" list="collections" type="text" x-bind:id="'collection' + index" class="form-control form-control-sm">
                                <datalist id="collections">
                                    <?php foreach ($locations as $loc) : ?>
                                        <?php if ($loc['type'] === 'collection') : ?>
                                            <option>
                                                <?= $loc['name'] ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'signature' + index">Signature</label>
                                <input x-bind:value="decodeHTML(copies[index]['signature'])" @input="copies[index]['signature'] = $event.target.value" x-id:for="'signature' + index" type="text" class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label x-bind:for="'location_note' + index">Notes on location</label>
                                <textarea x-bind:value="decodeHTML(copies[index]['location_note'])" @input="copies[index]['location_note'] = $event.target.value" x-id:for="'location_note' + index" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="addCopy()" class="mt-2 mb-4 btn btn-sm btn-outline-info">
                        <span class="oi oi-plus"></span> Add letter copy
                    </button>
                    <input type="hidden" name="copies" x-bind:value="JSON.stringify(copies)">
                </fieldset>
                <fieldset id="a-copyright">
                    <legend>Copyright</legend>
                    <div class="form-group">
                        <label for="copyright">Copyright</label>
                        <textarea id="copyright" name="copyright" class="form-control form-control-sm"><?= input_value($letter, 'copyright') ?></textarea>
                    </div>
                </fieldset>
                <fieldset id="a-description">
                    <legend>Description</legend>
                    <div class="form-group required">
                        <label for="description">Description</label>
                        <button type="button" class="p-0 ml-1 align-baseline btn btn-sm oi oi-transfer" @click="generateDescription()" title="Generate description" aria-label="Generate description"></button>
                        <textarea x-model="description" name="description" class="form-control form-control-sm" required><?= input_value($letter, 'name') ?></textarea>
                        <small class="form-text text-muted">
                            "DD. MM. YYYY Author (Origin) to Recipient (Destination)", vygenerovat pomocí ikonky
                        </small>
                    </div>
                </fieldset>
                <fieldset id="a-status" class="form-group">
                    <legend>Status</legend>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="status" value="draft" required <?= !isset($letter['status']) ? 'checked' : ''; ?> <?= isset($letter['status']) && $letter['status'] !== 'publish' ? 'checked' : ''; ?>>
                            Private
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="status" value="publish" required <?= isset($letter['status']) && $letter['status'] === 'publish' ? 'checked' : ''; ?>>
                            Public
                        </label>
                    </div>
                </fieldset>
                <input type="hidden" name="save_post" value="<?= $action ?>">
                <input type="submit" value="Uložit" class="btn btn-primary">
                <?php if ($action === 'edit') : ?>
                    <a href="<?= home_url('/' . $pods_types['path'] . '/letters-media/?l_type=' . $pods_types['letter'] . '&letter=' . $_GET['edit']) ?>" class="btn btn-secondary" target="_blank">
                        Obrazové přílohy
                    </a>
                    <a href="<?= home_url('/letter-preview/?l_type=' . $pods_types['letter'] . '&letter=' . $_GET['edit'] . '&lang=' . $pods_types['default_lang']) ?>" class="btn btn-secondary" target="_blank">
                        Náhled
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>
<?php endif; ?>

<script id="entities" type="application/json">
    <?= json_encode(
        list_entities($pods_types['person']),
        JSON_UNESCAPED_UNICODE
    ); ?>
</script>

<script id="places-list" type="application/json">
    <?= list_places($pods_types['place'], false); ?>
</script>

<script id="languages-list" type="application/json">
    <?= json_encode(get_languages()); ?>
</script>

<script id="keywords-list" type="application/json">
    <?= json_encode(get_keywords_names($pods_types['keyword'], $pods_types['default_lang'])); ?>
</script>

<script id="location-list" type="application/json">
    <?php  ?>
</script>
