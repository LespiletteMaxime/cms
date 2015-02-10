@extends('devise::admin.layouts.master')

@section('title')
     <div id="dvs-admin-title">
        <h1>Editing Menu</h1>

        <p>Update menu details and set order and parent/child item(s).</p>
    </div>

    <div id="dvs-admin-actions">
        <?= link_to(URL::route('dvs-menus'), 'List of Menus', array('class'=>'dvs-button')) ?>
    </div>
@stop

@section('main')
    <div class="dvs-admin-form-horizontal">
        <?= Form::open(array('method' => 'put', 'route' => array('dvs-menus-update', $menu->id), 'class' => 'js-menu-form')) ?>

    		<div class="dvs-form-group">
    		    <?= Form::label('Menu Name') ?>
    		    <?= Form::text('name', $menu->name, array('placeholder' => 'Menu')) ?>
    		</div>

    		<div class="dvs-form-group">
    		    <?= Form::label('Menu Items') ?>
    		    <button type="button" class="dvs-button js-add-menu-item">Add Item</button>
    		</div>

    		<div class="dvs-form-group">

    			<ol class="sortable dvs-menu-items">
    				@foreach ($menu->items as $item)
    					@include('devise::admin.menus._items', ['item' => $item])
    				@endforeach
    			</ol>
    		</div>

    		<?= Form::submit('Update Menu', array('class' => 'dvs-button dvs-button-large')) ?>
        <?= Form::close() ?>
    </div>

    @include('devise::admin.menus._itemsjs')

    <script>
        var autocompletePagesUrl = "{{ URL::route('dvs-pages-list', ['includeAdmin' => 0]) }}";

        devise.require(['app/admin/menus']);
    </script>

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/packages/devisephp/cms/css/jquery.nestedSortable.css') }}">

@stop