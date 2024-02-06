<?php


namespace App\Domain\Codex\Blocks;


class Header extends BaseBlock
{
    public const TYPE = 'header';

    public function getTag()
    {
        return $this->config('tag', 'h' . $this->data('level', 1));
    }

    public function getText(): ?string
    {
        return $this->processText($this->data('text'));
    }

    public function html(): ?string
    {
        return $this->tag($this->getTag(), $this->getText(), $this->config);
    }
}
