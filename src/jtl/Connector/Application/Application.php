<?php
/**
 *
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\Application
 */
namespace jtl\Connector\Application;

use jtl\Core\Serializer\Json;

use \jtl\Core\Application\Application as CoreApplication;
use \jtl\Core\Exception\RpcException;
use \jtl\Core\Rpc\Handler;
use \jtl\Core\Rpc\Packet;
use \jtl\Core\Rpc\RequestPacket;
use \jtl\Core\Rpc\ResponsePacket;
use \jtl\Core\Rpc\Error;
use \jtl\Core\Http\Request;
use \jtl\Core\Http\Response;
use \jtl\Core\Authentication\Wawi as WawiAuthentication;
use \jtl\Core\Utilities\Config\Config;
use \jtl\Core\Utilities\Config\Loader\Json as ConfigJson;
use \jtl\Core\Utilities\Config\Loader\System as ConfigSystem;
use \jtl\Connector\Result\Action;
use \jtl\Core\Validator\Schema;
use \jtl\Core\Exception\SchemaException;
use \jtl\Core\Validator\ValidationException;
use \jtl\Core\Database\Sqlite3;
use \jtl\Connector\Session\Session;
use \jtl\Connector\Base\Connector;

/**
 * Application Class
 *
 * @access public
 * @author Daniel Böhmer <daniel.boehmer@jtl-software.de>
 */
class Application extends CoreApplication
{
    /**
     * List of connected EndpointConnectors
     *
     * @var multiple: IEndpointConnector
     */
    protected static $_connectors = array();
    
    /**
     * Global Session
     * 
     * @var \jtl\Connector\Session\Session
     */
    public static $session;

    /**
     * (non-PHPdoc)
     *
     * @see \jtl\Core\Application\Application::run()
     */
    public function run()
    {
        $jtlrpc = Request::handle();
        $sessionId = Request::getSession();
        $requestpackets = RequestPacket::build($jtlrpc);
                
        $rpcmode = is_object($requestpackets) ? Packet::SINGLE_MODE : Packet::BATCH_MODE;
                
        // Start Session
        $this->startSession($sessionId);
        
        // Creates the config instance
        $config = new Config(array(
            new ConfigJson(APP_DIR . '/../config/config.json'),
            new ConfigSystem()
        ));
        
        switch ($rpcmode) {
            case Packet::SINGLE_MODE:
                $this->runSingle($requestpackets, $config, $rpcmode);
                break;
            case Packet::BATCH_MODE:
                $this->runBatch($requestpackets, $config, $rpcmode);
                break;
        }
    }
    
    /**
     * Execute RPC Method
     * 
     * @param RequestPacket $requestpacket
     * @param Config $config
     * @param integer $rpcmode
     * @throws RpcException
     * @return \jtl\Core\Rpc\ResponsePacket
     */
    protected function execute(RequestPacket $requestpacket, Config $config, $rpcmode)
    {
        // Core Connector
        $coreconnector = Connector::getInstance();
        if ($coreconnector->canHandle($requestpacket->getMethod())) {
            $coreconnector->setConfig($config);
            $actionresult = $coreconnector->handle($requestpacket->getId(), $requestpacket->getMethod(), $requestpacket->getParams());
            if ($actionresult->isHandled()) {
                $responsepacket = $this->buildRpcResponse($requestpacket, $actionresult);
                
                if ($rpcmode == Packet::SINGLE_MODE) {
                    Response::send($responsepacket);
                }
                else {
                    return $responsepacket;
                }
            }
        }
        
        // Endpoint Connector
        foreach (self::$_connectors as $endpointconnector) {
            if ($endpointconnector->canHandle($requestpacket->getMethod())) {
                $endpointconnector->setConfig($config);
                $actionresult = $endpointconnector->handle($requestpacket->getId(), $requestpacket->getMethod(), $requestpacket->getParams());
                if (get_class($actionresult) == "jtl\\Connector\\Result\\Action") {
                    if ($actionresult->isHandled()) {
                        $responsepacket = $this->buildRpcResponse($requestpacket, $actionresult);
                        
                        if ($rpcmode == Packet::SINGLE_MODE) {
                            Response::send($responsepacket);
                        }
                        else {
                            return $responsepacket;
                        }
                    }
                }
                else {
                    throw new RpcException("Internal error", -32603);
                }
            }
        }
        
        throw new RpcException("Method not found", -32601);
    }

