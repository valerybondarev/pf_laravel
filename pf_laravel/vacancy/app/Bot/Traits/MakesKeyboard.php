<?php

namespace App\Bot\Traits;

use App\Bot\Entities\Keyboard;
use App\Bot\Store\UserState;

trait MakesKeyboard
{
    /**
     * Description Добавляет обычную кнопку текста в клавиатуру
     * @param UserState|Keyboard $entity
     * @param string             $text
     * @param array              $callbackData
     * @param bool               $incrementRow
     */
    public function addBtn(UserState|Keyboard $entity, string $text, array $callbackData = [], bool $incrementRow = true): void
    {
        $entity->keyboardButtons[$entity->keyboardRow][] = [
            'text'          => $text,
            'callback_data' => json_encode($callbackData),
        ];
        if ($incrementRow) {
            $entity->keyboardRow++;
        }
        $entity->keyboard = ['keyboard' => $entity->keyboardButtons, 'resize_keyboard' => true];
    }

    /**
     * Description Добавляет встроенную в сообщение кнопку в клавиатуру
     * @param UserState|Keyboard $entity
     * @param string             $text
     * @param array              $callbackData
     * @param bool               $incrementRow
     */
    public function addInlineBtn(UserState|Keyboard $entity, string $text, array $callbackData = [], bool $incrementRow = true): void
    {
        $entity->keyboardButtons[$entity->keyboardRow][] = [
            'text'          => $text,
            'callback_data' => json_encode($callbackData),
        ];
        if ($incrementRow) {
            $entity->keyboardRow++;
        }
        $entity->keyboard = ['inline_keyboard' => $entity->keyboardButtons, 'resize_keyboard' => true];
    }

    public function resetKeyboard(UserState|Keyboard $entity): void
    {
        $entity->keyboardButtons = [];
        $entity->keyboardRow = 0;
        $entity->keyboard = null;
    }

    public function deleteKeyboard(UserState|Keyboard $entity): void
    {
        $this->resetKeyboard($entity);
        $entity->keyboard = ['remove_keyboard' => true];
    }
}