<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\User;
use App\Page;
use App\Permission;
use App\Employee;
use App\Occupation;
use Laravel\Lumen\Auth\Authorizable;

class Api_authsController extends Controller {

	protected function checkAuth(Request $data)
	{
		# code...
		$token = $data->header('token');
		$user = new App\User;
		$user = App\User::where('api_token',$token)->where('api_token','!=','')->get();
		if(!$user->isEmpty()){
			$user = App\User::where('api_token',$token)->first();
			$lastCon = Carbon::parse($user->last_connection);
			$now = Carbon::now();
			if($user->last_ip != $data->ip()){
				return response()->json(['error'=>true,'message'=>'ip no coincide','code'=>1]);
			}
			if($now->diffInDays($lastCon)>0){
				return response()->json(['error'=>true,'message'=>'limite de conexion alcanzado','code'=>1]);
			}
			return response()->json(['error'=>false,'message'=>'ok','user'=>$user]);
		}
		else{
			return response()->json(['error'=>true,'message'=>'token inexistente o no coincide','code'=>1]);
		}
	}

	protected function refreshAdminToken(Request $data){
		$response = this.checkAuth($data);
		if($response['message']='token inexistente o no coincide'){
			$response['code']=404;
		}
		return $response;
	}

	protected function refreshClientToken(Request $data){
		$response = this.checkClientsAuth($data);
		if($response['message']='token inexistente o no coincide'){
			$response['code']=404;
		}
		return $response;
	}

	protected function checkClientsAuth(Request $data)
	{
		# code...
		$token = $data->header('token');
		$user = App\Clients_User::where('api_token',$token)->where('api_token','!=','')->get();
		if(!$user->isEmpty()){
			$user = App\Clients_User::where('api_token',$token)->first();
			$lastCon = Carbon::parse($user->last_connection);
			$now = Carbon::now();
			if($user->last_ip != $data->ip()){
				return response()->json(['error'=>true,'message'=>'ip no coincide','code'=>1]);
			}
			if($now->diffInDays($lastCon)>0){
				return response()->json(['error'=>true,'message'=>'limite de conexion alcanzado','code'=>1]);
			}
			return response()->json(['error'=>false,'message'=>'ok','user'=>$user]);
		}
		else{
			return response()->json(['error'=>true,'message'=>'token inexistente o no coincide','code'=>1]);
		}
	}




	public function checkRole(Request $data)
	{
		# code...
		$auth = $this->checkAuth($data);
		if(!$auth->getData()->error){
			$user = $auth->getData()->user;
			$path = $data->segments();
			$page = App\Page::where('url',$path[1])->get();
			if(!$page->isEmpty()){
				$page = App\Page::where('url',$path[1])->first();
				$permission = App\Permission::where('iduser',$user->id)->where('idpage',$page->id)->get();
				if(!$permission->isEmpty()){
					$permission = App\Permission::where('iduser',$user->id)->where('idpage',$page->id)->first();
					switch ($path[2]) {
						case 'show':
							if($permission->show == 1)
								return response()->json(['error'=>false,'message'=>'rol ok','permisos'=>$permission]);
							break;
						case 'all':
							if($permission->show)
								return response()->json(['error'=>false,'message'=>'rol ok','permisos'=>$permission]);
							break;
						case 'add':
							if($permission->insert)
								return response()->json(['error'=>false,'message'=>'rol ok','permisos'=>$permission]);
							break;
						case 'update':
							if($permission->edit)
								return response()->json(['error'=>false,'message'=>'rol ok','permisos'=>$permission]);
							break;
						case 'delete':
							if($permission->delete)
								return response()->json(['error'=>false,'message'=>'rol ok','permisos'=>$permission]);
							break;
						case 'report':
							if($permission->report)
								return response()->json(['error'=>false,'message'=>'rol ok','permisos'=>$permission]);
							break;
						default:
							return response()->json(['error'=>true,'message'=>'no tiene ese permiso.','code'=>2]);
					}
				}
				return response()->json(['error'=>true,'message'=>'no tiene acceso a esta ruta.','code'=>2]);
			}
			return response()->json(['error'=>true,'message'=>'no existe ruta.','code'=>2]);
		}
		else{
			if($auth->getData()->message != 'token inexistente o no coincide'){
				return response()->json(['error'=>true,'message'=>$auth->getData()->message]);
			}
			$this->AdminLogout($data);
			return response()->json(['error'=>true,'message'=>$auth->getData()->message]);
		}
	}

