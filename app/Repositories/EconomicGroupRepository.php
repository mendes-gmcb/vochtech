<?php

namespace App\Repositories;

use App\Models\EconomicGroup;
use App\Models\User;

class EconomicGroupRepository 
{
  public function listAll()
  {
    return (EconomicGroup::paginate());
  }

  public function listPaginate(User $user)
  {
    return (EconomicGroup::where("user_id", $user->id)->paginate());
  }

  public function list(User $user) {
    return EconomicGroup::where('user_id', $user->id)->get();
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