<?php
namespace App\Http\Controllers;
use App\Models\News;
use App\Models\Category;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class NewsController extends Controller {
 public function index(Request $request) {
  $news=News::published()->with(['category','author'])->when($request->q,fn($q,$v)=>$q->where(fn($w)=>$w->where('title','like',"%$v%")->orWhere('excerpt','like',"%$v%")))->latest('published_at')->paginate(12)->withQueryString();
  $latest=News::published()->with('category')->latest('published_at')->take(6)->get();
  $categories=Category::orderBy('name')->get();
  return view('news.index',compact('news','latest','categories'));
 }
 public function city(string $city) {
  $normalized=Str::slug($city);
  $match=News::published()->whereNotNull('city')->where('city','!=','')->select('city')->distinct()->pluck('city')->first(fn($name)=>Str::slug($name)===$normalized);
  abort_unless($match,404);
  $news=News::published()->with(['category','author'])->where('city',$match)->latest('published_at')->paginate(12)->withQueryString();
  $latest=News::published()->with('category')->latest('published_at')->take(6)->get();
  $categories=Category::orderBy('name')->get();
  return view('news.city',compact('news','latest','categories','match'));
 }
 public function show(News $news) {
  abort_unless($news->status==='published' && $news->published_at?->isPast(),404);
  $similar=News::published()->with('category')->when($news->category_id,fn($q)=>$q->where('category_id',$news->category_id))->whereKeyNot($news->id)->latest('published_at')->take(3)->get();
  $recommended=News::published()->with('category')->whereKeyNot($news->id)->when($news->category_id,fn($q)=>$q->where('category_id','!=',$news->category_id))->latest('published_at')->take(3)->get();
  $vacancies=Vacancy::published()->with('entity')->latest('published_at')->take(3)->get();
  return view('news.show',compact('news','similar','recommended','vacancies'));
 }
 public function category(Category $category) { $news=News::published()->with(['category','author'])->where('category_id',$category->id)->latest('published_at')->paginate(12); $latest=News::published()->with('category')->latest('published_at')->take(6)->get(); $categories=Category::orderBy('name')->get(); return view('news.index',compact('news','category','latest','categories')); }
 public function international() { $news=News::published()->with('category')->whereHas('category',fn($q)=>$q->where('slug','internacional'))->latest('published_at')->paginate(12); return view('news.international',compact('news')); }
}
