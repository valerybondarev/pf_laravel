<?php

namespace App\Domain\Application\Language\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Language
 *
 * @package App\Domain\Application\Entities
 *
 * @property int    $id
 * @property string $code
 * @property string $name
 * @property bool   $is_default
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Language extends Model
{

}
