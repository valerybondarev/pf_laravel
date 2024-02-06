<?php


namespace App\Domain\Codex\Blocks;


use Arr;

class Carousel extends BaseBlock
{
    public const TYPE = 'carousel';

    public function renderImage($image): string
    {
        return $this->wrap(
            $this->tag('img', '', array_merge($this->config('image', []), [
                'src' => Arr::get($image, 'url'),
                'alt' => Arr::get($image, 'caption'),
            ])),
            $this->config('image', [])
        );
    }

    public function appendHtml()
    {
        return $this->config('append', '');
    }

    public function html(): ?string
    {
        return collect($this->data)->map(fn($image) => $this->renderImage($image))->join(PHP_EOL);
    }

    public function render(): ?string
    {
        return parent::render() . $this->appendHtml();
    }
}
