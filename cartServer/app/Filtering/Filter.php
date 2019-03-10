<?php

namespace App\Filtering;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Filter
{
  protected $request;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function apply(Builder $builder, array $filters)
  {
    foreach ($filters as $key => $filter) {
      if (!$filter instanceof \App\Filtering\Contracts\Filter)
        continue;

      $filter->apply($builder, $this->request->get($key));
    }
    return $builder;
  }
}