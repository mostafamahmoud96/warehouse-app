<?php
namespace App\Repositories;

use App\Models\Admin;

class AdminRepository
{
    /**
     * Create a new repository instance.
     * @param Admin $model
     */
    public function __construct(public Admin $model)
    {}

    /**
     * Get all admin IDs.
     *
     * @return array
     */
    public function getAdminIds(): array
    {
        return Admin::pluck('id')->toArray();
    }

}
