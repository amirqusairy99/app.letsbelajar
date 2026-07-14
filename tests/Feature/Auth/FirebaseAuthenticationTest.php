<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kreait\Firebase\Contract\Auth;
use Lcobucci\JWT\Token\DataSet;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\Signature;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class FirebaseAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private function mockFirebaseAuthReturning(Plain $token): void
    {
        /** @var Auth&MockObject $auth */
        $auth = $this->createMock(Auth::class);
        $auth->method('verifyIdToken')->willReturn($token);
        $this->app->instance(Auth::class, $auth);
    }

    private function mockFirebaseAuthThrowing(): void
    {
        /** @var Auth&MockObject $auth */
        $auth = $this->createMock(Auth::class);
        $auth->method('verifyIdToken')->willThrowException(new \Exception('Invalid Firebase token.'));
        $this->app->instance(Auth::class, $auth);
    }

    private function makeToken(string $uid, ?string $email, bool $emailVerified, ?string $name = 'Test User'): Plain
    {
        $claims = ['sub' => $uid, 'email_verified' => $emailVerified];

        if ($email !== null) {
            $claims['email'] = $email;
        }

        if ($name !== null) {
            $claims['name'] = $name;
        }

        return new Plain(
            new DataSet(['typ' => 'JWT', 'alg' => 'none'], ''),
            new DataSet($claims, ''),
            new Signature('', ''),
        );
    }

    public function test_verified_token_links_to_existing_user_with_same_email(): void
    {
        $user = User::factory()->create([
            'email' => 'linked@example.com',
            'email_verified_at' => null,
            'firebase_uid' => null,
        ]);

        $this->mockFirebaseAuthReturning($this->makeToken('firebase-uid-123', 'linked@example.com', true));

        $response = $this->post('/auth/firebase', ['id_token' => 'fake-token']);

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($user);
        $this->assertSame('firebase-uid-123', $user->fresh()->firebase_uid);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_verified_token_creates_new_user_when_no_match(): void
    {
        $this->mockFirebaseAuthReturning($this->makeToken('new-uid-1', 'new@example.com', true, 'New Person'));

        $response = $this->post('/auth/firebase', ['id_token' => 'fake-token']);

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticated();

        $user = User::where('email', 'new@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('new-uid-1', $user->firebase_uid);
        $this->assertNotNull($user->email_verified_at);
        $this->assertNotNull($user->password);
    }

    public function test_invalid_token_returns_401_and_does_not_login(): void
    {
        $this->mockFirebaseAuthThrowing();

        $response = $this->post('/auth/firebase', ['id_token' => 'bad-token']);

        $response->assertStatus(401);
        $this->assertGuest();
    }

    public function test_unverified_email_does_not_link_existing_account(): void
    {
        $user = User::factory()->create([
            'email' => 'unverified@example.com',
            'firebase_uid' => null,
        ]);

        $this->mockFirebaseAuthReturning($this->makeToken('uid-x', 'unverified@example.com', false));

        $response = $this->post('/auth/firebase', ['id_token' => 'fake-token']);

        $response->assertStatus(409);
        $this->assertGuest();
        $this->assertNull($user->fresh()->firebase_uid);
        $this->assertDatabaseCount('users', 1);
    }
}
