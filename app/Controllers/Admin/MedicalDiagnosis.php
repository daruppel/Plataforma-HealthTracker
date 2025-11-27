<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MedicalDiagnosisModel;

class MedicalDiagnosis extends BaseController
{
    protected $diagnosisT;

    public function __construct()
    {
        $this->diagnosisT = new MedicalDiagnosisModel();
    }

    public function index()
    {
        $data['diagnosisTypes'] = $this->diagnosisT->findAll();
        return view('templates/header')
            . view('templates/sidebar')
            . view('admin/medical_diagnosis/index', $data)
            . view('templates/footer');
    }

    public function create(){
        // Si la solicitud es GET, mostrar el formulario
        if ($this->request->getMethod() === 'GET') {
            return view('admin/medical_diagnosis/create');
        }

        // Si la solicitud es POST, procesar el formulario
        if ($this->request->getMethod() === 'POST') {
            $diagnosisT = new MedicalDiagnosisModel();
            // Mapeo de campos
            $datos = [
                'nombre'   => $this->request->getPost('name'),
                'descripcion' => $this->request->getPost('description')
            ];
            // Insertar la entidad - con save inserta si no recibe id o hace un update en caso contrario
            if (!$diagnosisT->save($datos)) {
                return redirect()
                    ->back()
                    ->with('errors', $diagnosisT->errors())
                    ->with('errors_create', $diagnosisT->errors())
                    ->withInput();
            }

            return redirect()
                ->to('/admin/medical-diagnosis')
                ->with('success', 'Tipo de diagnostico creado correctamente');
        }
    }

    public function delete(){
         $id = $this->request->getPost('medical_diagnosis_id');

        if (!$id) {
            return redirect()->back()
                ->with('errors', $this->diagnosisT->errors())
                ->with('error', 'ID del tipo de diagnostico no especificado');
        }

        // Soft delete
        if (!$this->diagnosisT->delete($id)) {
            return redirect()->back()
            ->with('errors', $this->diagnosisT->errors())
            ->with('error', 'No se pudo eliminar el tipo de diagnostico');
        }

        return redirect()->to(base_url('admin/medical-diagnosis'))
            ->with('success', 'Tipo de diagnostico eliminado correctamente');
    }
}