<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\User;
use Validator;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;

class UserController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
     
     public function all(Request $data)
    {
        $users = User::get(['id','name','email']);
        if(!$users->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','users'=>$users]);
        }
        return response()->json(['error'=>true,'message'=>'no hay usuarios registrados.','users'=>null]);
    } 
    public function show($id)
    {
        $user = User::where('id',$id)->get();
        if(!$user->isEmpty())
        {
            $user = User::where('id',$id)->first(['id','name','email']);
            return response()->json(['error'=>false,'message'=>'ok','user'=>$user]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro usuario.','user'=>null]);
    } 
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $user = new App\User;
            $user->name = $data['name'];
            app('hash')->make($data['password']);
            $user->password = app('hash')->make($data['password']);
            $user->email = $data['email'];
            $user->api_token = str_random(60);
            $user->last_connection = Carbon::now();
            $user->last_ip = str_random(15);
            $user->save();            
            return response()->json(['error'=>false,'message'=>'usuario agregado correctamente.','id'=>$user->id]);
        }
    }

    public function addClient(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{
            $user = new App\Clients_User;
            $user->name = $data['name'];
            $user->password =  app('hash')->make($data['password']);
            $user->email = $data['email'];
            $user->api_token = str_random(60);
            $user->last_connection = Carbon::now();
            $user->last_ip = str_random(15);
            $user->save();
            $id = $user->id;
            return response()->json(['error'=>false,'message'=>'usuario cliente agregado correctamente.','id'=>$id],200);
        }
    }

    public function confirmClient($clientId)
    {
        $user = App\Clients_User::where('id',$clientId)->withTrashed()->first();
        if($user == null){
            return response()->json(['error'=>true,'message'=>'Usuario no existe'],404);
        }
        $user->restore();
        return response()->json(['error'=>false,'message'=>'usuario cliente confirmado correctamente.'],200);
    }

    public function delete($id)
    {
        # code...
        $user = App\User::where('id', $id)->get();
        if(!$user->isEmpty()){
            try {
                $user = App\User::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'usuario eliminado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar usuario.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro usuario.']);
    }
    public function update(Request $request,$id)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'email' => 'email|max:255|unique:users',
            'password' => 'min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $user = App\User::where('id',$id)->get();
        if(!$user->isEmpty()){
            try {
                $user = App\User::where('id',$id)->find($id);  
                if ( $request->has('name') )
                {
                    $user->name = $request->get('name');
                }
            
                if ( $request->has('password') )
                {
                    $user->password =  app('hash')->make($request->get('password'));
                }
                if ( $request->has('email') )
                {
                    $user->email = $request->get('email');
                }                          
                $user->save();
            
                return response()->json(['error'=>false,'message'=>'usuario editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'usuario eliminado correctamente.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro usuario.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }
}