<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\News;
use App\Models\Vacancy;
use Illuminate\Support\Str;
class SeoController extends Controller {
 public function sitemap() {
  $news=News::published()->get(); $jobs=Vacancy::published()->get(); $categories=Category::orderBy('name')->get();
  $cities=News::published()->whereNotNull('city')->where('city','!=','')->select('city')->distinct()->pluck('city')
   ->map(fn($city)=>['name'=>$city,'slug'=>Str::slug($city)])
   ->filter(fn($city)=>$city['slug']!=='')
   ->unique('slug')->values();
  return response()->view('seo.sitemap',compact('news','jobs','cities','categories'))->header('Content-Type','application/xml');
 }
 public function feed() { $news=News::published()->latest('published_at')->take(30)->get(); return response()->view('seo.feed',compact('news'))->header('Content-Type','application/rss+xml'); }
}
