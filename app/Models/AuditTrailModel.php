<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditTrailModel extends Model
{
    protected $table = 'audit_trails';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'action', 'table_name', 'record_id', 'old_value', 'new_value', 'created_at'];
    protected $useTimestamps = false;

    protected $clusterModel;

    public function __construct()
    {
        parent::__construct();
        $this->clusterModel = new \App\Models\ClusterModel();
    }
    
    // store logs in every move of admin CRUD
    public function logAction($user_id, $action, $table_name, $record_id, $oldData = null, $newData = null)
    {
        log_message('debug', 'Audit Trail Log Action Called');
        log_message('debug', 'Action: ' . $action);
        log_message('debug', 'Old Data: ' . json_encode($oldData));
        log_message('debug', 'New Data: ' . json_encode($newData));

        if ($action === 'Updated' && $oldData && $newData) {
            $changes = [];
            foreach ($newData as $key => $newValue) {
                if (isset($oldData[$key]) && $oldData[$key] != $newValue) {
                    $changes[$key] = [
                        'old' => $oldData[$key],
                        'new' => $newValue
                    ];
                }
            }
            
            if (empty($changes)) {
                return;
            }

            $oldValue = array_column($changes, 'old');
            $newValue = array_column($changes, 'new');
        } else {
            $oldValue = $oldData;
            $newValue = $newData;
        }

        if (isset($oldValue['cluster_id'])) {
            $oldValue['cluster'] = $this->getClusterName($oldValue['cluster_id']);
            unset($oldValue['cluster_id']);
        }
        if (isset($newValue['cluster_id'])) {
            $newValue['cluster'] = $this->getClusterName($newValue['cluster_id']);
            unset($newValue['cluster_id']);
        }

        $data = [
            'user_id' => $user_id,
            'action' => $action,
            'table_name' => $table_name,
            'record_id' => $record_id,
            'old_value' => json_encode($oldValue),
            'new_value' => json_encode($newValue),
            'created_at' => date('Y-m-d H:i:s')
        ];

        log_message('debug', 'Inserting audit log: ' . json_encode($data));

        try {
            $this->insert($data);
        } catch (\Exception $e) {
            log_message('error', 'Error inserting audit log: ' . $e->getMessage());
        }
    }

    protected function getClusterName($clusterId)
    {
        if (!$clusterId) return 'Unknown Cluster';
        
        try {
            $cluster = $this->clusterModel->find($clusterId);
            return $cluster ? $cluster['name'] : 'Unknown Cluster';
        } catch (\Exception $e) {
            log_message('error', 'Error getting cluster name: ' . $e->getMessage());
            return 'Unknown Cluster';
        }
    }

    public function getFormattedAuditTrails()
    {
        $logs = $this->orderBy('created_at', 'DESC')->findAll();
        
        foreach ($logs as &$log) {
            try {
                $oldValue = json_decode($log['old_value'], true);
                $newValue = json_decode($log['new_value'], true);

                $log['old_value'] = $this->formatValue($oldValue);
                $log['new_value'] = $this->formatValue($newValue);
            } catch (\Exception $e) {
                log_message('error', 'Error formatting audit trail: ' . $e->getMessage());
                $log['old_value'] = 'Error formatting value';
                $log['new_value'] = 'Error formatting value';
            }
        }

        return $logs;
    }

    // pang format lang ng string to convert int to string
    protected function formatValue($value)
    {
        if (empty($value)) {
            return 'N/A';
        }

        if (is_array($value)) {
            array_walk_recursive($value, function(&$item, $key) {
                if ($key === 'cluster_id') {
                    $item = $this->getClusterName($item);
                }
            });
        }

        return $value;
    }
}


// this is a reusable code, para ma-log lahat