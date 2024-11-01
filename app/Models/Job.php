<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'job_listing'; 

    protected $fillable = [
        'job_category',  
        'description',
        'title',
        'company_name',
        'job_type',
        'employer_id',
        'salary_range',
    ];

    public function users(){

        return $this->belongsTo(User::class);
    }

    public function applications(){

        return $this->hasMany(Application::class);
    }
}
