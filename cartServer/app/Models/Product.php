<?php

namespace App\Models;

use App\Filtering\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  public function getRouteKeyName()
  {
    return 'slug';
  }

  public function categories()
  {
    return $this->belongsToMany(Category::class);
  }

  public function scopeWithFilters(Builder $builder, $filters = [])
  {
    $filter = new Filter(request());
    return $filter->apply($builder, $filters);
  }
}
