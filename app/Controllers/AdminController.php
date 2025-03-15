<?php

namespace App\Controllers;

use App\Models\ClusterModel;
use App\Models\EventModel;
use App\Models\ParticipantModel;
use App\Models\EventResultModel;
use App\Models\AuditTrailModel;
use CodeIgniter\Controller;

class AdminController extends BaseController
{
    public function __construct()
    {
        $this->session = session(); // Load the session

        // Redirect to login if user is not logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must log in first.');
        }
    }
    
    public function index()
    {
        $clusterModel = new ClusterModel();
        $eventModel = new EventModel();
        $participantModel = new ParticipantModel();
        $eventResultModel = new EventResultModel();

        $data['clusters'] = $clusterModel->orderBy('name', 'ASC')->findAll();
        $data['events'] = $eventModel->orderBy('name', 'ASC')->findAll();
        $data['participants'] = $participantModel->getParticipants();
        $data['event_results'] = $eventResultModel->getResults();

        return view('admin/dashboard', $data);
    }

    //START OF CLUSTER FUNCTIONS


    // function for creating cluster(group)
    public function createCluster()
    {
        $clusterModel = new ClusterModel();
        $auditTrailModel = new AuditTrailModel();
    
        $newData = $this->request->getPost();
        unset($newData['csrf_test_name']);
    
        // Insert the new cluster
        $clusterModel->insert($newData);
        $insertedId = $clusterModel->insertID();
    
        // used to insert logs sa audit trail
        $auditTrailModel->logAction(
            session()->get('user_id'),
            'Created Cluster',
            'clusters',
            $insertedId,
            null,
            $newData
        );
    
        return redirect()->to('/admin')->with('success', 'Cluster added successfully!');
    }
    
    //handles update function
    public function updateCluster($id)
    {
        $clusterModel = new ClusterModel();
        $auditTrailModel = new AuditTrailModel();
    
        $oldData = $clusterModel->find($id);
    
        $newData = $this->request->getPost();
    
        $clusterModel->update($id, $newData);
    
        $oldClusterName = $oldData ? $oldData['name'] : null;
        $newClusterName = $newData['name'];
    
        // Log action
        $auditTrailModel->logAction(
            session()->get('user_id'),
            'Updated Cluster',
            'clusters',
            $id,
            ['name' => $oldClusterName],  
            ['name' => $newClusterName]   
        );
    
        return redirect()->to('/admin')->with('success', 'Cluster updated successfully!');
    }

    public function deleteCluster($id)
{
    log_message('debug', 'Delete cluster method called with ID: ' . $id);
    
    $clusterModel = new ClusterModel();
    $auditTrailModel = new AuditTrailModel();

    $oldData = $clusterModel->find($id);
    log_message('debug', 'Old cluster data: ' . json_encode($oldData));

    if (!$oldData) {
        return redirect()->to('/admin')->with('error', 'Cluster not found.');
    }

    // Save the deleted data to archive (soft delete)
    $clusterModel->delete($id);

    // Store it to audit trail
    $auditTrailModel->logAction(
        session()->get('user_id'),
        'Deleted Cluster',
        'clusters',
        $id,
        ['name' => $oldData['name']],
        null
    );

    return redirect()->to('/admin')->with('success', "Cluster '{$oldData['name']}' deleted successfully!");
}

//END OF CLUSTER FUNCTIONS

