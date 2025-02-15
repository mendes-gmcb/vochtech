<?php

namespace Tests\Feature\Controllers;

use App\Models\EconomicGroup;
use App\Models\User;
use App\Repositories\EconomicGroupRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Mockery;

class EconomicGroupControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $economicGroupRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->economicGroupRepository = Mockery::mock(EconomicGroupRepository::class);
        $this->app->instance(EconomicGroupRepository::class, $this->economicGroupRepository);
    }

    public function test_index_displays_economic_groups_list()
    {
        // Arrange
        $user = User::factory()->create();
        $groups = collect([['id' => 1, 'name' => 'Economic Group 1']]);

        $this->economicGroupRepository
            ->shouldReceive('listPaginate')
            ->with($user)
            ->andReturn($groups);

        // Act & Assert
        $this->actingAs($user)
            ->get(route('economic.group.list'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('EconomicGroups/index')
                ->has('paginatedGroups')
            );
    }

    public function test_store_creates_new_economic_group()
    {
        // Arrange
        $user = User::factory()->create();
        $groupData = [
            'name' => 'New Economic Group'
        ];

        $expectedData = [...$groupData, 'user_id' => $user->id];

        $this->economicGroupRepository
            ->shouldReceive('create')
            ->once()
            ->with($expectedData);

        // Act & Assert
        $this->actingAs($user)
            ->post(route('economic.group.create'), $groupData)
            ->assertRedirect(route('economic.group.list'));
    }

    public function test_edit_displays_economic_group_form()
    {
        // Arrange
        $user = User::factory()->create();
        $economicGroup = EconomicGroup::factory()->create(['user_id' => $user->id]);

        // Act & Assert
        $this->actingAs($user)
            ->get(route('economic.group.edit', $economicGroup))
            ->assertInertia(fn (Assert $page) => $page
                ->component('EconomicGroups/Edit')
                ->has('group')
            );
    }

    public function test_update_modifies_existing_economic_group()
    {
        // Arrange
        $user = User::factory()->create();
        $economicGroup = EconomicGroup::factory()->create(['user_id' => $user->id]);
        
        $updateData = [
            'name' => 'Updated Economic Group Name'
        ];

        $this->economicGroupRepository
            ->shouldReceive('update')
            ->once()
            ->with($updateData, $economicGroup->id);

        // Act & Assert
        $this->actingAs($user)
            ->patch(route('economic.group.update', $economicGroup), $updateData)
            ->assertRedirect(route('economic.group.edit', $economicGroup->id));
    }

    public function test_destroy_deletes_economic_group()
    {
        // Arrange
        $user = User::factory()->create();
        $economicGroup = EconomicGroup::factory()->create(['user_id' => $user->id]);

        $this->economicGroupRepository
            ->shouldReceive('delete')
            ->once()
            ->with($economicGroup->id);

        // Act & Assert
        $this->actingAs($user)
            ->delete(route('economic.group.delete', $economicGroup))
            ->assertRedirect(route('economic.group.list'));
    }

    public function test_unauthorized_user_cannot_access_other_users_economic_groups()
    {
        // Arrange
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $economicGroup = EconomicGroup::factory()->create(['user_id' => $otherUser->id]);

        // Act & Assert
        $this->actingAs($user)
            ->get(route('economic.group.edit', $economicGroup))
            ->assertStatus(403);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}