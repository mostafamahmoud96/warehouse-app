<?php
namespace App\Http\Dto;

use Spatie\LaravelData\Data;

class ListItemsRequestData extends Data
{
    public function __construct(
        public ?string $search = null,
        public ?int $page = 1,
        public ?int $per_page = 10
    ) {
    }

    public function toArray(): array
    {
        return [
            'search'   => $this->search,
            'page'     => $this->page,
            'per_page' => $this->per_page,
        ];
    }
}
