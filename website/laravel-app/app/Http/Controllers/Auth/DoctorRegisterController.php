<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Traits\UserProfileManager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DoctorRegisterController extends Controller
{
    use UserProfileManager;

    protected $redirectTo = '/doctor/login';

    public function index()
    {
        return view('auth.doctorRegister');
    }

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
        ], [
            'firstname.required' => 'Please provide a firstname',
            'lastname.required' => 'Please provide a lastname',
            'email.required' => 'Please provide an email',
            'password.required' => 'Please provide a password',
            'password_confirmation.required' => 'Please provide the same password',
            'password.min' => 'Please provide a valid password. Contain minimum of 8 characters consisting of a symbol and digit',
            'password.confirmed' => 'Verify password and password are not the same',
            'email.email' => 'Please provide a valid email',
        ]);
    }

    protected function create(array $data)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $doctor = Doctor::create([
                'user_id' => $user->id,
            ]);

            $this->createProfile($user);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Fout bij registratie: " . $e->getMessage());
            throw $e;
        }
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        return redirect($this->redirectTo);
    }
}
