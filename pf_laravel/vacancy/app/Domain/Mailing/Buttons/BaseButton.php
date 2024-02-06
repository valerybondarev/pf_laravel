<?php

namespace App\Domain\Mailing\Buttons;

use App\Bot\Entities\Keyboard;
use App\Bot\Traits\MakesKeyboard;
use App\Domain\Mailing\Entities\MailingButton;
use Illuminate\Validation\ValidationException;

abstract class BaseButton implements ButtonInterface
{
    use MakesKeyboard;

    public string $type;
    public array  $json;
    public array  $rules;

    /**
     * Description Валидация входных данных
     *
     * @throws ValidationException
     */
    public function validate($data): static
    {
        $validator = \Validator::make($data, $this->rules);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->messages()->toArray());
        }
        return $this;
    }

    public function addToKeyboard(Keyboard $keyboard, MailingButton $button): void
    {
        $this->addInlineBtn($keyboard, $button->title, ["mailingButton.default", $button->id]);
    }
}