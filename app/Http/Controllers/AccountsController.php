<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use DB;
use Auth;
use App\User;
use App\Role;
use App\Agent;
use App\Client;
use App\Department;
use App\Device;

class AccountsController extends Controller
{
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

        return view('accounts', array(
            "name"      => "經銷商",
            "type"      => "agent",
        ));
    }
    public function updateAgent (Request $request, $agentId)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $agent = User::find($agentId);
        $agent->name = $request->input('name');
        $agent->save();

        return Redirect::route('agents');
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
        ]);

        DB::transaction(function($request) use ($request) {
            $client = Role::where('name', '=', "client")->first();

            $user = $this->createUser($request);
            $user->attachRole($client);

            $client = new Client;
            $client->agent_id = Auth::id();
            $client->user_limit = $request->get('user_limit');
            $client->phone = $request->get('phone');
            $user->client()->save($client);
        });

        return view('accounts', array(
            "name"      => "客戶",
            "type"      => "client",
        ));
    }
    public function updateClient (Request $request, $clientId)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'user_limit' => 'required|numeric|between:0,16',
        ]);

        $client = User::find($clientId);
        $client->name = $request->get('name');
        $client->save();

        Client::where('user_id', '=', $clientId)->update(
            array('user_limit' => $request->get('user_limit'))
        );

        for ($i = 0; $i < 16; $i++) {
            if ($request->get('device-id_'.$i)) {
                $deviceId = $request->get('device-id_'.$i);
                $deviceName = $request->get('device-name_'.$i);
                $deviceIndex = $i;

                Device::updateOrCreate([
                    'id'   => $deviceId,
                ], [
                    'name'      => $deviceName,
                    'client_id' => $clientId,
                    'index'     => $deviceIndex,
                ]);
            }
        }

        return Redirect::route('clients');
    }


    /**
     * department handlers
     */
    public function department () {
        return view('accounts', array(
            "name"      => "部門",
            "type"      => "department",
            "devices"   => Device::all(),
        ));
    }
    public function departmentDetails (Request $request, $departmentId) {
        $department = User::find($departmentId);

        $departmentData = Department::where('user_id', '=', $departmentId)->first();
        $department['device_id'] = $departmentData->device_id;

        return view('account-details', array(
            "name"      => "經銷商",
            "type"      => "department",
            "department"     => $department,
        ));
    }
    public function createDepartment(Request $request) {
        $this->validate($request, [
            'device_id' => 'required|unique:departments'
        ]);

        DB::transaction(function($request) use ($request) {
            $department = Role::where('name', '=', 'department')->first();

            $user = $this->createUser($request);
            $user->attachRole($department);

            $department = new Department();
            $department->client_id = Auth::id();
            $department->device_id = $request->get('device_id');
            $department->phone = $request->get('phone');
            $user->department()->save($department);
        });

        return Redirect::back();
    }
    public function updateDepartment (Request $request, $departmentId)
    {
        $departmentData = Department::where('user_id', '=', $departmentId)->first();
        $device_id = $request->get('device_id');

        $validateRule = [
            'name' => 'required|max:255',
        ];
        if ($departmentData->device_id !== $device_id) {
            $validateRule['device_id'] = 'required|unique:departments,device_id';
        }
        $this->validate($request, $validateRule);

        $department = User::find($departmentId);
        $department->name = $request->input('name');
        $department->save();

        Department::where('user_id', '=', $departmentId)->update(
            array('device_id' => $device_id)
        );

        return Redirect::route('departments');
    }


    public function device () {
        return view('accounts', array(
            "name"      => "儀器",
            "type"      => "device",
        ));
    }
}
