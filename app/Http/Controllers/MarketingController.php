<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MarketingController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return view('marketing.index', [
            'marketing' => User::where('is_admin', 0)->filters(request(['search']))->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('marketing.create', [
            'marketing' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request) {
        // dd($request);
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $validated['image'] = $request->file('image')->store('users');

            $validated['password'] = Hash::make($validated['password']);

            User::create($validated);
            DB::commit();

            return redirect(route('marketing.index'))->with('success', "New User Added");
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th
            return back()->with('error', '');
        }
    }

    /**
     * Display the specified resource.
     */
}