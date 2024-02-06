<?php

$wrapperAttributes['class'] = $wrapperAttributes['class'] ?? 'form-group';

?>

<div {!! Html::attributes($wrapperAttributes) !!}>
    {{ Form::submit($label, $attributes + ['class' => "btn $className", 'name' => $name]) }}
</div>
