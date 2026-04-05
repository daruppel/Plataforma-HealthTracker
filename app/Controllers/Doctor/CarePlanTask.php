<?php

namespace App\Controllers\Doctor;

use App\Controllers\BaseController;
use App\Models\CarePlanTaskModel;

class CarePlanTask extends BaseController
{
    protected $carePlanTaskModel;

    public function __construct()
    {
        $this->carePlanTaskModel = new CarePlanTaskModel();
    }

    public function index()
    {
        $data['carePlanTasks'] = $this->carePlanTaskModel->findAll();
        return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/CarePlanTask/index', $data)
            . view('templates/footer');
    }

    //TODO: revisar datos recibidos del POST
    public function create()
    {
        // Si la solicitud es GET, mostrar el formulario
        if ($this->request->getMethod() === 'GET') {
            return view('doctor/CarePlanTask/create'); 
        }

        // Si la solicitud es POST, procesar el formulario
        if ($this->request->getMethod() === 'POST') {
            $carePlanTaskModel = new CarePlanTaskModel();
            // Mapeo de campos -- TODO: revisar campos recibidos por POST, para crear una meta de plan de cuidado 
            $datos = [
                'meta_cumplida' => false,
                'plan_cuidado_id' => $this->request->getPost('care_plan_id'),
                'tipo_meta_id' => $this->request->getPost('tipo_meta_id'),
                'descripcion' => $this->request->getPost('description')
            ];
            // Insertar el plan de cuidad - con save inserta si no recibe id o hace un update en caso contrario
            if (!$carePlanTaskModel->save($datos)) {
                return redirect()
                    ->back()
                    ->with('errors', $carePlanTaskModel->errors())
                    ->with('errors_create', $carePlanTaskModel->errors())
                    ->withInput();
            }

            //TODO: redirigir a la vista del plan de cuidado al que pertenece la meta creada
            return redirect()
                ->to('/medical_staff/care-plan-task')
                ->with('success', 'Tarea del plan de cuidado creada correctamente');
        }
    }
    //TODO: Revisar datos recibidos del POST
    public function delete(){
        $id = $this->request->getPost('care_plan_task_id');

        if (!$id) {
            return redirect()->back()
                ->with('errors', $this->carePlanTaskModel->errors())
                ->with('error', 'ID de la meta de plan de cuidado no especificado');
        }

        // Soft delete
        if (!$this->carePlanTaskModel->delete($id)) {
            return redirect()->back()->with('error', 'No se pudo eliminar la tarea del plan de cuidado');
        }

        return redirect()->to(base_url('medical_staff/care-plan-task'))
            ->with('success', 'Tarea del plan de cuidado eliminada correctamente');
    }
    //TODO: revisar datos recibidos del POST
    public function update()
    {
        $id = $this->request->getPost('care_plan_task_update_id');
        if (!$id) {
            return redirect()->back()
                ->with('errors', $this->carePlanTaskModel->errors())
                ->with('errors_update', 'ID de la tarea del plan de cuidado no especificado');
        }

        // Mapeo de nombres del formulario -> campos de la BD
        $data = [
            'metas_plan_cuidado_id' => $this->request->getPost('care_plan_task_update_id'),
            'descripcion'   => $this->request->getPost('description'),
            'meta_cumplida' => $this->request->getPost('meta_cumplida'),
            'tipo_meta_id' => $this->request->getPost('tipo_meta_id'),
            'plan_cuidado_id' => $this->request->getPost('plan_cuidado_id')
        ];

        // Actualizar tarea del plan de cuidado
        if (!$this->carePlanTaskModel->save($data)) {
            return redirect()
                ->back()
                ->with('errors', $this->carePlanTaskModel->errors() )
                ->with('errors_update', $this->carePlanTaskModel->errors())
                ->withInput();
        }

        return redirect()->to(base_url('medical_staff/care-plan-task'))
            ->with('success', 'Tarea del plan de cuidado actualizada correctamente');
    }
}
