<?php

namespace App\Controllers\Doctor;

use App\Controllers\BaseController;
use App\Models\CarePlanModel;

class CarePlan extends BaseController
{
    protected $carePlanModel;

    public function __construct()
    {
        $this->carePlanModel = new CarePlanModel();
    }

    public function index()
    {
        $data['carePlan'] = $this->carePlanModel->findAll();
        return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/CarePlan/index', $data)
            . view('templates/footer');
    }

    //TODO: Revisar datos recibidos del POST
    public function create()
    {
        // Si la solicitud es GET, mostrar el formulario
        if ($this->request->getMethod() === 'GET') {
           /* $data = [
                'medicalDiagnosis' => 'algo', //$medicalDiagnosisModel->findAll(),
                'patients' => 'algo' //$userModel->getUsersByRole('paciente')
            ]; , $data  */
            return view('doctor/CarePlan/create');
        }

        // Si la solicitud es POST, procesar el formulario
        if ($this->request->getMethod() === 'POST') {
            $carePlanModel = new CarePlanModel();
            // Mapeo de campos
            $datos = [
                'fec_inicio'   => $this->request->getPost('fec_inicio'),
                'fec_fin' => $this->request->getPost('fec_fin'),
                'diagnostico_id' => $this->request->getPost('diagnostico_id')
            ];
            // Insertar el plan de cuidad - con save inserta si no recibe id o hace un update en caso contrario
            if (!$carePlanModel->save($datos)) {
                return redirect()
                    ->back()
                    ->with('errors', $carePlanModel->errors())
                    ->with('errors_create', $carePlanModel->errors())
                    ->withInput();
            }

            return redirect()
                ->to('/medical_staff/care-plan')
                ->with('success', 'Plan de cuidado creado correctamente');
        }
    }
    //TODO: Revisar datos recibidos del POST
    public function delete(){
        $id = $this->request->getPost('care_plan_id');

        if (!$id) {
            return redirect()->back()
                ->with('errors', $this->carePlanModel->errors())
                ->with('error', 'ID del plan de cuidado no especificado');
        }
        // Soft delete
        if (!$this->carePlanModel->delete($id)) {
            return redirect()->back()->with('error', 'No se pudo eliminar el plan de cuidado');
        }
        return redirect()
            ->to('/medical_staff/care-plan')
            ->with('success', 'Plan de cuidado eliminado correctamente');
    }
    //TODO: Revisar datos recibidos del POST
    public function update()
    {
        $id = $this->request->getPost('care_plan_update_id');
        if (!$id) {
            return redirect()->back()
                ->with('errors', $this->carePlanModel->errors())
                ->with('errors_update', 'ID del plan de cuidado no especificado');
        }
        
        // Mapeo de nombres del formulario -> campos de la BD
        $data = [
            'plan_cuidado_id' => $id,
            'fec_inicio' => $this->request->getPost('fec_inicio'),
            'fec_fin' => $this->request->getPost('fec_fin'),
            'comentario_paciente' => $this->request->getPost('comentario_paciente'),
            'diagnostico_id' => $this->request->getPost('diagnostico_id')
        ];
        
        // Actualizar plan de cuidado
        if (!$this->carePlanModel->save($data)) {
            return redirect()
                    ->back()
                    ->with('errors', $this->carePlanModel->errors() )
                    ->with('errors_update', $this->carePlanModel->errors())
                    ->withInput();
        }

        return redirect()->to(base_url('admin/care-plans'))
            ->with('success', 'Plan de cuidado actualizado correctamente');
    }
}
