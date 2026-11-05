<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Entity extends Model {
 protected $fillable=['name','slug','entity_type','logo','cover_image','description','website','official_jobs_url','region'];
 public function vacancies(){ return $this->hasMany(Vacancy::class); }
}
