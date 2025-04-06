<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    
});

Route::view('/invoice3', 'backend.admin.invoice.invoice2');

require __DIR__.'/auth.php';