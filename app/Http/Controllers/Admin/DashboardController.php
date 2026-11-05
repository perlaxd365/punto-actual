<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\News; use App\Models\Vacancy; use App\Models\Entity;
class DashboardController extends Controller {
 public function __invoke(){ return view('admin.dashboard',['newsCount'=>News::count(),'vacancyCount'=>Vacancy::count(),'entityCount'=>Entity::count(),'pending'=>Vacancy::where('status','draft')->count()]); }
}
