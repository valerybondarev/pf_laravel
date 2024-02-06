<?php


namespace App\Domain\Codex\Blocks;


class Table extends BaseBlock
{
    public const TYPE = 'table';

    public function renderCell($cell): string
    {
        $tag = $this->config('cell.tag', 'td');

        return $this->wrap(
            $this->tag($tag, $cell, $this->config('cell', [])),
            $this->config('cell', [])
        );
    }

    public function renderRow($cells): string
    {
        $tag = $this->config('row.tag', 'td');

        return $this->wrap(
            $this->tag($tag, $cells, $this->config('row', [])),
            $this->config('row', [])
        );
    }

    public function renderTable(): string
    {
        $tag = $this->config('tag', 'table');

        return $this->wrap(
            $this->tag(
                $tag,
                collect($this->data('content'))
                    ->map(function ($cells) {
                        return $this->renderRow(collect($cells)->map(fn($cell) => $this->renderCell($cell))->join(''));
                    })
                    ->join(PHP_EOL),
                $this->config
            ),
            $this->config
        );
    }

    public function html(): ?string
    {
        return $this->renderTable();
    }
}
