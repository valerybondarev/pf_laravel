<?php


namespace App\Domain\Codex\Blocks;


class Quote extends BaseBlock
{
    public const TYPE = 'quote';

    public function renderText(): string
    {
        $tag = $this->config('text.tag', 'blockquote');

        return $this->wrap(
            $this->tag($tag, $this->data('text'), $this->config('text', [])),
            $this->config('text', [])
        );
    }

    public function renderCaption(): ?string
    {
        $tag = $this->config('caption.tag', 'p');
        $caption = $this->data('caption');
        return $caption
            ? $this->wrap(
                $this->tag($tag, $caption, $this->config('caption', [])),
                $this->config('caption', [])
            )
            : null;
    }

    public function html(): ?string
    {
        return $this->renderText() . $this->renderCaption();
    }
}
