<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class VersionRepository {

    private $model;
    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function selectFieldsRelatedRegisters($fields) {
        $this->model = $this->model->with($fields);
    }

    public function filter($filters) {
        $filters = explode(';', $filters);
        
        foreach($filters as $key => $condition) {

            $c = explode(':', $condition);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }

    public function selectFields($fields) {
        $this->model = $this->model->selectRaw($fields);
    }

    public function getResponse() {
        return $this->model->get();
    }

}

?>