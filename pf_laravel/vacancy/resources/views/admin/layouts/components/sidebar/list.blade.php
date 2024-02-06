<?php
/**
 * @var array $list
 */
$icon = Arr::get($list, 'icon');
$label = Arr::get($list, 'label');
$items = Arr::get($list, 'items');
$permission = Arr::wrap(Arr::get($list, 'permissions'));

$listId = Str::random(8);

$isActive = collect($items)->filter(fn($item) => Route::is(str_replace('index', '*', Arr::get($item, 'route'))))->count() > 0;
?>
@can($permission)
	<li class="nav-item">
		<a class="nav-link @if($isActive) active @endif" href="#{{ $listId }}" data-toggle="collapse" role="button"
		   aria-expanded="{{ $isActive ? 'true' : 'false' }}" aria-controls="{{ $listId }}">
			{!! $icon !!}
			<span class="nav-link-text">{{ __($label) }}</span>
		</a>
		
		<div class="collapse @if($isActive) show @endif" id="{{ $listId }}">
			<ul class="nav nav-sm flex-column">
				@foreach($items as $item)
					@include('admin.layouts.components.sidebar.link', ['link' => $item])
				@endforeach
			</ul>
		</div>
	</li>
@endcan