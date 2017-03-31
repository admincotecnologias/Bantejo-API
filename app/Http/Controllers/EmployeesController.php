<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Employee;
use Validator;


class EmployeesController extends Controller {

	public function all(Request $data)
    {
        $employees = DB::select("select
            e.id,
            e.name,
            e.lastname,
            o.name as occupation,
            u.email
            from employees as e
            inner join users as u on e.deleted_at <=> NULL
            left join occupations as o on e.idoccupation = o.id
            where e.iduser=u.id;");
        if($employees!= null)
        {
            return response()->json(['error'=>false,'message'=>'ok','employees'=>$employees]);
        }
        return response()->json(['error'=>true,'message'=>'no hay empleados registrados.','employees'=>null]);
    } 
    public function show($id)
    {
        $employee = Employee::where('id',$id)->get();
        if(!$employee->isEmpty())
        {            
            $employee = Employee::where('id',$id)->first();
            $occupation = App\Occupation::where('id',$employee->idoccupation)->first();
            $user = App\User::where('id',$employee->iduser)->first();
            $permissions = App\Permission::where('iduser',$user->id)->get();
            return response()->json(['error'=>false,'message'=>'ok','id'=>$employee->id,'name'=>$employee->name,'lastname'=>$employee->lastname,'email'=>$user->email,'puesto'=>$occupation,'permisos'=>$permissions,'iduser'=>$user->id]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro empleado.','employee'=>null]);
    } 
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), App\Employee::$rules['create']);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
			$user = App\User::where('id',$data['iduser'])->get();
			$occupation = App\Occupation::where('id',$data['idoccupation'])->get();
			if($user->isEmpty()){
				return response()->json(['error'=>true,'message'=>'usuario no encontrado.']);
			}
			if($occupation->isEmpty()){
				return response()->json(['error'=>true,'message'=>'puesto no encontrado.']);
			}
            $employee = App\Employee::create($data->all());
			$employee->save();
            return response()->json(['error'=>false,'message'=>'empleado agregado correctamente.','id'=>$employee->id]);
        }
    }
    public function delete($id)
    {
        # code...
        $employee = App\Employee::where('id', $id)->get();
        if(!$employee->isEmpty()){
            try {
                $employee = App\Employee::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'empleado eliminado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar empleado.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro empleado.']);
    }
    public function update(Request $request,$id)
    {
        # code...
        $validator = Validator::make($request->all(), App\Employee::$rules['update']);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $employee = App\Employee::where('id',$id)->get();
        if(!$employee->isEmpty()){
            try {
                $employee = App\Employee::where('id',$id)->find($id); 
                $employee->fill($request->all());
                $employee->save();
                return response()->json(['error'=>false,'message'=>'empleado editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'no se pudo actualizar empleado.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro empleado.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }

}
