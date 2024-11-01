<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'job_id',
        'user_id',     
        'cover_letter', 
        'resume',        
        'status',        
    ];


    public function jobs(){
    return $this->belongsTo(Job::class, 'job_id');
    }

 // Application.php (Application Model)
public function user()
{
    return $this->belongsTo(User::class, 'user_id'); // Assuming user_id is the foreign key in applications table
}

    


}
