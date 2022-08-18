<?php

use Gadelgado\Sql2Seeder\Controllers\InspirationController;
use Illuminate\Support\Facades\Route;

// Route::get('inspire', InspirationController::class);
Route::get('inspire', function(Gadelgado\Sql2seeder\Inspire $inspire) {
    return $inspire->justDoIt();
});
