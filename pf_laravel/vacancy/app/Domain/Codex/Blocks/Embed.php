<?php


namespace App\Domain\Codex\Blocks;


class Embed extends BaseBlock
{
    public const TYPE = 'embed';

    public function html(): ?string
    {
        $tag = $this->config('tag', 'iframe');
        return $this->tag($tag, '', array_merge($this->config, [
            'src' => $this->data('embed'),
            'border' => 0,
            'allowfullscreen' => true,
            'frameborder' => 0,
            'allow' => 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture'
        ]));
    }
}
