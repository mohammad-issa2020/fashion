<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\adminRequest;  //
use App\Traits\GeneralTrait;
use Auth;
use Validator;
use Session;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usage;
use App\Models\Expert;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Table_user;
use App\Models\Fashion_news;

class ExpertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use GeneralTrait;
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
     * @param  \App\Models\Expert  $expert
     * @return \Illuminate\Http\Response
     */
    public function show(Expert $expert)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expert  $expert
     * @return \Illuminate\Http\Response
     */
    public function edit(Expert $expert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expert  $expert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expert $expert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expert  $expert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expert $expert)
    {
        //
    }


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
             $token = auth()->guard('expert-api')->attempt($credentials);
            if (!$token)
                return $this->returnError('E001', 'error in inputs');

            $expert = Auth::guard('expert-api')->user();
            $expert->api_token = $token;
            //return token
            return $this->returnData('expert', $expert);

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
            "name"=>"required",
            "date_of_birth"=>"required|date"
        ]);  
        if ($validator->fails()) {  
        return response()->json(['error'=>$validator->errors()], 401); 
        } 

        $expert = new Expert();
        $expert->email = $request->email;
        $expert->name = $request->name;
        $expert->date_of_birth = $request->date_of_birth;
        $expert->gender = $request->gender;
        $expert->password = bcrypt($request->password);
         //////////////////////attampt //////////////////////////
        //1.search in expert table
            $expertInCompanies =Company::where('email', '=', $expert->email)->first();
            $expertInUser = Table_user::where('email', '=', $expert->email)->first();
            $expertInExpert =Expert::where('email', '=', $expert->email)->first();
            $expertInAdmin = Admin::where('email', '=', $expert->email)->first();
            if($expertInCompanies==null && $expertInUser==null && $expertInExpert==null && $expertInAdmin==null){
                $expert->save();
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

    public function displayExpertProfile(Request $request){
        $credentials = $request->only(['id']);
        $token = auth()->guard('expert-api')->attempt($credentials);
        $expert = Auth::guard('expert-api')->user();
        return response()->json([
            "name"=>$expert->name,
            "gender"=>$expert->gender,
            "image"=>$expert->image
        ]); 
    }

    public function editExpertProfile(Request $request){
         
      
        $credentials = $request->only(['id']);
        $token = auth()->guard('expert-api')->attempt($credentials);
        $expert = Auth::guard('expert-api')->user();
        return response()->json([
            "name"=>$expert->name,
            "email"=>$expert->email,
            "password"=>$expert->password,
            "date_of_birth"=>$expert->date_of_birth,
            "gender"=>$expert->gender,
            "details"=>$expert->details,
            "image"=>$expert->image
        ]); 
    }

    public function updateExpertProfile(Request $request){
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
            'image' 
        
        ]);
        $token = auth()->guard('expert-api')->attempt($credentials);
        $expert = Auth::guard('expert-api')->user();
        $expert->name = $request['name'];
        $expert->email = $request['email'];
        if(!Hash::check($request['new_password'],  $expert->password)){
            $expert->password = Hash::make($request['new_password']);
        }
        $image_name = $this->saveImage($request['image'], 'images/expertImages');
        $expert->image = $image_name;
        $expert->date_of_birth = $request['date_of_birth'];
        $expert->details = $request['details'];
        $expert->gender = $expert->gender;
        //gender
        $expert->gender = $expert->gender;
        $expert->save();

        return response()->json([
            "status"=>true,
            "message"=>"updated successfully"
        ]);  
       

    }

    public function addFashionNews(Request $request){
        $validator = Validator::make($request->all(), 
        [ 
            "details" => "required|max:255",
            "title" => "required|max:50",
            "image" => "required",   
        ]);  
        if ($validator->fails()) {  
        return response()->json(['error'=>$validator->errors()], 401); 
        } 
        

        $credentials = $request->only([]);
        $token = auth()->guard('expert-api')->attempt($credentials);
        $expert = Auth::guard('expert-api')->user();

        //input the details
        $fashionNews = new Fashion_news();
        $fashionNews->title = $request->title;
        $fashionNews->details = $request->details;
        $fashionNews->image = $request->image;
        $fashionNews->type = "expert";
        $fashionNews->type_id = $expert->id;
        $fashionNews->save();

        return response()->json([
            "status"=>true,
            "message"=>"fashion news added successfully"
        ]);
    }
}
