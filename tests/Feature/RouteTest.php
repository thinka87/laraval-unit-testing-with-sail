<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_home_screen_shows_welcome()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
        $response->assertViewHas('pageTitle', 'Homepage');
    }

    public function test_user_page_existing_user_found()
    {
        $user = User::factory()->create();
        $response = $this->get('/user/' . $user->name);

        $response->assertOk();
        $response->assertViewIs('users.show');
    }

    public function test_about_page_is_loaded()
    {
        $response = $this->get('/about');

        $response->assertViewIs('pages.about');
    }

    public function test_task_crud_is_working()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/app/dashboard');
        $response->assertViewIs('dashboard');

        $response = $this->actingAs($user)->get('/app/tasks');
        $response->assertOk();

        $response = $this->actingAs($user)->get('/app/tasks/create');
        $response->assertOk();

        $response = $this->actingAs($user)->post('/app/tasks', ['name' => 'Test']);
        $response->assertRedirect('app/tasks');

        $task = Task::factory()->create();
        $response = $this->actingAs($user)->put('/app/tasks/' . $task->id, ['name' => 'Test 2']);
        $response->assertRedirect('app/tasks');

        $this->assertDatabaseHas(Task::class, ['name' => 'Test 2']);
        $response = $this->actingAs($user)->delete('/app/tasks/' . $task->id);
        $response->assertRedirect('app/tasks');
        $this->assertDatabaseMissing(Task::class, ['name' => 'Test 2']);
    }

    public function test_task_api_crud_is_working()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/v1/tasks');
        $response->assertOk();

        $response = $this->actingAs($user)->post('/api/v1/tasks', ['name' => 'Test']);
        $response->assertCreated();
        $this->assertDatabaseHas(Task::class, ['name' => 'Test']);

        $task = Task::factory()->create();
        $response = $this->actingAs($user)->put('/api/v1/tasks/' . $task->id, ['name' => 'Test 2']);
        $response->assertOk();
        $this->assertDatabaseHas(Task::class, ['name' => 'Test 2']);

        $response = $this->actingAs($user)->delete('/api/v1/tasks/' . $task->id);
        $response->assertNoContent();
        $this->assertDatabaseMissing(Task::class, ['name' => 'Test 2']);
    }

    public function test_is_admin_middleware_is_working()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect('login');

        $response = $this->get('/admin/stats');
        $response->assertStatus(302);
        $response->assertRedirect('login');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get('/admin/stats');
        $response->assertStatus(403);

        $admin = User::factory()->create(['is_admin' => 1]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertViewIs('admin.dashboard');

        $response = $this->actingAs($admin)->get('/admin/stats');
        $response->assertViewIs('admin.stats');
    }

    public function test_email_can_be_verified()
    {
        $newData = [
            'name' => 'New name',
            'email' => 'new@email.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ];
        $response = $this->post('/register', $newData);
        $response->assertRedirect('/app/dashboard');

        $response = $this->get('/secretpage');
        $response->assertRedirect('/verify-email');

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)->get($verificationUrl);
        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        $response = $this->get('/secretpage');
        $response->assertOk();
    }

    public function test_password_confirmation_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/verysecretpage');
        $response->assertRedirect('/confirm-password');

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }
}
