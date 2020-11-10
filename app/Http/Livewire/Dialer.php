<?php

namespace App\Http\Livewire;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;
use Livewire\Component;

class Dialer extends Component
{
    public $phone_number = '+9779810019931';
    public $call_button_message = 'Call';
    public function render()
    {
        return view('livewire.dialer');
    }
    public function addNumber($number)
    {
        if(strlen($this->phone_number) <= 10){
            $this->phone_number .= $number;
        }
    }

    public function makePhoneCall()
    {
        $this->call_button_message = 'Dialing ...';
        try {
            $client = new Client(
                getenv('TWILIO_ACCOUNT_SID'),
                getenv('TWILIO_AUTH_TOKEN')
            );
            // call
            try{
                $client->account->calls->create(  
                    $this->phone_number,
                    getenv('TWILIO_NUMBER'),
                    array(
                        "url" => "http://demo.twilio.com/docs/voice.xml"
                    )
                );
                $this->call_button_message = 'Call Connected!';
            }catch(\Exception $e){
                $this->call_button_message = $e->getMessage();
            }
            //sms
            try{

                $client->messages->create(
                    // Where to send a text message (your cell phone?)
                    $this->phone_number,
                    array(
                        'from' => getenv('TWILIO_NUMBER'),
                        'body' => 'I sent this message in under 10 minutes!'
                    )
                );

            }catch(\Exception $e){
                $this->call_button_message = $e->getMessage();
            }




        } catch (ConfigurationException $e) {
            $this->call_button_message = $e->getMessage();
        }
    }
}
