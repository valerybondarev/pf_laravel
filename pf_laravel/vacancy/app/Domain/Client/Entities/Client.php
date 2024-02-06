<?php

namespace App\Domain\Client\Entities;


use App\Base\Models\BaseModel;
use App\Domain\Client\Enums\MembershipStatusEnum;
use App\Domain\Event\Entities\Event;
use App\Domain\TourClub\Entities\TourClub;
use App\Domain\University\Entities\University;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * This is the model class for table "clients".
 * Class Client
 *
 * @package  App\Domain\Client\Entities\Client
 * @mixin  Eloquent
 * @property  int                        $id
 * @property  string                     $role                   Роль пользователя (пользователь, администратор, модератор)
 * @property  int                        $tour_club_id           ID турклуба
 * @property  int                        $sports_category_id     Спортивный разряд
 * @property  int                        $source_id              Источник члена
 * @property  string                     $command                Состояние пользователя
 * @property  int                        $telegram_id            ID телеграм
 * @property  string                     $telegram_status        статус в телеграм
 * @property  string                     $username               Никнейм
 * @property  string                     $first_name             Имя
 * @property  string                     $last_name              Фамилия
 * @property  string                     $middle_name            Отчество
 * @property  string                     $phone                  Телефон
 * @property  string                     $year_in_tk             С какого года в турклубе
 * @property  string                     $status_learn           Учится 1, Работает 0
 * @property  string                     $university_id          Университет
 * @property  int                        $year_in_university     С какого года в универе
 * @property  string                     $department             Факультет
 * @property  string                     $group                  Группа
 * @property  int                        $live_in_dorm           Адрес проживания (фактическое место жительства)
 * @property  string                     $work_organization      Название организации где работает
 * @property  string                     $vk_link                Ссылка на Вконтакте
 * @property  string                     $camping_experience     Опыт в походах
 * @property  string                     $status                 Статус: Активный, забанен в будущем
 * @property  Carbon                     $registered_at          Дата регистрации (Учитывается как признак регистрации)
 * @property  Carbon                     $born_at                Дата рождения
 * @property  int                        $points
 * @property  int                        $mailing_news           Подписка на новости
 * @property  int                        $mailing_events         Подписка на мероприятия
 * @property  int                        $start                  Первое сообщение было отправлено
 * @property  Carbon                     $created_at
 * @property  Carbon                     $updated_at
 *
 *
 * @property  ClientMessage[]|Collection $clientMessages
 * @property  TourClub|null              $tourClub
 * @property  SportsCategory|null        $sportsCategory
 * @property  University|null            $university
 * @property  string|null                $fullName
 * @property  Membership|null            $membership
 * @property  Membership|null            $activeMembership
 * @property  Event[]|Collection         $events
 */
class Client extends BaseModel
{
    public function getFullNameAttribute(): ?string
    {
        return trim($this->first_name . ' ' . $this->last_name) ?: $this->username ?: $this->phone;
    }

    protected $table = 'clients';

    protected $dates = ['registered_at', 'born_at'];

    public function clientMessages(): HasMany
    {
        return $this->hasMany(ClientMessage::class);
    }

    public function tourClub()
    {
        return $this->belongsTo(TourClub::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function sportsCategory()
    {
        return $this->belongsTo(SportsCategory::class);
    }

    /**
     * Активная подписка
     *
     * @return HasOne
     */
    public function activeMembership(): HasOne
    {
        return $this->hasOne(Membership::class)->where('status')->where('extended_to', '>', Carbon::now());
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'client_event');
    }

    /**
     * Наличие попытки подписаться на этот сезон
     *
     * @return HasOne
     */
    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class)->where('extended_to', '>', Carbon::now());
    }

    public function createMemberShip()
    {
        if (!$this->membership) {
            $this->membership()->create([
                'status'      => MembershipStatusEnum::ACTIVE,
                'extended_to' => $this->tourClub->membership_to,
                //'created'
            ]);
            $this->refresh();
        }
    }

    /**
     * @param $command
     */
    public function setCommand($command)
    {
        $newCommand = !is_string($command) ? json_encode($command) : $command;
        if ($newCommand != $this->command) {
            $this->command = $newCommand;
            $this->save();
        }
    }

    public function setStart()
    {
        if (!$this->start) {
            $this->start = 1;
            $this->save();
        }
    }
}
