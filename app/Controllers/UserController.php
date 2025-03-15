<?php

namespace App\Controllers;

use App\Models\EventResultModel;
use App\Models\EventModel;
use CodeIgniter\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->session = session();

        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please log in to continue.');
        }
    }

    public function index()
    {
        $eventResultModel = new EventResultModel();
        $eventModel = new EventModel(); 



        $data['rankings'] = $eventResultModel->getRankedResults();
        $data['event_results'] = $eventResultModel->getResults();
        $data['events'] = $eventModel->findAll();
        

        return view('user/home', $data);
    }
}
