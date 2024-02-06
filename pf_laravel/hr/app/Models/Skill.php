<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Worker;

class Skill extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'skills';

    protected $fillable = ['name'];

    public $timestamps = false;

    public static function saveSkills($skills) {
        $result = [];
        if ($skills && is_array($skills) && !empty($skills)) {
            $prefix = Worker::getPrefixName();
            foreach ($skills as $skill) {
                $skill = trim(strip_tags($skill));
                if ($skill) {
                    if (!is_numeric($skill)) {
                        if (!self::where('name' . $prefix, $skill)->count()) {
                            $newSkill = self::create([
                                'name' . $prefix => $skill
                            ]);
                            $result[] = $newSkill->id;
                        }
                    } else {
                        $result[] = $skill;
                    }
                }
            }
        }
        return (!empty($result)) ? implode(',', $result) : null;
    }
}
