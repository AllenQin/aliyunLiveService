<?php
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\Regions\Endpoint;
use Aliyun\Core\Regions\EndpointConfig;
use Aliyun\Core\Regions\EndpointProvider;
use Live\Request\V20161101\DescribeLiveStreamsPublishListRequest;
use Live\Request\V20161101\DescribeLiveMixConfigRequest;

/**
 * Class AliyunService
 */
class AliyunLiveService
{
    private static $instance;
    private static $client;

    private function __construct()
    {
        static::$client = new DefaultAcsClient($this->getProfile());
    }

    private function __clone()
    {

    }

    private function __wakeup()
    {

    }

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * get aliyun config
     *
     * @todo get data from config file
     * 
     * @return array
     */
    private function getConfig()
    {
        return [
            'access_key_id' => '',
            'access_key_secret' => '',
            'live_domain' => '',
            'app_name' => '',
            'end_point' => '',
            'record_origin_url' => '',
            'record_url' => '',
            'push_url' => '',
            'pull_rtmp_url' => '',
            'pull_hls_url' => '',
            'auth' => [
                'private_key' => '',
                'expire' => 300,
            ],
        ];
    }

    /**
     * get access profile
     *
     * @return DefaultProfile
     */
    private function getProfile()
    {
        $config = $this->getConfig();
        $endpointConfig = EndpointConfig::returnEndpointConfig();
        $endpoint = new Endpoint($config['end_point'], $endpointConfig['regionIds'], $endpointConfig['productDomains']);
        EndpointProvider::setEndpoints([$endpoint]);

        return DefaultProfile::getProfile($config['end_point'], $config['access_key_id'], $config['access_key_secret']);
    }

    /**
     * @return DefaultAcsClient
     */
    private function getClient()
    {
        return static::$client;
    }

    /**
     * @return aliyun appName
     */
    private function getAppName()
    {
        return $this->getConfig()['app_name'];
    }

    /**
     * @return aliyun live domain
     */
    private function getDomainName()
    {
        return $this->getConfig()['live_domain'];
    }

    /**
     * Get the publish history record
     *
     * @return mixed
     */
    public function getPublishHistory()
    {
        $request = new DescribeLiveStreamsPublishListRequest();
        $request->setActionName('DescribeLiveStreamsPublishList');
        $request->setDomainName($this->getDomainName());
        $request->setAppName($this->getAppName());
        $request->setStartTime(date('Y-m-d\TH:i:s\Z', time() - 86400 * 10));
        $request->setEndTime(date('Y-m-d\TH:i:s\Z', time()));
        $response = $this->getClient()->getAcsResponse($request);
        if ($response && $response = json_decode(json_encode($response), true)) {
            $data = [
                'RequestId' => $response['RequestId'],
                'LiveStreamPublishInfo' => $response['PublishInfo']['LiveStreamPublishInfo'],
            ];
            return $data;
        }
        return false;
    }

    /**
     * Get aliyun live mix config
     *
     * @return mixed
     */
    public function getLiveMixConfig()
    {
        $request = new DescribeLiveMixConfigRequest();
        $request->setActionName('DescribeLiveMixConfig');
        $request->setDomainName($this->getDomainName());
        $response = $this->getClient()->getAcsResponse($request);
        if ($response && $response = json_decode(json_encode($response), true)) {
            return $response;
        }
        return false;
    }

    // @todo other open api
}
