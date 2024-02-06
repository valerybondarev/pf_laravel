<?php


namespace App\Domain\Codex\Interfaces;


interface CodexBlockInterface
{
    public function render(): ?string;
}
