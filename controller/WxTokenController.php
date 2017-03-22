<?php
class WxTokenController{
    protected $ci;
    protected $logger;

    public function __construct(\Slim\Container $ci) {
       $this->ci = $ci;
       $this->logger = $this->ci->logger;
    }

    public function token($request, $response, $args) {
        $timestamp = $request->getAttribute('timestamp');
        $nonce = $request->getAttribute('nonce');
        $token='weixin';
        $signature = $request->getAttribute('signature');
        $array=array($timestamp,$nonce,$token);
        sort($array);
        $tmpstr=implode('', $array);
        $tmpstr=sha1($tmpstr);
        if($tmpstr==$signature){
          $echostr = $request->getAttribute('echostr');
          return $response->withJson($echostr);
        }
   }
}
