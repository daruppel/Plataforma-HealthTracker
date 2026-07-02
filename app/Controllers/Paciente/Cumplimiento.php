<?php

namespace App\Controllers\Paciente;

use App\Controllers\BaseController;
use App\Models\CumplimientoMetaModel;

class Cumplimiento extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new CumplimientoMetaModel();
    }

    public function index()
    {
        $pacienteId = (int) session()->get('user_id');
        $metas = $this->model->listar_metas_plan_activo($pacienteId);

        return view('templates/header')
            . view('templates/sidebar')
            . view('paciente/cumplimiento/index', ['metas' => $metas])
            . view('templates/footer');
    }

    public function store()
    {
        $pacienteId = (int) session()->get('user_id');

        if (!$this->validate([
            'metas_plan_cuidado_id' => 'required|integer',
            'fecha'                 => 'required|valid_date[Y-m-d]',
            'duracion_minutos'      => 'required|integer|greater_than[0]',
            'comentario'            => 'permit_empty',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $metaId = (int) $this->request->getPost('metas_plan_cuidado_id');

        if ($this->model->ya_registrado_hoy($metaId, $pacienteId)) {
            return redirect()->back()->with('error', 'Ya registraste esta meta hoy.');
        }

        $ok = $this->model->insertar([
            'metas_plan_cuidado_id' => $metaId,
            'paciente_id'           => $pacienteId,
            'fecha'                 => $this->request->getPost('fecha'),
            'duracion_minutos'      => (int) $this->request->getPost('duracion_minutos'),
            'comentario'            => $this->request->getPost('comentario') ?? '',
        ]);

        if (!$ok) {
            return redirect()->back()->with('error', 'No se pudo registrar el cumplimiento.');
        }

        return redirect()->to('/paciente/cumplimiento')->with('success', 'Cumplimiento registrado correctamente.');
    }
}
