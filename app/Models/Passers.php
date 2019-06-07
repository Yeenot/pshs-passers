<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passers extends Model
{
    protected $table = 'Passers';

    protected $fillable = [
        'surname',
        'firstname',
        'campus_eligibility',
        'school',
        'division'
    ];
    protected $appends = ['fullname'];
    public $timestamps = false;

    public function getFullnameAttribute()
    {
        return "{$this->surname}, {$this->firstname}";
    }

    public function disableDynamicAccessors()
    {
        $this->setAppends([]);
    }
}
