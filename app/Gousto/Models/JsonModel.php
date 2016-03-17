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
    const TABLE_DATA = 'data';
    const TABLE_INDEXES = 'indexes';
    const TABLE_INCREMENT = 'increment';

    const FIELD_ID = 'id';
    const FIELD_CREATED_AT = 'created_at';
    const FIELD_UPDATED_AT = 'updated_at';

    const TIMESTAMP_FORMAT = 'd/m/Y H:i:s';

    /**
     * @var string
     */
    protected $data_file_path;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $indexes;

    /**
     * @var int
     */
    protected $increment;

    /**
     * @var array
     */
    protected $table_data;

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
        $this->data_file_path = env('DATASTORE') . $this->table . '.json';
        if (Storage::exists($this->data_file_path)) {
            $this->loadData(Storage::get($this->data_file_path));
        } else {
            throw new DatastoreConnectionException('Unable to use ' . $this->table . ' data store');
        }
    }

    /**
     * @param $data
     */
    public function loadData($data)
    {
        try {
            if (!empty($this->table)) {
                $json = json_decode($data, true);
                if (array_key_exists($this->table, $json)) {
                    $this->data = $json[$this->table][self::TABLE_DATA];
                    $this->indexes = $json[$this->table][self::TABLE_INDEXES];
                    $this->increment = $json[$this->table][self::TABLE_INCREMENT];
                    $this->setTableData($this->data, $this->indexes, $this->increment);
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
     * @param $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param array $data
     * @param array $indexes
     * @param int $increment
     */
    public function setTableData($data, $indexes, $increment)
    {
        $this->table_data = [
            self::TABLE_DATA => $data,
            self::TABLE_INDEXES => $indexes,
            self::TABLE_INCREMENT => $increment,
        ];
    }

    /**
     * @return array
     */
    public function getTableData()
    {
        return [
            self::TABLE_DATA => $this->data,
            self::TABLE_INDEXES => $this->indexes,
            self::TABLE_INCREMENT => $this->increment,
        ];
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
            if (array_key_exists($cr_key, $this->table_data[self::TABLE_INDEXES]) && array_key_exists($cr_value, $this->table_data[self::TABLE_INDEXES][$cr_key])) {
                $results = array_merge($results, $this->table_data[self::TABLE_INDEXES][$cr_key][$cr_value]);
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
        $this->id = $this->table_data[self::TABLE_INCREMENT] + 1;
        $this->model_data = array_merge(
            [
                self::FIELD_ID => $this->id,
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
        $this->updateData();
        $this->updateIndexes();
        $this->updateIncrement();
        $this->saveData();
        return $this->data[$this->id];
    }

    /**
     * @return array
     */
    protected function timestamps()
    {
        $now = date(self::TIMESTAMP_FORMAT);

        return [
            self::FIELD_CREATED_AT => array_key_exists($this->id, $this->data) ? $this->data[$this->id][self::FIELD_CREATED_AT] : $now,
            self::FIELD_UPDATED_AT => $now
        ];
    }

    /**
     * Sets the data for the model to the table
     */
    protected function updateData()
    {
        $this->data[$this->id] = $this->model_data;
    }

    /**
     * Loop through existing indexes on data and update.
     */
    protected function updateIndexes()
    {
        $indexes = [];

        foreach ($this->indexes as $index => $values) {
            foreach ($this->data as $id => $data) {
                if (array_key_exists($index, $data) && !empty($data[$index])) {
                    $indexes[$index][$data[$index]][] = $id;
                }
            }
        }

        $this->indexes = $indexes;
    }

    /**
     * Update increment of id if current id is greater.
     */
    protected function updateIncrement()
    {
        $this->increment = ($this->id > $this->increment) ? $this->id : $this->increment;
    }

    /**
     * @throws DatastoreConnectionException
     */
    protected function saveData()
    {
        $contents = json_encode([
            $this->table => $this->getTableData()
        ]);

        if (!Storage::put($this->data_file_path, $contents)) {
            throw new DatastoreConnectionException('Unable to save data');
        }
    }
}
