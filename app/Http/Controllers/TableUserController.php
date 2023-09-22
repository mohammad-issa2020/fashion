<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Auth;
use Validator;
use Session;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Models\Table_user;
use App\Models\Usage;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Expert;
use App\Models\Question;

class TableUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use GeneralTrait;

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
             $token = auth()->guard('user-api')->attempt($credentials);
            if (!$token)
                return $this->returnError('E001','error in inputs');

            $user = Auth::guard('user-api')->user();
            $user->api_token = $token;
            //return token
            return $this->returnData('user', $user);

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
            "gender"=>"required",
            "name"=>"required"
        
        ]);  
        if ($validator->fails()) {  
        return response()->json(['error'=>$validator->errors()], 401); 
        } 

        $user = new Table_user();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->password = bcrypt($request->password);
         //////////////////////attampt //////////////////////////
        //1.search in user table
        $userInCompanies =Company::where('email', '=', $user->email)->first();
        $userInUser = Table_user::where('email', '=', $user->email)->first();
        $usertInExpert =Expert::where('email', '=', $user->email)->first();
        $userInAdmin = Admin::where('email', '=', $user->email)->first();
        if($userInCompanies==null && $userInUser==null && $usertInExpert==null && $userInAdmin==null){
            $user->save();
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
     * @param  \App\Models\Table_user  $table_user
     * @return \Illuminate\Http\Response
     */
    public function show(Table_user $table_user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table_user  $table_user
     * @return \Illuminate\Http\Response
     */
    public function edit(Table_user $table_user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table_user  $table_user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Table_user $table_user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table_user  $table_user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Table_user $table_user)
    {
        //
    }

    public function displayUserProfile(Request $request){
        $credentials = $request->only(['id']);
        $token = auth()->guard('user-api')->attempt($credentials);
        $user = Auth::guard('user-api')->user();
        return response()->json([
            "name"=>$user->name,
            "gender"=>$user->gender,
            "image"=>$user->image
        ]); 
    }

    public function editUserProfile(Request $request){
         
      
        $credentials = $request->only(['id']);
        $token = auth()->guard('user-api')->attempt($credentials);
        $user = Auth::guard('user-api')->user();
        return response()->json([
            "name"=>$user->name,
            "email"=>$user->email,
            "password"=>$user->password,
            "date_of_birth"=>$user->date_of_birth,
            "details"=>$user->details,
            "weight"=>$user->weight,
            "length"=>$user->length,
            "prefered_color"=>$user->prefered_color,
            "prefered_style"=>$user->prefered_style,
            "image"=>$user->image
        ]); 
    }

    public function updateUserProfile(Request $request){
        $validator = Validator::make($request->all(), 
        [ 
            "email" => "required|email|max:255",
            "password" => "required",
            "new_password" => "required|min:3|max:51",
            "name"=>"required",
            "image"=>"required"
        
        ]);  
        if ($validator->fails()) {  
        return response()->json(['error'=>$validator->errors()], 401); 
        }

        $credentials = $request->only([
            'name',
            'email',
            'password',
            'date_of_birth',
            'details',
            'weight',
            'length',
            'prefered_color',
            'prefered_style',
            'image' 
        
        ]);
        $token = auth()->guard('user-api')->attempt($credentials);
        $user = Auth::guard('user-api')->user();
        $user->name = $request['name'];
        $user->email = $request['email'];
        if(!Hash::check($request['new_password'],  $user->password)){
            $user->password = Hash::make($request['new_password']);
        }
        $user->date_of_birth = $request['date_of_birth'];
        $user->details = $request['details'];
        $user->weight = $request['weight'];
        $user->length = $request['length'];
        $user->prefered_color = $request['prefered_color'];
        $user->prefered_style = $request['prefered_style'];
        $image_name = $this->saveImage($request['image'], 'images/userImages');
        $user->image = $image_name;
        $user->save();
        return response()->json([
            "status"=>true,
            "message"=>"updated successfully"
        ]);  
       

    }

    public function addQuestion(Request $request){
        $validator = Validator::make($request->all(), 
        [ 
            "details" => "required|max:255"   
        ]);  
        if ($validator->fails()) {  
        return response()->json(['error'=>$validator->errors()], 401); 
        } 
        

        $credentials = $request->only(['details']);
        $token = auth()->guard('user-api')->attempt($credentials);
        $user = Auth::guard('user-api')->user();

        //input the details
        $question = new Question();
        $question->details = $request->details;
        $question->user_id = $user->id;
        $question->save();
        return response()->json([
            "status"=>true,
            "message"=>"question added successfully"
        ]);
    }
    
}
