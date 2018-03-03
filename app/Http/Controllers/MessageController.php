<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aloha\Twilio\Twilio;

/**
 * Class TwilioController
 * @package App\Http\Controllers
 */
class MessageController extends Controller
{

    protected $twilio;

    /**
     * TwilioController constructor.
     */
    public function __construct()
    {
        $this->twilio = new \Aloha\Twilio\Twilio(env("TWILIO_SID"), env("TWILIO_TOKEN"), env("TWILIO_FROM"));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        $this->validate($request, ['message' => 'required']);

        $message  = $request->input('message');
        $imageUrl = $request->input('imageUrl');
        $number = $request->input('number');

//        Twilio::message($number, $message);

        $this->sendMessage($number, $message, $imageUrl);

        return response()->json('message sent');
    }

    /**
     * @param $phoneNumber
     * @param $message
     * @param null $imageData
     */
    private function sendMessage($phoneNumber, $message, $imageData = null)
    {
        if($imageData !== null) {
            $imageUrl = $this->storeFile($imageData);

            $twilio = new \Aloha\Twilio\Twilio(env("TWILIO_SID"), env("TWILIO_TOKEN"), env("TWILIO_FROM"));

            $twilio->message($phoneNumber, $message, $imageUrl);

        } else {
            $twilio = new \Aloha\Twilio\Twilio(env("TWILIO_SID"), env("TWILIO_TOKEN"), env("TWILIO_FROM"));

            $twilio->message($phoneNumber, $message);
        }
    }


    public function storeFile($imageData)
    {

        if (preg_match('/data:image\/(gif|jpeg|png);base64,(.*)/i', $imageData, $matches)) {
            $imageType = $matches[1];
            $imageData = base64_decode($matches[2]);
            $image = imagecreatefromstring($imageData);
            $filename = md5($imageData) . '.png';

            if (imagepng($image, public_path().'/img/' . $filename)) {
                return 'http://192.35.252.40//img/' . $filename;
            } else {

                return response()->json("could not save the file");

            }
        } else {

            return response()->json("Invalid Data URL");

        }

    }

}
