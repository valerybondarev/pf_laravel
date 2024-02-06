<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\LanguageLevel;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CandidateController extends Controller
{
    public function welcome()
    {
        return view('candidate.welcome');
    }

    public function index (Request $request)
    {
        $search = $request->get('search');

        $searchPart = null;
        if (!empty($search)) {
            $searchPart = "%{$search}%";
        }
        $skills = Skill::all()->getDictionary();
        $users = User::query()
            ->select(['users.slug', 'users.first_name', 'users.name', 'users.name_en', 'workers.photo', 'workers.skills',
                'specializations.name as specialization', 'specializations.name_en as specialization_en'])
            ->join('workers', 'users.id', '=', 'workers.user_id')
            ->leftJoin('specializations', 'specializations.id', '=', 'workers.specialization_id')
            ->leftJoin('statuses', 'statuses.id', '=', 'workers.status_id')
            ->where('statuses.show_in_public', '=', 1)
            ->when($searchPart, function ($query, $searchPart) {
                $query
                    ->orWhere('specializations.name', 'like', $searchPart)
                    ->orWhere('specializations.name_en', 'like', $searchPart);

                $foundSkills = Skill::query()
                    ->where('skills.name', 'like', $searchPart)
                    ->orWhere('skills.name_en', 'like', $searchPart)
                    ->get()
                    ->pluck('id')
                    ->all();

                if (!empty($foundSkills)) {
                    $query->orWhere(function ($query) use ($foundSkills) {
                        foreach ($foundSkills as $skillId) {
                            $query
                                ->orWhereRaw("workers.skills REGEXP '^{$skillId}$'")
                                ->orWhereRaw("workers.skills REGEXP '^{$skillId},'")
                                ->orWhereRaw("workers.skills REGEXP ',{$skillId},'")
                                ->orWhereRaw("workers.skills REGEXP ',{$skillId}$'");
                        }

                        return $query;
                    });
                }
            })
            ->get();
        $locale = App::getLocale();
        $locale = $locale === 'ru' ? '' : '_' . $locale;
        return view('candidate.index', compact('users', 'skills', 'search', 'locale'));
    }

    public function view (string $slug)
    {
        $user = User::with(['worker', 'experiences'])
            ->where('users.slug', '=', $slug)
            ->first();

        $age = null;
        if (isset($user->worker['birthday'])) {
            $age = $user->worker::getAge($user->worker['birthday']);
        }
        $skills = Skill::all()->getDictionary();
        $languages = Language::all()->getDictionary();
        $languageLevelsList = LanguageLevel::all()->getDictionary();
        $locale = App::getLocale();
        $languageLevels = [];
        if (!empty($user->worker->language_levels)) {
            $languageLevels = explode(',', $user->worker->language_levels);
        }

        foreach ($user->experiences as $experience) {
            if (!empty($experience['date_start'])) {
                $experience['date_start'] =  $this->formatDate($experience['date_start'], $locale);
            }

            if (!empty($experience['date_end'])) {
                $experience['date_end'] =  $this->formatDate($experience['date_end'], $locale);
            }
        }

        $locale = $locale === 'ru' ? '' : '_' . $locale;
        return view('candidate.view', compact('user', 'age', 'skills', 'languages', 'languageLevels', 'languageLevelsList', 'locale'));
    }

    private function formatDate(string $date, string $locale)
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $date);

        $intlLocale = $locale . '_' . strtoupper($locale);

        $formatter = new \IntlDateFormatter($intlLocale, \IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE);
        $formatter->setPattern('LLLL YYYY');
        return mb_convert_case($formatter->format($dateTime), MB_CASE_TITLE);
    }
}
