<?php
/**
 * @var array $section
 */

$divide = Arr::get($section, 'divide', true);
$label = Arr::get($section, 'label');
$items = Arr::get($section, 'items', []);
$permission = Arr::wrap(Arr::get($section, 'permissions'));

?>
@can($permission)
	@if ($divide)
		<hr class="my-3">
	@endif
	@if ($label)
		<h6 class="navbar-heading p-0 text-muted">{!! __($label) !!}</h6>
	@endif
	
	<ul class="navbar-nav">
		@foreach($items as $item)
			@isset($item['items'])
				@include('admin.layouts.components.sidebar.list', ['list' => $item])
			@else
				@include('admin.layouts.components.sidebar.link', ['link' => $item])
			@endisset
		@endforeach
	</ul>
@endif