<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuideController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }


    public function index() {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $guids = [];
        $items = DB::table('roles')->get();
        $guids[] = ['name' => __('site.roles'), 'table' => 'roles', 'items' => $items];
        $items = DB::table('statuses')->get();
        $guids[] = ['name' => __('site.statuses'), 'table' => 'statuses', 'items' => $items];
        $items = DB::table('sources')->get();
        $guids[] = ['name' => __('site.sources'), 'table' => 'sources', 'items' => $items];
        $items = DB::table('skills')->get();
        $guids[] = ['name' => __('site.skills'), 'table' => 'skills', 'items' => $items];
        $items = DB::table('specializations')->get();
        $guids[] = ['name' => __('site.specializations'), 'table' => 'specializations', 'items' => $items];
        $items = DB::table('levels')->get();
        $guids[] = ['name' => __('site.levels'), 'table' => 'levels', 'items' => $items];
        $items = DB::table('languages')->get();
        $guids[] = ['name' => __('site.languages'), 'table' => 'languages', 'items' => $items];
        $items = DB::table('language_levels')->get();
        $guids[] = ['name' => __('site.language_levels'), 'table' => 'language_levels', 'items' => $items];
        return view('guide.index', compact('guids'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_delete'));
        $data = $request->all();
        $prefix = $this->getPrefixName();
        if (!$data['table'] || !$data['name' . $prefix])
            return redirect()->route('welcome')->withErrors(__('site.not_parameter'));
        $table = trim(strip_tags($data['table']));
        $result = DB::table($table)->insert(['name' . $prefix => trim(strip_tags($data['name' . $prefix]))]);
        if (!$result)
            return redirect()->route('guide')->withErrors(__('site.add_error'));
        return redirect()->route('guide')->with('success',  __('site.add_success'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $data = $request->all();
        $prefix = $this->getPrefixName();
        if (!$data['table'] || !$data['name' . $prefix] || !$data['id'])
            return redirect()->route('welcome')->withErrors(__('site.not_parameter'));
        $table = trim(strip_tags($data['table']));
        $result = DB::table($table)->where('id', intval($data['id']))->update(['name' . $prefix => trim(strip_tags($data['name' . $prefix]))]);
        if ($table === 'statuses') {
            $show_in_public = false;
            if(isset($data['show_in_public']) && $data['show_in_public'] == 'on') {
                $show_in_public = true;
            }
            $result2 = DB::table($table)->where('id', intval($data['id']))->update(['show_in_public' => $show_in_public ]);
            if (!$result && !$result2) {
                return redirect()->route('guide')->withErrors(__('site.update_error'));
            } else {
                return redirect()->route('guide')->with('success', __('site.update_success'));
            }
        }
        if (!$result )
            return redirect()->route('guide')->withErrors(__('site.update_error'));
        return redirect()->route('guide')->with('success', __('site.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $data = $request->all();
        if (!$data['table'] || !$data['id'])
            return redirect()->route('welcome')->withErrors(__('site.not_parameter'));
        $table = trim(strip_tags($data['table']));
        //сделать валидатор
        if ($table == 'statuses') {
            if (DB::table('workers')->where('status_id', intval($data['id']))->count())
                return redirect()->route('guide')->withErrors(__('site.exist_error'));
        }
        if ($table == 'roles') {
            if (DB::table('users')->where('role_id', intval($data['id']))->count())
                return redirect()->route('guide')->withErrors(__('site.exist_error'));
        }
        if ($table == 'skills') {
            if (DB::table('workers')->whereRaw('FIND_IN_SET("' . intval($data['id']) . '", skills)')->count())
                return redirect()->route('guide')->withErrors(__('site.exist_error'));
        }
        $result = DB::table($table)->where('id', intval($data['id']))->delete();
        if (!$result)
            return redirect()->route('guide')->withErrors(__('site.delete_error'));
        return redirect()->route('guide')->with('success', __('site.delete_success'));
    }

    protected function setDefault($table, $id) {
        DB::table($table)->update(['active' => 0]);
        return DB::table($table)->where('id', intval($id))->update(['active' => 1]);
    }

    public function default(Request $request) {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $data = $request->all();
        if (!$data['table'] || !$data['id'])
            return redirect()->route('welcome')->withErrors(__('site.not_parameter'));
        $table = trim(strip_tags($data['table']));
        if (!$this->setDefault($table, $data['id']))
            return redirect()->route('guide')->withErrors(__('site.update_error'));
        return redirect()->route('guide')->with('success', __('site.update_success'));
    }

    protected function getPrefixName() {
        $lang = session()->get('locale');
        return ($lang == 'ru') ? '' : '_' . $lang;
    }
}
