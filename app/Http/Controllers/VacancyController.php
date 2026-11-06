<?php
namespace App\Http\Controllers;
use App\Models\Vacancy;
use Illuminate\Http\Request;
class VacancyController extends Controller {
 public function index(Request $request) {
  $vacancies=Vacancy::published()->with('entity')
   ->when($request->q,function($q,$v){
    $terms=$this->searchTerms($v);
    $q->where(function($sub)use($terms){
     foreach($terms as $term){
      $like='%'.$term.'%';
      $sub->orWhere('title','like',$like)
       ->orWhere('professional_career','like',$like)
       ->orWhere('education_level','like',$like)
       ->orWhere('description','like',$like)
       ->orWhere('knowledge','like',$like)
       ->orWhere('requirements','like',$like)
       ->orWhereHas('entity',fn($e)=>$e->where('name','like',$like));
     }
    });
   })
   ->when($request->region,fn($q,$v)=>$q->where('department',$v))
   ->when($request->regime,fn($q,$v)=>$q->where('employment_regime',$v))
   ->when($request->entity,fn($q,$v)=>$q->whereHas('entity',fn($e)=>$e->where('slug',$v)))
   ->latest('published_at')->paginate(12)->withQueryString();
  $regions=['Amazonas','Áncash','Apurímac','Arequipa','Ayacucho','Cajamarca','Callao','Cusco','Huancavelica','Huánuco','Ica','Junín','La Libertad','Lambayeque','Lima','Loreto','Madre de Dios','Moquegua','Pasco','Piura','Puno','San Martín','Tacna','Tumbes','Ucayali'];
  return view('vacancies.index',compact('vacancies','regions'));
 }
 private function searchTerms(string $value): array {
  $groups=[
   ['computacion','computo','informatica','sistemas','software','programacion','desarrollador','analista de sistemas','tecnologia de la informacion','ingenieria de sistemas','ingenieria informatica','tecnologias de la informacion'],
   ['ingenieria','ingeniero','ingeniera','ingenieros','ingenieras'],
   ['contabilidad','contador','contadora','contable','contables','ciencias contables'],
   ['administracion','administrador','administradora','administrativo','gestion','gestion publica'],
   ['derecho','abogado','abogada','legal','juridica','juridico','ciencias juridicas'],
   ['medicina','medico','medica','salud','ciencias de la salud'],
   ['enfermeria','enfermero','enfermera','licenciado en enfermeria'],
   ['economia','economista','finanzas','financiero','financiera'],
   ['psicologia','psicologo','psicologa'],
   ['educacion','docente','profesor','profesora','maestro','maestra','educador','pedagogia'],
   ['secretaria','secretario','asistente administrativo','asistente','auxiliar administrativo'],
   ['conductor','chofer','transportista','operador de vehiculo'],
   ['seguridad','vigilante','sereno','agente de seguridad','seguridad ciudadana'],
   ['limpieza','auxiliar de limpieza','personal de limpieza','servicios generales'],
   ['cocina','cocinero','cocinera','chef','ayudante de cocina'],
   ['ventas','vendedor','vendedora','asesor comercial','comercial','ejecutivo comercial'],
   ['atencion','atencion al cliente','recepcionista','call center','teleoperador'],
   ['recursos humanos','rrhh','personal','talento humano'],
   ['logistica','almacen','almacenero','abastecimiento','inventarios'],
   ['mecanica','mecanico','mecanica','mecanica automotriz','mecanico automotriz'],
   ['electricidad','electricista','electrico','electronica','electronico'],
   ['civil','construccion','obras','ingenieria civil','edificaciones'],
   ['arquitectura','arquitecto','arquitecta'],
   ['quimica','quimico','quimica','ingenieria quimica'],
   ['biologia','biologo','biologa','microbiologia','microbiologo'],
   ['agronomia','agronomo','agronoma','agraria','agricola','ingenieria agronoma'],
   ['laboratorio','laboratorista','tecnico de laboratorio','tecnologia medica'],
   ['tecnico','tecnica','tecnologia','tecnologo'],
   ['practicante','practicas','practica','practicas preprofesionales','practicas profesionales'],
   ['nutricion','nutricionista','dietista'],
   ['veterinaria','veterinario','veterinaria'],
   ['comunicacion','comunicador','periodismo','periodista','ciencias de la comunicacion'],
   ['marketing','mercadeo','publicidad','comunicaciones'],
   ['auditoria','auditor','auditoria interna','control interno'],
   ['trabajo social','asistente social','servicio social'],
  ];
  $normalize=function(string $text):string{
   $text=mb_strtolower(trim($text),'UTF-8');
   return strtr($text,['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n']);
  };
  $query=$normalize($value);
  if($query==='') return [];
  $words=preg_split('/\s+/u',$query,-1,PREG_SPLIT_NO_EMPTY)?:[];
  $terms=[$query];
  foreach($groups as $group){
   $group=array_map($normalize,$group);
   $match=false;
   foreach($group as $term){
    if(mb_strlen($term)<4) continue;
    if(str_contains($term,$query)||str_contains($query,$term)){ $match=true; break; }
    foreach($words as $word){
     if(mb_strlen($word)<4) continue;
     if(str_contains($term,$word)||str_contains($word,$term)){ $match=true; break 2; }
    }
   }
   if($match) $terms=array_merge($terms,$group);
  }
  $terms=array_values(array_unique(array_filter($terms,fn($term)=>mb_strlen($term)>=3)));
  return array_slice($terms,0,40);
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
