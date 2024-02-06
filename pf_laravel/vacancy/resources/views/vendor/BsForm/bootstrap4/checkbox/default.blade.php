<?php

$id = 'checkbox-' . Str::random('4') . '-' . $name;
$labelAttributes['class'] = 'custom-control-label ' . ($labelAttributes['class'] ?? '');
?>

<div {!! Html::attributes($wrapperAttributes) !!}>
    @if($hasDefaultValue)
        {{ Form::hidden($name, $defaultValue) }}
    @endif
    {{ Form::checkbox($name, $value, $checked, ['class' => 'custom-control-input', 'id' => $id]) }}

    {{ Form::label($id, $label, $labelAttributes) }}

    <small class="form-text text-muted">{{ $note }}</small>
</div>