    /**
     *
     * @param IEndpointConnector $endpointconnector        
     */
    public static function register(IEndpointConnector $endpointconnector)
    {
        $classname = get_class($endpointconnector);
        if (!isset(self::$_connectors[$classname])) {
            self::$_connectors[$classname] = $endpointconnector;
        }
    }
    
    /**
     * Single Mode
     * 
     * @param ResponsePacket $requestpacket
     * @param Config $config
     * @param integer $rpcmode
     */
    protected function runSingle(RequestPacket $requestpacket, Config $config, $rpcmode)
    {
        $requestpacket->validate();
        $this->runActionValidation($requestpacket);
        
        try {
            $this->execute($requestpacket, $config, $rpcmode);
        }
        catch (RpcException $exc) {
            $error = new Error();
            $error->setCode($exc->getCode())
            ->setMessage($exc->getMessage());
        
            $responsepacket = new ResponsePacket();
            $responsepacket->setId($requestpacket->getId())
                ->setJtlrpc($requestpacket->getJtlrpc())
                ->setError($error);
        
            Response::send($responsepacket);
        }
    }
    
    /**
     * Batch Mode
     * 
     * @param array $requestpackets
     * @param Config $config
     * @param integer $rpcmode
     */
    protected function runBatch(array $requestpackets, Config $config, $rpcmode)
    {
        $jtlrpcreponses = array();
        
        foreach ($requestpackets as $requestpacket) {
            try {
                $requestpacket->validate();
                $this->runActionValidation($requestpacket);
                $jtlrpcreponses[] = $this->execute($requestpacket, $config, $rpcmode);
            }
            catch (RpcException $exc) {
                $error = new Error();
                $error->setCode($exc->getCode())
                ->setMessage($exc->getMessage());
        
                $responsepacket = new ResponsePacket();
                $responsepacket->setId($requestpacket->getId())
                ->setJtlrpc($requestpacket->getJtlrpc())
                ->setError($error);
        
                $jtlrpcreponses[] = $responsepacket;
            }
        }
        
        Response::sendAll($jtlrpcreponses);
    }

    /**
     * Build RPC Reponse Packet
     *
     * @param \jtl\Core\Rpc\ResponsePacket $requestpacket        
     * @param \jtl\Connector\Result\Action $actionresult        
     * @return \jtl\Core\Rpc\ResponsePacket
     * @throws \jtl\Core\Exception\RpcException
     */
    protected function buildRpcResponse(RequestPacket $requestpacket, Action $actionresult)
    {        
        $responsepacket = new ResponsePacket();
        $responsepacket->setId($requestpacket->getId())
            ->setJtlrpc($requestpacket->getJtlrpc())
            ->setResult($actionresult->getResult())
            ->setError($actionresult->getError());
        
        $responsepacket->validate();
        
        return $responsepacket;
    }
    
    /**
     * Validate Action
     * 
     * @param RequestPacket $requestpacket
     * @throws SchemaException
     */
    protected function runActionValidation(RequestPacket $requestpacket)
    {
        list ($controller, $action) = explode(".", $requestpacket->getMethod());
        
        try {
            Schema::validateAction(CONNECTOR_DIR . "schema/{$controller}/params/{$action}.json", $requestpacket->getParams());
        }
        catch (ValidationException $exc) {
            throw new SchemaException($exc->getMessage());
        }
    }
    
    /**
     * Starting Session
     * 
     * @throws \jtl\Core\Exception\DatabaseException
     */
    protected function startSession($sessionId = null)
    {
        if (self::$session === null) {
            $sqlite3 = Sqlite3::getInstance();
            $sqlite3->connect(array("location" => CONNECTOR_DIR . "db/connector.s3db"));
        
            self::$session = new Session($sqlite3, $sessionId);
        }
    }
}
?>