<?php


namespace App\Domain\Codex\Blocks;


class EmptyBlock extends BaseBlock
{
    public function render(): ?string
    {
        return null;
    }
}
