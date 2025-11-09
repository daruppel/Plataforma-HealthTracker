<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends \App\Controllers\BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('templates/header')
            . view('templates/sidebar')
            . view('admin/users/index', $data)
            . view('templates/footer');
    }
}