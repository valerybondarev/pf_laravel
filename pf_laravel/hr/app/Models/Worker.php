<?php

namespace App\Models;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'workers';

    public $timestamps = true;

    protected $fillable = ['user_id', 'status_id', /*'age',*/ 'sex', 'birthday', 'source_id', 'region', 'phone',
        'telegram', 'watsapp', 'vyber', 'skype', 'resume', 'experience', 'education', 'skills', 'photo', 'comment',
        'specialization_id', 'level_id', 'languages', 'language_level'];

    public function fields()
    {
        return $this->hasMany('App\Models\Field', 'worker_id');
    }

    public function status() {
        return $this->hasOne('App\Models\Status', 'id', 'status_id');
    }

    public function source() {
        return $this->hasOne('App\Models\Source', 'id', 'source_id');
    }

    public function specialization()
    {
        return $this->hasOne(Specialization::class, 'id', 'specialization_id');
    }

    public function level()
    {
        return $this->hasOne(Levels::class, 'id', 'level_id');
    }

    public function saveWorker($data) {
        $prefix = self::getPrefixName();
        $this->user_id = intval($data['user_id']);
        $this->status_id = (isset($data['status_id']) && $data['status_id']) ? intval($data['status_id']) : Status::getDefaultStatus();
        //$this->age = intval($data['age']);
        $this->{'sex' . $prefix} = trim(strip_tags($data['sex' . $prefix]));
        $this->birthday = date('Y-m-d', strtotime($data['birthday']));
        $this->source_id = (isset($data['source_id']) && $data['source_id']) ? intval($data['source_id']) : null;
        $this->{'region' . $prefix} = trim(strip_tags($data['region' . $prefix]));
        $this->phone = trim(strip_tags($data['phone']));
        $this->telegram = trim(strip_tags($data['telegram']));
        $this->watsapp = trim(strip_tags($data['watsapp']));
        $this->vyber = trim(strip_tags($data['vyber']));
        $this->skype = trim(strip_tags($data['skype']));
        $this->resume = trim(strip_tags($data['resume']));
        $this->{'experience' . $prefix} = trim(strip_tags($data['experience' . $prefix]));
        $this->{'education' . $prefix} = trim(strip_tags($data['education' . $prefix]));
        $this->specialization_id = empty($data['specialization_id']) ? null : $data['specialization_id'];
        $this->level_id = empty($data['level_id']) ? null : $data['level_id'];
        $this->skills = (isset($data['skills'])) ? Skill::saveSkills($data['skills']) : null;
        $this->languages = empty($data['languages']) ? null : implode(',', $data['languages']);
        $this->language_levels = empty($data['language_levels']) ? null : implode(',', $data['language_levels']);
        $this->photo = (isset($data['photo']) && $data['photo']) ? $data['photo'] : null;
        $this->{'comment' . $prefix} = (isset($data['comment' . $prefix]) && $data['comment' . $prefix]) ? $data['comment' . $prefix] : null;
        return $this->save();
    }

    public function updateWorker($data) {
        $prefix = self::getPrefixName();
        $model = Worker::find($data['id']);
        $model->user_id = intval($data['user_id']);
        $model->status_id = (isset($data['status_id']) && $data['status_id']) ? intval($data['status_id']) : Status::getDefaultStatus();
        //$model->age = intval($data['age']);
        $model->{'sex' . $prefix} = trim(strip_tags(isset($data['sex' . $prefix]) ? $data['sex' . $prefix] : ''));
        $model->birthday = date('Y-m-d', strtotime($data['birthday']));
        $model->source_id = (isset($data['source_id']) && $data['source_id']) ? intval($data['source_id']) : null;
        $model->{'region' . $prefix} = trim(strip_tags($data['region' . $prefix]));
        $model->phone = trim(strip_tags($data['phone']));
        $model->telegram = trim(strip_tags($data['telegram']));
        $model->watsapp = trim(strip_tags($data['watsapp']));
        $model->vyber = trim(strip_tags($data['vyber']));
        $model->skype = trim(strip_tags($data['skype']));
        $model->resume = trim(strip_tags($data['resume']));
        $model->{'experience' . $prefix} = trim(strip_tags($data['experience' . $prefix]));
        $model->{'comment' . $prefix} = trim(strip_tags($data['comment' . $prefix]));
        $model->{'education' . $prefix} = trim(strip_tags($data['education' . $prefix]));
        $model->specialization_id = empty($data['specialization_id']) ? null : $data['specialization_id'];
        $model->level_id = empty($data['level_id']) ? null : $data['level_id'];
        $model->skills = (isset($data['skills'])) ? Skill::saveSkills($data['skills']) : null;
        $model->languages = empty($data['languages']) ? null : implode(',', $data['languages']);
        $model->language_levels = empty($data['language_levels']) ? null : implode(',', $data['language_levels']);

        if (isset($data['photo']) && $data['photo']) {
            $model->photo = $data['photo'];
        }
        return $model->update();
    }

    public function saveWorkerFromPdf($worker) {
        $prefix = self::getPrefixName();
        $this->user_id = intval($worker->user_id);
        $this->status_id = Status::getDefaultStatus();
        $this->age = isset($worker->age) ? intval($worker->age) : null;
        $this->{'sex' . $prefix} = isset($worker->sex) ? mb_substr(trim(strip_tags($worker->sex)), 0, 1, "UTF-8") : null;
        $this->birthday = isset($worker->birthday) ? $worker->birthday : null;
        $this->source_id = null;
        $this->{'region' . $prefix} = isset($worker->live) ? trim(strip_tags($worker->live)) : null;
        $this->phone = isset($worker->phone) ? trim(strip_tags($worker->phone)) : null;
        $this->telegram = isset($worker->telegram) ? trim(strip_tags($worker->telegram)) : null;
        $this->watsapp = isset($worker->watsapp) ? trim(strip_tags($worker->watsapp)) : null;
        $this->vyber = isset($worker->vyber) ? trim(strip_tags($worker->vyber)) : null;
        $this->skype = isset($worker->skype) ? trim(strip_tags($worker->skype)) : null;
        $this->resume = isset($worker->resume) ? trim(strip_tags($worker->resume)) : null;
        $this->{'experience' . $prefix} = isset($worker->experience) ? trim(strip_tags($worker->experience)) : null;
        $this->{'education' . $prefix} = isset($worker->education) ? trim(strip_tags($worker->education)) : null;
        $this->specialization_id = empty($worker->specialization_id) ? null : $worker->specialization_id;
        $this->level_id = empty($worker->level_id) ? null : $worker->level_id;
        $this->skills = (isset($worker->skills) && !empty($worker->skills)) ? Skill::saveSkills($worker->skills) : null;
        if (isset($worker->placeWork) && !empty($worker->placeWork)) {
            Experience::saveWorkPlaces($worker->placeWork, $this->user_id);
        }
        $this->languages = empty($worker->languages) ? null : $worker->languages;
        $this->language_levels = empty($worker->language_levels) ? null : $worker->language_levels;
        $this->photo = (isset($worker->photo) && $worker->photo) ? $worker->photo : null;
        $this->{'comment' . $prefix} = (isset($worker->comment) && $worker->comment) ? $worker->comment : null;

        return $this->save();
    }

    public static function getAge($birth) {
        return intval(date('Y', time() - strtotime($birth))) - 1970;
    }

    public static function getRight() {
        $rights = Role::all();
        $result = [];
        foreach ($rights as $right) {
            $result[] = $right->{'name' . self::getPrefixName()};
        }
        return $result;
    }

    public static function getStatus() {
        $statuses = Status::all();
        $result = [];
        foreach ($statuses as $status) {
            $result[] = $status->{'name' . self::getPrefixName()};
        }
        return $result;
    }

    public function delete() {
        //dd($this);
        $this->fields()->delete();
        return parent::delete();
    }

    public static function getPrefixName() {
        $lang = session()->get('locale');
        return ($lang == 'ru') ? '' : '_' . $lang;
    }

}
