<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;

test('cleanCurrencyValue handles Indonesian currency format', function () {
    $controller = new ReservationController();

    // Test various Indonesian currency formats
    expect($controller->cleanCurrencyValue('Rp 20.000.000'))->toBe(20000000.0);
    expect($controller->cleanCurrencyValue('20.000.000'))->toBe(20000000.0);
    expect($controller->cleanCurrencyValue('Rp20.000.000'))->toBe(20000000.0);
    expect($controller->cleanCurrencyValue('20000000'))->toBe(20000000.0);
    expect($controller->cleanCurrencyValue('Rp 1.500.000,50'))->toBe(1500000.5);
    expect($controller->cleanCurrencyValue(''))->toBeNull();
    expect($controller->cleanCurrencyValue(null))->toBeNull();
});