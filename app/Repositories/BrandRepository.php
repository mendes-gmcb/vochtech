<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Models\EconomicGroup;

class BrandRepository 
{
  public function listAll()
  {
    return (Brand::paginate());
  }

  public function listPaginate(array $groups)
  {
    return Brand::whereIn("economic_group_id", $groups)->with('group')->paginate();
  }

  public function list(array $groups)
  {
    return Brand::whereIn("economic_group_id", $groups)->get();
  }

  public function create($data) 
  {
    Brand::create($data);
  }

  public function update($data, $id)
  {
    Brand::find($id)->update($data);
  }

  public function delete($id)
  {
    Brand::find($id)->delete();
  }
}