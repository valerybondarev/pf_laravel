<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'experiences';

    protected $fillable = ['company', 'position', 'duties', 'current', 'date_start', 'date_end', 'user_id'];

    public $timestamps = false;

    public static function updates($arr, $user_id) {
        if (!empty($arr)) {
            foreach ($arr as $item) {
                if ($model = self::find($item)) {
                    $model->user_id = $user_id;
                    $model->save();
                }
            }
        }
    }

    public static function saveWorkPlaces($places, $user_id) {
        $result = [];
        if ($places && is_array($places) && !empty($places)) {
            if ($user_id && (!self::where('user_id', $user_id)->count())) {
                foreach ($places as $place) {
                    $new = self::create([
                        'company' => $place['company'],
                        'date_start' => $place['date_start'],
                        'date_end' => $place['date_end'],
                        'current' => $place['current'],
                        'duties' => $place['duties'],
                        'user_id' => $user_id
                    ]);
                    $result[] = $new->id;
                }
            }
        }
        //return (!empty($result)) ? implode(',', $result) : null;
    }
}
