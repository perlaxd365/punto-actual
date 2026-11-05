<?php
namespace App\Http\Controllers;
use App\Models\Vacancy;
use Illuminate\Http\Request;
class VacancyController extends Controller {
 public function index(Request $request) {
  $vacancies=Vacancy::published()->with('entity')
   ->when($request->q,fn($q,$v)=>$q->where('title','like',"%$v%"))
   ->when($request->region,fn($q,$v)=>$q->where('department',$v))
   ->when($request->regime,fn($q,$v)=>$q->where('employment_regime',$v))
   ->when($request->entity,fn($q,$v)=>$q->whereHas('entity',fn($e)=>$e->where('slug',$v)))
   ->latest('published_at')->paginate(12)->withQueryString();
  $regions=['Amazonas','Áncash','Apurímac','Arequipa','Ayacucho','Cajamarca','Callao','Cusco','Huancavelica','Huánuco','Ica','Junín','La Libertad','Lambayeque','Lima','Loreto','Madre de Dios','Moquegua','Pasco','Piura','Puno','San Martín','Tacna','Tumbes','Ucayali'];
  return view('vacancies.index',compact('vacancies','regions'));
 }
 public function show(Vacancy $vacancy) {
  abort_unless(in_array($vacancy->status,['published','expiring']) && (!$vacancy->application_end_at || $vacancy->application_end_at->isFuture()),404);
  $vacancy->load('entity');
  $similar=Vacancy::published()->with('entity')->whereKeyNot($vacancy->id)
   ->when($vacancy->department,fn($q)=>$q->where('department',$vacancy->department))
   ->when(!$vacancy->department,fn($q)=>$q->where('employment_regime',$vacancy->employment_regime))
   ->latest('published_at')->take(6)->get();
  $recommended=Vacancy::published()->with('entity')->whereKeyNot($vacancy->id)
   ->when($vacancy->employment_regime,fn($q)=>$q->where('employment_regime','!=',$vacancy->employment_regime))
   ->latest('published_at')->take(6)->get();
  return view('vacancies.show',compact('vacancy','similar','recommended'));
 }
}
