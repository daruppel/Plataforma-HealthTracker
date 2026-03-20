<?php

namespace App\Controllers\Doctor;

use App\Controllers\BaseController;
use App\Models\DiagnosisModel;
use App\Models\MedicalDiagnosisModel;
use App\Models\UserModel;

class Diagnosis extends BaseController
{
    protected $diagnosisModel;

    public function __construct()
    {
        $this->diagnosisModel = new DiagnosisModel();
    }

    public function index()
    {
        $data['diagnosis'] = $this->diagnosisModel->findAllByDoctor(session()->get('user_id'));//Enviar solo los del medico de la sesión  
        return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/diagnosis/index', $data)
            . view('templates/footer');
    }

    public function create()
    {
        // Si la solicitud es GET, mostrar el formulario
        if ($this->request->getMethod() === 'GET') {
            $medicalDiagnosisModel = new MedicalDiagnosisModel();
            $userModel = new UserModel();
            $data = [
                'medicalDiagnosis' => $medicalDiagnosisModel->findAll(),
                'patients' => $userModel->getUsersByRole('paciente')
            ]; 
            return view('templates/header')
                . view('templates/sidebar')
                . view('doctor/diagnosis/create', $data)
                . view('templates/footer'); //List pacientes, list tipos de diganosticos
        }

        // Si la solicitud es POST, procesar el formulario
        if ($this->request->getMethod() === 'POST') {
            $diagnosisModel = new DiagnosisModel();
            // Mapeo de campos
            $datos = [
                'tipo_diagnostico_id'   => $this->request->getPost('tipo_diagnostico_id'),
                'paciente_id' => $this->request->getPost('paciente_id'),
                'medico_id' => session()->get('user_id'),
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
    
    public function delete(){
        $id = $this->request->getPost('diagnosis_id');

        if (!$id) { 
            return redirect()->back()
                ->with('errors', $this->diagnosisModel->errors())
                ->with('error', 'ID del diagnostico no especificado');
        }

        // Soft delete
        if (!$this->diagnosisModel->delete($id)) {
            return redirect()->back()->with('error', 'No se pudo eliminar el diagnostico');
        }

        return redirect()->to('/medical_staff/diagnosis')
            ->with('success', 'Diagnostico eliminado correctamente');
    }

    //Probar funcionamiento
    public function update()
    {
        $id = $this->request->getPost('diagnosis_id');
        if (!$id) {
            return redirect()->back()
                ->with('errors', $this->diagnosisModel->errors())
                ->with('errors_update', 'ID del diagnostico no especificado');
        }
        
        // Mapeo de nombres del formulario -> campos de la BD
         $datos = [
                'diagnostico_id' => $id,
                'tipo_diagnostico_id'   => $this->request->getPost('tipo_diagnostico_id'),
                'paciente_id' => $this->request->getPost('paciente_id'),
                'medico_id' => session()->get('user_id'),
                'fecha' => $this->request->getPost('fecha'),
                'estado_id' => $this->request->getPost('estado_id')
            ];
        
        // Actualizar diagnostico
        if (!$this->diagnosisModel->save($datos)) {
            return redirect()
                    ->back()
                    ->with('errors', $this->diagnosisModel->errors() )
                    ->with('errors_update', $this->diagnosisModel->errors())
                    ->withInput();
        }

        return redirect()->to('/medical_staff/diagnosis')
            ->with('success', 'Diagnostico actualizado correctamente');
    }
}
