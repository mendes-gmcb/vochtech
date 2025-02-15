<?php

namespace Tests\Unit\Repositories;

use App\Models\EconomicGroup;
use App\Models\User;
use App\Repositories\EconomicGroupRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EconomicGroupRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EconomicGroupRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EconomicGroupRepository();
  }

  public function test_can_list_all_economic_groups()
  {
    // Arrange
    $user = User::factory()->create();
    EconomicGroup::factory()->count(3)->create(['user_id' => $user->id]);

    // Act
    $result = $this->repository->listAll();

    // Assert
    $this->assertEquals(3, $result->count());
  }

  public function test_can_list_economic_groups_by_user()
  {
    // Arrange
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    EconomicGroup::factory()->count(2)->create(['user_id' => $user->id]);
    EconomicGroup::factory()->count(3)->create(['user_id' => $otherUser->id]);

    // Act
    $result = $this->repository->list($user);

    // Assert
    $this->assertEquals(2, $result->count());
    $this->assertTrue($result->every(fn($group) => $group->user_id === $user->id));
  }

  public function test_can_paginate_economic_groups_by_user()
  {
    // Arrange
    $user = User::factory()->create();
    EconomicGroup::factory()->count(15)->create(['user_id' => $user->id]);

    // Act
    $result = $this->repository->listPaginate($user);

    // Assert
    $this->assertEquals(15, $result->total());
    $this->assertTrue($result->items()[0]->user_id === $user->id);
  }

  public function test_can_create_economic_group()
  {
    // Arrange
    $user = User::factory()->create();
    $data = [
      'name' => 'Test Economic Group',
      'user_id' => $user->id
    ];

    // Act
    $this->repository->create($data);

    // Assert
    $this->assertDatabaseHas('economic_groups', [
      'name' => 'Test Economic Group',
      'user_id' => $user->id
    ]);
  }

  public function test_can_update_economic_group()
  {
    $user = User::factory()->create();
    // Arrange
    $economicGroup = EconomicGroup::factory()->create(['user_id' => $user->id]);
    $data = ['name' => 'Updated Group Name'];

    // Act
    $this->repository->update($data, $economicGroup->id);

    // Assert
    $this->assertDatabaseHas('economic_groups', [
      'id' => $economicGroup->id,
      'name' => 'Updated Group Name'
    ]);
  }

  public function test_can_delete_economic_group()
  {
    $user = User::factory()->create();
    // Arrange
    $economicGroup = EconomicGroup::factory()->create(['user_id' => $user->id]);

    // Act
    $this->repository->delete($economicGroup->id);

    // Assert
    $this->assertSoftDeleted('economic_groups', [
      'id' => $economicGroup->id
    ]);
  }
}
