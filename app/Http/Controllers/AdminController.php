<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\adminRequest;
use App\Traits\GeneralTrait;
use Auth;
use Validator;
use Session;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Usage;
use App\Models\Company;
use App\Models\Table_user;
use App\Models\Expert;

class AdminController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     
    public function login(Request $request)
    {

        try {
            $rules = [
                "email" => "required|email|max:255",
                "password" => "required|min:3|max:51"

            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($validator, $code);
            }

            // //login
                 
             $credentials = $request->only(['email', 'password']);
             $token = auth()->guard('admin-api')->attempt($credentials);
            if (!$token)
                return $this->returnError('E001','error in inputs');

            $admin = Auth::guard('admin-api')->user();
            $admin->api_token = $token;
            //return token
            return $this->returnData('admin', $admin);

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }

    public function register(Request $request)
    { 
        $validator = Validator::make($request->all(), 
        [ 
            "email" => "required|email|max:255",
            "password" => "required|min:3|max:51",
        ]);  
        if ($validator->fails()) {  
        return response()->json(['error'=>$validator->errors()], 401); 
        } 

        $admin = new Admin();
        $admin->email = $request->email;
        $admin->password = bcrypt($request->password);

         //////////////////////attampt //////////////////////////
        //1.search in admin table
        $adminInCompanies =Company::where('email', '=', $admin->email)->first();
        $adminInUser = Table_user::where('email', '=', $admin->email)->first();
        $adminInExpert =Expert::where('email', '=', $admin->email)->first();
        $adminInAdmin = Admin::where('email', '=', $admin->email)->first();
        if($adminInCompanies==null && $adminInUser==null && $adminInExpert==null && $adminInAdmin==null){
        $admin->save();
        return $this->login($request);
        return response()->json([
        'success' => true,
        'data' => $user
        ], Response::HTTP_OK);
     }
        else
            return  $this -> returnError('','some thing went error');
        ////////////////////end attempt ///////////////////////////
        
    }

    public function logout(Request $request)
    {
         $token = $request -> header('auth-token');
        if($token){
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return  $this -> returnError('','some thing went wrongs');
            }
            return $this->returnSuccessMessage('Logged out successfully');
        }else{
            $this -> returnError('','some thing went wrongs');
        }

    }
    
    public function index()
    {
        $categories = Usage::get();
        return response()->json($categories);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }

}
