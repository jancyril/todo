<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait DataTables
{
    /**
     * Abstract method to force being required for classes that will use this trait.
     *
     * @param object $records
     */
    abstract protected function map(Collection $records) : Collection;

    /**
     * Will be used for data table that uses ajax to fetch data.
     *
     * @param array $data The data requested by the client
     *
     * @return array
     */
    public function dataTables(array $data) : array
    {
        $index = $data['order'][0]['column'];
        $column = $data['columns'][$index]['name'];
        $keyword = (string)$data['search']['value'];

        return $this->format(
            $data['draw'],
            $this->countResults($data, $column, $keyword),
            $this->map($this->records($data, $column, $keyword))
        );
    }

    /**
     * This will query the model for the records requested.
     *
     * @param array  $data    The data requested by the client
     * @param string $column  The column used for sorting and searching
     * @param string $keyword The value of the column
     *
     * @return collection
     */
    private function records(array $data, string $column, string $keyword) : Collection
    {
        return $this->dataTablesQuery($data, $column, $keyword)
                    ->take($data['length'])
                    ->skip($data['start'])
                    ->get();
    }

    /**
     * This will query the model for the number of records found.
     *
     * @param array  $data    The data requested by the client
     * @param string $column  The column used for sorting and searching
     * @param string $keyword The value of the column
     *
     * @return int
     */
    private function countResults(array $data, string $column, string $keyword) : int
    {
        return $this->dataTablesQuery($data, $column, $keyword)->count();
    }

    /**
     * This will prepare the basic query for every request.
     *
     * @param array  $data    The data requested by the client
     * @param string $column  The column used for sorting and searching
     * @param string $keyword The value of the column
     *
     * @return object
     */
    private function dataTablesQuery(array $data, string $column, string $keyword) : Builder
    {
        return $this->select($this->fields($data['columns']))
            ->with($this->relatedTables())
            ->where($column, 'like', $keyword . '%')
            ->orderBy($column, $data['order'][0]['dir']);
    }

    /**
     * This will get the fields requested.
     *
     * @param array $columns The columns requested
     *
     * @return collection
     */
    private function fields(array $columns)
    {
        return collect($columns)->filter(function ($column) {
            return $column['name'] != '';
        })->map(function ($column) {
            return $column['name'];
        })->all();
    }

    /**
     * This will format the data to be returned to the user.
     *
     * @param int   $draw    An integer sent by dataTables
     * @param int   $count   The number of the total results
     * @param array $results The number of results filtered
     *
     * @return array
     */
    private function format(int $draw, int $count, array $results) : array
    {
        return [
            'draw' => intval($draw),
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $results,
        ];
    }

    /**
     * This method will contain the relationships for the dataTables.
     *
     * @return array
     */
    protected function relatedTables() : array
    {
        return [];
    }
}
