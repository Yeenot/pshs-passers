<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Passers;

class PassersController extends Controller
{
    public function index(Request $request) {
        $page = isset($request->page) ? (int) $request->page : 1;
        $limit = isset($request->limit) ? (int) $request->limit : 50;
        $search = isset($request->search) ? $request->search : '';

        $offset = ($page * $limit) - $limit;
        $passers = Passers::orderBy('surname', 'ASC')
            ->orderBy('firstname', 'ASC');
            
        if ($search != '') {
            $passers->whereRaw("MATCH(surname, firstname, school, division) AGAINST(? IN NATURAL LANGUAGE MODE)", 
                array($search))
                ->orWhere('surname', 'LIKE', '%'.$search.'%')
                ->orWhere('firstname', 'LIKE', '%'.$search.'%')
                ->orWhere('school', 'LIKE', '%'.$search.'%')
                ->orWhere('division', 'LIKE', '%'.$search.'%');
        }

        $total = $passers->count();
        $list = $passers->offset($offset)->limit($limit)->get();
        $data = [
            'list' => $list,
            'page' => $page,
            'limit' => $limit,
            'total' => $total
        ];

        return response()->json($data);
    }

    public function add(Request $request) {
        $data = $request->all();
        foreach ($data as $key => &$value) {
           $value = strtoupper($value);
        }
        Passers::create($data);
        return response()->json($data);
    }

    public function schools() {
        $data = Passers::select('school', DB::raw('count(*) as count'))
            ->groupBy('school')
            ->orderBy('count', 'DESC')
            ->get();
        $data->makeHidden('fullname');
        return response()->json($data);
    }
}
