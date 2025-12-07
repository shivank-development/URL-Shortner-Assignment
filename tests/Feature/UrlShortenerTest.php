<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Url;
use Illuminate\Support\Facades\Hash;

class UrlShortenerTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_invite_company_admin()
    {
        $superAdmin = User::factory()->create(['role' => 'SuperAdmin']);
        
        $response = $this->actingAs($superAdmin)->post('/invitations/company', [
            'company_name' => 'Acme Corp',
            'name' => 'Alice Admin',
            'email' => 'alice@acme.com',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('companies', ['name' => 'Acme Corp']);
        $this->assertDatabaseHas('users', ['email' => 'alice@acme.com', 'role' => 'Admin']);
    }

    public function test_admin_can_invite_member_via_signed_route()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['role' => 'Admin', 'company_id' => $company->id]);

        $response = $this->actingAs($admin)->post('/invitations/user', [
            'email' => 'bob@acme.com',
            'role' => 'Member',
        ]);

        $response->assertSessionHas('success');
        
        $url = session('success');
        $this->assertStringContainsString('Invitation Link Generated:', $url);
    }

    public function test_url_list_visibility_rules()
    {
        $company1 = Company::factory()->create(['name' => 'Company A']);
        $company2 = Company::factory()->create(['name' => 'Company B']);

        $adminA = User::factory()->create(['role' => 'Admin', 'company_id' => $company1->id]);
        $memberA = User::factory()->create(['role' => 'Member', 'company_id' => $company1->id]);

        $adminB = User::factory()->create(['role' => 'Admin', 'company_id' => $company2->id]);
        
        $superAdmin = User::factory()->create(['role' => 'SuperAdmin']);

        Url::create(['original_url' => 'http://1.com', 'short_code' => 'AAAAAA', 'user_id' => $adminA->id, 'company_id' => $company1->id]);
        Url::create(['original_url' => 'http://2.com', 'short_code' => 'BBBBBB', 'user_id' => $memberA->id, 'company_id' => $company1->id]);
        Url::create(['original_url' => 'http://3.com', 'short_code' => 'CCCCCC', 'user_id' => $adminB->id, 'company_id' => $company2->id]);

        $respMember = $this->actingAs($memberA)->get('/dashboard');
        $respMember->assertSee('BBBBBB');
        $respMember->assertDontSee('AAAAAA');
        $respMember->assertDontSee('CCCCCC');

        $respAdmin = $this->actingAs($adminA)->get('/dashboard');
        $respAdmin->assertSee('AAAAAA');
        $respAdmin->assertSee('BBBBBB');
        $respAdmin->assertDontSee('CCCCCC');

        $respSuper = $this->actingAs($superAdmin)->get('/dashboard');
        $respSuper->assertSee('AAAAAA');
        $respSuper->assertSee('BBBBBB');
        $respSuper->assertSee('CCCCCC');
    }

    public function test_public_redirection_and_logging()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);
        $url = Url::create([
            'original_url' => 'https://example.com',
            'short_code' => 'xyz123',
            'user_id' => $user->id,
            'company_id' => $company->id,
            'clicks' => 0
        ]);

        $response = $this->get('/xyz123');
        
        $response->assertRedirect('https://example.com');
        $this->assertDatabaseHas('urls', ['short_code' => 'xyz123', 'clicks' => 1]);
        $this->assertDatabaseHas('click_logs', ['url_id' => $url->id]);
    }
}
