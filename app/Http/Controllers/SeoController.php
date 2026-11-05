<?php
namespace App\Http\Controllers;
use App\Models\News;
use App\Models\Vacancy;
class SeoController extends Controller {
 public function sitemap() {
  $news=News::published()->get(); $jobs=Vacancy::published()->get();
  return response()->view('seo.sitemap',compact('news','jobs'))->header('Content-Type','application/xml');
 }
 public function feed() { $news=News::published()->latest('published_at')->take(30)->get(); return response()->view('seo.feed',compact('news'))->header('Content-Type','application/rss+xml'); }
}
