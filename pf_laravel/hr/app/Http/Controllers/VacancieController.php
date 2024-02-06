<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Specialization;
use App\Models\Vacancie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VacancieController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //доступ всем кроме пользователей
        if (Auth::user()->role_id == 2)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        //фильтрация
        $skills = Skill::all();

        $filter = [];
        $fname = false;
        $fskills = false;
        $data = $request->all();
        if (isset($data['name']) && $data['name'] != '') {
            $filter['name'] = $data['name'];
            $fname = $filter['name'];
        }
        if (isset($data['skills']) && !empty($data['skills'])) {
            $filter['skills'] = $data['skills'];
            $fskills = $filter['skills'];
        }

        $skills = Skill::all();

        //запрос
        $vacancies = DB::table('vacancies')
            ->select(
                'vacancies.id',
                'vacancies.name',
                'vacancies.name_en',
                'specializations.name as specialization',
                'specializations.name_en as specialization_en',
                'vacancies.salary_from',
                'vacancies.salary_from_en',
                'vacancies.salary_to',
                'vacancies.salary_to_en',
                'vacancies.taxes',
                'vacancies.experience',
                'vacancies.experience_en',
                'vacancies.created_at',
                'vacancies.skills'
            )
            ->join('specializations', 'specializations.id', '=', 'vacancies.specialization_id')
           ->when($fname, function ($query, $fname) {
                return $query->where('vacancies.name', 'like', "%" . $fname . "%");
            })
            ->when($fskills, function ($query, $fskills) {
                if (!empty($fskills)) {
                    foreach ($fskills as $fskill) {
                        $query->whereRaw('FIND_IN_SET("'.$fskill.'", vacancies.skills)');
                    }
                }
                //dd($query);
                return $query;
            }) ->get();

        //dd($vacancies);

        return view('vacancies.index', compact('vacancies', 'skills', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));

        $vacancie = new Vacancie();
        $skills = Skill::all();
        $specializations = Specialization::all();

        return view('vacancies.create', compact('vacancie', 'skills', 'specializations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = $request->all();
        if(isset($data['skills'])){
            $data['skills'] = implode(',', $data['skills']);
        }

        $data['slug'] = Vacancie::generateUniqueCode();
        //dd($data);
        $vacancie = Vacancie::create($data);
        if(!$vacancie){
            return redirect()->route('vacancie')->withErrors(__('site.add_vacancie_error'));
        }

        return redirect()
            ->route('vacancie')
            ->with('success', __('site.add_vacancie_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        if (is_numeric($id))
            $vacancie = Vacancie::where('id', '=', $id)->with('specialization')->first();
        else
            $vacancie = Vacancie::where('slug', '=', $id)->with('specialization')->first();
        $skills = Skill::all();

        return view('vacancies.show', compact('vacancie', 'skills'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Vacancie  $vacancie
     * @return \Illuminate\Http\Response
     */
    public function edit(Vacancie  $vacancie)
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));

        $skills = Skill::all();
        $specializations = Specialization::all();

        return view('vacancies.edit', compact('vacancie', 'skills', 'specializations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Vacancie  $vacancie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vacancie  $vacancie)
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $data = $request->all();
        if(isset($data['skills'])){
            $data['skills'] = implode(',', $data['skills']);
        }


        if (!$vacancie->slug)
            $vacancie->slug = Vacancie::generateUniqueCode();
        $vacancie->update($data);

        if($vacancie){
            return redirect()->route('vacancie.edit', $vacancie->id)
                ->with('success', __('site.update_vacancie_success'));
        }

        return redirect()->route('vacancie')->withErrors(__('site.update_vacancie_error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Vacancie  $vacancie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vacancie  $vacancie)
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $vacancie->delete();
        return redirect()->route('vacancie')
            ->with('success', __('site.delete_vacancie_success'));
    }
}
