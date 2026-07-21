<?php

namespace App\Controllers\Paciente;

use App\Controllers\BaseController;
use App\Models\MedicalDocumentModel;
use App\Models\DocumentTypeModel;

class Documentacion extends BaseController
{
    protected $model;
    protected $typeModel;

    public function __construct()
    {
        $this->model = new MedicalDocumentModel();
        $this->typeModel = new DocumentTypeModel();
    }

    public function index()
    {
        $pacienteId = (int) session()->get('user_id');
        $documentos = $this->model->getPatientDocuments($pacienteId);
        $tipos = $this->typeModel->findAll();

        return view('templates/header')
            . view('templates/sidebar')
            . view('paciente/documentacion/index', [
                'documentos' => $documentos,
                'tipos'      => $tipos
            ])
            . view('templates/footer');
    }

    public function store()
    {
        $pacienteId = (int) session()->get('user_id');

        if (!$this->validate([
            'tipo_documento_id' => 'required|integer',
            'descripcion'       => 'permit_empty|max_length[255]',
            'archivo'           => 'uploaded[archivo]|max_size[archivo,5120]|ext_in[archivo,pdf,jpg,jpeg,png,doc,docx]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('archivo');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            
            // Asegurar que la ruta writable/uploads/documentos_medicos exista
            $uploadPath = WRITEPATH . 'uploads/documentos_medicos';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($file->move($uploadPath, $newName)) {
                $this->model->insert([
                    'paciente_id'       => $pacienteId,
                    'tipo_documento_id' => (int) $this->request->getPost('tipo_documento_id'),
                    'descripcion'       => $this->request->getPost('descripcion') ?? '',
                    'archivo_path'      => $newName,
                ]);

                return redirect()->to('/paciente/documentacion')->with('success', 'Documento subido correctamente.');
            }
        }

        return redirect()->back()->withInput()->with('error', 'No se pudo subir el archivo.');
    }

    public function download($id)
    {
        $pacienteId = (int) session()->get('user_id');
        $document = $this->model->where('documento_medico_id', $id)
                                ->where('paciente_id', $pacienteId)
                                ->first();

        if (!$document) {
            return redirect()->to('/paciente/documentacion')->with('error', 'Documento no encontrado o acceso no autorizado.');
        }

        $filePath = WRITEPATH . 'uploads/documentos_medicos/' . $document['archivo_path'];

        if (!file_exists($filePath)) {
            return redirect()->to('/paciente/documentacion')->with('error', 'El archivo físico no existe en el servidor.');
        }

        $tipo = $this->typeModel->find($document['tipo_documento_id']);
        $tipoNombre = preg_replace('/[^A-Za-z0-9_\-]/', '_', $tipo['nombre'] ?? 'Documento');
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $cleanName = "{$tipoNombre}_" . date('Ymd_His', strtotime($document['created_at'])) . ".{$extension}";

        return $this->response->download($filePath, null)->setFileName($cleanName);
    }

    public function delete($id)
    {
        $pacienteId = (int) session()->get('user_id');
        $document = $this->model->where('documento_medico_id', $id)
                                ->where('paciente_id', $pacienteId)
                                ->first();

        if (!$document) {
            return redirect()->to('/paciente/documentacion')->with('error', 'Documento no encontrado o acceso no autorizado.');
        }

        // Eliminar registro
        if ($this->model->delete($id)) {
            // Nota: Al usar soft delete, dejamos el archivo físico en el servidor por seguridad y auditoría.
            // Si fuera hard delete, podríamos hacer unlink(WRITEPATH . 'uploads/documentos_medicos/' . $document['archivo_path']);
            return redirect()->to('/paciente/documentacion')->with('success', 'Documento eliminado correctamente.');
        }

        return redirect()->to('/paciente/documentacion')->with('error', 'No se pudo eliminar el documento.');
    }
}
