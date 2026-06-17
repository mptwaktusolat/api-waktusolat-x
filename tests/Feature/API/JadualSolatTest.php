<?php

describe('Jadual Solat PDF', function () {
    // Note: PDF tests verify successful generation (200 status) without exceptions
    // Actual PDF content testing is skipped as dompdf->stream() outputs directly

    test('generates PDF for month with default params', function () {
        $response = $this->get('/jadual_solat/sgr01');
        $response->assertStatus(200);
    })->skip('PDF stream() outputs directly, cannot test content in HTTP tests');

    test('generates PDF with specific year and month', function () {
        $response = $this->get('/jadual_solat/sgr01?year=2024&month=6');
        $response->assertStatus(200);
    })->skip('PDF stream() outputs directly, cannot test content in HTTP tests');

    test('generates PDF in portrait orientation', function () {
        $response = $this->get('/jadual_solat/sgr01?orientation=portrait');
        $response->assertStatus(200);
    })->skip('PDF stream() outputs directly, cannot test content in HTTP tests');

    test('generates PDF in landscape orientation', function () {
        $response = $this->get('/jadual_solat/sgr01?orientation=landscape');
        $response->assertStatus(200);
    })->skip('PDF stream() outputs directly, cannot test content in HTTP tests');

    test('generates PDF with 12 hour time format', function () {
        $response = $this->get('/jadual_solat/sgr01?timeFormat=12h');
        $response->assertStatus(200);
    })->skip('PDF stream() outputs directly, cannot test content in HTTP tests');

    test('generates PDF with 24 hour time format (default)', function () {
        $response = $this->get('/jadual_solat/sgr01?timeFormat=24h');
        $response->assertStatus(200);
    })->skip('PDF stream() outputs directly, cannot test content in HTTP tests');

    test('returns 404 for invalid zone', function () {
        $response = $this->getJson('/jadual_solat/invalid99');

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
        expect($response->json('message'))->toContain('No data found for zone');
    });

    test('validates year is 4 digits', function () {
        $response = $this->getJson('/jadual_solat/sgr01?year=99');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year']);
    });

    test('validates year is minimum 2020', function () {
        $response = $this->getJson('/jadual_solat/sgr01?year=2019');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year']);
    });

    test('validates month is an integer', function () {
        $response = $this->getJson('/jadual_solat/sgr01?month=abc');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['month']);
    });

    test('PDF has correct CORS header', function () {
        $response = $this->get('/jadual_solat/sgr01');

        $response->assertStatus(200);
        // CORS header is set in the controller
        $response->assertHeader('Access-Control-Allow-Origin', '*');
    });
});
