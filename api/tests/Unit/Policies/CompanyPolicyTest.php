<?php

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use App\Policies\CompanyPolicy;

test('admin or engineer can view any company', function () {
    $company1 = Company::factory()->make(['id' => 1]);
    $company2 = Company::factory()->make(['id' => 2]);
    $admin = User::factory()->make(['role' => UserRole::Admin, 'company_id' => 1]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer, 'company_id' => 1]);

    $policy = new CompanyPolicy();

    expect($policy->view($admin, $company1))->toBeTrue()
        ->and($policy->view($admin, $company2))->toBeTrue()
        ->and($policy->view($engineer, $company1))->toBeTrue()
        ->and($policy->view($engineer, $company2))->toBeTrue();
});

test('customer can view their own company', function () {
    $company1 = Company::factory()->make(['id' => 1]);
    $company2 = Company::factory()->make(['id' => 2]);
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => 2]);
    $policy = new CompanyPolicy();

    expect($policy->view($customer, $company2))->toBeTrue()
        ->and($policy->view($customer, $company1))->toBeFalse();
});

test('admin or engineer can create a company', function () {
    $admin = User::factory()->make(['role' => UserRole::Admin]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer]);
    $customer = User::factory()->make(['role' => UserRole::Customer]);

    $policy = new CompanyPolicy();

    expect($policy->create($admin))->toBeTrue()
        ->and($policy->create($engineer))->toBeTrue()
        ->and($policy->create($customer))->toBeFalse();
});

test('admin or engineer can update any company', function () {
    $company1 = new Company();
    $company2 = new Company();
    $admin = User::factory()->make(['role' => UserRole::Admin, 'company_id' => 1]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer, 'company_id' => 1]);

    $policy = new CompanyPolicy();

    expect($policy->update($admin, $company1))->toBeTrue()
        ->and($policy->update($admin, $company2))->toBeTrue()
        ->and($policy->update($engineer, $company1))->toBeTrue()
        ->and($policy->update($engineer, $company2))->toBeTrue();
});

test('customer can not update any company', function () {
    $company1 = new Company();
    $company2 = new Company();
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => 2]);

    $policy = new CompanyPolicy();

    expect($policy->update($customer, $company1))->toBeFalse()
        ->and($policy->update($customer, $company2))->toBeFalse();
});

test('only admin can delete or restore a company', function () {
    $admin = User::factory()->make(['role' => UserRole::Admin, 'company_id' => 1]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer, 'company_id' => 1]);
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => 1]);
    $company = new Company();

    $policy = new CompanyPolicy();

    expect($policy->delete($admin, $company))->toBeTrue()
        ->and($policy->delete($engineer, $company))->toBeFalse()
        ->and($policy->delete($customer, $company))->toBeFalse()
        ->and($policy->restore($admin, $company))->toBeTrue()
        ->and($policy->restore($engineer, $company))->toBeFalse()
        ->and($policy->restore($customer, $company))->toBeFalse();
});

test('nobody can force delete a company', function () {
    $admin = User::factory()->make(['role' => UserRole::Admin, 'company_id' => 1]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer, 'company_id' => 1]);
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => 1]);
    $company = new Company();

    $policy = new CompanyPolicy();

    expect($policy->forceDelete($admin, $company))->toBeFalse()
        ->and($policy->forceDelete($engineer, $company))->toBeFalse()
        ->and($policy->forceDelete($customer, $company))->toBeFalse();
});
