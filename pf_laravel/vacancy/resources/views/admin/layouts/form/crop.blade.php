<?php

use App\Domain\Application\Repositories\FileRepository;

$file = $file ?? null;
$name = $name ?? null;
$label = $label ?? null;
$width = $width ?? null;
$height = $height ?? null;

if ($fileId = old($name)) {
    $file = (new FileRepository())->one($fileId);
}

?>

<div class="d-flex flex-column crop__container" cropper-container>
    @if($label)
        <p class="">{{ $label }}</p>
    @endif
    <div class="crop @if($file->exists ?? false) cropped @endif" cropper cropper-width="{{ $width }}" cropper-height="{{ $height }}">

        <div class="crop__preview">
            <img class="crop__preview__img" src="{{ $file->url ?? null }}" alt="" cropper-preview>
        </div>
        <label class="crop__upload">
            {{ Form::file(null, ['hidden' => true, 'cropper-file-input' => true, 'accept' => 'image/*']) }}
            @if ($errors->has($name))
                <span class="text-danger p-4"><small>{{ $errors->first($name) }}</small></span>
            @else
                <i class="fa fa-file-upload"></i>
            @endif
        </label>

        {{ Form::text($name, $file->id ?? null, ['hidden' => true, 'cropper-value-input' => true]) }}
    </div>

    <button cropper-clean type="button" class="btn btn-danger mt-3 w-100">{{ __('admin.crop.clear') }}</button>

</div>
