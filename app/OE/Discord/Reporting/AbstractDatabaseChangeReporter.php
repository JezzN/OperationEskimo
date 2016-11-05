<?php
namespace App\OE\Discord\Reporting;

use Illuminate\Cache\Repository;
use Illuminate\Support\Collection;

abstract class AbstractDatabaseChangeReporter
{
    /** @var Repository */
    private $cache;

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    protected function getNewRecords($query)
    {
        $lastId = $this->cache->get($this->generateCacheKey());

        $records = $this->getRecords($query, (int) $lastId);

        if( $records->isEmpty() ) return $records;

        $this->updateCacheWithId($records->last()->id);

        if( $records->count() > 5 ) return new Collection();

        return $records;
    }

    private function updateCacheWithId($id)
    {
        $this->cache->forever($this->generateCacheKey(), $id);
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