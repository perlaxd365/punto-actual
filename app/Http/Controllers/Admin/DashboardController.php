<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\News; use App\Models\Vacancy; use App\Models\Entity; use App\Models\Course; use App\Models\Subscriber; use App\Models\Visit;
class DashboardController extends Controller {
 public function __invoke(){ return view('admin.dashboard',['newsCount'=>News::count(),'vacancyCount'=>Vacancy::count(),'entityCount'=>Entity::count(),'courseCount'=>Course::count(),'pending'=>Vacancy::where('status','draft')->count(),'subscriberCount'=>Subscriber::where('status','active')->count(),'todayVisits'=>Visit::whereDate('visited_on',today())->count(),'yesterdayVisits'=>Visit::whereDate('visited_on',today()->subDay())->count(),'totalVisits'=>Visit::count()]); }
}
