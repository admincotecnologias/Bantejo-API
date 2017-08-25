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


class FilesStockController extends BaseController {

    public function add(Request $data){

        //


        $validator = Validator::make($data->all(),[
            'idstock'=> 'required|numeric|exists:applications,id'
        ]);
        if($validator->fails()){
            $failed = $validator->failed();
            if(isset($failed['idstock']['Required'])){
                return response()->json(['error'=>true,'message' => 'Falto especificar el id del fondeador.']);
            }
            if(isset($failed['idstock']['Exists'])){
                return response()->json(['error'=>true,'message' => 'ID de fondeador no existe.']);
            }
        }


        if ($data->hasFile('file')) {
            //
            $file = $data->file('file');
            $path = realpath(base_path('public/storage/'));
            $extension = '.'.$file->guessClientExtension();
            $namefile = md5($data->file('file')->getClientOriginalName().str_random(15)).$extension;
            $data->file('file')->move($path,$namefile);
            $filedb = new App\Files_Stock;
            $filedb->name = $data->file('file')->getClientOriginalName();
            $filedb->idstock = (int)$data->input('idstock');
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
        $files = App\Files_Stock::where('id',$id)->get();
        if(!$files->isEmpty()){
            $file = $files[0];
            return response()->json(['filepath'=>basename($file->path),'name'=>$file->name,'content-type' => $file->mime]);
            //return response()->download($file->path,$file->name,['content-type' => $file->mime,
            //'Access-Control-Allow-Origin' => '*']);
        }
    }

}
