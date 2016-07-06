<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use App\Http\Requests;
use DB;
use Auth;
use App\User;
use App\Role;
use App\Agent;
use App\Client;
use App\Department;
use App\Device;
use App\DeviceHistory;
use App\Threshold;
use App\UserFile;

class AccountsController extends Controller
{
    private $uploadBasePath = '/uploads';

    public function index () {
        return view('accounts');
    }
    private function createUser($request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'username' => 'required|max:31|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
        ]);

        return $user;
    }

    /**
     * agent handlers
     */
    public function agent () {
        return view('accounts', array(
            "name"      => "經銷商",
            "type"      => "agent",
        ));
    }
    public function agentDetails (Request $request, $agentId) {
        $agent = Agent::where("user_id", "=", $agentId)->first();
        return view('account-details', array(
            "name"      => "經銷商",
            "type"      => "agent",
            "agent"     => $agent,
        ));
    }
    public function createAgent (Request $request)
    {
        DB::transaction(function($request) use ($request) {
            $agent = Role::where('name', '=', "agent")->first();

            $user = $this->createUser($request);
            $user->attachRole($agent);

            $agent = new Agent;
            $agent->admin_id = Auth::id();
            $agent->phone = $request->get('phone');
            $user->agent()->save($agent);
        });

        return Redirect::back();
    }
    public function updateAgent (Request $request, $agentId)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'password' => 'min:6|confirmed',
        ]);

        $agent = User::find($agentId);
        $agent->name = $request->input('name');
        if ($request->input('password')) {
            $agent->password = bcrypt($request->input('password'));
        }
        $agent->save();

        return Redirect::back();
    }


    /**
     * client handlers
     */
    public function client () {
        return view('accounts', array(
            "name"      => "客戶",
            "type"      => "client",
        ));
    }
    public function clientDetails (Request $request, $clientId) {
        $client = Client::where("user_id", "=", $clientId)->first();
        $currentDevices = Device::where("client_id", "=", $clientId)->get();

        $devices = array_fill(0, 16, ['id'=>NULL, 'name'=>NULL]);

        foreach ($currentDevices as $currentDevice) {
            $devices[$currentDevice->index] = $currentDevice;
        }
        $client->devices = $devices;
        return view('account-details', array(
            "name"      => "客戶",
            "type"      => "client",
            "client"     => $client,
        ));
    }
    public function createClient (Request $request)
    {
        $this->validate($request, [
            'user_limit' => 'required|numeric|between:0,16',
            'device_account' => 'required|unique:clients,device_account',
        ]);

        DB::transaction(function($request) use ($request) {
            $client = Role::where('name', '=', "client")->first();

            $user = $this->createUser($request);
            $user->attachRole($client);

            $client = new Client;
            $client->agent_id = Auth::id();
            $client->user_limit = $request->get('user_limit');
            $client->device_account = $request->get('device_account');
            $client->phone = $request->get('phone');
            $user->client()->save($client);
        });

        return Redirect::back();
    }
    public function updateClient (Request $request, $clientId)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'user_limit' => 'required|numeric|between:0,16',
            'password' => 'min:6|confirmed',
        ]);

        $client = User::find($clientId);
        $client->name = $request->get('name');
        if ($request->input('password')) {
            $client->password = bcrypt($request->input('password'));
        }
        $client->save();

        Client::where('user_id', '=', $clientId)->update([
            'user_limit' => $request->get('user_limit'),
            'device_account' => $request->get('device_account'),
        ]);

        for ($i = 0; $i < 16; $i++) {

            $deviceName = $request->get('device-name_'.$i);
            $deviceIndex = $i;

            $device = Device::where("client_id", "=", $clientId)->where("index", '=', $deviceIndex)->first();
            if ($device && !$deviceName) {
                Device::where("client_id", "=", $clientId)->where("index", '=', $deviceIndex)
                    ->delete();
            } else if ($deviceName) {
                if ($device) {
                    Device::find($device->id)
                    ->update([
                        'name'      => $deviceName,
                        'client_id' => $clientId,
                        'index'     => $deviceIndex,
                    ]);
                } else {
                    Device::create([
                        'name'      => $deviceName,
                        'client_id' => $clientId,
                        'index'     => $deviceIndex,
                    ]);
                }
            }

        }

        return Redirect::back();
    }


    /**
     * department handlers
     */
    public function department () {
        return view('accounts', array(
            "name"      => "客戶帳號",
            "type"      => "department",
            "devices"   => Device::all(),
        ));
    }
    public function departmentDetails (Request $request, $departmentId) {
        $department = User::find($departmentId);

        $departmentData = Department::where('user_id', '=', $departmentId)->first();
        $department['device_id'] = $departmentData->device_id;

        return view('account-details', array(
            "name"      => "客戶帳號",
            "type"      => "department",
            "department"     => $department,
        ));
    }
    public function createDepartment(Request $request) {
        $departmentCount = Department::where("client_id", "=", Auth::id())->count();
        $userLimit = Client::where("user_id", "=", Auth::id())->first()->user_limit - 1;
        $data = [
            'user_limit' => $departmentCount
        ];
        $rule  =  array(
            'user_limit' => 'required|numeric|max:'.$userLimit,
        );
        $validator = Validator::make($data, $rule);
        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        DB::transaction(function($request) use ($request) {
            $department = Role::where('name', '=', 'department')->first();

            $user = $this->createUser($request);
            $user->attachRole($department);

            $department = new Department();
            $department->client_id = Auth::id();
            $department->phone = $request->get('phone');
            $user->department()->save($department);
        });

        return Redirect::back();
    }
    public function updateDepartment (Request $request, $departmentId)
    {
        $departmentData = Department::where('user_id', '=', $departmentId)->first();

        $this->validate($request, [
            'name' => 'required|max:255',
            'password' => 'min:6|confirmed',
        ]);

        $department = User::find($departmentId);
        $department->name = $request->input('name');
        if ($request->input('password')) {
            $department->password = bcrypt($request->input('password'));
        }
        $department->save();

        return Redirect::back();
    }


    public function device () {
        return view('accounts', array(
            "name"      => "儀器",
            "type"      => "device",
        ));
    }

    public function deactive (Request $request, $id) {
        $user = User::find($id);
        $user->active = false;
        $user->save();
        if ($user->hasRole('client')) {
            $departments = Department::where("client_id", "=", $user->id)->get();
            foreach ($departments as $department) {
                $depUser = User::find($department->user_id);
                $depUser->active = false;
                $depUser->save();
            }
        }
        return Redirect::back();
    }

    public function active (Request $request, $id) {
        $user = User::find($id);
        $user->active = true;
        $user->save();
        if ($user->hasRole('client')) {
            $departments = Department::where("client_id", "=", $user->id)->get();
            foreach ($departments as $department) {
                $depUser = User::find($department->user_id);
                $depUser->active = true;
                $depUser->save();
            }
        }
        return Redirect::back();
    }

    public function delete (Request $request, $id) {
        $user = User::find($id);
        if ($user->hasRole('client')) {
            Device::where("client_id", "=", $id)->delete();
            Department::where("client_id", "=", $id)->delete();
            Threshold::where("user_id", "=", $id)->delete();
            UserFile::where("user_id", "=", $id)->delete();
            Client::where("user_id", "=", $id)->delete();

            $destinationPath = base_path() . $this->uploadBasePath . '/' . $user->username;
            if(File::exists($destinationPath)) {
                File::deleteDirectory($destinationPath);
            }

            $user->delete();
        }
        return redirect('/');
    }
}
