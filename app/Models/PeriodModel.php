<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriodModel extends Model
{
    protected $table = 'periods';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['year_start', 'year_end', 'status'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get active period
    public function getActivePeriod()
    {
        return $this->where('status', 'active')->first();
    }

    // Set period as active and deactivate others
    public function setActivePeriod($periodId)
    {
        $this->db->transStart();
        $this->where('id !=', $periodId)->set(['status' => 'inactive'])->update();
        $this->update($periodId, ['status' => 'active']);
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }
} 