<?php

namespace Numesia\NuEvent;
use GuzzleHttp\Client as HttpClient;

class NuEvent
{
    public function __construct()
    {
        // Dynamically create all components emit methods
        foreach (config('nuevent.components') as $componentName => $url) {
            $methodName = "emit".ucfirst(strtolower($componentName));
            $this->$methodName = function($eventName, $eventData) use($componentName){
                return $this->emit($componentName, $eventName, $eventData);
            };
        }
    }

    /**

     */

    /**
     * Dispatch event to selected components
     *
     * @param      <string>  $eventName   The event name
     * @param      <string>  $eventData   The event data
     * @param      <mixed>   $components  The components
     */
    public function dispatch($eventName, $eventData, $components = null)
    {
        if (!$components) {
            return $this->broadcast($eventName, $eventData);
        }

        if (!is_array($components)) {
            return $this->emit($components, $eventName, $eventData);
        }

        $response = [];

        foreach($components as $component) {
            $response[$component] = $this->emit($component, $eventName, $eventData);
        }

        return $response;
    }

    /**
     * Broadcast event to all components
     *
     * @param      <string>  $eventName   The event name
     * @param      <string>  $eventData   The event data
     */
    public function broadcast($eventName, $eventData)
    {
        $response = [];

        foreach(config('nuevent.components') as $componentName => $url) {
            $response[$componentName] = $this->emit($componentName, $eventName, $eventData);
        }

        return $response;
    }

    /*

     */

    /**
     * Emit event to component name
     *
     * @param      <string>  $componentName  The component name
     * @param      <string>  $eventName      The event name
     * @param      <string>  $eventData      The event data
     */
    public function emit($componentName, $eventName, $eventData)
    {
        $componentName = strtolower($componentName);

        $data = [
            'name' => $eventName,
            'data' => $eventData,
        ];
        $url = config('nuevent.components.' . $componentName);
        $url = ends_with($url, '/') ? $url : $url.'/';
        $url .= '__nuevent';

        return $this->request($url, $data);
    }

    /**
     * Send an HTTP Request
     *
     * @param      <type>  $url    The url
     * @param      <type>  $data   The data
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    protected function request($url, $params)
    {
        $params['nuEventToken'] = env('NUEVENT_TOKEN');

        $client = new HttpClient;

        $data = [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type'     => 'application/x-www-form-urlencoded',
            ],
            'form_params' => $params,
            'verify' => false
        ];

        try {
            $response = $client->request('POST', $url, $data);
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            \Log::info(["NuEvent Error in " . __FILE__ => [
                'url' => $url,
                'params' => $params
            ]]);
        }
    }
}
