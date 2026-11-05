<?php
namespace App\Http\Controllers;
use App\Models\News;
use App\Models\Category;
use App\Models\Vacancy;
use Illuminate\Http\Request;
class NewsController extends Controller {
 public function index(Request $request) {
  $news=News::published()->with(['category','author'])->when($request->q,fn($q,$v)=>$q->where(fn($w)=>$w->where('title','like',"%$v%")->orWhere('excerpt','like',"%$v%")))->latest('published_at')->paginate(12)->withQueryString();
  return view('news.index',compact('news'));
 }
 public function show(News $news) {
  abort_unless($news->status==='published' && $news->published_at?->isPast(),404);
  $similar=News::published()->with('category')->when($news->category_id,fn($q)=>$q->where('category_id',$news->category_id))->whereKeyNot($news->id)->latest('published_at')->take(3)->get();
  $recommended=News::published()->with('category')->whereKeyNot($news->id)->when($news->category_id,fn($q)=>$q->where('category_id','!=',$news->category_id))->latest('published_at')->take(3)->get();
  $vacancies=Vacancy::published()->with('entity')->latest('published_at')->take(3)->get();
  return view('news.show',compact('news','similar','recommended','vacancies'));
 }
 public function category(Category $category) { $news=News::published()->where('category_id',$category->id)->latest('published_at')->paginate(12); return view('news.index',compact('news','category')); }
 public function international() { $news=News::published()->with('category')->whereHas('category',fn($q)=>$q->where('slug','internacional'))->latest('published_at')->paginate(12); return view('news.international',compact('news')); }
}
