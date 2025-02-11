<?php

namespace App\Repositories;

use App\Models\EconomicGroup;

class EconomicGroupRepository 
{
  public function listAll()
  {
    return (EconomicGroup::paginate());
  }

  public function create($data) 
  {
    EconomicGroup::create($data);
  }

  public function update($data, $id)
  {
    EconomicGroup::find($id)->update($data);
  }

  public function delete($id)
  {
    EconomicGroup::find($id)->delete();
  }
}