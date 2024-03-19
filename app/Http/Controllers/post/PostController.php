<?php

namespace App\Http\Controllers\post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use GeneralTrait ;
    
    public function index(){

        $posts = Post::all();
        return $this->returnData('posts',$posts );
    }


    public function create(Request $request){
        
        
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'content' => 'required',
        ]);
        if($validator->fails()){
            return \response()->json($validator->errors(), 422);
        }
       $post = Post::create([
            'title' =>$request->title,
            'content' =>$request->content,
            'user_id' => auth()->user()->id
        ]);
        if(!$post){
            return $this->returnError(404 , 'some thing went wrong');
        }
        return $this->returnSuccessMessage('post created successfuly');


    }

    
    public function update(Request $request ,$id){
        
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'content' => 'required',
        ]);
        if($validator->fails()){
            return \response()->json($validator->errors(), 422);
        }
       $post = Post::find($id);
        if(!$post){
            return $this->returnError(404 , 'some thing went wrong');
        }

        $post->update([
            'title ,'=> $request->title ,
            'content ,'=> $request->content 
        ]);

        return $this->returnSuccessMessage('post updated successfuly');

    }
    

   

    public function delete($id){
        
        $post = Post::find($id);

        if(!$post){
            return $this->returnError(404,'some thing went wrong');
        }

        $post->delete() ;

        return $this->returnSuccessMessage('post deleted successfuly');

    }
}
