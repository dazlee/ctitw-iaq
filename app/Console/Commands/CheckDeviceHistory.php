<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\User;
use App\Client;
use App\Device;
use App\Agent;
use App\DeviceHistory;
use Mail;

class CheckDeviceHistory extends Command
{
    const _30MINS = '1';
    const _60MINS = '2';
    const _90MINS = '3';
    const _120MINS = '4';
    const _DURATION_TRANSLATION = array(
        self::_30MINS   => "30分鐘",
        self::_60MINS   => "一小時",
        self::_90MINS   => "一小時30分鐘",
        self::_120MINS   => "二小時",
    );
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devicehistory:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    private function getClients () {
        return Client::all();
    }
    private function getClientDevices ($client) {
        $devices = Device::where("client_id", "=", $client->user["id"])->get();
        $_devices = [];
        foreach ($devices as $device) {
            $_devices[] = array(
                'device_id' => $client->device_account . '-' . $device->index,
                'device_name'   => $device->name,
            );
        }
        return $_devices;
    }
    private function getDeviceLastRecord ($device) {
        return DeviceHistory::ofDevice($device['device_id'])->sortRecord('desc')->first();
    }
    private function getEmailType ($record) {
        date_default_timezone_set("Asia/Taipei");
        $recordAt = strtotime($record['record_at']);
        $now = time();
        Log::info('getEmailType minutes diff: ' . round(abs($recordAt - $now) / 60) . " minutes");
        $diff = round(abs($recordAt - $now) / 60);
        if ($diff >= 30 && $diff < 40) {
            return self::_30MINS;
        } else if ($diff >= 60 && $diff < 70) {
            return self::_60MINS;
        } else if ($diff >= 90 && $diff < 100) {
            return self::_90MINS;
        } else if ($diff >= 120 && $diff < 130) {
            return self::_120MINS;
        } else {
            return null;
        }
    }
    private function getEmailMessage ($client, $device, $type) {
        $time = self::_DURATION_TRANSLATION[$type];
        $clientName = $client->user['name'];
        return "$clientName - 儀器 ${device['device_name']}(${device['device_id']}) 已經${time}沒有回應。 </br>\r\n";
    }
    private function sendEmail ($client, $body) {
        $clientEmail = $client->user['email'];
        $agentEmail = $client->agent->user['email'];
        $adminEmail = $client->agent->admin['email'];
        Mail::send('emails.warning', ['to' => $clientEmail, 'body' => $body], function ($message) use ($clientEmail, $agentEmail, $adminEmail, $body) {
            $message->to($clientEmail)->cc($agentEmail)->cc($adminEmail);
            $message->subject('儀器無回應警報')->setBody($body);
        });
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $clients = $this->getClients();
        // check every client
        foreach ($clients as $client) {
            $devices = $this->getClientDevices($client);
            $body = "";
            // check every device for client
            foreach ($devices as $device) {
                $record = $this->getDeviceLastRecord($device);
                if ($record) {
                    $type = $this->getEmailType($record);
                    // if it has email type, append the message to body
                    if ($type) {
                        $body .= $this->getEmailMessage($client, $device, $type);
                    }
                }
            }
            if (!empty($body)) {
                $this->sendEmail($client, $body);
            }
        }
    }
}
