<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CategoryFilterController;
use App\Http\Controllers\NewsFilterController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\JobController;
use App\Models\Article;
use App\Models\State;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\LocationController as AdminLocationController;
use App\Http\Controllers\Admin\ToolController as AdminToolController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;



Route::middleware(['auth', 'role:user', 'verified'])->group(function () {

    Route::controller(App\Http\Controllers\User\DashboardController::class)->group(function () {
        Route::get('/user/dashboard', 'index')->name('user.dashboard');
        Route::get('/user/saved-items', 'savedItems')->name('user.saved-items');
        Route::get('/user/applications', 'applications')->name('user.applications.index');
        Route::get('/user/settings', 'settings')->name('user.settings');
        Route::put('/user/settings', 'updateSettings')->name('user.settings.update');
        Route::put('/user/settings/password', 'updatePassword')->name('user.settings.password');
        Route::put('/user/settings/subscriptions', 'updateSubscriptions')->name('user.settings.update-subscriptions');
        Route::delete('/user/account', 'deleteAccount')->name('user.account.delete');
    });

    Route::resource('user/resumes', App\Http\Controllers\User\ResumeController::class, [
        'names' => [
            'index' => 'user.resumes.index',
            'create' => 'user.resumes.create',
            'store' => 'user.resumes.store',
            'show' => 'user.resumes.show',
            'edit' => 'user.resumes.edit',
            'update' => 'user.resumes.update',
            'destroy' => 'user.resumes.destroy',
        ]
    ]);

    // Application Routes
    Route::post('/jobs/apply', [App\Http\Controllers\JobApplicationController::class, 'store'])->name('job.apply');
    Route::delete('/applications/{id}', [App\Http\Controllers\JobApplicationController::class, 'destroy'])->name('applications.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Organizer Routes
    Route::controller(App\Http\Controllers\User\OrganizerController::class)->group(function () {
        Route::get('/user/organizer', 'index')->name('user.organizer.index'); // Dashboard
        Route::get('/user/organizer/create', 'create')->name('user.organizer.create');
        Route::post('/user/organizer', 'store')->name('user.organizer.store');
        Route::get('/user/organizer/edit', 'edit')->name('user.organizer.edit');
        Route::put('/user/organizer', 'update')->name('user.organizer.update');
    });

    // Organizer Event Routes
    Route::resource('user/organizer/events', App\Http\Controllers\User\OrganizerEventController::class, [
        'names' => [
            'index' => 'user.organizer.events.index',
            'create' => 'user.organizer.events.create',
            'store' => 'user.organizer.events.store',
            'edit' => 'user.organizer.events.edit',
            'update' => 'user.organizer.events.update',
            'destroy' => 'user.organizer.events.destroy',
        ]
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/saved-items', [App\Http\Controllers\SavedItemController::class, 'store'])->name('saved-items.store');
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

Route::middleware(['auth', 'role:company', 'verified'])->prefix('company-panel')->name('company.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Company\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Company\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Company\ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('company.verified')->group(function () {
        Route::resource('jobs', App\Http\Controllers\Company\JobController::class);
        Route::resource('articles', App\Http\Controllers\Company\ArticleController::class);
        Route::resource('events', App\Http\Controllers\Company\EventController::class);
    });
});

Route::middleware('auth', 'role:admin')->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/locations', [AdminLocationController::class, 'index'])->name('admin.locations.index');
    Route::post('/admin/locations', [AdminLocationController::class, 'store'])->name('admin.locations.store');
    Route::delete('/admin/locations', [AdminLocationController::class, 'destroy'])->name('admin.locations.destroy');

    Route::get('/admin/tools', [AdminToolController::class, 'index'])->name('admin.tools.index');
    Route::post('/admin/tools', [AdminToolController::class, 'store'])->name('admin.tools.store');
    Route::delete('/admin/tools/{tool}', [AdminToolController::class,      'destroy'])->name('admin.tools.destroy');

    Route::get('/admin/categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/admin/categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
    Route::delete('/admin/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/admin/events', [AdminEventController::class, 'index'])->name('admin.events.index');
    Route::get('/admin/events/create', [AdminEventController::class, 'create'])->name('admin.events.create');
    Route::post('/admin/events', [AdminEventController::class, 'store'])->name('admin.events.store');
    Route::get('/admin/events/{id}/edit', [AdminEventController::class, 'edit'])->name('admin.events.edit');
    Route::put('/admin/events/{id}', [AdminEventController::class, 'update'])->name('admin.events.update');
    Route::delete('/admin/events/{event}', [AdminEventController::class, 'destroy'])->name('admin.events.destroy');

    Route::get('/admin/jobs', [AdminJobController::class, 'index'])->name('admin.jobs.index');
    Route::get('/admin/jobs/create', [AdminJobController::class, 'create'])->name('admin.jobs.create');
    Route::post('/admin/jobs', [AdminJobController::class, 'store'])->name('admin.jobs.store');
    Route::get('/admin/jobs/{id}/edit', [AdminJobController::class, 'edit'])->name('admin.jobs.edit');
    Route::put('/admin/jobs/{id}', [AdminJobController::class, 'update'])->name('admin.jobs.update');
    Route::delete('/admin/jobs/{job}', [AdminJobController::class, 'destroy'])->name('admin.jobs.destroy');

    Route::get('/admin/applications', [App\Http\Controllers\Admin\JobApplicationController::class, 'index'])->name('admin.applications.index');
    Route::get('/admin/applications/{id}', [App\Http\Controllers\Admin\JobApplicationController::class, 'show'])->name('admin.applications.show');
    Route::put('/admin/applications/{id}', [App\Http\Controllers\Admin\JobApplicationController::class, 'update'])->name('admin.applications.update');

    Route::get('/admin/companies', [AdminCompanyController::class, 'index'])->name('admin.companies.index');
    Route::get('/admin/companies/create', [AdminCompanyController::class, 'create'])->name('admin.companies.create');
    Route::post('/admin/companies', [AdminCompanyController::class, 'store'])->name('admin.companies.store');
    Route::get('/admin/companies/{id}/edit', [AdminCompanyController::class, 'edit'])->name('admin.companies.edit');
    Route::put('/admin/companies/{id}', [AdminCompanyController::class, 'update'])->name('admin.companies.update');
    Route::delete('/admin/companies/{id}', [AdminCompanyController::class, 'destroy'])->name('admin.companies.destroy');

    Route::get('/admin/articles', [AdminArticleController::class, 'index'])->name('admin.articles.index');
    Route::get('/admin/articles/create', [AdminArticleController::class, 'create'])->name('admin.articles.create');
    Route::post('/admin/articles', [AdminArticleController::class, 'store'])->name('admin.articles.store');
    Route::get('/admin/articles/{id}/edit', [AdminArticleController::class, 'edit'])->name('admin.articles.edit');
    Route::put('/admin/articles/{id}', [AdminArticleController::class, 'update'])->name('admin.articles.update');
    Route::delete('/admin/articles/{id}', [AdminArticleController::class, 'destroy'])->name('admin.articles.destroy');

    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');

    Route::get('admin/reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::get('admin/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('admin.reviews.edit');
    Route::put('admin/reviews/{id}', [ReviewController::class, 'update'])->name('admin.reviews.update');
    Route::delete('admin/reviews/{id}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
    Route::get('/admin/profile/edit', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/admin/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password.update');
    Route::delete('/admin/profile', [AdminProfileController::class, 'destroy'])->name('admin.profile.destroy');
});



require __DIR__ . '/auth.php';



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/about', 'public.about')->name('about');

/*
|--------------------------------------------------------------------------
| Company
|--------------------------------------------------------------------------
*/
Route::get('/company/{slug}', [CompanyController::class, 'showPremium'])
    ->name('company-profile');

/*
|--------------------------------------------------------------------------
| Articles / News
|--------------------------------------------------------------------------
*/
Route::get('/news', [ArticleController::class, 'index'])->name('news');

Route::get('news-filter', [NewsFilterController::class, 'index'])->name('news-filter');

Route::get('/article/{slug}', function ($slug) {
    $article = Article::where('slug', $slug)->firstOrFail();
    return view('public.article-detail', compact('article'));
})->name('article-detail');

/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
*/
Route::get('/categories/{slug}', [CategoryController::class, 'home'])
    ->name('category-detail');

Route::get('/category-filter', [CategoryFilterController::class, 'index'])
    ->name('category-filter');

/*
|--------------------------------------------------------------------------
| States
|--------------------------------------------------------------------------
*/
Route::get('/all-states', function () {
    $states = State::all();
    return view('public.all-states', compact('states'));
})->name('all-states');

Route::get('/states/{slug}', [StateController::class, 'show'])->name('state-detail');
Route::get('/states', [StateController::class, 'index'])->name('states');

/*
|--------------------------------------------------------------------------
| Events
|--------------------------------------------------------------------------
*/
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{category}', [EventController::class, 'category'])->name('events.category');
Route::get('/event/{slug}', [EventController::class, 'show'])->name('events.show');

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/
Route::get('/search-results', [SearchController::class, 'search'])
    ->name('search-results');

/*
|--------------------------------------------------------------------------
| Jobs
|--------------------------------------------------------------------------
*/
Route::get('/jobs', [JobController::class, 'index'])->name('jobs');

Route::get('/jobs/{slug}', [JobController::class, 'show'])
    ->name('job.show');
