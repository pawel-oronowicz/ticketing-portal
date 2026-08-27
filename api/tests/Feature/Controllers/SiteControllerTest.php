<?php

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Site;
use App\Models\User;

test('unauthenticated user cannot view sites for company', function () {
    $response = $this->getJson('/api/companies/1/sites');

    $response->assertStatus(401);
});

test('engineer and admin can view all sites for any company', function () {
    $company = Company::factory()->withSites(3)->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);

    $response = $this->actingAs($engineer)->getJson("/api/companies/$company->id/sites");
    $response->assertStatus(200)
        ->assertJsonCount(3);

    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $response = $this->actingAs($admin)->getJson("/api/companies/$company->id/sites");
    $response->assertStatus(200)
        ->assertJsonCount(3);
});

test('customer can only view sites belonging to their company', function () {
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company1->id]);
    User::factory()->create(['role' => UserRole::Engineer]);

    Site::factory()->count(2)->create(['company_id' => $company1->id]);
    Site::factory()->count(1)->create(['company_id' => $company2->id]);

    $response = $this->actingAs($customer)->getJson("/api/companies/$company1->id/sites");
    $response->assertStatus(200)
        ->assertJsonCount(2);

    $response = $this->actingAs($customer)->getJson("/api/companies/$company2->id/sites");
    $response->assertStatus(404);
});
