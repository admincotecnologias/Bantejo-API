<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Occupation;
use Validator;


class OccupationsController extends Controller {

	public function all(Request $data)
    {
        $occupations = Occupation::get();
        if(!$occupations->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','occupations'=>$occupations]);
        }
        return response()->json(['error'=>true,'message'=>'no hay puestos registradas.','occupations'=>null]);
    } 
    public function show($id)
    {
        $occupation = Occupation::where('id',$id)->get();
        if(!$occupation->isEmpty())
        {
            $occupation = Occupation::where('id',$id)->first();
            return response()->json(['error'=>false,'message'=>'ok','occupation'=>$occupation]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro puesto.','occupation'=>null]);
    } 
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), [
            'name' => 'required|max:255|unique:occupations',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $occupation = new App\Occupation;
            $occupation->name = $data['name'];
            $occupation->save();            
            return response()->json(['error'=>false,'message'=>'puesto agregado correctamente.','id'=>$occupation->id]);
        }
    }
    public function delete($id)
    {
        # code...
        $occupation = App\Occupation::where('id', $id)->get();
        if(!$occupation->isEmpty()){
            try {
                $occupation = App\Occupation::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'puesto eliminado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar puesto.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro puesto.']);
    }
    public function update(Request $request,$id)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'max:255|unique:occupations',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $occupation = App\Occupation::where('id',$id)->get();
        if(!$occupation->isEmpty()){
            try {
                if ( $request->has('name') )
                {
                    $occupation->name = $request->get('name');
                } 
                $occupation = App\Occupation::where('id',$id)->find($id);          
                $occupation->save();
            
                return response()->json(['error'=>false,'message'=>'pagina editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'pagina no se pudo actualizar.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro pagina.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }

}
