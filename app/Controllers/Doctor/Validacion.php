<?php

namespace App\Controllers\Doctor;

use App\Controllers\BaseController;
use App\Models\ValidacionModel;

class Validacion extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new ValidacionModel();
    }

    public function index()
    {
        $medicoId = (int) session()->get('user_id');
        $pendientes = $this->model->listar_pendientes($medicoId);

        return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/validacion/index', ['pendientes' => $pendientes])
            . view('templates/footer');
    }

    public function validar()
    {
        if (!$this->validate([
            'cumplimiento_id'   => 'required|integer',
            'accion'            => 'required|in_list[validado,rechazado]',
            'puntuacion'        => 'permit_empty|integer|greater_than[0]|less_than_equal_to[5]',
            'comentario_medico' => 'permit_empty',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $accion = $this->request->getPost('accion');

        if ($accion === 'validado' && empty($this->request->getPost('puntuacion'))) {
            return redirect()->back()->with('error', 'La puntuación es requerida al aprobar un cumplimiento.');
        }

        $this->model->validar(
            (int) $this->request->getPost('cumplimiento_id'),
            [
                'validado_at'       => date('Y-m-d H:i:s'),
                'validado_por'      => (int) session()->get('user_id'),
                'puntuacion'        => $accion === 'validado' ? (int) $this->request->getPost('puntuacion') : null,
                'comentario_medico' => $this->request->getPost('comentario_medico') ?? '',
            ]
        );

        $msg = $accion === 'validado' ? 'Cumplimiento aprobado correctamente.' : 'Cumplimiento rechazado.';
        return redirect()->to('/medical_staff/validacion')->with('success', $msg);
    }
}
