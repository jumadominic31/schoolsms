<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Attendance;
use App\SmsApi;
use App\MsgTemplate;
use Auth;

class sendcheckinoutsms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendcheckinoutsms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This commands send an sms fo the checked in or out students every 10 minutes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //ATG username, apikey, sender_id
        $apidetails = SmsApi::where('school_id', '=', '1')->first();
        $msgtemplate = MsgTemplate::where('school_id', '=', '1')->first();

        $tosendsms = Attendance::join('USERINFO', 'CHECKINOUT.USERID', '=', 'USERINFO.USERID')
                            ->select('CHECKINOUT.ID', 'CHECKINOUT.USERID', 'CHECKINOUT.CHECKTIME', 'CHECKINOUT.CHECKTYPE', 'USERINFO.OPHONE', 'USERINFO.NAME', 'USERINFO.GENDER', 'USERINFO.Admno')
                            ->where('CHECKINOUT.SentSMS', '!=', '1')
                            ->get();
        $count = $tosendsms->count();        
    
        if ($count > 0)
        {
            $atgusername   = $apidetails->atgusername;
            $atgapikey     = $apidetails->atgapikey;
            // $atgsender_id  = $apidetails->atgsender_id;

            foreach ($tosendsms as $item)
            {
                $id = $item['ID'];
                $phone = $item['OPHONE'];
                $checktype = $item['CHECKTYPE'];
                $name = $item['NAME'];
                $checktime = $item['CHECKTIME'];
                $gender = $item['GENDER'] == '0' ? 'son' : 'daughter';
                $admno = $item['Admno'];
                $variables = array('name' => $name, 'clockdatetime' => $checktime, 'gender' => $gender, 'admno' => $admno);

                if ($checktype == "I")
                {
                    $type = "checked in at";
                    $message = $msgtemplate->clockinmsg;
                    foreach($variables as $key => $value){
                        $message = str_replace('{'.$key.'}', $value, $message);
                    }
                }
                else if ($checktype == "O")
                {
                    $type = "checked out from";
                    $message = $msgtemplate->clockoutmsg;
                    foreach ($variables as $key => $value){
                        $message = str_replace('{'.$key.'}', $value, $message);
                    }
                }
                
                $recipients = "+". $phone;

                $from = $apidetails->atgsender_id;

                $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
                
                try 
                { 
                  $results = $gateway->sendMessage($recipients, $message, $from);
                            
                  foreach($results as $result) {

                    echo " Number: " .$result->number;
                    echo " Status: " .$result->status;
                    echo " StatusCode: " .$result->statusCode;
                    echo " MessageId: " .$result->messageId;
                    echo " Cost: "   .$result->cost."\n";
                  } 
                  
                }
                catch ( AfricasTalkingGatewayException $e )
                {
                  echo "Encountered an error while sending: ".$e->getMessage();
                }
                
                $checkinout = Attendance::find($id);
                $checkinout->SentSMS = '1';
                $checkinout->save();

            }
            return response()->json($count.' SMS sent successfully');
        }

        else 
        {
            return response()->json('No SMS to be sent');
        }
    }
    // public function handle()
    // {
    //     $students = Attendance::count();
    //     dd($students);
    // }
}
