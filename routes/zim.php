<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    
});

Route::view('/invoice3', 'backend.admin.invoice.invoice3');

require __DIR__.'/auth.php';