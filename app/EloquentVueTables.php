<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\VueTables\VueTablesInterface;

class EloquentVueTables implements VueTablesInterface  {

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get data from a model for VueTables.
     * 
     * @param  string $model  The model
     * @param  Array  $fields The fields
     * @return Array         Processed data
     */
    public function get($model, Array $fields) {
        $query = $this->request->get('query');
        $limit = $this->request->get('limit');
        $page = $this->request->get('page');
        $orderBy = $this->request->get('orderBy');
        $ascending = $this->request->get('ascending');
        $byColumn = $this->request->get('byColumn');

        $data = $model->select($fields);

        if (isset($query) && $query) {
            $data = $byColumn == 1 ? $this->filterByColumn($data, $query):

            $this->filter($data, $query, $fields);
        }

        $count = $data->count();

        $data->limit($limit)
            ->skip($limit * ($page-1));

        if (isset($orderBy) && $orderBy){
            $direction = $ascending == 1 ? "ASC" : "DESC";
            $data->orderBy($orderBy,$direction);
        }

        $results = $data->get()
            ->toArray();

        return [
            'data' => $results,
            'count' => $count
        ];

    }

    /**
     * Filter the result from a column in the query.
     * 
     * @param  Builder $data  
     * @param  String $query 
     * @return Bulder
     */
    protected function filterByColumn($data, $query) {
        foreach ($query as $field => $query){

            if (!$query){
                continue;
            }

            if (is_string($query)) {
                $data->where($field, 'LIKE' , "%{$query}%");
            } else {
                $start = Carbon::createFromFormat('Y-m-d',$query['start'])->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d',$query['end'])->endOfDay();

                $data->whereBetween($field,[$start, $end]);
            }
        }

        return $data;
    }

    /**
     * Filter the data.
     * 
     * @param  Builder $data   
     * @param  String $query  
     * @param  Array $fields 
     * @return Builder
     */
    protected function filter($data, $query, $fields) {

        foreach ($fields as $index => $field){
            $method = $index ? "orWhere" : "where";
            $data->{$method}($field, 'LIKE' , "%{$query}%");
        }

        return $data;
    }

}