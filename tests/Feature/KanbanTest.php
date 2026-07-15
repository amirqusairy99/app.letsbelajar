<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\AssignmentMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KanbanTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_kanban_board(): void
    {
        $response = $this->get('/kanban');
        $response->assertRedirect('/login');
    }

    public function test_user_without_assignments_sees_empty_kanban_view(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/kanban');

        $response->assertStatus(200);
        $response->assertViewIs('kanban.empty');
        $response->assertSee('Create Your First Assignment');
    }

    public function test_user_with_assignments_is_redirected_to_first_assignment_kanban(): void
    {
        $user = User::factory()->create();
        
        $assignment = Assignment::create([
            'subject' => 'Software Engineering',
            'name' => 'Final Year Project',
            'status' => 'active',
            'created_by' => $user->id,
        ]);

        AssignmentMember::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/kanban');

        $response->assertRedirect(route('kanban.index', $assignment->id));
    }
}
