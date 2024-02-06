<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MainController extends Controller
{

    public function list() {

        $userInfo = DB::table('users')
            ->join('country', 'users.country_id', '=', 'country.id')
            ->orderBy('users.id')
            ->get(['users.id',
                'users.name',
                'users.phone',
                'country.phone_code']);

        foreach($userInfo as $key => $user) {
            $docInfo = DB::table('documents')
                ->join('doctype', 'documents.doctype_id', '=', 'doctype.id')
                ->where('documents.user_id', '=', $user->id)
                ->get(['doctype.docname',
                    'documents.field1',
                    'documents.field2',
                    'documents.field3'
                ]);
            $userInfo[$key]->documents = json_decode($docInfo, true);
        }
        return view('welcome', ['data' => $userInfo]);

    }
}
