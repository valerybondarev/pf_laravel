<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FieldController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                ->withErrors('Не достаточно прав для добавления полей');
        //сохраняем новое поле для сотрудника
        $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string',
            'user_id' => 'required|integer',
            'worker_id' => 'required|integer'
        ]);

        Field::create([
            'label' => $request->label,
            'value' => $request->value,
            'worker_id' => $request->worker_id,
        ]);

        return redirect()
            ->route('user.edit', $request->user_id)
            ->with('success','Поле для Сотрудника успешно добавлено');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Field $field)
    {
        //доступ админам
        if (Auth::user()->role_id !== 1)
            return redirect()
                ->route('welcome')
                ->withErrors('Не достаточно прав для удаления полей');
        return $field->delete();
    }
}
