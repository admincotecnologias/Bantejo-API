<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Creditaid;
use Validator;
use Illuminate\Validation\Rule as Rule;


class CreditaidsController extends Controller {

	public function all(Request $data)
    {
        $creditaids = Creditaid::get();
        if(!$creditaids->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','creditaids'=>$creditaids]);
        }
        return response()->json(['error'=>true,'message'=>'no hay avales registradas.','creditaids'=>null]);
    } 
    public function show($id)
    {
        $creditaid = Creditaid::where('id',$id)->get();
        if(!$creditaid->isEmpty())
        {
            $creditaid = Creditaid::where('id',$id)->first();
            $creditaidbank = App\CreditaidBank::where('idcreditaid',$creditaid->id)->get();
            $creditaidshareholder = App\Creditaidshareholder::where('idcreditaid',$creditaid->id)->get();
            return response()->json(['error'=>false,'message'=>'ok','creditaid'=>$creditaid,'banks'=>$creditaidbank,'shareholders'=>$creditaidshareholder]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro aval.','creditaid'=>null]);
    } 
    public function add(Request $data)
    {      
        if ( $data->has('typeguarantee') )
        {
            if($data->input('typeguarantee')=='Moral'){
                $validator = Validator::make($data->all(), App\Creditaid::$rules['moral']['create']);
                    if ($validator->fails()) {
                    return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
                    }
                    else{  
                        $creditaid =  App\Creditaid::create($data->all());
                        $creditaid->save();            
                        return response()->json(['error'=>false,'message'=>'aval agregado correctamente.','id'=>$creditaid->id]);
                    }
            }else{
                $validator = Validator::make($data->all(), App\Creditaid::$rules['fisica']['create']);
                if ($validator->fails()) {
                    return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
                }
                else{
                    $creditaid =  App\Creditaid::create($data->all());
                    $creditaid->save();            
                    return response()->json(['error'=>false,'message'=>'aval agregado correctamente.','id'=>$creditaid->id]);
                }
            }
        }
    }
    public function delete($id)
    {
        # code...
        $creditaid = App\Creditaid::where('id', $id)->get();
        if(!$creditaid->isEmpty()){
            try {
                $creditaid = App\Creditaid::where('id', $id)->delete();
                if($creditaid>0){
                    return response()->json(['error'=>false,'message'=>'aval eliminado correctamente.']);
                }else{
                    return response()->json(['error'=>true,'message'=>'no se elimino aval.']);
                }
            } catch (\Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar aval.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro aval.']);
    }
    public function update(Request $data,$id)
    {
        $updateRules = null;
        # code...
        if($data->input('typeguarantee')=='Moral'){
            $updateRules = App\Creditaid::$rules['moral']['update'];
            $updateRules['rfc']='required|max:12|min:12|unique:creditaids,rfc,'.$id.'|unique:clients,rfc,'.$id;
        }else{
            $updateRules = App\Creditaid::$rules['fisica']['update'];
            $updateRules['rfc']='required|max:13|min:13|unique:creditaids,rfc,'.$id.'|unique:clients,rfc,'.$id;
        }
        $validator = Validator::make($data->all(), $updateRules);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $creditaid = App\Creditaid::where('id',$id)->get();
        if(!$creditaid->isEmpty()){
            try {
				$creditaid = App\Creditaid::where('id',$id)->find($id);
                $creditaid->fill($data->all());
                $creditaid->save();
            
                return response()->json(['error'=>false,'message'=>'aval editado correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'aval no se pudo actualizar.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro aval.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }

}
