<?php

namespace App\Bot\Helpers;


use App\Bot\Store\UserState;

class UserStateTextBuilder
{
    public function __construct(protected UserState $userState)
    {
    }

    public static function make($userState): static
    {
        return new static($userState);
    }

    public function content(): string
    {
        return $this->userState->messageText;
    }

    public function t($key, $variables = []): static
    {
        $this->userState->messageText = __($key, $variables);

        return $this;
    }

    public function tAdd($key, $variables = []): static
    {
        $this->userState->messageText .= __($key, $variables);

        return $this;
    }

    public function p(string $string): static
    {
        return $this->writeLn($string)->newLine();
    }

    public function writeLn(string $string): static
    {
        return $this->newLine()->write($string);
    }

    public function newLine(int $count = 1): static
    {
        $this->userState->messageText .= str_repeat("\n", $count);

        return $this;
    }

    public function writeBold($string, $reset = false): static
    {
        return $this->write("<b>$string</b>", $reset);
    }

    public function writeItalic($string, $reset = false): static
    {
        return $this->write("<i>$string</i>", $reset);
    }

    public function write($string, $reset = false): static
    {
        if ($reset) {
            $this->userState->messageText = $string;
        } else {
            $this->userState->messageText .= $string;
        }

        return $this;
    }

    public function writeNew($string): static
    {
        $this->userState->messageText = $string;

        return $this;
    }

}
