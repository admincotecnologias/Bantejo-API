<?php 
namespace App\Http\Controllers;
use app\managerclient;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Validator;


class ManagerclientsController extends Controller {

	public function all(Request $data)
    {
        $manager = App\managerclient::get();
        if(!$manager->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','manager'=>$manager]);
        }
        return response()->json(['error'=>true,'message'=>'no hay representantes registradas.','manager'=>null]);
    } 
    public function show($id)
    {
        $manager = App\managerclient::where('id',$id)->get();
        if(!$manager->isEmpty())
        {
            $manager = App\managerclient::where('id',$id)->first();
            return response()->json(['error'=>false,'message'=>'ok','manager'=>$manager]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro representante.','manager'=>null]);
    } 
  
    public function showByClient($id)
    {
        $manager = App\managerclient::where('idclient',$id)->get();
        if(!$manager->isEmpty())
        {
            $manager = App\managerclient::where('idclient',$id)->get();
            return response()->json(['error'=>false,'message'=>'ok','manager'=>$manager]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro representante.','manager'=>null]);
    } 
  
  
  
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), [
            'name' => 'required|max:255',
			'lastname' => 'required|max:255',
			'rfc' => 'required|max:255|min:12|max:13',
			'idclient' => 'required|integer|exists:clients,id',
			'idfile' => 'required|integer|exists:filesclient,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $manager = new App\managerclient;
            $manager->name = $data['name'];
			$manager->lastname = $data['lastname'];
			$manager->rfc = $data['rfc'];
			$manager->idclient = $data['idclient'];
			$manager->idfile = $data['idfile'];
            if ($data->has('phone')){
                    $manager->phone = $data['phone'];
            }else{
                $manager->phone = null;
            }
            $manager->save();
            if($manager->id>0){
                $return = App\Manager::where('idclient',$manager->idclient)->get();
                return response()->json(['error'=>false,'message'=>'representante agregado correctamente.','managers'=>$return]);
            }
            return response()->json(['error'=>true,'message'=>'representante no se agrego correctamente.','managers'=>null]);
        }
    }
    public function delete($id)
    {
        # code...
        $manager = App\managerclient::where('id', $id)->get();
        if(!$manager->isEmpty()){
            try {
                $manager = App\managerclient::where('id', $id)->delete();
                if($manager>0){
                    return response()->json(['error'=>false,'message'=>'representante eliminado correctamente.']);
                }else{
                    return response()->json(['error'=>true,'message'=>'Error al eliminar.']);
                }
            } catch (\Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar representante.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro representante.']);
    }
}
