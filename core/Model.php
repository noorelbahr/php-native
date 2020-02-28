<?php

class Model extends Database
{
    protected $table;

    /**
     * Select all
     * - - -
     * @return array
     */
    public function all()
    {
        $sql    = 'SELECT * FROM ' . $this->table;
        $query  = $this->prepareExecute($sql);
        return $query->fetchAll();
    }

    /**
     * Find by id
     * - - -
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $sql    = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
        $query  = $this->prepareExecute($sql, [$id]);
        return $query->fetch();
    }

    /**
     * Find by
     * - - -
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function findBy($attribute, $value)
    {
        $sql    = 'SELECT * FROM ' . $this->table . ' WHERE ? = ? LIMIT 1';
        $query  = $this->prepareExecute($sql, [$attribute, $value]);
        return $query->fetch();
    }

    /**
     * Create data
     * - - -
     * @param array $attributes
     * @return int
     */
    public function create(array $attributes)
    {
        $sql    = 'INSERT INTO ' . $this->table . ' (' . $this->getKeys($attributes) . ') VALUES ' . $this->getValues($attributes);
        $query  = $this->prepareExecute($sql);
        return $query->rowCount();
    }

    /**
     * Update a data
     * - - -
     * @param $id
     * @param array $attributes
     * @return int
     */
    public function update($id, array $attributes)
    {
        $sql    = 'UPDATE ' . $this->table . ' SET ' . $this->getKeyValues($attributes) . ' WHERE id = ?';
        $query  = $this->prepareExecute($sql, [$id]);
        return $query->rowCount();
    }

    /**
     * Delete a data
     * - - -
     * @param $id
     * @return int
     */
    public function destroy($id)
    {
        $sql    = 'DELETE FROM ' . $this->table . ' WHERE id = ?';
        $query  = $this->prepareExecute($sql, [$id]);
        return $query->rowCount();
    }

    /**
     * PRIVATE
     * Get column keys from array of attributes
     * - - -
     * @param array $attributes
     * @return string
     */
    private function getKeys(array $attributes) : string
    {
        if (isset($attributes[0]) && is_array($attributes[0]))
            $attributes = $attributes[0];

        $keys = array_keys($attributes);
        return implode(', ', $keys);
    }

    /**
     * PRIVATE
     * Get values from array of attributes
     * - - -
     * @param array $attributes
     * @return string
     */
    private function getValues(array $attributes) : string
    {
        $values = [];
        $attributes = array_values($attributes);
        if (isset($attributes[0]) && is_array($attributes[0])) {
            foreach ($attributes as $attr) {
                $values[] = "('" . implode("', '", $attr) . "')";
            }
        } else {
            $values[] = "('" . implode("', '", $attributes) . "')";
        }
        return implode(', ', $values);
    }

    /**
     * PRIVATE
     * Get key values from array of attributes
     * - - -
     * @param array $attributes
     * @return string
     */
    private function getKeyValues(array $attributes) : string
    {
        $keyValues = [];
        foreach ($attributes as $key => $attr) {
            $keyValues[] = $key . "='" . $attr . "'";
        }
        return implode(', ', $keyValues);
    }
}
