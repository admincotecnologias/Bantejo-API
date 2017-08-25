<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Bank;
use Validator;


class BanksController extends Controller {

	public function all(Request $data)
    {
        $banks = Bank::get();
        if(!$banks->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','banks'=>$banks]);
        }
        return response()->json(['error'=>true,'message'=>'no hay bancos registradas.','banks'=>null]);
    } 
    public function show($id)
    {
        $bank = Bank::where('id',$id)->get();
        if(!$bank->isEmpty())
        {
            $bank = Bank::where('id',$id)->first();
            return response()->json(['error'=>false,'message'=>'ok','bank'=>$bank]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro banco.','bank'=>null]);
    } 
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), [
            'name' => 'required|max:255|unique:banks',
        ]);
        if ($validator->fails()) {
            $failed = $validator->failed();
            //si la pagina ya estaba, pero se le habia hecho soft delete, la restauramos
            if(isset($failed['name']['Unique'])){
                App\Bank::where('name',$data['name'])->restore();
                return response()->json(['error'=>false,'message'=>'campo restaurado.']);
            }
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $bank = new App\Bank;
            $bank->name = $data['name'];
            $bank->save();            
            return response()->json(['error'=>false,'message'=>'banco agregado correctamente.','id'=>$bank->id]);
        }
    }
    public function delete($id)
    {
        # code...
        $bank = App\Bank::where('id', $id)->get();
        if(!$bank->isEmpty()){
            try {
                $bank = App\Bank::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'banco eliminado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar banco.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro banco.']);
    }
    public function update(Request $request,$id)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'max:255|unique:banks',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $bank = App\Bank::where('id',$id)->get();
        if(!$bank->isEmpty()){
            try {
				$bank = App\Bank::where('id',$id)->find($id); 
                if ( $request->has('name') )
                {
                    $bank->name = $request->get('name');
                }                          
                $bank->save();
            
                return response()->json(['error'=>false,'message'=>'banco editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'banco no se pudo actualizar.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro banco.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }

}
