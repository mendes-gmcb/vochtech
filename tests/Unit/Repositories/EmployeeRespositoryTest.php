<?php

namespace Tests\Unit\Repositories;

use App\Models\Brand;
use App\Models\EconomicGroup;
use App\Models\Employee;
use App\Models\Unit;
use App\Models\User;
use App\Repositories\EmployeeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EmployeeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EmployeeRepository();
    }

    public function test_can_list_all_employees()
    {
        // Arrange
        $user = User::factory()->create();
        $group = EconomicGroup::factory()->create(['user_id' => $user->id]);
        $brand = Brand::factory()->create(['economic_group_id' => $group->id]);
        $unit = Unit::factory()->create(['brand_id' => $brand->id]);
        Employee::factory()->count(3)->create(['unit_id' => $unit->id]);

        // Act
        $result = $this->repository->listAll();

        // Assert
        $this->assertEquals(3, $result->count());
    }

    public function test_can_list_employees_by_units()
    {
        $user = User::factory()->create();
        $group = EconomicGroup::factory()->create(['user_id' => $user->id]);
        $brand = Brand::factory()->create(['economic_group_id' => $group->id]);
        // Arrange
        $unit1 = Unit::factory()->create(['brand_id' => $brand->id]);
        $unit2 = Unit::factory()->create(['brand_id' => $brand->id]);
        
        Employee::factory()->count(2)->create(['unit_id' => $unit1->id]);
        Employee::factory()->count(3)->create(['unit_id' => $unit2->id]);

        // Act
        $result = $this->repository->list([$unit1->id]);

        // Assert
        $this->assertEquals(2, $result->count());
    }

    public function test_can_create_employee()
    {
        $user = User::factory()->create();
        $group = EconomicGroup::factory()->create(['user_id' => $user->id]);
        $brand = Brand::factory()->create(['economic_group_id' => $group->id]);
        // Arrange
        $unit = Unit::factory()->create(['brand_id' => $brand->id]);
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'cpf' => '12345678901',
            'unit_id' => $unit->id
        ];

        // Act
        $this->repository->create($data);

        // Assert
        $this->assertDatabaseHas('employees', [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);
    }

    public function test_can_update_employee()
    {
        $user = User::factory()->create();
        $group = EconomicGroup::factory()->create(['user_id' => $user->id]);
        $brand = Brand::factory()->create(['economic_group_id' => $group->id]);
        $unit = Unit::factory()->create(['brand_id' => $brand->id]);
        // Arrange
        $employee = Employee::factory()->create(['unit_id' => $unit->id]);
        $data = ['name' => 'Updated Name'];

        // Act
        $this->repository->update($data, $employee->id);

        // Assert
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => 'Updated Name'
        ]);
    }

    public function test_can_delete_employee()
    {
        $user = User::factory()->create();
        $group = EconomicGroup::factory()->create(['user_id' => $user->id]);
        $brand = Brand::factory()->create(['economic_group_id' => $group->id]);
        $unit = Unit::factory()->create(['brand_id' => $brand->id]);
        // Arrange
        $employee = Employee::factory()->create(['unit_id' => $unit->id]);

        // Act
        $this->repository->delete($employee->id);

        // Assert
        $this->assertSoftDeleted('employees', [
            'id' => $employee->id
        ]);
    }
}