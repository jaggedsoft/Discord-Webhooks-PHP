<?php

namespace DiscordWebhooks;

class Client
{
    /*
     * The URL generated by Discord to receive webhooks
     * @var string $url The URL generated by Discord to receive webhooks
     */
     
    protected $url = null;
    
    /*
     * (Optional) The name that should be shown as the user sending the message
     * @var string $name The name that should be shown as the user sending the message
     */
     
    protected $name = null;
    
    /*
     * (Optional) The image to be used as the avatar for the user sending the message
     * @var string $avatar The image to be used as the avatar for the user sending the message
     */
     
    protected $avatar = null;
    
    /*
     * The message to be sent to Discord
     * @var string $message The message to be sent to Discord
     */
     
    protected $message = null;
    
    /*
     * Set up the class
     * @param string $url The URL generated by Discord to receive webhooks
     */
     
    public function __construct($url) {
        $this->url = $url;
    }
    
    /* 
     * (Optional) Set the name of the user sending the message; if not set, uses the name set in Discord
     * @param string $name The name that should be shown as the user sending the message
     */
     
    protected function name($param) {
        $this->name = $name;
    }
    
    /* 
     * (Optional) Set the avatar of the user sending the message; if not set, uses the avatar set in Discord
     * @param string $avatar The image to be used as the avatar for the user sending the message
     */
     
    public function avatar($avatar) {
        $this->avatar = $avatar;
    }
    
    /*
     * (Optional) Set the message to be sent; if not set, uses the message set in $this->send()
     * @param string $message The message to send through the webhook
     */
    
     public function message($message) {
         $this->message = $message;
     }
    
     /* 
     * Sends a message through the webhook
     * @param string $message The message to send through the webhook
     */
    
    public function send($message = '') {
        if (!empty($message)) {
            $this->message = $message;
        }
        
        $url = $this->url;
        
        $data = array(
            'content' => $this->message,
            'name' => $this->name,
            'avatar_url' => $this->avatar,
        );
        
        $data_string = json_encode($data);
        
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        
        $output = curl_exec($curl);
        $output = json_decode($output, true);
        
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
            throw new Exception($output['message']);
        }
        
        curl_close($curl);
        return true;
    }
}
