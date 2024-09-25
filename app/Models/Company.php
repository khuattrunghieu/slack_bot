<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
class Company extends Model
{
    use HasFactory, AsSource;
    
    protected $fillable = ['company_name',];
}
