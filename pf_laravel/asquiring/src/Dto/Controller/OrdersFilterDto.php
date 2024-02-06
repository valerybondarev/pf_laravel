<?php

namespace App\Dto\Controller;

use Symfony\Component\HttpFoundation\InputBag;

class OrdersFilterDto
{
    public ?array $product = null;
    public ?array $status = null;
    public ?string $clientId = null;
    public ?string $agentId = null;
    private ?string $sort = 'created_at';
    public ?string $sortBy = 'asc';
    public ?int $page = 1;
    public ?int $perPage = 15;

    public static function fromRequestParams(InputBag $params): OrdersFilterDto
    {
        $filters = new OrdersFilterDto();
        if (!empty($params->get('product'))) {
            $filters->product = array_map('trim', explode(',', $params->get('product')));
        }
        if (!empty($params->get('status'))) {
            $filters->status = array_map('trim', explode(',', $params->get('status')));
        }
        $filters->clientId = $params->get('client_id');
        $filters->agentId = $params->get('agent_id');
        $filters->sort = $params->get('sort', 'created_at');
        $filters->sortBy = $params->get('sort_by', 'asc');
        $filters->page = $params->getInt('page', 1);
        $filters->perPage = $params->getInt('per_page', 15);

        return $filters;
    }

    public function getSort(): string
    {
        if ('created_at' === $this->sort) {
            return 'createdAt';
        }

        return $this->sort;
    }
}
