<?php

namespace App\Providers;

use App\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use View;
use App\Course;
use App\User;
use App\Quiz;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        View::composer('*', function ($view) {
            $all_courses = Course::all()->load('instructors');
            $quizzes = Quiz::all()->load('questions');
            $courses = User::filterByUser(Auth::id(), $all_courses, 'instructors');
            $courses_id = Course::retrieveId($courses);
            $quizzes = Quiz::filterByCourse($courses_id, $quizzes);
            $questions = Question::separateQuestionTypes($quizzes,'MCQ');
            $problems = Question::separateQuestionTypes($quizzes, 'JUDGE');
            $view->with('auth', Auth::user());
            $view->with('courses', $courses);
            $view->with('quizzes', $quizzes);
            $view->with('questions', $questions);
            $view->with('problems', $problems);
        });
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
