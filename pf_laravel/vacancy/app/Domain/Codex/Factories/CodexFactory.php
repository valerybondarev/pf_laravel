<?php


namespace App\Domain\Codex\Factories;


use App\Domain\Codex\Blocks\Carousel;
use App\Domain\Codex\Blocks\Embed;
use App\Domain\Codex\Blocks\EmptyBlock;
use App\Domain\Codex\Blocks\Header;
use App\Domain\Codex\Blocks\Image;
use App\Domain\Codex\Blocks\ListBlock;
use App\Domain\Codex\Blocks\Paragraph;
use App\Domain\Codex\Blocks\Quote;
use App\Domain\Codex\Blocks\Table;
use App\Domain\Codex\Interfaces\CodexBlockInterface;

class CodexFactory
{
    public function create($type, $data, $config): ?CodexBlockInterface
    {
        return match ($type) {
            Header::TYPE => new Header($data, $config),
            Image::TYPE => new Image($data, $config),
            Paragraph::TYPE => new Paragraph($data, $config),
            ListBlock::TYPE => new ListBlock($data, $config),
            Quote::TYPE => new Quote($data, $config),
            Embed::TYPE => new Embed($data, $config),
            Table::TYPE => new Table($data, $config),
            Carousel::TYPE => new Carousel($data, $config),
            default => new EmptyBlock($data, $config),
        };
    }
}
