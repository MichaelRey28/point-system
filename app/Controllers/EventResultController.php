<?php

namespace App\Controllers;

use App\Models\EventResultModel;
use CodeIgniter\RESTful\ResourceController;
//HINAHANDLE LANG NETO LAHAT NG CRUD OPERATIONS

class EventResultController extends ResourceController
{
    protected $modelName = 'App\Models\EventResultModel';
    protected $format    = 'json';

    public function index()
    {
        return $this->respond($this->model->getResults());
    }

    public function create()
    {
        $data = $this->request->getPost();
        $this->model->insert($data);
        return $this->respondCreated($data);
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();
        $this->model->update($id, $data);
        return $this->respond($data);
    }

    public function delete($id = null)
    {
        $this->model->delete($id);
        return $this->respondDeleted(['message' => 'Event result deleted']);
    }

    public function rankings()
    {
        return $this->respond($this->model->getClusterRanking());
    }
}
