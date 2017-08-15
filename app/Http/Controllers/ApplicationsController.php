<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Application;
use Validator;


class ApplicationsController extends Controller
{

    public function all(Request $data)
    {
        $applications = App\Application::all();
        if (!$applications->isEmpty()) {
            return response()->json([
                'error' => false,
                'message' => 'ok',
                'applications' => $applications
            ]);
        }
        return response()->json([
            'error' => true,
            'message' => 'no hay solicitudes registradas.',
            'applications' => null
        ]);
    }

    public function ClientsToCredit(Request $request)
    {
        $ClientsMoral = App\Client::selectRaw(
            'clients.*,' .
            '(select count(filesclient.id) from filesclient where filesclient.idclient = clients.id and filesclient.deleted_at is null) as files,' .
            '(select count(managerclient.id) from managerclient where managerclient.idclient = clients.id and managerclient.deleted_at is null) as managers,' .
            '(select count(client_shareholder.id) from client_shareholder where client_shareholder.idclient = clients.id and client_shareholder.deleted_at is null) as shareholders,' .
            '(select count(client_banks.id) from client_banks where client_banks.idclient = clients.id and client_banks.deleted_at is null) as accounts'
        )->whereRaw(
            '(select count(filesclient.id) from filesclient where filesclient.idclient = clients.id and filesclient.deleted_at is null) > 0 AND ' .
            '(select count(managerclient.id) from managerclient where managerclient.idclient = clients.id and managerclient.deleted_at is null) > 0 AND ' .
            '(select count(client_shareholder.id) from client_shareholder where client_shareholder.idclient = clients.id and client_shareholder.deleted_at is null) > 0 AND' .
            '(select count(client_banks.id) from client_banks where client_banks.idclient = clients.id and client_banks.deleted_at is null) > 0'
        )->where('clients.type', 'Moral')->get();
        $ClientsFisica = App\Client::selectRaw(
            'clients.*,' .
            '(select count(filesclient.id) from filesclient where filesclient.idclient = clients.id and filesclient.deleted_at is null) as files,' .
            '(select count(managerclient.id) from managerclient where managerclient.idclient = clients.id and managerclient.deleted_at is null) as managers,' .
            '(select count(client_shareholder.id) from client_shareholder where client_shareholder.idclient = clients.id and client_shareholder.deleted_at is null) as shareholders,' .
            '(select count(client_banks.id) from client_banks where client_banks.idclient = clients.id and client_banks.deleted_at is null) as accounts'
        )->whereRaw(
            '(select count(filesclient.id) from filesclient where filesclient.idclient = clients.id and filesclient.deleted_at is null) > 0 AND ' .
            '(select count(client_banks.id) from client_banks where client_banks.idclient = clients.id and client_banks.deleted_at is null) > 0'
        )->where('clients.type', 'Fisica')->get();
        $Clients = $ClientsMoral->merge($ClientsFisica);
        if (!$Clients->isEmpty()) {
            return response()->json([
                'error' => false,
                'message' => 'ok',
                'clients' => $Clients
            ]);
        }
        return response()->json([
            'error' => true,
            'message' => 'no hay Clientes que puedan solicitar créditos.',
            'clients' => null
        ]);
    }

    public function show($id)
    {
        $application = Application::where('id', $id)->get();
        $creditaids = App\Creditaid::where('idapplication', $id)->get();
        $Files = App\Files::where('idapplication', $id)->get();
        $ApprovedCredit = App\creditavailable::all();
        $Credit = App\approvedcredit::where('application',$id)->orderBy('start_date','DESC')->first();
        if (!$application->isEmpty()) {
            $application = Application::where('id', $id)->first();
            return response()->json([
                'error' => false,
                'message' => 'ok',
                'application' => $application,
                'creditaids' => $creditaids,
                'files' => $Files,
                'credit' => $ApprovedCredit,
                'creditapproved' => $Credit
            ]);
        }
        return response()->json([
            'error' => true,
            'message' => 'no se encontro solicitud.',
            'application' => null,
            'creditaids' => null,
            'files' => null,
            'creditapproved' => null,
            'credit' => null
        ]);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), App\Application::$rules['create']);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'error al validar campos.',
                'errors' => $validator->errors()->all()
            ]);
        } else {
            $application = App\Application::create($request->all());
            $application->save();
            return response()->json([
                'error' => false,
                'message' => 'solicitud agregada correctamente.',
                'app' => $application
            ]);
        }
    }

    public function delete($id)
    {
        # code...
        $application = App\Application::where('id', $id)->get();
        if (!$application->isEmpty()) {
            try {
                $application = App\Application::where('id', $id)->delete();
                return response()->json([
                    'error' => false,
                    'message' => 'solicitud eliminada correctamente.'
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => 'no se pudo eliminar solicitud.',
                    'exception' => $e->getMessage()
                ]);
            }
        }
        return response()->json([
            'error' => true,
            'message' => 'no se encontro solicitud.'
        ]);
    }

    public function update(Request $request, $id)
    {
        # code...
        $validator = Validator::make($request->all(), App\Application::$rules['update']);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'error al validar campos.',
                'errors' => $validator->errors()->all()
            ]);
        }
        $application = App\Application::where('id', $id)->get();
        if (!$application->isEmpty()) {
            try {
                $application = App\Application::where('id', $id)->find($id);
                $application->fill($request->all());
                $application->save();

                return response()->json([
                    'error' => false,
                    'message' => 'solicitud editado correctamente.'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => false,
                    'message' => 'solicitud no se pudo actualizar.',
                    'errors' => $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'error' => false,
                'message' => 'no se encontro solicitud.'
            ]);
        }
    }

    public function report($id)
    {
        # code...        
        return response()->json([
            'error' => false,
            'message' => 'no se ha definido ningun reporte.'
        ]);
    }

}
