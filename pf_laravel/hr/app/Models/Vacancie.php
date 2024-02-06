<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vacancie extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'vacancies';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'specialization_id',
        'salary_from',
        'salary_to',
        'taxes',
        'experience',
        'requirements',
        'skills'
    ];

    public function specialization()
    {
        return $this->hasOne(Specialization::class, 'id', 'specialization_id');
    }

    public static function generateUniqueCode()
    {
        do {
            $referal_code = strtolower(Str::random(20));
        } while (self::where("slug", "=", $referal_code)->first());

        return $referal_code;
    }

}
