<?php


namespace App\Domain\Codex\Blocks;


use App\Domain\Codex\Interfaces\CodexBlockInterface;
use Collective\Html\HtmlFacade;
use Illuminate\Support\Arr;

abstract class BaseBlock implements CodexBlockInterface
{
    public function __construct(
        protected $data,
        protected $config
    )
    {
    }

    public function html(): ?string
    {
        return null;
    }

    public function render(): ?string
    {
        return $this->wrap($this->html(), $this->config);
    }

    protected function wrap(string $content, array $config = []): string
    {
        $wrappers = Arr::get($config, 'wrappers', []);

        if ($wrapper = Arr::get($config, 'wrapper')) {
            array_push($wrappers, $wrapper);
        }

        foreach ($wrappers as $wrapper) {
            $content = $this->tag(Arr::get($wrapper, 'tag', 'div'), $content, $wrapper);
        }

        return $content;
    }

    protected function tag(string $tag, ?string $content, array $options = []): ?string
    {
        $options = collect($options)->filter(fn($option) => is_string($option))->except(['id', 'tag'])->toArray();

        return HtmlFacade::tag($tag, $content ?: '', $options)->toHtml();
    }

    protected function processText(string $string): string
    {
        return html_entity_decode($string);
    }

    protected function config($key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

    protected function data($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }


}
