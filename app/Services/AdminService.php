<?php
namespace App\Services;

use App\Repositories\AdminRepository;

class AdminService
{
    public function __construct(public AdminRepository $adminRepository)
    {
    }

    /**
     * Get all admin IDs.
     *
     * @return array
     */
    public function getAdminIds(): array
    {
        return $this->adminRepository->getAdminIds();
    }
}
