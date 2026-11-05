<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class Vacancy extends Model {
 protected $fillable=[
 'entity_id','title','slug','reference_code','image','employment_regime','employment_type','description','functions',
 'requirements','education_level','professional_career','general_experience','specific_experience',
 'courses_training','knowledge','salary_min','salary_max','salary_currency','vacancies_count',
 'department','province','district','workplace','work_mode','published_at','application_start_at',
 'application_end_at','schedule','official_url','bases_url','attachments','status','is_featured','editorial_verified_at'
 ];
 protected $casts=['published_at'=>'datetime','application_start_at'=>'datetime','application_end_at'=>'datetime','editorial_verified_at'=>'datetime','attachments'=>'array','is_featured'=>'boolean','salary_min'=>'decimal:2','salary_max'=>'decimal:2'];
 public function entity(){ return $this->belongsTo(Entity::class); }
 public function scopePublished(Builder $q): Builder { return $q->whereIn('status',['published','expiring'])->where(function($x){$x->whereNull('application_end_at')->orWhere('application_end_at','>=',today());}); }
 public function getSalaryLabelAttribute(): string {
   if (!$this->salary_min && !$this->salary_max) return 'No especificada';
   if ($this->salary_min && $this->salary_max && $this->salary_min != $this->salary_max) return 'S/ '.number_format((float)$this->salary_min,2).' – S/ '.number_format((float)$this->salary_max,2);
   return 'S/ '.number_format((float)($this->salary_min ?? $this->salary_max),2);
 }
}
