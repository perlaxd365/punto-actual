<?php
namespace App\Http\Controllers;
use App\Models\Entity;
class EntityController extends Controller {
 public function show(Entity $entity) { $vacancies=$entity->vacancies()->whereIn('status',['published','expiring'])->latest()->paginate(10); return view('entities.show',compact('entity','vacancies')); }
}
