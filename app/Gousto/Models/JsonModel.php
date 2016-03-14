<?php

namespace Gousto\Models;

use Gousto\Contracts\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

/**
 * Class JsonModel
 * @package Gousto\Models
 */
class JsonModel implements Model
{
    /**
     * @var string
     */
    protected static $table;

    /**
     * @var array
     */
    protected $raw;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    protected $id;

    /**
     * JsonModel constructor.
     */
    public function __construct()
    {
        $this->loadData(Storage::get(env('DATA')));
    }

    /**
     * @param $data
     */
    protected function loadData($data)
    {
        try {
            if (!empty(static::$table)) {
                $json = json_decode($data, true);
                if (array_key_exists(static::$table, $json)) {
                    $this->raw = $json[static::$table];
                    $this->data = $this->raw['data'];
                } else {
                    throw new ModelNotFoundException;
                }
            } else {
                throw new ModelNotFoundException;
            }
        } catch (ModelNotFoundException $me) {
            $this->data = new Collection();
        }
    }

    /**
     * @return Collection
     */
    public function all()
    {
        return new Collection(array_map([$this, 'mapToObject'], array_values($this->data)));
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function find($id)
    {
        if (!is_null($id) && array_key_exists($id, $this->data)) {
            return $this->data[$id];
        } else {
            throw new ModelNotFoundException;
        }
    }

    /**
     * @param array $criteria
     *
     * @return Collection
     */
    public function findBy(array $criteria)
    {
        $results = [];

        foreach ($criteria as $cr_key => $cr_value) {
            if (array_key_exists($cr_key, $this->raw) && array_key_exists($cr_value, $this->raw[$cr_key])) {
                $results = array_merge($results, $this->raw[$cr_key][$cr_value]);
            }
        }

        return new Collection(array_map([$this, 'mapToObject'], array_values(array_intersect_key($this->data, array_flip($results)))));
    }

    /**
     * @param $item
     *
     * @return object
     */
    protected function mapToObject($item)
    {
        return (object) $item;
    }
}
