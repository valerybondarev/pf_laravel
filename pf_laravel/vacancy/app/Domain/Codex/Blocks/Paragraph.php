<?php


namespace App\Domain\Codex\Blocks;


class Paragraph extends BaseBlock
{
    public const TYPE = 'paragraph';

    public function getTag()
    {
        return $this->config('tag', 'p');
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