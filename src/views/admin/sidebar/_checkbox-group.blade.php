<?= Form::open(array('route' => array('dvs-fields-update', $element->id), 'method' => 'put', 'class' => 'dvs-element-checkbox-group', 'data-dvs-field-id' => $element->id, 'data-dvs-field-type' => $element->dvs_type, 'id' => 'dvs-sidebar-field-form')) ?>

    @include('devise::admin.sidebar._collection_instance_id')

    <div class="dvs-editor-values">
        <div class="dvs-checkboxes">
            @if($element->value->checkboxes)
                @foreach ($element->value->checkboxes as $checkbox)
                    @php
                        $keyname = $checkbox->key;
                    @endphp
                    <div class="dvs-checkbox">
                        <?= Form::hidden($checkbox->key, 0) ?>
                        <label>
                            <?= Form::checkbox($checkbox->key, 1, ($element->value->$keyname) ? $element->value->$keyname : $checkbox->default) ?> <span><?= $checkbox->label ?></span>
                        </label>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <hr>

    <div class="dvs-editor-settings">

        <table class="dvs-checkboxes-manager">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Label</th>
                    <th>Default</th>
                    <th><button type="button" class="dvs-button dvs-button-small dvs-new-checkbox dvs-button-secondary">New Option</button></th>
                </tr>
            </thead>
            <tbody>
                @if($element->value->checkboxes)
                    @foreach ($element->value->checkboxes as $index => $checkbox)
                        <tr class="dvs-checkbox-fields">
                            <td><?= Form::text('checkboxes['.$index.'][key]', $checkbox->key)?></td>
                            <td><?= Form::text('checkboxes['.$index.'][label]', $checkbox->label)?></td>
                            <td><?= Form::select('checkboxes['.$index.'][default]',['Off','On'], $checkbox->default, ['class' => 'dvs-select dvs-select-small'])?></td>
                            <td><button type="button" class="dvs-button dvs-button-small dvs-table-row-delete">Delete</button></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        @include('devise::admin.sidebar._field_scope')

    </div>
<?= Form::close() ?>

<div style="display:none">
    <table><tbody class="dvs-row-template">
        <tr class="dvs-checkbox-fields">
            <td><?= Form::text('checkboxes[0][key]')?></td>
            <td><?= Form::text('checkboxes[0][label]')?></td>
            <td><?= Form::select('checkboxes[0][default]',['Off','On'], null, ['class' => 'dvs-select dvs-select-small'])?></td>
            <td><button type="button" class="dvs-button dvs-button-small dvs-table-row-delete">Delete</button></td>
        </tr>
    </tbody></table>
    <div class="dvs-checkbox-template">
        <div class="dvs-checkbox">
            <?= Form::hidden('') ?>
            <label>
                <?= Form::checkbox('') ?> <span></span>
            </label>
        </div>
    </div>
</div>

<script type="text/javascript">
    devise.require(['app/sidebar/checkbox-group'], function(obj)
    {
        obj.init();
    });
</script>