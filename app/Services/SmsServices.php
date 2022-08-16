<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use GuzzleHttp\Exception\RequestException;

class SmsServices {

    public $owneremail = null;
    public $subacct = null;
    public $subacctpwd = null;
    public $sender = null;
    public $url = null;

   public function __construct(){

       $this->owneremail="emacy_245@yahoo.com";
       $this->subacct="Speedaf-Ex";
       $this->subacctpwd="speedaf@1";
       $this->sender="Speedaf-Ex"; /* sender id */ //SpeedafNG.com will be better
       $this->$url = "http://www.smslive247.com/http/index.aspx?". "cmd=sendquickmsg". "&owneremail=" . UrlEncode($owneremail) . "&subacct=" . UrlEncode($subacct)."&subacctpwd=" . UrlEncode($subacctpwd)."&sender=".urlencode($sender)."&sendto=".urlencode($sendto)."&msgtype=0". "&message=";

    }

    // public function test()
    // {
    //     try {

    //         $response =   Http::timeout(3)->get("smslive24w7.com");
    //         // echo $response->successful();
    //     }
    //     catch(RequestException $ex)
    //     {
    //         echo "Guzzle Http Exception";
    //     }
    //     catch(Exception $ex)
    //     {
    //         echo "Other Exception";
    //     }

    // }

    public function sendManifestDispatchedMessage($to, $message)
    {

        try {

            $url += UrlEncode($message);
            $response = Http::timeout(3)->get($url);
            // dd($response->successful());
            echo $response->successful();

            //ERR: 404: You do not have enough credits to complete the request
            // dd("we are here yes". $response->successful(), $url);
            // echo $url;

        } catch(RequestException $ex)
        {

            echo $ex->getMessage();
        }

    }

    public function sendMessage2($to, $message){
        $sendto= $to; /* destination number */
        $message="Your site just dispatched manifest manifest({$data['manifest_id']})  to  {$data['destination_site']}"; /* message to be sent */
       // dd($message);
        $url = "http://www.smslive247.com/http/index.aspx?". "cmd=sendquickmsg". "&owneremail=" . UrlEncode($owneremail) . "&subacct=" . UrlEncode($subacct)."&subacctpwd=" . UrlEncode($subacctpwd). "&message=" . UrlEncode($message)."&sender=".urlencode($sender)."&sendto=".urlencode($sendto)."&msgtype=0";

        $response = Http::timeout(3)->get($url);
        // dd($response->successful());
        echo $response->successful();
        // echo $url;
    }

    public function sendAcknowledgementOverDueMessage($to, $data)
    {
        // $owneremail="emacy_245@yahoo.com";
        // $subacct="Speedaf-Ex";
        // $subacctpwd="speedaf@1";
        // $sendto=$data['receiver']; /* destination number */
        // $sender="Speedaf-Ex"; /* sender id */ //SpeedafNG.com will be better
        // $message="OverDue Alert, \n {$data['overdue_manifest_count']} found!. \n Kindly Check Over Due Tab in SpeedafUtility app for details \n Thanks"; /* message to be sent */
        $url = "http://www.smslive247.com/http/index.aspx?". "cmd=sendquickmsg". "&owneremail=" . UrlEncode($owneremail) . "&subacct=" . UrlEncode($subacct)."&subacctpwd=" . UrlEncode($subacctpwd). "&message=" . UrlEncode($message)."&sender=".urlencode($sender)."&sendto=".urlencode($sendto)."&msgtype=0";
        $response = Http::get($url);
        echo $response->successful();
    }


    public function sendManifestAcknowledgementMessage($to, $data)
    {
            // $owneremail="emacy_245@yahoo.com";
            // $subacct="Speedaf-Ex";
            // $subacctpwd="speedaf@1";
            // $sendto=$data['receiver']; /* destination number */
            // $sender="Speedaf-Ex"; /* sender id */ //SpeedafNG.com will be better
            // $message="Acknowledgement Alert, \n Manifest ID ({$data['manifest_id']}) has just been Acknowledged. Thanks"; /* message to be sent */
            $url = "http://www.smslive247.com/http/index.aspx?". "cmd=sendquickmsg". "&owneremail=" . UrlEncode($owneremail) . "&subacct=" . UrlEncode($subacct)."&subacctpwd=" . UrlEncode($subacctpwd). "&message=" . UrlEncode($message)."&sender=".urlencode($sender)."&sendto=".urlencode($sendto)."&msgtype=0";
            $response = Http::timeout(3)->get($url);
            echo $response->successful();

    }

}

?>
