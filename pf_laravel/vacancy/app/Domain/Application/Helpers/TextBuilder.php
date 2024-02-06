<?php

namespace App\Domain\Application\Helpers;



class TextBuilder
{
    public function __construct(private $content)
    {

    }

    public static function make($content): static
    {
        return new static($content);
    }

    public function content(): string
    {
        return $this->content;
    }

    public function t($key, $variables = []): static
    {
        $this->content = __($key, $variables);

        return $this;
    }

    public function tAdd($key, $variables = []): static
    {
        $this->content .= __($key, $variables);

        return $this;
    }

    public function p(string $string): static
    {
        return $this->writeLn($string)->newLine();
    }

    public function writeLn(string $string): static
    {
        return $this->newLine()->write($string);
    }

    public function newLine(int $count = 1): static
    {
        $this->content .= str_repeat("\n", $count);

        return $this;
    }

    public function writeBold($string, $reset = false): static
    {
        return $this->write("<b>$string</b>", $reset);
    }

    public function writeItalic($string, $reset = false): static
    {
        return $this->write("<i>$string</i>", $reset);
    }

    public function write($string, $reset = false): static
    {
        if ($reset) {
            $this->content = $string;
        } else {
            $this->content .= $string;
        }

        return $this;
    }

    public function writeNew($string): static
    {
        $this->content = $string;

        return $this;
    }
}
