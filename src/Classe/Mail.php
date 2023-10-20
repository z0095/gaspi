<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = 'c0dda986c0c32c25e58b4f52110684ec';
    private $api_key_secret = '144bf550d401190593f2b284e67e8cb3';


    public function send($to_email,$to_name, $subject,$content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "mahoussein@hotmail.com",
                        'Name' => "AntiGaspillage"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' =>5201798,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables'=> [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() ;
}
}
