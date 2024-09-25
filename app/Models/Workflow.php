<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Workflow extends Model
{
    use HasFactory, AsSource;

    protected $fillable = ['name', 'status', 'channel', 'company_id'];

    public function companyName()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
