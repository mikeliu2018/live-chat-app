<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        // $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return [            
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json([
                'status' => true,
                'message' => __('Unauthorized')
            ], 401);
        }

        return response()->json(array_merge([
            'status' => true,
            'message' => __('Operation complete'),
            ], 
            $this->createNewToken($token),
            ['userProfile' => auth()->user()]
        ));
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => __('Registration failed'),
                'validator' => $validator->errors()
            ]                
            , 400);
        }

        $userProfile = User::create(array_merge(
            ['id' => Uuid::uuid()],
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'status' => true,
            'message' => __('User successfully registered'),
            'userProfile' => $userProfile
        ], 201);
    }    


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth('api')->logout();
        return response()->json([
            'status' => true,
            'message' => __('User successfully signed out')
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {        
        return response()->json(array_merge([
                'status' => true,
                'message' => __('Operation complete')            
            ],
            $this->createNewToken(auth()->refresh())
        ));
        
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        $userProfile = auth()->user();
        if ($userProfile !== NULL)
            return response()->json([
                'status' => true,
                'message' => __('Operation complete'),
                'userProfile' => $userProfile
            ]);        
    }    

    /**
     * Register or login a User from vendor (google/facebook/line/apple)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function vendorSignin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|max:2048',
            'vendor' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => __('Parameter error'),
                'validator' => $validator->errors()
            ]                
            , 400);
        }
        
        $auth_data = [];

        switch ($request->vendor)
        {
            case 'google':
                $resp = Http::get('https://oauth2.googleapis.com/tokeninfo', [
                    'id_token' => $request->token
                ]);
                if ($resp->ok())
                {
                    $json = $resp->json();
                    if ((isset($json['sub']) && isset($json['exp']) && $json['exp'] > time()))
                    {                        
                        $auth_data['vendor'] = $request->vendor;
                        $auth_data['vendor_uid'] = $json['sub'];
                        $auth_data['vendor_email'] = $json['email'];
                        $auth_data['vendor_name'] = $json['name'];
                        $auth_data['vendor_image_url'] = $json['picture'];
                    }                    
                }
                break;
            default:                
        }

        if (empty($auth_data))
        {
            return response()->json([
                'status' => false,
                'message' => __('Unsupported third-party login')
            ]);
        }
        else
        {
            $userProfile = User::where('vendor', $auth_data['vendor'])->where('vendor_uid', $auth_data['vendor_uid'])->first();
            
            if ($userProfile === NULL)
            {
                $userProfile = array_merge([
                    'id' => Uuid::uuid(),
                    'name' => $auth_data['vendor_name'],
                    'email' => "{$auth_data['vendor_email']}",
                    'password' => bcrypt(uniqid())
                ], $auth_data);
                
                User::create($userProfile);
            }                    
            
            $token = auth()->tokenById($userProfile['id']);
                
            return response()->json(array_merge([
                    'status' => true,
                    'message' => __('Operation complete'),                
                ],
                $this->createNewToken($token),
                ['userProfile' => $userProfile]
            ));
        }
    }

}