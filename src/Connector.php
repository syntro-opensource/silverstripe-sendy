<?php

namespace Syntro\SilverStripeSendy;

use SilverStripe\Core\Config\Configurable;

/**
 * Connector which allows the creation of a campaign
 *
 * @author Matthias Leutenegger
 */
class Connector
{
    use Configurable;

    /**
     * @config
     */
    private static $installation_url = null;

    /**
     * @config
     */
    private static $api_key = null;

    /**
     * @config
     */
    private static $brand_id = null;



    /**
     * __construct - constructor
     *
     * @return void
     */
    function __construct()
    {
        $config = static::config();
        // Check for required config options and fail if they are not provided
        if (!$config->get('installation_url')) {
            throw new \Exception("No Sendy instance provided", 1);
        }
        if (!$config->get('api_key')) {
            throw new \Exception("No API key provided", 1);
        }
        if (!$config->get('brand_id')) {
            throw new \Exception("No brand ID provided", 1);
        }
    }


    /**
     * createCampaign - create a campaign in the remote instance
     *
     * @param  array $body array with parameters
     * @return array
     */
    public function createCampaign($body)
    {

        $body['api_key'] = static::config()->get('api_key');
        $body['brand_id'] = static::config()->get('brand_id');

        $result = $this->buildAndSend('api/campaigns/create.php', $body);

        switch ($result) {
            case 'Campaign created':
                return [
                    'status' => true,
                    'message' => $result
                ];
            default:
                return [
                    'status' => false,
                    'message' => $result
                ];
        }
    }

    /**
     * buildAndSend - actually send the request
     *
     * @param  string $path where to post to
     * @param  array  $body what to post
     * @return string
     */
    private function buildAndSend($path, array $body)
    {
        //error checking
        if (empty($path)) {
            throw new \Exception("Required config parameter [path] is not set or empty", 1);
        }

        if (empty($body)) {
            throw new \Exception("Required config parameter [body] is not set or empty", 1);
        }

        //build a query using the $content
        $postdata = http_build_query($body);

        $ch = curl_init(static::config()->get('installation_url') . '/' . $path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