    public function checkClientsRole(Request $data)
    {
        # code...
        $auth = $this->checkClientsAuth($data);
        if(!$auth->getData()->error){
            $user = $auth->getData()->user;
            $path = $data->segments();
            if($path[0] != "Clients"){
				return response()->json(['error'=>true,'message'=>'no existe ruta.','code'=>2]);
			}else{
				return response()->json(['error'=>false,'message'=>'rol ok']);
			}
            
        }
        else{
            if($auth->getData()->message != 'token inexistente o no coincide'){
                return response()->json(['error'=>true,'message'=>$auth->getData()->message]);
            }
            $this->ClientsLogout($data);
            return response()->json(['error'=>true,'message'=>$auth->getData()->message]);
        }
    }

	public function AdminLogOut(Request $data)
	{
			$token = $data->header('token');
			$user = new App\User;
			$user = App\User::where('api_token',$token)->where('api_token','!=','')->get();
			if(!$user->isEmpty()){
				$user = App\User::where('api_token',$token)->first();
				$user->last_ip = str_random(15);
				$user->api_token = str_random(60);
				$user->save();
				return response()->json(['error'=>false,'message'=>'LogOut OK.']);
			}
			else{
				return response()->json(['error'=>true,'message'=>'token no existe.']);
			}
	}
	
	public function AdminLogIn(Request $data)
	{
		# code...
		if(!$data->has('email')){
			return response()->json(['error'=>true,'message'=>'Falta campo Email.']);
		}
		if(!$data->has('password')){
			return response()->json(['error'=>true,'message'=>'Falta campo Password.']);
		}
		$user = new App\User;
		$user = App\User::where('email',$data->email)->get();
		if(!$user->isEmpty()){
			$user = App\User::where('email',$data->email)->first();
			if(password_verify($data->password, $user->password)){
				$user->last_ip = $data->ip();
				$user->api_token = str_random(60);
				$user->last_connection = Carbon::now();
				$user->save();
				$employee = App\Employee::where('iduser',$user->id)->first();
				$occupation = App\Occupation::where('id',$employee->idoccupation)->first();
				$permissions = App\Permission::where('iduser',$user->id)->leftjoin('pages as pages','pages.id','=',"permissions.idpage")->get(["show","delete","edit","report","insert","pages.url"]);
				return response()->json(['error'=>false,'message'=>'LogIn correcto.', 'permissions' => $permissions, 'id' => $employee->id,'token'=>$user->api_token,'nombre'=>$user->name,'date'=>$user->last_connection->toDateString(),'puesto'=>$occupation->name]);
			}
			else{
				return response()->json(['error'=>true,'message'=>'Contraseña erronea.']);
			}
		}
		return response()->json(['error'=>true,'message'=>'Email incorrecto.']);
	}
    public function ClientsLogOut(Request $data)
    {
        $token = $data->header('token');
        $user = new App\User;
        $user = App\User::where('api_token',$token)->where('api_token','!=','')->get();
        if(!$user->isEmpty()){
            $user = App\User::where('api_token',$token)->first();
            $user->last_ip = str_random(15);
            $user->api_token = str_random(60);
            $user->save();
            return response()->json(['error'=>false,'message'=>'LogOut OK.']);
        }
        else{
            return response()->json(['error'=>true,'message'=>'token no existe.']);
        }
    }

    public function ClientsLogIn(Request $data)
    {
        # code...
        if(!$data->has('email')){
            return response()->json(['error'=>true,'message'=>'Falta campo Email.']);
        }
        if(!$data->has('password')){
            return response()->json(['error'=>true,'message'=>'Falta campo Password.']);
        }
        $user = App\Clients_User::where('email',$data->email)->get();
        if(!$user->isEmpty()){
            $user = App\Clients_User::where('email',$data->email)->first();
            if(password_verify($data->password, $user->password)){
				if(!$user->iduser){
					return response()->json(['error'=>true,'message'=>'Cliente no registrado.']);
				}
                $user->last_ip = $data->ip();
                $user->api_token = str_random(60);
                $user->last_connection = Carbon::now();
                $user->save();
                return response()->json(['error'=>false,'message'=>'LogIn correcto.','token'=>$user->api_token,'nombre'=>$user->name,'date'=>$user->last_connection->toDateString()]);
            }
            else{
                return response()->json(['error'=>true,'message'=>'Contraseña erronea.']);
            }
        }
        return response()->json(['error'=>true,'message'=>'Email incorrecto.']);
    }
}
