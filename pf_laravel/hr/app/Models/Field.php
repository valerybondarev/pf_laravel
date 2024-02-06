<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'worker_fields';

    protected $fillable = ['label', 'value', 'worker_id'];

    public $timestamps = false;

    public function updateField($data) {
        $model = Field::find($data['id']);
        $model->worker_id = $data['worker_id'];
        $model->label = $data['label'];
        $model->value = $data['value'];
        return $model->update();
    }

}
