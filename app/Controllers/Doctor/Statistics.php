<?php

namespace App\Controllers\Doctor;

use App\Controllers\BaseController;
use App\Models\CarePlanTaskModel;

class Statistics extends BaseController
{
    protected $carePlanTaskModel;

    public function __construct()
    {
        $this->carePlanTaskModel = new CarePlanTaskModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $doctorId = (int) session()->get('user_id');

        $data = $this->carePlanTaskModel->getComplianceStatisticsByDoctor($doctorId);

        return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/statistics/index', $data)
            . view('templates/footer');
    }
}
