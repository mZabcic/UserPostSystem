<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFound;
use Auth;
use Illuminate\Support\Facades\Storage;

/**
 *
 * @OA\RequestBody(
 *     request="CreatePost",
 *     description="Object for creating new post",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/PostCreate")
 * )
 */
class PostController extends Controller
{
         /**
     * @OA\Post(
     *     path="/posts",
     *     tags={"Posts"},
     *     summary="Creats new post into system",
     *     operationId="create",
     *     @OA\RequestBody(ref="#/components/requestBodies/CreatePost"),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/Post"),
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

        $currentUser = Auth::user();
        

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = Post::create([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'user_id' => $currentUser->id
        ]);

        if ($request->exists('picture')) {
            $image = $request->file('picture');
            $name = $image->getClientOriginalName();
            $size = $image->getClientSize();
            $extension = $image->getClientOriginalExtension();
            $storage = Storage::put('post-images', $image);
            $imageInfo = array (
                "name" => $name,
                "size" => $size,
                "extension" => $extension,
                "path" => $storage
            );
            $post->picture = $imageInfo;
            $post->save();
        }
        
        $post = Post::with('user')->find($currentUser->id );

        return response()->json($post, 201);
    }
}
