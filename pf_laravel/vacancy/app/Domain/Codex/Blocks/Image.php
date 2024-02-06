<?php


namespace App\Domain\Codex\Blocks;


class Image extends BaseBlock
{
    public const TYPE = 'image';

    public function getImageUrl(): ?string
    {
        return $this->data('file.url');
    }

    public function getCaptionText(): ?string
    {
        return $this->data('caption');
    }

    public function renderCaption(): ?string
    {
        $tag = $this->config('caption.tag');
        $text = $this->getCaptionText();
        if ($tag && $text) {
            return $this->tag($tag, $text, $this->config('caption'));
        }

        return null;
    }

    public function renderImage(): ?string
    {
        $tag = $this->config('tag', 'img');

        return $this->tag($tag, '', array_merge($this->config, [
            'src' => $this->data('file.url')
        ]));
    }

    public function renderPicture($image): ?string
    {
        return $this->tag('picture', $image);
    }

    public function html(): ?string
    {
        return $this->renderPicture($this->renderImage()) . PHP_EOL . $this->renderCaption();
    }
}
