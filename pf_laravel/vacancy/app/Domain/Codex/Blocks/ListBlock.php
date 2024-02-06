<?php


namespace App\Domain\Codex\Blocks;


class ListBlock extends BaseBlock
{
    public const TYPE = 'list';

    public function getTag(): string
    {
        return $this->config('style', 'unordered') === 'unordered' ? 'ul' : 'ol';
    }

    public function renderText($text): string
    {
        return $this->wrap($text, $this->config('item.text', []));
    }

    public function renderItem($text): ?string
    {
        return $this->wrap(
            $this->tag($this->config('item.tag', 'li'), $this->renderText($text), $this->config('item', [])),
            $this->config('item', [])
        );
    }

    public function renderItems(): string
    {
        $items = $this->data('items', []);

        return implode(PHP_EOL, array_map(fn($item) => $this->renderItem($item), $items));
    }

    public function renderList($content): ?string
    {
        $tag = $this->config('tag', $this->getTag());
        return $this->tag($tag, $content, $this->config);
    }

    public function html(): ?string
    {
        return $this->renderList($this->renderItems());
    }
}
