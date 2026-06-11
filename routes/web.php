<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuizController::class, 'index'])->name('quiz.index');
Route::post('/api/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');
