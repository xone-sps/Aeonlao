<?php

namespace App\Http\Controllers\Auth;

use App\Models\InstituteCategory;
use App\Models\InstituteParentCategory;
use App\Models\UserProfile;
use App\Traits\UserRoleTrait;
use Illuminate\Http\Response;
use App\Jobs\SendNewUserRegistration;
use App\User;
use App\Http\Controllers\Controller;
use App\Models\UserType;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, UserRoleTrait;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    public function registerCheckerFiledInspector(Request $request)
    {
        if (!$type = $this->getValidateUserType($request->get('type'))) {
            return response()->json(['errors' => ['type' => ['Your entered type is not exists in our system.']]], 422);
        }
        $request->request->add(['type' => $type]);
        return $this->register($request);
    }

    public function registerInstitute(Request $request)
    {
        $request->request->add(['type' => 'institute']);
        $request->request->add(['first_name' => $request->get('institute_name')]);
        $request->request->add(['last_name' => $request->get('short_name')]);
        if (!$type = $this->getValidateUserType($request->get('type'))) {
            return response()->json(['errors' => ['type' => ['Your entered type is not exists in our system.']]], 422);
        }
        Validator::make($request->all(), [
            'institute_category' => ['required'],
            'institute_category.id' => ['required'],
            'institute_name' => ['required', 'max:191'],
            'short_name' => ['required', 'max:80']
        ])->validate();
        $item = InstituteCategory::find($request->input('institute_category.id'));
        if (!isset($item)) {
            return response()->json(['errors' => ['institute_category' => ['Your entered institute category is not exists in our system.']]], 422);
        }

        $checking_name = UserProfile::where('institute_name', $request->get('institute_name'))->first();
        if (isset($checking_name)) {
            return response()->json(['errors' => ['institute_name' => ['Your entered institute name already exists in our system.']]], 422);
        }
        $checking_short_name = UserProfile::where('short_institute_name', $request->get('short_name'))->first();
        if (isset($checking_short_name)) {
            return response()->json(['errors' => ['short_name' => ['Your entered short institute name already exists in our system.']]], 422);
        }

        if ($item->have_parent === 'yes') {
            $parent_item = InstituteParentCategory::where('child', $item->id)->where('parent_id', $request->input('parent_institute_category.id'));
            if (!isset($parent_item)) {
                return response()->json(['errors' => ['parent_institute_category' => ['Your entered parent institute category is not exists in our system.']]], 422);
            }
        }
        $request->request->add(['type' => $type]);
        return $this->register($request);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response | mixed
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        return ($response = $this->registered($request, $user))
            ? $response : redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if (auth() !== null && auth()->user() !== null && User::isAdminUser(auth()->user())) {//check if admin register
            $user->setStatus('approved');
        } else {
            $this->dispatch(new SendNewUserRegistration($user));
        }
        // add user type for first register
        $userType = new UserType(['type_user_id' => $request->get('type')]);
        $user->userType()->save($userType);
        //add user profile for institute
        if ($request->get('type') === $this->getTypeUserId('institute')) {
            $userProfile = new UserProfile([
                'institute_category_id' => $request->input('institute_category.id'),
                'parent_institute_category_id' => $request->input('parent_institute_category.id'),
                'institute_name' => $request->get('institute_name'),
                'short_institute_name' => $request->get('short_name'),
            ]);
            $user->userProfile()->save($userProfile);
        }
        //add user profile for institute
        if ($request->ajax()) {
            return [
                'success' => true,
                'user' => $user->transformUser()
            ];
        }
        return false;
    }

    protected function user_type_wrapper($title)
    {
        return str_replace('-', '_', $title);
    }

    protected function getValidateUserType($type)
    {
        $userTypeId = $this->getTypeUserId($this->user_type_wrapper($type));
        return $userTypeId ?? null;
    }
}