//START OF EVENT FUNCTIONS
public function createEvent()
{
    $eventModel = new EventModel();
    $auditTrailModel = new AuditTrailModel();

    $newData = $this->request->getPost();
    unset($newData['csrf_test_name']);

    // Insert the new event
    $eventModel->insert($newData);
    $insertedId = $eventModel->insertID();

    // Log action
    $auditTrailModel->logAction(
        session()->get('user_id'),
        'Created Event',
        'events',
        $insertedId,
        null,
        $newData
    );

    return redirect()->to('/admin')->with('success', 'Event added successfully!');
}
    
    public function updateEvent($id)
    {
        $eventModel = new EventModel();
        $auditTrailModel = new AuditTrailModel();
    
        $oldData = $eventModel->find($id);
    
        $newData = ['name' => $this->request->getPost('name')];
    
        $eventModel->update($id, $newData);
    
        $auditTrailModel->logAction(session()->get('user_id'), 'Updated Event', 'events', $id, $oldData, $newData);
    
        return redirect()->to('/admin')->with('success', 'Event updated successfully!');
    }
    
    public function deleteEvent($id)
    {
        $eventModel = new EventModel();
        $auditTrailModel = new AuditTrailModel();
    
        $oldData = $eventModel->find($id);
    
        if (!$oldData) {
            return redirect()->to('/admin')->with('error', 'Event not found.');
        }
    
        // Soft delete
        $eventModel->delete($id);
    
        $auditTrailModel->logAction(
            session()->get('user_id'),
            'Deleted Event',
            'events',
            $id,
            ['name' => $oldData['name']],
            null
        );
    
        return redirect()->to('/admin')->with('success', "Event '{$oldData['name']}' deleted successfully!");
    }

    //END OF EVENT FUNCTIONS

    //START OF PARTICIPANT FUNCTIONS
    public function createParticipant()
{
    $participantModel = new ParticipantModel();
    $auditTrailModel = new AuditTrailModel();

    $data = $this->request->getPost();
    unset($data['csrf_test_name']);

    $participantModel->insert($data);
    $insertedId = $participantModel->insertID();

    $auditTrailModel->logAction(
        session()->get('user_id'),
        'Created Participant',
        'participants',
        $insertedId,
        null,
        $data
    );

    return redirect()->to('/admin')->with('success', 'Participant added successfully!');
}

    
public function updateParticipant($id)
{
    $participantModel = new ParticipantModel();
    $eventResultModel = new EventResultModel();
    $auditTrailModel = new AuditTrailModel();
    $clusterModel = new ClusterModel();
    $eventModel = new EventModel();

    $oldData = $participantModel->find($id);
    if (!$oldData) {
        return redirect()->to('/admin')->with('error', 'Participant not found.');
    }

    $newData = $this->request->getPost();
    unset($newData['csrf_test_name']); 

    $oldAuditData = [];
    $newAuditData = [];

    if ($oldData['name'] !== $newData['name']) {
        $oldAuditData['name'] = $oldData['name'];
        $newAuditData['name'] = $newData['name'];
    }

    if ($oldData['cluster_id'] !== $newData['cluster_id']) {
        $oldCluster = $clusterModel->find($oldData['cluster_id']);
        $newCluster = $clusterModel->find($newData['cluster_id']);
        $oldAuditData['cluster'] = $oldCluster ? $oldCluster['name'] : 'Unknown Cluster';
        $newAuditData['cluster'] = $newCluster ? $newCluster['name'] : 'Unknown Cluster';
    }

    if ($oldData['event_id'] !== $newData['event_id']) {
        $oldEvent = $eventModel->find($oldData['event_id']);
        $newEvent = $eventModel->find($newData['event_id']);
        $oldAuditData['event'] = $oldEvent ? $oldEvent['name'] : 'Unknown Event';
        $newAuditData['event'] = $newEvent ? $newEvent['name'] : 'Unknown Event';
    }

    if (!empty($oldAuditData) && !empty($newAuditData)) {
        $participantModel->update($id, $newData);
        $eventResultModel->getResults();

        $auditTrailModel->logAction(
            session()->get('user_id'),
            'Updated Participant',
            'participants',
            $id,
            $oldAuditData,
            $newAuditData
        );

        return redirect()->to('/admin')->with('success', 'Participant updated successfully!');
    }

    return redirect()->to('/admin')->with('info', 'No changes were made.');
}   
    public function deleteParticipant($id)
    {
        $participantModel = new ParticipantModel();
        $auditTrailModel = new AuditTrailModel();
        $clusterModel = new ClusterModel();
        $eventModel = new EventModel();
    
        $oldData = $participantModel->find($id);
        if (!$oldData) {
            return redirect()->to('/admin')->with('error', 'Participant not found.');
        }
    
        $cluster = $clusterModel->find($oldData['cluster_id']);
        $event = $eventModel->find($oldData['event_id']);
    
        // Soft delete the participant
        $participantModel->delete($id);
    
        $auditTrailModel->logAction(
            session()->get('user_id'),
            'Deleted Participant',
            'participants',
            $id,
            [
                'name' => $oldData['name'],
                'cluster' => $cluster['name'],
                'event' => $event['name']
            ],
            null
        );
    
        return redirect()->to('/admin')->with('success', "Participant '{$oldData['name']}' deleted successfully!");
    }

    //END OF PARTICIPANTS FUNCTIONS

    //START OF EVENT-RESULTS FUNCTIONS
    public function createEventResult()
{
    $eventResultModel = new EventResultModel();
    $auditTrailModel = new AuditTrailModel();

    $data = $this->request->getPost();
    unset($data['csrf_test_name']);

    $eventResultModel->insert($data);
    $insertedId = $eventResultModel->insertID();

    $auditTrailModel->logAction(
        session()->get('user_id'),
        'Added Score',
        'event_results',
        $insertedId,
        null,
        $data
    );

    return redirect()->to('/admin')->with('success', 'Event result added successfully!');
}

    public function updateEventResult($participant_id)
    {
        $eventResultModel = new EventResultModel();
        $auditTrailModel = new AuditTrailModel();

        $oldData = $eventResultModel->where('participant_id', $participant_id)->first();
        
        $newPoints = $this->request->getPost('points');

        $eventResultModel->set('points', $newPoints)
                        ->where('participant_id', $participant_id)
                        ->update();

        $auditTrailModel->logAction(
            session()->get('user_id'),
            'Updated Score',
            'event_results',
            $participant_id,
            ['points' => $oldData['points']],
            ['points' => $newPoints]
        );

        return redirect()->to('/admin')->with('success', 'Points updated successfully!');
    }

    public function deleteEventResult($id)
    {
        $eventResultModel = new EventResultModel();
        $auditTrailModel = new AuditTrailModel();

        $oldData = $eventResultModel->where('id', $id)
            ->select('event_results.*, events.name AS event_name, participants.name AS participant_name')
            ->join('events', 'events.id = event_results.event_id')
            ->join('participants', 'participants.id = event_results.participant_id')
            ->first();

        if (!$oldData) {
            return redirect()->to('/admin')->with('error', 'Event result not found.');
        }

        $eventResultModel->delete($id);

        $auditTrailModel->logAction(
            session()->get('user_id'),
            'Deleted Score',
            'event_results',
            $id,
            [
                'event' => $oldData['event_name'],
                'participant' => $oldData['participant_name'],
                'points' => $oldData['points']
            ],
            null
        );

        return redirect()->to('/admin')->with('success', "Score deleted successfully!");
    }
    //END OF EVENT-RESULTS FUNCTIONS


    //GETTER FUNCTION LANG ITO PARA SA PARTICIPANTS INSIDE THE CLUSTER
    public function getParticipantsByCluster($cluster_id)
    {
        $participantModel = new ParticipantModel();
        $participants = $participantModel->where('cluster_id', $cluster_id)->findAll();
    
        if (empty($participants)) {
            return $this->response->setJSON(['error' => 'No participants found']);
        }
    
        return $this->response->setJSON($participants);
    }

    //FUNCTION ITO PARA PARA MASORT DITO LAHAT NG MOVEMENTS ABOVE AND MASASAVE ITO SA DATABASE NIYA
    public function auditTrails()
    {
        $auditTrailModel = new AuditTrailModel();
        $data['audit_logs'] = $auditTrailModel->orderBy('created_at', 'DESC')->findAll();
        return view('admin/audit_trails', $data);
    }

    //FUNCTION TO STORE ALL THE DELETED DATA (SOFT DELETE)
    public function archive()
    {
        $participantModel = new ParticipantModel();
        $eventModel = new EventModel();
        $clusterModel = new ClusterModel();
        $eventResultModel = new EventResultModel();

        $data['archived_participants'] = $participantModel->onlyDeleted()->findAll();
        $data['archived_events'] = $eventModel->onlyDeleted()->findAll();
        $data['archived_clusters'] = $clusterModel->onlyDeleted()->findAll();
        $data['archived_event_results'] = $eventResultModel->onlyDeleted()->findAll();

        return view('admin/archive', $data);
    }

    // FUNCTION TO RESTORE DATA FROM THE SOFT DELETE (PARTICIPANT)
    public function restoreParticipant($id)
    {
        $participantModel = new ParticipantModel();
        $participant = $participantModel->onlyDeleted()->find($id);
    
        if ($participant) {
            $participantModel->set('deleted_at', null)->where('id', $id)->update();
            return redirect()->to('/admin/archive')->with('success', 'Participant restored successfully.');
        } else {
            return redirect()->to('/admin/archive')->with('error', 'Participant not found or already restored.');
        }
    }

    // FUNCTION TO RESTORE DATA FROM THE SOFT DELETE (EVENT)
    public function restoreEvent($id)
    {
        $eventModel = new EventModel();
        $event = $eventModel->onlyDeleted()->find($id);
    
        if ($event) {
            $eventModel->set('deleted_at', null)->where('id', $id)->update();
            return redirect()->to('/admin/archive')->with('success', 'Event restored successfully.');
        } else {
            return redirect()->to('/admin/archive')->with('error', 'Event not found or already restored.');
        }
    }

    //FROM ARCHIVE, DITO NA PWEDE IDELETE ANG DATA PERMANENTLY
    public function permanentDeleteParticipant($id)
    {
        $participantModel = new ParticipantModel();
        $participantModel->delete($id, true);
        return redirect()->to('/admin/archive')->with('success', 'Participant permanently deleted.');
    }

    public function permanentDeleteEvent($id)
    {
        $eventModel = new EventModel();
        $eventModel->delete($id, true);
        return redirect()->to('/admin/archive')->with('success', 'Event permanently deleted.');
    }
}