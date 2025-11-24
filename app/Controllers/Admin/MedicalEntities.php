<?php

namespace App\Controllers\Admin;

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

    public function create()
    {
        // Si la solicitud es GET, mostrar el formulario
        if ($this->request->getMethod() === 'GET') {
            return view('admin/medical_entities/create');
        }

        // Si la solicitud es POST, procesar el formulario
        if ($this->request->getMethod() === 'POST') {
            $entityModel = new MedicalEntityModel();
            // Mapeo de campos
            $datos = [
                'nombre'   => $this->request->getPost('name'),
                'descripcion' => $this->request->getPost('description')
            ];
            // Insertar la entidad - con save inserta si no recibe id o hace un update en caso contrario
            if (!$entityModel->save($datos)) {
                return redirect()
                    ->back()
                    ->with('errors', $entityModel->errors())
                    ->withInput();
            }

            return redirect()
                ->to('/admin/medical-entities')
                ->with('success', 'Entidad creada correctamente');
        }
    }
    public function delete(){
        $id = $this->request->getPost('medical_entitie_id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID de la entidad medica no especificado');
        }

        // Soft delete
        if (!$this->entityModel->delete($id)) {
            return redirect()->back()->with('error', 'No se pudo eliminar la entidad medica');
        }

        return redirect()->to(base_url('admin/medical-entities'))
            ->with('success', 'Entidad medica eliminada correctamente');
    }
    public function update()
    {
        $id = $this->request->getPost('medical_entitie_update_id');
        if (!$id) {
            return redirect()->back()->with('error', 'ID de la entidad medica no especificado');
        }

        // Mapeo de nombres del formulario -> campos de la BD
        $data = [
            'entidad_medica_id' => $this->request->getPost('medical_entitie_update_id'),
            'nombre'   => $this->request->getPost('name'),
            'decripcion' => $this->request->getPost('description')
        ];

        // Actualizar entidad medica
        if (!$this->entityModel->save($data)) {
            return redirect()
                    ->back()
                    ->with('errors', $this->entityModel->errors())
                    ->withInput();
        }

        return redirect()->to(base_url('admin/medical-entities'))
            ->with('success', 'Entidad medica actualizada correctamente');
    }
}
