<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function index(){

        $company = Company::all();
        return response()->json($company);
    }

    public function store(Request $request){

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $company = new Company();
        $company->user_id = $request->input('user_id');
        $company->company_name = $request->input('company_name');
        $company->contact_person = $request->input('contact_person');
        $company->phone = $request->input('phone');
        $company->address = $request->input('address');
        $company->save();

        return response()->json($company, 201);
    }

    public function show($id){

        $company = Company::find($id);
        if ($company){

            return response()->json($company);
        } else {
            return response()->json(['message' => 'Company not found'], 404);
        }
    }

    public function update(Request $request, $id){

        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'company_name' => 'sometimes|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $company = Company::find($id);
        if ($request->has('user_id')) {
            $company->user_id = $request->input('user_id');
        }
        if ($request->has('company_name')) {
            $company->company_name = $request->input('company_name');
        }
        if ($request->has('contact_person')) {
            $company->contact_person = $request->input('contact_person');
        }
        if ($request->has('phone')) {
            $company->phone = $request->input('phone');
        }
        if ($request->has('address')) {
            $company->address = $request->input('address');
        }
        $company->save();

        return response()->json($company);
    }

    public function destroy($id){

        $company = Company::find($id);
        $company->delete();
        return response()->json(null, 204);
    }
}