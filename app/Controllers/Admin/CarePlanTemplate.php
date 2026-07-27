<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CarePlanTemplateModel;
use App\Models\CarePlanTemplateTaskModel;
use App\Models\MedicalDiagnosisModel;
use App\Models\TaskTypeModel;

class CarePlanTemplate extends BaseController
{
    protected $templateModel;
    protected $templateTaskModel;

    public function __construct()
    {
        $this->templateModel     = new CarePlanTemplateModel();
        $this->templateTaskModel = new CarePlanTemplateTaskModel();
    }

    public function index()
    {
        $data['templates']      = $this->templateModel->getAllWithDetails();
        $data['diagnosisTypes'] = (new MedicalDiagnosisModel())->findAll();
        $data['taskTypes']      = (new TaskTypeModel())->findAll();

        return view('templates/header')
            . view('templates/sidebar')
            . view('admin/care_plan_templates/index', $data)
            . view('templates/footer');
    }

    public function create()
    {
        if ($this->request->getMethod() === 'GET') {
            $data['diagnosisTypes'] = (new MedicalDiagnosisModel())->findAll();
            $data['taskTypes']      = (new TaskTypeModel())->findAll();

            return view('templates/header')
                . view('templates/sidebar')
                . view('admin/care_plan_templates/create', $data)
                . view('templates/footer');
        }

        if ($this->request->getMethod() === 'POST') {
            // Validar tareas (≥1 tarea requerida)
            $tasks = $this->request->getPost('tasks');
            if (empty($tasks) || !is_array($tasks)) {
                return redirect()->back()->withInput()
                    ->with('error', 'Debe agregar al menos una tarea al plan.')
                    ->with('errors_create', ['tareas' => 'Debe agregar al menos una tarea al plan.']);
            }

            foreach ($tasks as $task) {
                if (empty($task['tipo_meta_id']) || empty($task['descripcion'])) {
                    return redirect()->back()->withInput()
                        ->with('error', 'Todas las tareas deben tener tipo de meta y descripción.')
                        ->with('errors_create', ['tareas' => 'Todas las tareas deben tener tipo de meta y descripción.']);
                }
            }

            $datos = [
                'tipo_diagnostico_id' => $this->request->getPost('tipo_diagnostico_id'),
                'nombre'              => $this->request->getPost('nombre'),
                'descripcion'         => $this->request->getPost('descripcion'),
                'activo'              => $this->request->getPost('activo') ?? 1,
            ];

            if (!$this->templateModel->save($datos)) {
                return redirect()->back()->withInput()
                    ->with('errors', $this->templateModel->errors())
                    ->with('errors_create', $this->templateModel->errors());
            }

            $templateId = $this->templateModel->getInsertID();

            $db = \Config\Database::connect();
            $db->transStart();

            foreach ($tasks as $task) {
                $this->templateTaskModel->insert([
                    'plan_cuidado_estandar_id' => $templateId,
                    'tipo_meta_id'             => (int) $task['tipo_meta_id'],
                    'descripcion'              => $task['descripcion'],
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()
                    ->with('error', 'Error al guardar las tareas del plan.');
            }

            return redirect()->to(base_url('admin/care-plan-templates'))
                ->with('success', 'Plan de cuidado estandarizado creado correctamente.');
        }
    }

    public function edit(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            return redirect()->to(base_url('admin/care-plan-templates'))
                ->with('error', 'Plantilla no encontrada.');
        }

        $data['template']       = $template;
        $data['tasks']          = $this->templateTaskModel->getByTemplate($id);
        $data['diagnosisTypes'] = (new MedicalDiagnosisModel())->findAll();
        $data['taskTypes']      = (new TaskTypeModel())->findAll();

        return view('templates/header')
            . view('templates/sidebar')
            . view('admin/care_plan_templates/edit', $data)
            . view('templates/footer');
    }

    public function update(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            return redirect()->to(base_url('admin/care-plan-templates'))
                ->with('error', 'Plantilla no encontrada.');
        }

        // Validar tareas (≥1 tarea requerida)
        $tasks = $this->request->getPost('tasks');
        if (empty($tasks) || !is_array($tasks)) {
            return redirect()->back()->withInput()
                ->with('error', 'Debe agregar al menos una tarea al plan.');
        }

        foreach ($tasks as $task) {
            if (empty($task['tipo_meta_id']) || empty($task['descripcion'])) {
                return redirect()->back()->withInput()
                    ->with('error', 'Todas las tareas deben tener tipo de meta y descripción.');
            }
        }

        $datos = [
            'plan_cuidado_estandar_id' => $id,
            'tipo_diagnostico_id'      => $this->request->getPost('tipo_diagnostico_id'),
            'nombre'                   => $this->request->getPost('nombre'),
            'descripcion'              => $this->request->getPost('descripcion'),
            'activo'                   => $this->request->getPost('activo') ?? 1,
        ];

        if (!$this->templateModel->save($datos)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->templateModel->errors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Reemplazar tareas: soft-delete antiguas e insertar nuevas
        $this->templateTaskModel->where('plan_cuidado_estandar_id', $id)->delete();

        foreach ($tasks as $task) {
            $this->templateTaskModel->insert([
                'plan_cuidado_estandar_id' => $id,
                'tipo_meta_id'             => (int) $task['tipo_meta_id'],
                'descripcion'              => $task['descripcion'],
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()
                ->with('error', 'Error al actualizar las tareas del plan.');
        }

        return redirect()->to(base_url('admin/care-plan-templates'))
            ->with('success', 'Plan de cuidado estandarizado actualizado correctamente.');
    }

    public function delete(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            return redirect()->to(base_url('admin/care-plan-templates'))
                ->with('error', 'Plantilla no encontrada.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Soft-delete de tareas asociadas
        $this->templateTaskModel->where('plan_cuidado_estandar_id', $id)->delete();
        // Soft-delete de la plantilla
        $this->templateModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()
                ->with('error', 'No se pudo eliminar la plantilla.');
        }

        return redirect()->to(base_url('admin/care-plan-templates'))
            ->with('success', 'Plan de cuidado estandarizado eliminado correctamente.');
    }
}
