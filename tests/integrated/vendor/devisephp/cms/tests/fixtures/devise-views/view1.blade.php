@if ($isTrue)
	<div data-devise="keyname1, type, humanName">
		stuff goes here
	</div>
@else
	<div data-devise="keyname2, type, humanName">
		<div data-devise="keyname3, type, humanName">
			more stuff here
		</div>
	</div>
@endif

@if($stuff)
	@foreach($stuff as $thing)
		<div data-devise="keyname4, type, humanName"></div>
	@endforeach
@endif

<div data-devise="col[keyname5], type, humanName, groupName, categoryName"></div>

@include('devise-views::view2')

@if ($moreStuff)
	@include('devise-views::view2')
@endif