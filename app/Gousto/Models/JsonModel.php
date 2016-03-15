<?php

namespace Gousto\Models;

use Gousto\Contracts\Model;
use Gousto\Exceptions\DatastoreConnectionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

/**
 * Class JsonModel
 * @package Gousto\Models
 */
class JsonModel implements Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const INCREMENT = 'increment';
    const INDEXES = 'indexes';

    /**
     * @var string
     */
    protected static $table;

    /**
     * @var string
     */
    protected $data_file_path;

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
     * @var array
     */
    protected $model_data;

    /**
     * JsonModel constructor.
     */
    public function __construct()
    {
        $this->data_file_path = env('DATASTORE') . static::$table . '.json';
        if (Storage::exists($this->data_file_path)) {
            $this->loadData(Storage::get($this->data_file_path));
        } else {
            throw new DatastoreConnectionException('Unable to use ' . static::$table . ' data store');
        }
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
            $this->id = $id;
            $this->model_data = $this->data[$this->id];
            return $this->model_data;
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
            if (array_key_exists($cr_key, $this->raw[self::INDEXES]) && array_key_exists($cr_value, $this->raw[self::INDEXES][$cr_key])) {
                $results = array_merge($results, $this->raw[self::INDEXES][$cr_key][$cr_value]);
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

    /**
     * @inheritdoc
     */
    public function create(array $data)
    {
        $this->id = $this->raw[self::INCREMENT] + 1;
        $this->model_data = array_merge(
            [
                'id' => $this->id,
            ],
            $this->timestamps(),
            $data
        );
    }

    /**
     * @inheritdoc
     */
    public function update(array $data)
    {
        $this->model_data = array_merge($this->model_data, $data);
    }

    /**
     * @return mixed
     * @throws DatastoreConnectionException
     */
    public function save()
    {
        $this->data[$this->id] = $this->model_data;
        $this->updateIncrement();
        $this->updateIndexes();
        $this->saveData();
        return $this->data[$this->id];
    }

    /**
     * @return array
     */
    protected function timestamps()
    {
        $now = date('d/m/Y H:i:s');

        return [
            self::CREATED_AT => array_key_exists($this->id, $this->data) ? $this->data[$this->id][self::CREATED_AT] : $now,
            self::UPDATED_AT => $now
        ];
    }

    /**
     * Update increment of id if current id is greater.
     */
    protected function updateIncrement()
    {
        $this->raw[self::INCREMENT] = ($this->id > $this->raw[self::INCREMENT]) ? $this->id : $this->raw[self::INCREMENT];
    }

    /**
     * Loop through existing indexes on data and update.
     */
    protected function updateIndexes()
    {
        $indexes = [];

        foreach ($this->raw[self::INDEXES] as $index => $values) {
            foreach ($this->data as $id => $data) {
                if (array_key_exists($index, $data) && !empty($data[$index])) {
                    $indexes[$index][$data[$index]][] = $id;
                }
            }
        }

        $this->raw[self::INDEXES] = $indexes;
    }

    /**
     * @throws DatastoreConnectionException
     */
    protected function saveData()
    {
        $this->raw['data'][$this->id] = $this->model_data;
        $contents = json_encode([
            static::$table => $this->raw
        ]);

        if (!Storage::put($this->data_file_path, $contents)) {
            throw new DatastoreConnectionException('Unable to save data');
        }
    }
}
