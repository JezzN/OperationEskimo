<?php
namespace App\OE\Discord\Reporting;

use Illuminate\Cache\Repository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;

abstract class AbstractDatabaseChangeReporter
{
    /** @var DatabaseManager */
    private $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    protected function getNewRecords($query)
    {
        $lastId = $this->getLastId();

        $records = $this->getRecords($query, (int) $lastId);

        if( $records->isEmpty() ) return $records;

        $this->updateCacheWithId($records->last()->id);

        if( $records->count() > 5 ) return new Collection();

        return $records;
    }

    protected function getLastId()
    {
        $position = $this->db->table('discord_reporting_status')->where('report', $this->generateCacheKey())->first();

        if( ! $position ) return 0;

        return $position->position;
    }

    private function updateCacheWithId($id)
    {
        $updated = (bool) $this->db->table('discord_reporting_status')->where('report', $this->generateCacheKey())->update([
            'position' => $id
        ]);

        if( $updated ) return;

        $this->db->table('discord_reporting_status')->insert([
            'report' => $this->generateCacheKey(),
            'position' => $id
        ]);
    }

    private function generateCacheKey()
    {
        return preg_replace('/[^a-z]/', '', strtolower(class_basename($this))) . "_last_id_processed";
    }

    private function getRecords($query, int $lastId)
    {
        return $query->where('id', '>', $lastId)->get();
    }
}