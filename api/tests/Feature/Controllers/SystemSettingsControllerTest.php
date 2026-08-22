<?php

test('unauthenticated user can fetch system settings', function () {
    $response = $this->getJson('/api/system-settings');

    $response->assertStatus(200);
});

test('index returns 1 system settings', function () {
    $response = $this->getJson("/api/system-settings");

    $response->assertJsonCount(1);
});
