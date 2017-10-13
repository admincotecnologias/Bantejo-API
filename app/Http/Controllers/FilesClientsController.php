<?php 
namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App;
use Validator;
use App\FileClient;


class FilesClientsController extends Controller {
	public function add(Request $data){		
        if ($data->hasFile('file')) {
    		//
            $validator = Validator::make($data->all(),[
                'type' => 'required',
                'idclient'=> 'required|numeric|exists:clients,id'
            ]);
            if($validator->fails()){
                $failed = $validator->failed();
                if(isset($failed['type']['Required'])){
                    return response()->json(['error'=>true,'message' => 'Falto especificar el tipo de archivo.']);
                }
                if(isset($failed['idclient']['Required'])){
                    return response()->json(['error'=>true,'message' => 'Falto especificar el id del cliente.']);
                }
                if(isset($failed['idclient']['Exists'])){
                    return response()->json(['error'=>true,'message' => 'ID de cliente no existe.']);
                }
            }
			try{
                $file = $data->file('file');
                $path = realpath(base_path('public/storage/'));
                $extension = '.'.$file->guessClientExtension();
                $namefile = md5($data->file('file')->getClientOriginalName().str_random(15)).$extension;
                $data->file('file')->move($path,$namefile);
                $filedb = new App\FileClient;
                $filedb->name = $data->file('file')->getClientOriginalName();
                $filedb->idclient = $data->input('idclient');
                $filedb->path = $namefile;
                $filedb->mime = $data->file('file')->getClientMimeType();
                $filedb->extension = $extension;
                $filedb->type = $data->input('type');
                $filedb->save();
                $files = App\FileClient::where('idclient',$filedb->idclient)->get();

                return response()->json(['error'=>false,'message'=>'Archivo guardado.','file'=>$filedb,'files'=>$files]);
            }catch (\Exception $ex){
			    return $ex;//response()->json(['error'=>true,'message'=>'Archivo no guardado.','file'=>$ex]);
            }
		} 
        return response()->json(['error'=>true,'message'=>'Archivo Invalido.','file'=>null]);
	}
	public function ReturnFile($id,Request $request){
		$files = App\FileClient::where('idclient',$id)->get();
		if(!$files->isEmpty()){
			$file = $files[0];
			return response()->json(['filepath'=>basename($file->path),'name'=>$file->name,'content-type' => $file->mime]);
			//return response()->download($file->path,$file->name,['content-type' => $file->mime,
           //'Access-Control-Allow-Origin' => '*']);
		}
	}
	public function DeleteFile($id){
	    $file = App\FileClient::where('id',$id)->first();
	    if($file){
            $file->delete();
            return response()->json(['error'=>false,'message'=>'Archivo eliminado.']);
        }else{
            return response()->json(['error'=>true,'message'=>'Archivo no pudo ser eliminado.']);
        }
    }

}
