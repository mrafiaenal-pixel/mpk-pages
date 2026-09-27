<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Laravel\Mcp\Request;
use PhpParser\Node\Expr\FuncCall;

class DivisionController extends Controller
{
    public function store(Request $req)
    {
        $validate = $req->validate([
            'nama_sekbid' => 'required|max:100'
        ]);
        Division::create($validate);
    }
}
