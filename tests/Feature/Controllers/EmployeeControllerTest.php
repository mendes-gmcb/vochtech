<?php

namespace Tests\Feature\Controllers;

use App\Models\Brand;
use App\Models\EconomicGroup;
use App\Models\Employee;
use App\Models\Unit;
use App\Models\User;
use App\Repositories\BrandRepository;
use App\Repositories\EconomicGroupRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\UnitRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Mockery;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $employeeRepository;
    protected $unitRepository;
    protected $brandRepository;
    protected $economicGroupRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->employeeRepository = Mockery::mock(EmployeeRepository::class);
        $this->unitRepository = Mockery::mock(UnitRepository::class);
        $this->brandRepository = Mockery::mock(BrandRepository::class);
        $this->economicGroupRepository = Mockery::mock(EconomicGroupRepository::class);

        $this->app->instance(EmployeeRepository::class, $this->employeeRepository);
        $this->app->instance(UnitRepository::class, $this->unitRepository);
        $this->app->instance(BrandRepository::class, $this->brandRepository);
        $this->app->instance(EconomicGroupRepository::class, $this->economicGroupRepository);
    }

    public function test_index_displays_employees_list()
    {
        // Arrange
        $user = User::factory()->create();
        $groups = collect([['id' => 1, 'name' => 'Group 1']]);
        $brands = collect([['id' => 1, 'name' => 'Brand 1']]);
        $units = collect([['id' => 1, 'name' => 'Unit 1']]);
        $employees = collect([['id' => 1, 'name' => 'Employee 1']]);

        $this->economicGroupRepository->shouldReceive('list')->andReturn($groups);
        $this->brandRepository->shouldReceive('list')->andReturn($brands);
        $this->unitRepository->shouldReceive('list')->andReturn($units);
        $this->employeeRepository->shouldReceive('listPaginate')->andReturn($employees);

        // Act & Assert
        $this->actingAs($user)
            ->get(route('employees.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Employee/index')
                ->has('employees')
                ->has('groups')
                ->has('brands')
                ->has('units')
            );
    }

    public function test_store_creates_new_employee()
    {
        // Arrange
        $user = User::factory()->create();
        $group = EconomicGroup::factory()->create(['user_id' => $user->id]);
        $brand = Brand::factory()->create(['economic_group_id' => $group->id]);
        $unit = Unit::factory()->create(['brand_id' => $brand->id]);
        
        $employeeData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'cpf' => '12345678901',
            'unit_id' => $unit->id
        ];

        $this->employeeRepository
            ->shouldReceive('create')
            ->once()
            ->with($employeeData);

        // Act & Assert
        $this->actingAs($user)
            ->post(route('employees.store'), $employeeData)
            ->assertRedirect(route('employees.index'));
    }

    public function test_update_modifies_existing_employee()
    {
        // Arrange
        $user = User::factory()->create();
        $group = EconomicGroup::factory()->create(['user_id' => $user->id]);
        $brand = Brand::factory()->create(['economic_group_id' => $group->id]);
        $unit = Unit::factory()->create(['brand_id' => $brand->id]);
        $employee = Employee::factory()->create(['unit_id' => $unit->id]);
        
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'cpf' => '39288406069',
        ];

        $this->employeeRepository
            ->shouldReceive('update')
            ->once()
            ->with($updateData, $employee->id);

        // Act & Assert
        $this->actingAs($user)
            ->put(route('employees.update', $employee), $updateData)
            ->assertRedirect(route('employees.edit', $employee->id));
    }

    public function test_destroy_deletes_employee()
    {
        // Arrange
        $user = User::factory()->create();
        $group = EconomicGroup::factory()->create(['user_id' => $user->id]);
        $brand = Brand::factory()->create(['economic_group_id' => $group->id]);
        $unit = Unit::factory()->create(['brand_id' => $brand->id]);
        $employee = Employee::factory()->create(['unit_id' => $unit->id]);

        $this->employeeRepository
            ->shouldReceive('delete')
            ->once()
            ->with($employee->id);

        // Act & Assert
        $this->actingAs($user)
            ->delete(route('employees.destroy', $employee))
            ->assertRedirect(route('employees.index'));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}