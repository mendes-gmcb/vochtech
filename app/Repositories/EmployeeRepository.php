<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository 
{
  public function listAll()
  {
    return (Employee::paginate());
  }

  public function listPaginate(array $units)
  {
    return Employee::whereIn("unit_id", $units)->with('unit')->paginate();
  }

  public function list(array $units)
  {
    return Employee::whereIn("unit_id", $units)->get();
  }

  public function create($data) 
  {
    Employee::create($data);
  }

  public function update($data, $id)
  {
    Employee::find($id)->update($data);
  }

  public function delete($id)
  {
    Employee::find($id)->delete();
  }
}