<?php

namespace App\Repositories;

use App\Models\Unit;

class UnitRepository 
{
  public function listAll()
  {
    return (Unit::paginate());
  }

  public function listPaginate(array $brands)
  {
    return Unit::whereIn("brand_id", $brands)->with('brand')->paginate();
  }

  public function list(array $brands)
  {
    return Unit::whereIn("brand_id", $brands)->get();
  }

  public function create($data) 
  {
    Unit::create($data);
  }

  public function update($data, $id)
  {
    Unit::find($id)->update($data);
  }

  public function delete($id)
  {
    Unit::find($id)->delete();
  }
}