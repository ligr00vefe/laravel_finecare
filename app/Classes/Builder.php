<?php


namespace App\Classes;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Builder
{

    protected string $table = "";
    public int $id = 0;

    public function table(string $table) : Builder
    {
        $this->table = $table;
        return $this;
    }

    public function where(array $attributes) : QueryBuilder
    {
        return DB::table($this->table)->where($attributes);
    }

    public function insertGetId(array $attributes) : int
    {
        $this->id = DB::table($this->table)->insertGetId($attributes);
        return $this->id;
    }

    public function updateGetId(array $attributes, array $values) : int
    {
        $update = DB::table($this->table)->where($attributes)->take(1)->update($values);
        if ($update) {
            $this->id = DB::table($this->table)->where($attributes)->first()->id;
            return $this->id;
        }

        return 0;
    }

    public function update(array $attributes, array $values) : bool
    {
        return DB::table($this->table)->where($attributes)->take(1)->update($values);
    }

    /**
     * update or insert
     * updateOrInsert 에서 update는 id를 리턴하지 못하므로 만들었음
     *
     * @param  array  $attributes : where->update or insert
     * @param  array|string  $values : update values or insert values
     * @return int : 항상 id를 리턴하는 메서드.
     */
    public function upsert(array $attributes, array $values = []) : int
    {
        if (! $this->where($attributes)->exists()) {
            return $this->insertGetId(array_merge($attributes, $values));
        }

        if (empty($values)) {
            echo "hello...";
            return true;
        }

        return $this->updateGetId($attributes, $values);
    }



    /**
     * checking duplicated and insert
     * 중복체크 이후에 인설트한다.
     * 중복이면 insert: false, id: null
     * insert면 insert: true, id: insertId 리턴한다
     * @param  array  $attributes : where->update or insert
     * @param  array|string  $values : update values or insert values
     * @return array :
     */
    public function dupsert(array $attributes, array $values = []) : array
    {

        if (empty($attributes)) {
            return [];
        }

        if ($this->where($attributes)->exists()) {
            return [ "insert" => false, "id" => null, "data" => null ];
        }

        $id = $this->insertGetId(array_merge($attributes, $values));
        if ($id) {
            return [ "insert" => true, "id" => $id ];
        }

    }
}
