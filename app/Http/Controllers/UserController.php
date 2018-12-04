<?php


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="RZNU LAB1",
 *      description="Računarstvo zasnovano na uslugama LAB1",
 *      @OA\Contact(
 *          email="mislav.zabcic@gmail.com"
 *      ),
 *     @OA\License(
 *         name="MIT license",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * ),
 
 * @OA\SecurityScheme(
 *     in="header",
 *     securityScheme="Authorization",
 *     name="Authorization",
 *     type="http",
*     scheme="bearer",
*      bearerFormat="JWT"
 * )
 * 
 */

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFound;
use Auth;



/**
 *
 * @OA\RequestBody(
 *     request="Login",
 *     description="Login object that needs to be authentificated",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/Login")
 * )
 */
/**
 *
 * @OA\RequestBody(
 *     request="Register",
 *     description="Register object for registration and creating new user",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/Register")
 * )
 */
/**
 *
 * @OA\RequestBody(
 *     request="Update",
 *     description="Object for updating user",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/UserUpdate")
 * )
 */

class UserController extends Controller
{

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Base"},
     *     summary="Logs user into system",
     *     operationId="authenticate",
     *     @OA\RequestBody(ref="#/components/requestBodies/Login"),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\Header(
     *             header="X-RateLimit-Limit",
     *             description="Rate limit for token",
     *             @OA\Schema(
     *                 type="Int64"
     *             )
     *         ),
     *         @OA\Header(
     *             header="X-RateLimit-Remaining",
     *             description="Rate limit remaining for token",
     *             @OA\Schema(
     *                 type="Int64"
     *             )
     *         ),
     *         @OA\JsonContent(ref="#/components/schemas/AuthResponse"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error with body parameters",
     *          @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     )
     * )
     */
    public function authenticate(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = User::where("email", "=", $request->get('email'))->firstOrFail();

        return response()->json(compact('user', 'token'));
    }

    /**
     * @OA\Get(
     *     path="/",
     *     tags={"Base"},
     *     summary="Get welcome message",
     *     description="Get welcome message",
     *     operationId="welcome",
     *     @OA\Response(
     *         response=200,
     *         description="Succes",
     *         @OA\JsonContent(ref="#/components/schemas/Message"),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     )
     * )
     */
    public function welcome() {
        return response()->json(["message" => "Thank you for using RZNU api for LAB1"]);
    }

    

     /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Base"},
     *     summary="Registers user into system",
     *     operationId="register",
     *     @OA\RequestBody(ref="#/components/requestBodies/Register"),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\Header(
     *             header="X-RateLimit-Limit",
     *             description="Rate limit for token",
     *             @OA\Schema(
     *                 type="Int64"
     *             )
     *         ),
     *         @OA\Header(
     *             header="X-RateLimit-Remaining",
     *             description="Rate limit remaining for token",
     *             @OA\Schema(
     *                 type="Int64"
     *             )
     *         ),
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error with body parameters",
     *          @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'date_of_birth' => 'required|date_format:Y-m-d|before:today',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'date_of_birth' => Carbon::parse($request->get('date_of_birth'))->format('Y-m-d'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);


        return response()->json(compact('user', 'token'), 201);
    }


      /**
     * @OA\Post(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Creats new user into system",
     *     operationId="create",
     *     @OA\RequestBody(ref="#/components/requestBodies/Register"),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\Header(
     *             header="X-RateLimit-Limit",
     *             description="Rate limit for token",
     *             @OA\Schema(
     *                 type="Int64"
     *             )
     *         ),
     *         @OA\Header(
     *             header="X-RateLimit-Remaining",
     *             description="Rate limit remaining for token",
     *             @OA\Schema(
     *                 type="Int64"
     *             )
     *         ),
     *         @OA\JsonContent(ref="#/components/schemas/AuthResponse"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error with body parameters",
     *          @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *      security={
     *         {"Authorization": {}}
     *     }
     * )
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'date_of_birth' => 'required|date_format:Y-m-d|before:today',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'date_of_birth' => Carbon::parse($request->get('date_of_birth'))->format('Y-m-d'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);


        return response()->json(compact('user', 'token'), 201);
    }




    /**
     * @OA\Get(
     *     path="/users/me",
     *     tags={"Users"},
     *     summary="Get current user",
     *     description="Returns current user",
     *     operationId="getAuthenticatedUser",
     *     @OA\Response(
     *         response=200,
     *         description="Succes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *     security={
     *         {"Authorization": {}}
     *     }
     * )
     */
    public function getAuthenticatedUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['error' => 'token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['error' => 'token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['error' => 'token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }



     /**
     * @OA\Get(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Get all users",
     *     description="Returns array of all registered users",
     *     operationId="getAllUsers",
     *     @OA\Response(
     *         response=200,
     *         description="Succes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *     security={
     *         {"Authorization": {}}
     *     }
     * )
     */
    public function getAll() {
        $users = User::all();
        return response()->json($users, 200);
    }



/**
     * @OA\Get(
     *     path="/users/{userId}",
     *     tags={"Users"},
     *     summary="Get user by id",
     *     description="Returns a user by his ID",
     *     operationId="getUserById",
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID of a user to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succes",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *     security={
     *         {"Authorization": {}}
     *     }
     * )
     */
    public function getById($id) {
        try {
            $user = User::where('id', '=', $id)->firstOrFail();
        } catch (NotFound $e) {
            return response()->json(['error' => 'No user found'], 404);
        }
        return response()->json($user);
    }

    /**
     * @OA\Delete(
     *     path="/users/{userId}",
     *     tags={"Users"},
     *     summary="Delete user by id",
     *     description="Deletes a user by his ID",
     *     operationId="deleteUserById",
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID of a user to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *      @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *     security={
     *         {"Authorization": {}}
     *     }
     * )
     */
    public function delete($id) {
        $currentUser = Auth::user();
        if ($currentUser['id'] != $id) {
            return response()->json(['error' => 'You can only delete your profile'], 403);
        }


        try {
            $user = User::where('id', '=', $id)->firstOrFail();
        } catch (NotFound $e) {
            return response()->json(['error' => 'No user found'], 404);
        }

        $user->delete();
        
        return response()->json([], 204);
    }




        /**
     * @OA\Put(
     *     path="/users/{userId}",
     *     tags={"Users"},
     *     summary="Updates user into system",
     *     operationId="update",
     *     @OA\RequestBody(ref="#/components/requestBodies/Update"),
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID of a user to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *  @OA\Parameter(
     *         name="Content-type",
     *         in="header",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             default="application/x-www-form-urlencoded"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error with body parameters",
     *          @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *      @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/Error"),
     *     ),
     *      security={
     *         {"Authorization": {}}
     *     }
     * )
     */
    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();
        if ($currentUser['id'] != $id) {
            return response()->json(['error' => 'You can only update your profile'], 403);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'string|min:6',
            'date_of_birth' => 'date_format:Y-m-d|before:today',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::find($id);
        

        $user->update($request->all());

        return response()->json($user, 200);
    }


}
