<?php
/**
 * @var File[] $images
 *
 */

use App\Domain\Application\Entities\File;

$id = 'carousel-'. Str::random(4)
?>

@if(count($images))
    <div id="{{ $id }}" class="carousel slide @isset($class) {{ $class }} @endisset" data-ride="carousel">
        <div class="carousel-inner">
            @foreach($images as $image)
                <div class="carousel-item @if($loop->first) active @endif">
                    <div class="w-100 h-100 d-flex justify-content-center align-content-center">
                        <img class="carousel-item-image" src="{{ $image->widen($width ?? 300)->url }}" alt="">
                    </div>
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#{{ $id }}" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#{{ $id }}" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only text-dark">Next</span>
        </a>
    </div>
@endif
