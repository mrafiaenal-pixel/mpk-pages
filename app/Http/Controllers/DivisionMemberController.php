<?php

namespace App\Http\Controllers;

use Laravel\Mcp\Request;

class DivisionMemberController extends Controller
{
    public function store(Request $request) {
        $validate = $request->validate([
            '' => 'required',
        ]);
    }
}
