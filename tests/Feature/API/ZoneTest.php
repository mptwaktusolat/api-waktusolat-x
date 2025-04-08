<?php

test('Get Prayer Zones available', function () {
    $response = $this->get('/api/zones');

    $response->assertStatus(200);
    
    // Assert response is JSON
    $response->assertHeader('Content-Type', 'application/json');
    
    // Get the response content
    $responseData = $response->json();
    
    // Assert the response is an array
    $this->assertIsArray($responseData);
    $this->assertNotEmpty($responseData);
    
    // Check for expected zone codes (sample check)
    $jakimCodes = array_column($responseData, 'jakimCode');
    $this->assertContains('JHR01', $jakimCodes);
    $this->assertContains('SGR01', $jakimCodes);
    $this->assertContains('WLY01', $jakimCodes);
    
    // Verify schema of a zone entry
    $firstZone = $responseData[0];
    $this->assertArrayHasKey('jakimCode', $firstZone);
    $this->assertArrayHasKey('negeri', $firstZone);
    $this->assertArrayHasKey('daerah', $firstZone);
    
    // Check specific zone data
    $johorZone = collect($responseData)->firstWhere('jakimCode', 'JHR01');
    $this->assertEquals('Johor', $johorZone['negeri']);
    $this->assertEquals('Pulau Aur dan Pulau Pemanggil', $johorZone['daerah']);
    
    // Count total zones
    $this->assertGreaterThan(50, count($responseData)); // There should be over 50 zones based on the example
});
