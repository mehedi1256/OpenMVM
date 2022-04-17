<?php

namespace App\Models\Marketplace\Component;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class Component_Model extends Model
{
    protected $table = 'component';
    protected $primaryKey = 'component_id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['author', 'type', 'value', 'date_added'];
    protected $useTimestamps = false;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getInstalledComponents($type = null)
    {
        $component_builder = $this->db->table('component');

        if (!empty($type)) {
            $component_builder->where('type', $type);
        }

        $component_query = $component_builder->get();

        $components = [];

        foreach ($component_query->getResult() as $result) {
            $components[] = [
                'component_id' => $result->component_id,
                'type' => $result->type,
                'author' => $result->author,
                'value' => $result->value,
                'date_added' => $result->date_added,
            ];
        }

        return $components;
    }

    public function getInstalledComponent($type, $author, $value)
    {
        $component_builder = $this->db->table('component');
        
        $component_builder->where('type', $type);
        $component_builder->where('author', $author);
        $component_builder->where('value', $value);

        $component_query = $component_builder->get();

        $component = [];

        if ($row = $component_query->getRow()) {
            $component = [
                'component_id' => $row->component_id,
                'type' => $row->type,
                'author' => $row->author,
                'value' => $row->value,
                'date_added' => $row->date_added,
            ];
        }

        return $component;
    }
}