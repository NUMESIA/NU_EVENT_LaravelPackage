<?php

namespace Numesia\NuEvent;

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
     * Dispache event to selected components
     *
     * @param      <string>  $eventName   The event name
     * @param      <string>  $eventData   The event data
     * @param      <mixed>   $components  The components
     */
    public function dispache($eventName, $eventData, $components = null)
    {
        if (!$components) {
            return $this->broadcast($eventName, $eventData);
        }

        if (!is_array($components)) {
            return $this->emit($components, $eventName, $eventData);
        }

        foreach($components as $component) {
            $this->emit($component, $eventName, $eventData);
        }
    }

    /**
     * Broadcast event to all components
     *
     * @param      <string>  $eventName   The event name
     * @param      <string>  $eventData   The event data
     */
    public function broadcast($eventName, $eventData)
    {
        foreach(config('nuevent.components') as $componentName => $url) {
            $this->emit($componentName, $eventName, $eventData);
        }
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

        $this->request(config('nuevent.components.' . $componentName), $data);
    }

    /**
     * Send an HTTP Request
     *
     * @param      <type>  $url    The url
     * @param      <type>  $data   The data
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    protected function request($url, $data)
    {
        $data['nuEventToken'] = env('NUEVENT_TOKEN');

        $client = new HttpClient;

        $data['headers'] = [
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type'     => 'application/x-www-form-urlencoded',
        ];

        try {
            $response = $client->request('POST', $url, $data);
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            $response = $e->getResponse();
            return $response->getBody();
        }
    }
}
