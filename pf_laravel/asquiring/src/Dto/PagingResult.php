<?php

namespace App\Dto;

class PagingResult
{
    public ?array $data;
    public int $total;
    public int $page;
    public int $perPage;

    public function __construct(?array $data, int $total, int $page, int $perPage)
    {
        $this->data = $data;
        $this->total = $total;
        $this->page = $page;
        $this->perPage = $perPage;
    }
}
