<li class="dvs-field" data-dvs-field-id="{fid}">
    <div class="ui-sortable-handle"><?= Form::text('fields[{fid}][name]', null, ['class' => 'dvs-pl', 'placeholder' => 'Name']) ?>
        <?= Form::select('fields[{fid}][type]', ['' => 'Field Type'] + $fieldTypesList, null, ['class' => 'dvs-not-null']) ?>
        <?= Form::text('fields[{fid}][label]', null, ['class' => 'dvs-pl', 'placeholder' => 'Label']) ?>
        <?= Form::text('fields[{fid}][default]', null, ['class' => 'dvs-pl', 'placeholder' => 'Default Value']) ?>
        <?= Form::select('fields[{fid}][formType]', ['' => 'Form Type'] + $formTypesList, null, ['class' => 'dvs-form-type dvs-not-null']) ?>
        <?= Form::button('<span class="ion-android-close"></span>', array('class' => 'dvs-remove-field dvs-button dvs-button-danger dvs-button-tiny dvs-pr')) ?>
        <div class="dvs-form-group dvs-borderless">
            <button class="dvs-hidden dvs-add-choice dvs-button dvs-button-secondary dvs-button-tiny" type="button">Add Choice</button>
            <label>
                <?= Form::hidden('fields[{fid}][displayForm]', 'off') ?>
                <?= Form::checkbox('fields[{fid}][displayForm]', 'on', true) ?>
                On Form
            </label>
            <label>
                <?= Form::hidden('fields[{fid}][displayIndex]', 'off') ?>
                <?= Form::checkbox('fields[{fid}][displayIndex]', 'on', true) ?>
                On Index
            </label>
            <label>
                <?= Form::checkbox('fields[{fid}][index]', 'index', false) ?>
                Is Index
            </label>
            <label>
                <?= Form::hidden('fields[{fid}][nullable]', 'off') ?>
                <?= Form::checkbox('fields[{fid}][nullable]', 'on', false) ?>
                Is Nullable
            </label>
        </div>
        <ol class="dvs-choices-list"></ol>
    </div>
</li>