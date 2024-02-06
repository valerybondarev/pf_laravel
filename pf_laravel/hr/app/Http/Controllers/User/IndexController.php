<?php

namespace App\Http\Controllers\User;

use App\Classes\parsePdf;
use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Field;
use App\Models\Language;
use App\Models\LanguageLevel;
use App\Models\Levels;
use App\Models\Specialization;
use App\Models\Worker;
use App\Models\User;
use App\Models\Role;
use App\Models\Source;
use App\Models\Status;
use App\Models\Skill;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    private function canModify(User $user)
    {
        return Auth::user()->role_id == 2 && $user->id == Auth::user()->id;
    }

    private function isAdmin(User $user)
    {
        return Auth::user()->role_id == 1;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function welcome(Request $request) {
        $user = Auth::user();

        return view('user.index')
            ->with('user', $user);
    }

    public function index(Request $request) {
        //доступ всем кроме пользователей
        if (Auth::user()->role_id == 2)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        //фильтрация
        $statuses = Status::all();
        $skills = Skill::all();

        $filter = [];
        $fname = false;
        $fskills = false;
        $fstatus = false;
        $data = $request->all();
        if (isset($data['name']) && $data['name'] != '') {
            $filter['name'] = $data['name'];
            $fname = $filter['name'];
        }
        if (isset($data['status']) && $data['status']) {
            $filter['status'] = $data['status'];
            $fstatus = $filter['status'];
        }
        if (isset($data['skills']) && !empty($data['skills'])) {
            $filter['skills'] = $data['skills'];
            $fskills = $filter['skills'];
        }
        if (empty($filter)) {
            //$filter['status'] = Status::getDefaultStatus();
            $filter['status'] = 0;
        }
        //запрос
        $workers = DB::table('users')
            ->select('users.id', 'users.name', 'users.slug', 'users.name_en', 'users.email', 'users.created_at', 'roles.name as role', 'statuses.name as status', 'workers.skills')
            ->join('workers', 'users.id', '=', 'workers.user_id')
            ->join('statuses', 'workers.status_id', '=', 'statuses.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            //->where('workers.status_id', '=', $filter['status'])
            ->when($fstatus, function ($query, $fstatus) {
                return $query->where('workers.status_id', '=', $fstatus);
            })
            ->when($fname, function ($query, $fname) {
                return $query->where('users.name', 'like', "%" . $fname . "%")->orWhere('users.name_en', 'like', '%' . $fname . '%')->orWhere('users.email', 'like', '%' . $fname . '%');
            })
            ->when($fskills, function ($query, $fskills) {
                if (!empty($fskills)) {
                    foreach ($fskills as $fskill) {
                        //$query->whereIn($fskill, 'workers.skills');
                        $query->whereRaw('FIND_IN_SET("'.$fskill.'", workers.skills)');
                    }
                }
                //dd($query);
                return $query;
            })
            ->get();

        //dd($workers);

        return view('user.workers', compact('workers', 'statuses', 'skills', 'filter'));
    }

    public function show($slug)
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        if (is_numeric($slug))
            $worker = User::where('id', '=', $slug)->first();
        else
            $worker = User::where('slug', '=', $slug)->first();
        $skills = Skill::all();
        return view('user.show', compact('worker', 'skills'));
    }

    public function create() {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $worker = new Worker();
        $roles = Role::all();
        $statuses = Status::all();
        $sources = Source::all();
        $skills = Skill::all();
        $specializations = Specialization::all();
        $levels = Levels::all();
        $languages = Language::all();
        $languageLevels = LanguageLevel::all();
        return view(
            'user.create',
            compact('worker', 'roles', 'statuses', 'sources', 'skills', 'specializations', 'levels', 'languages', 'languageLevels')
        );
    }

    public function store(Request $request)
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'documents.*' => 'mimes:csv,txt,xlsx,xls,pdf,doc,docx'
        ]);

        //dd($request->role_id);

        $name = trim(implode(' ', [
                              trim($request->last_name),
                              trim($request->first_name),
                              trim($request->middle_name)
                             ]));

        $user = User::create([
            'name' => $name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            "role_id" => ($request->role_id) ? $request->role_id : 2,
            "slug" => User::generateUniqueCode()
        ]);

        $data = $request->all();
        $data['user_id'] = $user->id;

        if ($image = $request->file('photo')) {
            //$name = $image->getClientOriginalName();
            $path = $image->store('public/images');
            //$photo = $image->move(storage_path('images'), time().'_'.$image->getClientOriginalName());
            $data['photo'] = str_replace('public/', '', $path);
        }

        $worker = new Worker();
        if ($worker->saveWorker($data)) {

            if ($request->hasfile('documents')) {
                $documents = $request->file('documents');
                foreach($documents as $document) {
                    $name = $document->getClientOriginalName();
                    $path = $document->storeAs('uploads', $name, 'public');
                    $find = DB::table('documents')->where('user_id', $user->id)->where('name', $name)->first();
                    if (!$find) {
                        Document::create([
                            'name' => $name,
                            'url' => '/storage/' . $path,
                            'user_id' => $user->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }

            if (isset($data['experiences']) && !empty($data['experiences'])) {
                Experience::updates($data['experiences'], $user->id);
            }

            return redirect()
                ->route('user.index')
                ->with('success', __('site.add_user_success'));
        }

        return redirect()->route('user.index')->withErrors(__('site.add_user_error'));
    }

    public function destroy(User $user) {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $user->delete();
        return redirect()->route('user.index')
            ->with('success', __('site.delete_user_success'));
    }

    public function edit(User $user) {
        //доступ админам
        if (!$this->canModify($user) && !$this->isAdmin($user)) {
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        }
        $worker = $user;
        $roles = Role::all();
        $statuses = Status::all();
        $sources = Source::all();
        $skills = Skill::all();
        $specializations = Specialization::all();
        $levels = Levels::all();
        $languages = Language::all();
        $languageLevels = LanguageLevel::all();
        $selfEdit = $user->id == Auth::user()->id && $user->role_id == 2;
        $view = $selfEdit ? 'user.self-edit' : 'user.edit';
        return view($view, compact('worker', 'roles', 'statuses', 'sources', 'skills', 'specializations',
            'levels', 'languages', 'languageLevels', 'user'));
    }

    public function update(Request $request, User $user) {
        //доступ админам
        if (!$this->canModify($user) && !$this->isAdmin($user))
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $self = $this->canModify(Auth::user());
        $request->validate([
            'first_name' => 'string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255',
            'role_id' => $self ? 'nullable' : 'required',
            'status_id' => $self ? 'nullable' : 'required',
            'documents.*' => 'mimes:csv,txt,xlsx,xls,pdf,doc,docx'
        ]);
        $data = $request->all();
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            $data['password'] = $user->password;
        }

        $data['name'] = trim(implode(' ', [
                                 trim($data['last_name']),
                                 trim($data['first_name']),
                                 trim($data['middle_name'])
                                ]));
        if (!$user->slug)
            $user->slug = User::generateUniqueCode();
        $user->update($data);

        if ($image = $request->file('photo')) {
            //$name = $image->getClientOriginalName();
            $path = $image->store('public/images');
            //$photo = $image->move(storage_path('images'), time().'_'.$image->getClientOriginalName());
            $data['photo'] = str_replace('public/', '', $path);
        }

        $data['id'] = $user->worker->id;
        $data['user_id'] = $user->id;
        $worker = new Worker();
        if ($worker->updateWorker($data)) {

            if (isset($data['label']) && isset($data['value']) && !empty($data['label']) && !empty($data['value'])) {
                foreach ($data['label'] as $key => $field) {
                    $data = [
                        'id' => $key,
                        'worker_id' => $user->worker->id,
                        'label' => $field,
                        'value' => (isset($data['value'][$key]) && $data['value'][$key]) ? $data['value'][$key] : null
                    ];
                    $field = new Field();
                    $field->updateField($data);
                }
            }

            if ($request->hasfile('documents')) {
                $documents = $request->file('documents');
                //dd($documents);
                foreach($documents as $document) {
                    //dd($document);
                    $name = $document->getClientOriginalName();
                    $path = $document->storeAs('uploads', $name, 'public');
                    $find = DB::table('documents')->where('user_id', $user->id)->where('name', $name)->first();
                    if (!$find) {
                        Document::create([
                            'name' => $name,
                            'url' => '/storage/' . $path,
                            'user_id' => $user->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }

            if (isset($data['experiences']) && !empty($data['experiences'])) {
                Experience::updates($data['experiences'], $user->id);
            }

            return redirect()->route('user.edit', $user->id)
                ->with('success', __('site.update_user_success'));

        }

        return redirect()->route('user.index')->withErrors(__('site.update_user_error'));
    }

    public function createExperience(Request $request, $id) {
        //доступ админам
        $user = User::where('id', $id)->first();
        if (!$this->canModify($user) && !$this->isAdmin($user))
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $data = $request->all();
        $data['user_id'] = $id;
        $data['current'] = (isset($data['current']) && $data['current'] && $data['current'] == 'on') ? 1 : 0;
        $exp = Experience::create($data);
        return json_encode($exp);
    }

    public function deleteExperience($id) {
        //доступ админам
        $user = User::where('id', $id)->first();
        if (!$this->canModify($user) && !$this->isAdmin($user))
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        $exp = Experience::find($id)->delete();
        return json_encode($exp);
    }

    public function savePdf(Request $request) {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors(__('site.access_denied'));
        if ($pdf = $request->file('pdf')) {
            //$path = $pdf->store('public/pdf');
            $pdf = $pdf->move(storage_path('pdf'), time().'_'.md5($pdf->getClientOriginalName()) . '.pdf');

            $parser = new parsePdf();
            if ($member = $parser->parse($pdf)) {
                //try {
                    $user = User::create([
                        'name' => $member->name,
                        'last_name' => $member->last_name,
                        'first_name' => $member->first_name,
                        'middle_name' => $member->middle_name,
                        'email' => $member->email,
                        //'password' => Hash::make($request->password),
                        "role_id" => 2,
                        "slug" => User::generateUniqueCode()
                    ]);
                    $member->user_id = $user->id;
                    $worker = new Worker();
                    if ($worker->saveWorkerFromPdf($member)) {
                        return redirect()
                            ->route('user.index')
                            ->with('success', __('site.add_user_success'));
                    }
                //} catch (\Exception $e) {
                    //return redirect()->route('user.index')->withErrors(__('site.create_user_error') . ' ' . $e->getMessage());
                //}

                //dd($worker);
            }

            return redirect()->route('user.index')->withErrors(__('site.create_user_error'));

        }
        return redirect()->route('user.index')->withErrors(__('site.create_user_error'));
    }
}
