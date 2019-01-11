<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    
	protected $fillable = ["title","author","website","description","category_id","image"];

}
