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

        $data['patients'] = $this->diagnosisModel->getGroupedByPatient(session()->get('user_id'));
        //$data['diagnosis'] = $this->diagnosisModel->findAllByDoctor(session()->get('user_id'));//Enviar solo los del medico de la sesión  
       return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/diagnosis/index', $data)
            . view('templates/footer');
    }

    public function edit($id = null)
    {
        if (!$id) {
            return redirect()->to('/medical_staff/diagnosis')->with('error', 'Diagnóstico no especificado.');
        }

        $diagnosis = $this->diagnosisModel
            ->select('diagnostico.*, estado_diagnostico.estado')
            ->join('estado_diagnostico', 'estado_diagnostico.estado_diagnostico_id = diagnostico.estado_id', 'left')
            ->where('diagnostico_id', $id)
            ->where('medico_id', session()->get('user_id'))
            ->first();

        if (!$diagnosis) {
            return redirect()->to('/medical_staff/diagnosis')->with('error', 'Diagnóstico no encontrado.');
        }

        $estado = $diagnosis['estado'] ?? '';
        if (!in_array($estado, ['Pendiente', 'en_proceso'], true)) {
            return redirect()->to('/medical_staff/diagnosis')->with('error', 'Solo se pueden editar diagnósticos en estado Pendiente o En proceso.');
        }

        $medicalDiagnosisModel = new MedicalDiagnosisModel();
        $userModel = new UserModel();
        $data = [
            'diagnosis'        => $diagnosis,
            'medicalDiagnosis' => $medicalDiagnosisModel->findAll(),
            'patients'         => $userModel->getUsersByRole('paciente'),
        ];

        return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/diagnosis/edit', $data)
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
                'estado_id' => 1,
                'descripcion' => $this->request->getPost('descripcion')
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
            'diagnostico_id'      => $id,
            'tipo_diagnostico_id' => $this->request->getPost('tipo_diagnostico_id'),
            'paciente_id'         => $this->request->getPost('paciente_id'),
            'medico_id'           => session()->get('user_id'),
            'fecha'               => $this->request->getPost('fecha'),
            'descripcion'         => $this->request->getPost('descripcion'),
            // ponytail: estado_id no se edita desde este formulario; se preserva el valor actual
            'estado_id'           => $this->request->getPost('estado_id')
                                     ?? $this->diagnosisModel->find($id)['estado_id'],
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
