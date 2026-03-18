<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DiagnosisModel;

class Diagnosis extends BaseController
{
    protected $diagnosisModel;

    public function __construct()
    {
        $this->diagnosisModel = new DiagnosisModel();
    }

    public function index()
    {
        $data['diagnosis'] = $this->diagnosisModel->findAll();
        return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/diagnosis/index', $data)
            . view('templates/footer');
    }

    public function create()
    {
        // Si la solicitud es GET, mostrar el formulario
        if ($this->request->getMethod() === 'GET') {
            return view('doctor/diagnosis/create');
        }

        // Si la solicitud es POST, procesar el formulario
        if ($this->request->getMethod() === 'POST') {
            $diagnosisModel = new DiagnosisModel();
            // Mapeo de campos
            $datos = [
                'tipo_diagnostico_id'   => $this->request->getPost('tipo_diagnostico_id'),
                'paciente_id' => $this->request->getPost('paciente_id'),
                'medico_id' => session()->get('role_id'),
                'fecha' => $this->request->getPost('fecha'),
                'estado_id' => $this->request->getPost('estado_id')
            ];
            // Insertar el plan de cuidad - con save inserta si no recibe id o hace un update en caso contrario
            if (!$diagnosisModel->save($datos)) {
                return redirect()
                    ->back()
                    ->with('errors', $diagnosisModel->errors())
                    ->with('errors_create', $diagnosisModel->errors())
                    ->withInput();
            }

            return redirect()
                ->to('/medical_staff/diagnosis')
                ->with('success', 'Diagnostico creado correctamente');
        }
    }
    //TODO
    public function delete(){
        $id = $this->request->getPost('medical_entitie_id');

        if (!$id) {
            return redirect()->back()
                ->with('errors', $this->diagnosisModel->errors())
                ->with('error', 'ID de la entidad medica no especificado');
        }

        // Soft delete
        if (!$this->diagnosisModel->delete($id)) {
            return redirect()->back()->with('error', 'No se pudo eliminar la entidad medica');
        }

        return redirect()->to(base_url('admin/medical-entities'))
            ->with('success', 'Entidad medica eliminada correctamente');
    }
    //TODO
    public function update()
    {
        $id = $this->request->getPost('medical_entitie_update_id');
        if (!$id) {
            return redirect()->back()
                ->with('errors', $this->diagnosisModel->errors())
                ->with('errors_update', 'ID de la entidad medica no especificado');
        }
        
        // Mapeo de nombres del formulario -> campos de la BD
        $data = [
            'entidad_medica_id' => $this->request->getPost('medical_entitie_update_id'),
            'nombre'   => $this->request->getPost('name'),
            'decripcion' => $this->request->getPost('description')
        ];
        
        // Actualizar entidad medica
        if (!$this->diagnosisModel->save($data)) {
            return redirect()
                    ->back()
                    ->with('errors', $this->diagnosisModel->errors() )
                    ->with('errors_update', $this->diagnosisModel->errors())
                    ->withInput();
        }

        return redirect()->to(base_url('admin/medical-entities'))
            ->with('success', 'Entidad medica actualizada correctamente');
    }
}
