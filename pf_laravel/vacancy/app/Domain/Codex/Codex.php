<?php


namespace App\Domain\Codex;


use App\Domain\Codex\Factories\CodexFactory;
use Arr;

class Codex
{
    public function __construct(private CodexFactory $factory)
    {
    }

    public function render(string|array $data, array $config = []): ?string
    {
        [$data, $config] = $this->parseArguments($data, $config);

        if (empty($data)) {
            return null;
        }
        return collect(Arr::get($data, 'blocks', []))
            ->map(fn($block) => $this->factory->create(
                Arr::get($block, 'type'),
                Arr::get($block, 'data'),
                Arr::get($config, Arr::get($block, 'type'), [])
            )->render())
            ->join(PHP_EOL);
    }

    public function hasBlocks(string|array $data, array $config = []): bool
    {
        return collect(Arr::get(is_array($data) ? $data : json_decode($data, true), 'blocks'))->count() > 0;
    }

    protected function parseArguments($data, $config): array
    {
        if (is_array($data)) {
            $config = Arr::get($data, 'config', $config);
            $data = Arr::get($data, 'data', $data);
        }
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        return [$data, $config];
    }
}
