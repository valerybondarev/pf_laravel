<?php
/**
 * @var array $link
 */

$url = Arr::get($link, 'url');
$route = Arr::get($link, 'route');
$icon = Arr::get($link, 'icon');
$label = Arr::get($link, 'label');
$permission = Arr::wrap(Arr::get($link, 'permissions'));

$href = null;

if (isset($url) && $url) {
    $href = $url;
}
if (isset($route) && $route) {
    $href = route($route);
}

$isActive = Route::is(str_replace('index', '*', $route));
?>
@can($permission)
	<li class="nav-item @if($isActive) active @endif">
		<a href="{{ $href }}" class="nav-link @if($isActive) active @endif">
			{!! $icon !!}
			<span class="nav-link-text">{{__($label) }}</span>
		</a>
	</li>
@endcan
