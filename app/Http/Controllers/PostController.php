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
use Illuminate\Support\Facades\Response;

/**
 *
 * @OA\RequestBody(
 *     request="CreatePost",
 *     description="Object for creating new post",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/PostCreate")
 * )
 */
/**
 *
 * @OA\RequestBody(
 *     request="UpdatePost",
 *     description="Object for updating post",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/PostUpdate")
 * )
 */
/**
 *
 * @OA\RequestBody(
 *     request="UpdatePostImage",
 *     description="Object for updating post image",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/PostUpdateImage")
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
            $storage = Storage::put('public/post-images', $image);
            $imageInfo = array (
                "name" => $name,
                "size" => $size,
                "extension" => $extension,
                "path" => $storage
            );
            $post->picture = $imageInfo;
            $post->save();
        }
        
        $post = Post::with('author')->find($post->id);

        return response()->json($post, 201);
    }


         /**
     * @OA\Get(
     *     path="/posts",
     *     tags={"Posts"},
     *     summary="Get all posts",
     *     description="Returns array of all posts",
     *     operationId="getAllPosts",
     *     @OA\Response(
     *         response=200,
     *         description="Succes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Post")
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
        $posts = Post::with('author')->get();
        return response()->json($posts, 200);
    }

   /**
     * @OA\Get(
     *     path="/posts/{postId}",
     *     tags={"Posts"},
     *     summary="Get post by id",
     *     description="Returns a post by his ID",
     *     operationId="getPostById",
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID of a post to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succes",
     *         @OA\JsonContent(ref="#/components/schemas/Post"),
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
            $post = Post::where('id', '=', $id)->with('author')->firstOrFail();
        } catch (NotFound $e) {
            return response()->json(['error' => 'No post found'], 404);
        }
        return response()->json($post);
    }
    


    /**
     * @OA\Get(
     *     path="/posts/{postId}/image",
     *     tags={"Posts"},
     *     summary="Get post image by id",
     *     description="Returns a image of a post by his ID",
     *     operationId="getPostImageById",
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID of a post image to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succes",
     *         @OA\MediaType(
 *                  mediaType="image/*"
 *              )
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
    public function getImageById($id) {
        try {
            $post = Post::where('id', '=', $id)->with('author')->firstOrFail();
        } catch (NotFound $e) {
            return response()->json(['error' => 'No post found'], 404);
        }
        $imageInfo = $post->picture;
        if (empty($imageInfo)) {
            return response()->json(['error' => 'No image found'], 404);
        }
        return Storage::download($imageInfo['path'], $imageInfo['name']);      
    }


    /**
     * @OA\Delete(
     *     path="/posts/{postId}",
     *     tags={"Posts"},
     *     summary="Delete post by id",
     *     description="Deletes a post by his ID",
     *     operationId="deletePostById",
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID of a post to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content"
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

        try {
            $post = Post::where('id', '=', $id)->firstOrFail();
        } catch (NotFound $e) {
            return response()->json(['error' => 'No post found'], 404);
        }
        if ($currentUser['id'] != $post->user_id) {
            return response()->json(['error' => 'You can only delete your posts'], 403);
        }

        if (!empty($post['picture'])) {
            $imageInfo = $post['picture'];
            Storage::delete($imageInfo['path']);
        }

        $post->delete();
        
        return response()->json([], 204);
    }


      /**
     * @OA\Put(
     *     path="/posts/{postId}",
     *     tags={"Posts"},
     *     summary="Updates post into system",
     *     operationId="updatePost",
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdatePost"),
     *     @OA\Parameter(
     *         name="post_id",
     *         in="path",
     *         description="ID of a post to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *  @OA\Parameter(
     *         name="Content-type",
     *         in="header",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="application/x-www-form-urlencoded"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
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

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'content' => 'string|max:10000'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $post = Post::where('id', '=', $id)->firstOrFail();
        } catch (NotFound $e) {
            return response()->json(['error' => 'No post found'], 404);
        }
        if ($currentUser['id'] != $post->user_id) {
            return response()->json(['error' => 'You can only update your posts'], 403);
        }

        $post->update($request->only(['title', 'content']));

        $post = Post::with('author')->find($post->id);

        return response()->json($post, 200);
    }


       /**
     * @OA\Post(
     *     path="/posts/{postId}/image",
     *     tags={"Posts"},
     *     summary="Updates post image in system",
     *     operationId="updatePostImage",
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdatePostImage"),
     *     @OA\Parameter(
     *         name="post_id",
     *         in="path",
     *         description="ID of a post for image to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
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
    public function updateImage(Request $request, $id)
    {
    
        $currentUser = Auth::user();

        $validator = Validator::make($request->all(), [
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $post = Post::where('id', '=', $id)->firstOrFail();
        } catch (NotFound $e) {
            return response()->json(['error' => 'No post found'], 404);
        }
        if ($currentUser['id'] != $post->user_id) {
            return response()->json(['error' => 'You can only update your posts'], 403);
        }

        //Delete current
        if (!empty($post['picture'])) {
            $imageInfo = $post['picture'];
            Storage::delete($imageInfo['path']);
        }

        $image = $request->file('picture');
        $name = $image->getClientOriginalName();
        $size = $image->getClientSize();
        $extension = $image->getClientOriginalExtension();
        $storage = Storage::put('public/post-images', $image);
        $imageInfo = array (
            "name" => $name,
            "size" => $size,
            "extension" => $extension,
            "path" => $storage
        );
        $post->picture = $imageInfo;
        $post->save();

        $post = Post::with('author')->find($post->id);

        return response()->json($post, 200);
    }


     
}
