<?php 
namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App;
use App\Files;
use Validator;


class FilesController extends BaseController {

	public function add(Request $data){

        //
        $validator = Validator::make($data->all(),[
            'idapplication'=> 'required|numeric|exists:applications,id'
        ]);
        if($validator->fails()){
            $failed = $validator->failed();
            if(isset($failed['idapplication']['Required'])){
                return response()->json(['error'=>true,'message' => 'Falto especificar el id de la aplicacion.']);
            }
            if(isset($failed['idapplication']['Exists'])){
                return response()->json(['error'=>true,'message' => 'ID de aplicacion no existe.']);
            }
        }


        if ($data->hasFile('file')) {
    		//
			$file = $data->file('file');
			$path = realpath(base_path('public/storage/'));
			$extension = '.'.$file->guessClientExtension();
			$namefile = md5($data->file('file')->getClientOriginalName().str_random(15)).$extension;
			$data->file('file')->move($path,$namefile);	
			$filedb = new App\Files;
			$filedb->name = $data->file('file')->getClientOriginalName();
			$filedb->idapplication = (int)$data->input('idapplication');
			$filedb->path = $namefile;
			$filedb->mime = $data->file('file')->getClientMimeType();
			$filedb->extension = $extension;
            $filedb->type = $data->input('type');
			$filedb->save();
			
			return response()->json(['error'=>false,'message'=>'Archivo guardado.','file'=>$filedb]);
		} 
        return response()->json(['error'=>true,'message'=>'Archivo Invalido.','file'=>null]);
	}
	public function ReturnFile($id,Request $request){
		$files = App\Files::where('id',$id)->get();
		if(!$files->isEmpty()){
			$file = $files[0];
			return response()->json(['filepath'=>basename($file->path),'name'=>$file->name,'content-type' => $file->mime]);
			//return response()->download($file->path,$file->name,['content-type' => $file->mime,
           //'Access-Control-Allow-Origin' => '*']);
		}
	}

}
