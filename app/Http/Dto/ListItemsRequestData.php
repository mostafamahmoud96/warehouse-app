<?php
namespace App\Http\Dto;

use Spatie\LaravelData\Data;

class ListItemsRequestData extends Data
{
    public function __construct(
        public ?int $page = 1,
        public ?int $per_page = 10
    ) {
    }

    public function toArray(): array
    {
        return [
            'page'  => $this->page,
            'limit' => $this->limit,
        ];
    }
}
