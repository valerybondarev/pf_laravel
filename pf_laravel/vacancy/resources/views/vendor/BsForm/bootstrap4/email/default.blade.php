<?php
$invalidClass = $errors->{$errorBag}->has($nameWithoutBrackets) ? ' is-invalid' : '';

$wrapperAttributes['class'] .= $invalidClass;
$labelAttributes['class'] = 'form-control-label ' . ($labelAttributes['class'] ?? '');
?>
<div {!! Html::attributes($wrapperAttributes) !!}>
    @if($label)
        {{ Form::label($name, $label, $labelAttributes) }}
    @endif
    {{ Form::email($name, $value, array_merge(['class' => 'form-control'.$invalidClass], $attributes)) }}

    @if($inlineValidation)
        @if($errors->{$errorBag}->has($nameWithoutBrackets))
            <div class="invalid-feedback">
                {{ $errors->{$errorBag}->first($nameWithoutBrackets) }}
            </div>
        @else
            <small class="form-text text-muted">{{ $note }}</small>
        @endif
    @else
        <small class="form-text text-muted">{{ $note }}</small>
    @endif
</div>
