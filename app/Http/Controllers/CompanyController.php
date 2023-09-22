<?php

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
use App\Models\Company;
use App\Models\Table_user;
use App\Models\Expert;
use App\Models\Admin;
use App\Models\Usage;
use App\Models\Fashion_news;
use App\Models\Season;
use App\Models\Sub_category;
use App\Models\Piece;
use App\Models\Color;
use App\Models\Size;
use App\Models\Master_category;
use App\Models\Piece_details;






class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use GeneralTrait;
    public function index()
    {
    //    $pieces = Piece::with(['usage'=>function($query){
    //     $query->select('name', 'id');
    // }])->get();
    //     return view('show_piece', compact('pieces'));

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
        return view('add_piece');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $piece = Piece::create([
            'name' =>  $request->name
          ]);
        return redirect()->route('show.piece');
        //return redirect()->back()->with('message' , 'added' ) ;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
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
             $token = auth()->guard('company-api')->attempt($credentials);
            if (!$token)
                return $this->returnError('E001', 'error in inputs');
            $company = Auth::guard('company-api')->user();
            $company->api_token = $token;
            //return token
            return $this->returnData('company', $company);

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
            "name"=>"required",
            "location"=>"required",
            "date_of_establishment"=>"required|date",
            "major_category"=>"required"
        ]);  
        if ($validator->fails()) {  
        return response()->json(['error'=>$validator->errors()], 401); 
        } 
        
        $company = new Company();
        $company->email = $request->email;
        $company->name = $request->name;
        $company->password = bcrypt($request->password);
        $company->date_of_establishment = $request->date_of_establishment;
        $company->location = $request->location;
        $company->major_category = $request->major_category;
        //////////////////////attampt //////////////////////////
        //1.search in company table
        $companyInCompanies =Company::where('email', '=', $company->email)->first();
        $companyInUser = Table_user::where('email', '=', $company->email)->first();
        $comanyInExpert =Expert::where('email', '=', $company->email)->first();
        $companyInAdmin = Admin::where('email', '=', $company->email)->first();
        if($companyInCompanies==null && $companyInUser==null && $comanyInExpert==null && $companyInAdmin==null){
                //if not exist in any table then add it to company table
                $company->save();
                //if ($this->token) {
                return $this->login($request);
                return response()->json([
                'success' => true,
                'data' => $user
                ], Response::HTTP_OK);
        }
        else
            return  $this -> returnError('','something error');
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

    public function displayCompanyProfile(Request $request){
        $credentials = $request->only(['id']);
        $token = auth()->guard('company-api')->attempt($credentials);
        $company = Auth::guard('company-api')->user();
        return response()->json([
            "name"=>$company->name,
            "location"=>$company->location,
            "image"=>$company->image,
            "major_category"=>$company->major_category, 
        ]); 
    }

    public function editCompanyProfile(Request $request){
         
      
        $credentials = $request->only(['id']);
        $token = auth()->guard('company-api')->attempt($credentials);
        $company = Auth::guard('company-api')->user();
        return response()->json([
            "name"=>$company->name,
            "email"=>$company->email,
            "password"=>$company->password,
            "location"=>$company->location,
            "major_category"=>$company->major_category,
            "date_of_establishment"=>$company->date_of_establishment,
            "image"=>$company->image, 
            "details"=>$company->details
        ]); 
    }

    public function updateCompanyProfile(Request $request){
        $validator = Validator::make($request->all(), 
        [ 
            "email" => "required|email|max:255",
            "password" => "required",
            "new_password" => "required|min:3|max:51",
            "name"=>"required",
        
        ]);  
        if ($validator->fails()) {  
        return response()->json(['error'=>$validator->errors()], 401); 
        }

        $credentials = $request->only([
            'name',
            'email',
            'password',
            'location',
            'major_category',
            'date_of_establishment',
            'image',
            'details' 
        
        ]);
        $token = auth()->guard('company-api')->attempt($credentials);
        $company = Auth::guard('company-api')->user();
        $company->name = $request['name'];
        $company->email = $request['email'];
        $company->details = $request['details'];
        if(!Hash::check($request['new_password'],  $company->password)){
            $company->password = Hash::make($request['new_password']);
        }
        $company->location = $request['location'];
        $company->major_category = $request['major_category'];
        $company->date_of_establishment = $request['date_of_establishment'];
        $company->image = $request['image'];
        $company->save();

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
        ]);  
        if ($validator->fails()) {  
        return response()->json(['error'=>$validator->errors()], 401); 
        } 
        

        $credentials = $request->only([]);
        $token = auth()->guard('company-api')->attempt($credentials);
        $company = Auth::guard('company-api')->user();

        //input the details
        $fashionNews = new Fashion_news();
        $fashionNews->title = $request->title;
        $fashionNews->details = $request->details;
        $fashionNews->type = "company";
        $fashionNews->type_id = $company->id;
        $fashionNews->save();

        return response()->json([
            "status"=>true,
            "message"=>"fashion news added successfully"
        ]);
    }

    public function getUsage(){
        $usages = Usage::get();
        return response()->json($usages);
    }

    public function getSeasons(){
        $seasons = Season::get();
        return response()->json($seasons);
    }

    public function getSubCategories(){
        $subCategories = Sub_category::get();
        return response()->json($subCategories);
    }

    public function getMasterCategories(){
        $masterCategories = Master_category::get();
        return response()->json($masterCategories);
    }

    public function getColor(){
        $colors = Color::get();
        return response()->json($colors);
    }

    public function getSize(){
        $sizes = Size::get();
        return response()->json($sizes);
    }

    public function addPiece(Request $request){
        $validator = Validator::make($request->all(), 
        [ 
            "name" => "required:max:50",
            "originalImage"=>"required",
                
        ]);  
        if ($validator->fails()) {  
        return response()->json(['error'=>$validator->errors()], 401); 
        }

        $credentials = $request->only([
             
        ]);
        $token = auth()->guard('company-api')->attempt($credentials);
        $company = Auth::guard('company-api')->user();
        $piece = new Piece();
        $piece->name = $request->name;
        $piece->price = $request->price;
        $piece->type = "company";
        $piece->type_id = $company->id;
        $piece->sub_category_id =  $request->sub_category_id;
        $piece->master_category_id =  $request->master_category_id;
        $piece->season_id =$request->season_id;
        $piece->usage_id = $request->usage_id;
        $image_name = $this->saveImage($request['originalImage'], 'images/piecesImages');
        $piece->image = $image_name;
        $piece->save();

        //add details
        $pieceDetails = new Piece_details();
        $pieceDetails->color_id =  $request->color_id;
        $pieceDetails->size_id =  $request->size_id;
        if($request['coloredImage']!=null){
            $image_name = $this->saveImage($request['coloredImage'], 'images/piecesImages');
            $pieceDetails->image = $image_name;
        }
        
        $pieceDetails->pieces()->associate($piece);
        $pieceDetails->save();

        return response()->json([
            "status"=>true,
            "message"=>"piece added successfully"
        ]);
    }

    public function displayPiece(Request $request){
        $credentials = $request->only([]);
        $token = auth()->guard('company-api')->attempt($credentials);
        $company = Auth::guard('company-api')->user();
        // $pieces = Piece::select(['name', 'price', 'image'])->where([
        //     ['type_id', '=', $company->id],
        //     ['type', '=', 'company']
        //     ])->get();
        // $piecesDetail = Piece_details()::select()
        // $s = Piece_details::where()
        // select('color_id', 'size_id', 'image')
        //     ->with('pieces')->get();
        // return response()->json($pieces);

        // return response()->json([
        //  "name"=>$s[0]->name,
            // "price"=>$pieces->price,
        //    // "originalImage"=>$pieces->image,
        //     "season_id"=>$pieces->season_id,
        //     "sub_category_id"=>$pieces->sub_category_id,
        //     "master_category_id"=>$pieces->master_category_id,
        //     "num_liked"=>$pieces->num_liked,
        //     "usage_id"=>$pieces->usage_id,
            // "color_id"=>$s[0]->color_id,
        //     "size_id"=>$pieces->size_id,
        //     "coloredImage"=>$pieces->piece_details->image
             
      //  ]); 

      $pieces = Piece::where([['type', 'company'], ['type_id',$company->id]])->select(

        "pieces.name",
        "pieces.usage_id",
        "pieces.season_id",
        "pieces.sub_category_id",
        "pieces.master_category_id",
        "pieces.usage_id",
        "pieces.price",
        "piece_details.color_id",
        "piece_details.size_id",

    
    )

    ->join("piece_details", "piece_details.pieces_id", "=", "pieces.id")

    ->get();



    return response()->json($pieces);
    }




}
