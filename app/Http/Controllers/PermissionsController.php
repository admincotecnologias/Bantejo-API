<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use App\Permission;
use Validator;


class PermissionsController extends Controller {

	public function all(Request $data)
    {
        $permissions = Permission::get();
        if(!$permissions->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','permissions'=>$permissions]);
        }
        return response()->json(['error'=>true,'message'=>'no hay permisos registrados.','permissions'=>null]);
    } 
    public function show($id)
    {
        $permission = Permission::where('id',$id)->get();
        if(!$permission->isEmpty())
        {
            $permission = Permission::where('id',$id)->first();
            return response()->json(['error'=>false,'message'=>'ok','permission'=>$permission]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro usuario.','permission'=>null]);
    } 
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), [
            'show' => 'required|boolean',
            'insert' => 'required|boolean',
            'edit' => 'required|boolean',
			'delete' => 'required|boolean',
			'report' => 'required|boolean',
			'iduser' => 'required|integer',
			'idpage' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $permission = new App\Permission;
            $permission->show = $data['show'];
            $permission->insert = $data['insert'];
            $permission->edit = $data['edit'];
            $permission->delete = $data['delete'];
            $permission->report = $data['report'];
            $permission->iduser = $data['iduser'];
			$permission->idpage = $data['idpage'];
            $permission->save();            
            return response()->json(['error'=>false,'message'=>'permiso agregado correctamente.','id'=>$permission->id]);
        }
    }
    public function delete($id)
    {
        # code...
        $permission = App\Permission::where('id', $id)->get();
        if(!$permission->isEmpty()){
            try {
                $permission = App\Permission::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'permiso eliminado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar permiso.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro permiso.']);
    }
    public function update(Request $request,$id)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'show' => 'boolean',
            'insert' => 'boolean',
            'edit' => 'boolean',
			'delete' => 'boolean',
			'report' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $permission = App\Permission::where('id',$id)->get();
        if(!$user->isEmpty()){
            try {
                if ( $request->has('show') )
                {
                    $permission->show = $request->get('show');
                }
            
                if ( $request->has('insert') )
                {
                    $permission->insert = bcrypt($request->get('insert'));
                }
                if ( $request->has('edit') )
                {
                    $permission->edit = $request->get('edit');
                }  
				if ( $request->has('delete') )
                {
                    $permission->delete = $request->get('delete');
                }
				if ( $request->has('report') )
                {
                    $permission->report = $request->get('report');
                }  
                $permission = App\Permission::where('id',$id)->find($id);          
                $permission->save();
            
                return response()->json(['error'=>false,'message'=>'permiso editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'no se pudo editar permiso.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro permiso.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }

}
