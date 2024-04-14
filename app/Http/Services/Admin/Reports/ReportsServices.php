<?php
namespace App\Http\Services\Admin\Reports;

use App\Http\Repository\Admin\Reports\ReportsRepository;

use App\Roles;
use Carbon\Carbon;


class ReportsServices
{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct()
    {
        $this->repo = new ReportsRepository();
    }
    public function getAllLabourLocation()
    {
        try {
            return $this->repo->getAllLabourLocation();
        } catch (\Exception $e) {
            return $e;
        }
    }

   



}