<?php


namespace App\Repositories;

use App\Traits\FindAbleTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Pipeline;

abstract class BaseRepositories
{
    use FindAbleTrait;

    public function __construct(Model $model, array $filters = [])
    {
        $this->model = $model;
        $this->filters = $filters;
    }

    protected function applyFilter($query)
    {
        $result = app(Pipeline::class)
            ->send($query)
            ->through($this->filters)
            ->thenReturn()
            ->latest();
        return $this->getPerPage() > 0 ? $result->paginate($this->getPerPage())->withQueryString() : $result->get();
    }

}
