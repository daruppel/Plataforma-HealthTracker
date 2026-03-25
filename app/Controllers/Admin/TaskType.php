<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TaskTypeModel;

class TaskType extends BaseController
{
    protected $taskTypeModel;

    public function __construct()
    {
        $this->taskTypeModel = new TaskTypeModel();
    }

    public function index()
    {
        $data['taskTypes'] = $this->taskTypeModel->findAll();
        return view('templates/header')
            . view('templates/sidebar')
            . view('admin/taskTypes/index', $data)
            . view('templates/footer');
    }

    public function create()
    {
        // Si la solicitud es GET, mostrar el formulario
        if ($this->request->getMethod() === 'GET') {
            return view('admin/taskTypes/create');
        }

        // Si la solicitud es POST, procesar el formulario
        if ($this->request->getMethod() === 'POST') {
            $taskTypeModel = new TaskTypeModel();
            // Mapeo de campos
            $datos = [
                'nombre'   => $this->request->getPost('name'),
                'descripcion' => $this->request->getPost('description')
            ];
            // Insertar el tipo de tarea - con save inserta si no recibe id o hace un update en caso contrario
            if (!$taskTypeModel->save($datos)) {
                return redirect()
                    ->back()
                    ->with('errors', $taskTypeModel->errors())
                    ->with('errors_create', $taskTypeModel->errors())
                    ->withInput();
            }

            return redirect()
                ->to('/admin/taskTypes')
                ->with('success', 'Tipo de tarea creado correctamente');
        }
    }
    //TODO delete
    public function delete(){
        $id = $this->request->getPost('medical_entitie_id');

        if (!$id) {
            return redirect()->back()
                ->with('errors', $this->taskTypeModel->errors())
                ->with('error', 'ID de la entidad medica no especificado');
        }

        // Soft delete
        if (!$this->taskTypeModel->delete($id)) {
            return redirect()->back()->with('error', 'No se pudo eliminar la entidad medica');
        }

        return redirect()->to(base_url('admin/medical_entities'))
            ->with('success', 'Entidad medica eliminada correctamente');
    }
    //TODO update
    public function update()
    {
        $id = $this->request->getPost('medical_entitie_update_id');
        if (!$id) {
            return redirect()->back()
                ->with('errors', $this->taskTypeModel->errors())
                ->with('errors_update', 'ID de la entidad medica no especificado');
        }
        
        // Mapeo de nombres del formulario -> campos de la BD
        $data = [
            'entidad_medica_id' => $this->request->getPost('medical_entitie_update_id'),
            'nombre'   => $this->request->getPost('name'),
            'decripcion' => $this->request->getPost('description')
        ];
        
        // Actualizar entidad medica
        if (!$this->taskTypeModel->save($data)) {
            return redirect()
                    ->back()
                    ->with('errors', $this->taskTypeModel->errors() )
                    ->with('errors_update', $this->taskTypeModel->errors())
                    ->withInput();
        }

        return redirect()->to(base_url('admin/taskTypes'))
            ->with('success', 'Tipo de tarea actualizado correctamente');
    }
}
