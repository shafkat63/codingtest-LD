<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('dashboard');
    }

    public function getUsers(Request $request)
    {
         if ($request->ajax()) {
        $users = User::select(['id', 'name', 'email', 'created_at']);

        return DataTables::of($users)
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-primary editUser" data-id="' . $row->id . '" data-name="' . $row->name . '" data-email="' . $row->email . '">Edit</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
         }
    }

    public function updateUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully.'
        ]);
    }
}
