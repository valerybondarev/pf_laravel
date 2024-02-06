<?php
$id = 'checkbox-'.Str::random('4').'-'.$name;
$labelAttributes['class'] = 'form-check-label ' . ($labelAttributes['class'] ?? '');

?>

<div {!! Html::attributes($wrapperAttributes) !!}>
    <div class="form-check">
        {{ Form::radio($name, $value, $checked, ['class' => 'form-check-input', 'id' => $id]) }}
        
        {{ Form::label($id, $label, $labelAttributes) }}
    
        <small class="form-text text-muted">{{ $note }}</small>
    </div>
</div>


