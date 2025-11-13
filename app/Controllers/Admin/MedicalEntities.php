<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MedicalEntityModel;

class MedicalEntities extends BaseController
{
    protected $entityModel;

    public function __construct()
    {
        $this->entityModel = new MedicalEntityModel();
    }

    public function index()
    {
        $data['entities'] = $this->entityModel->findAll();
        return view('templates/header')
            . view('templates/sidebar')
            . view('admin/medical_entities/index', $data)
            . view('templates/footer');
    }
}