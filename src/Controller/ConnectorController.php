<?php
/**
 *
 * @copyright 2010-2013 JTL-Software GmbH
 * @package Jtl\Connector\Core\Application
 */

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Application\Application;
use Jtl\Connector\Core\Connector\ConnectorInterface;
use Jtl\Connector\Core\Definition\Model;
use Jtl\Connector\Core\Exception\ApplicationException;
use Jtl\Connector\Core\Exception\AuthenticationException;
use Jtl\Connector\Core\Exception\DefinitionException;
use Jtl\Connector\Core\Exception\MissingRequirementException;
use Jtl\Connector\Core\Model\Ack;
use Jtl\Connector\Core\Model\Authentication;
use Jtl\Connector\Core\Model\ConnectorIdentification;
use Jtl\Connector\Core\Model\ConnectorServerInfo;
use Jtl\Connector\Core\Model\Features;
use Jtl\Connector\Core\Model\Session;
use Jtl\Connector\Core\Serializer\Json;
use Jtl\Connector\Core\System\Check;
use Jtl\Connector\Core\Logger\Logger;
use Jtl\Connector\Core\Linker\ChecksumLinker;
use Jtl\Connector\Core\Checksum\ChecksumInterface;
use Jtl\Connector\Core\Utilities\Str;

/**
 * Base Config Controller
 *
 * @access public
 */
class ConnectorController extends AbstractController
{
    /**
     * @param null $params
     * @return bool
     * @throws MissingRequirementException
     */
    public function init($params = null)
    {
        Check::run();
        return true;
    }

    /**
     * @param null $params
     * @return Features
     */
    public function features($params = null)
    {
        $featureData = $this->readFeaturesData();
        $features = Json::decode($featureData, true);

        $entities = [];
        if (isset($features['entities']) && is_array($features['entities'])) {
            $entities = $features['entities'];
        }

        $flags = [];
        if (isset($features['flags']) && is_array($features['flags'])) {
            $flags = $features['flags'];
        }

        return Features::create($entities, $flags);
    }

    /**
     * @return false|string
     */
    protected function readFeaturesData()
    {
        return file_get_contents(CONNECTOR_DIR . '/config/features.json');
    }

    /**
     * @param Ack $ack
     * @return bool
     * @throws DefinitionException
     * @throws \ReflectionException
     */
    public function ack(Ack $ack): bool
    {
        foreach ($ack->getIdentities() as $modelName => $identities) {
            $normalizedName = Str::toPascalCase($modelName);
            if (!Model::isModel($normalizedName)) {
                Logger::write(sprintf(
                    'ACK: Unknown core entity (%s)! Skipping related ack\'s...',
                    $normalizedName
                ), Logger::WARNING);
                continue;
            }

            foreach ($identities as $identity) {
                $this->application->getLinker()->save($identity->getEndpoint(), $identity->getHost(), $normalizedName);
            }
        }

        if (ChecksumLinker::checksumLoaderExists()) {
            // Checksum linking
            foreach ($ack->getChecksums() as $checksum) {
                if ($checksum instanceof ChecksumInterface) {
                    if (!ChecksumLinker::save($checksum)) {
                        Logger::write(sprintf(
                            'Could not save checksum for endpoint (%s), host (%s) and type (%s)',
                            $checksum->getForeignKey()->getEndpoint(),
                            $checksum->getForeignKey()->getHost(),
                            $checksum->getType()
                        ), Logger::WARNING, Logger::CHANNEL_CHECKSUM);
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param Authentication $auth
     * @return Session
     * @throws ApplicationException
     * @throws AuthenticationException
     */
    public function auth(Authentication $auth)
    {
        if (empty($auth->getToken())) {
            throw AuthenticationException::tokenMissing();
        }

        $tokenValidator = $this->application->getEndpointConnector()->getTokenValidator();
        if ($tokenValidator->validate($auth->getToken()) === false) {
            Logger::write(sprintf("Unauthorized access with token (%s) from ip (%s)", $auth->getToken(), $_SERVER['REMOTE_ADDR']), Logger::WARNING);
            throw AuthenticationException::failed();
        }

        if ($this->application->getSessionHandler() === null) {
            Logger::write('Could not get any Session', Logger::ERROR);
            throw ApplicationException::noSession();
        }

        return (new Session())
            ->setSessionId(session_id())
            ->setLifetime((int)ini_get('session.gc_maxlifetime'))
        ;
    }

    /**
     * @param ConnectorInterface $endpointConnector
     * @return ConnectorIdentification
     */
    public function identify(ConnectorInterface $endpointConnector): ConnectorIdentification
    {
        $returnBytes = function ($data): int {
            $data = trim($data);
            $len = strlen($data);
            $value = substr($data, 0, $len - 1);
            $unit = strtolower(substr($data, $len - 1));
            switch ($unit) {
                case 'g':
                    $value *= 1024;
                    break;
                case 'k':
                    $value /= 1024;
                    break;
            }
            return (int)round($value);
        };

        $serverInfo = (new ConnectorServerInfo())
            ->setMemoryLimit($returnBytes(ini_get('memory_limit')))
            ->setExecutionTime((int)ini_get('max_execution_time'))
            ->setPostMaxSize($returnBytes(ini_get('post_max_size')))
            ->setUploadMaxFilesize($returnBytes(ini_get('upload_max_filesize')));

        $connector = (new ConnectorIdentification())
            ->setEndpointVersion($endpointConnector->getEndpointVersion())
            ->setPlatformName($endpointConnector->getPlatformName())
            ->setPlatformVersion($endpointConnector->getPlatformVersion())
            ->setProtocolVersion(Application::PROTOCOL_VERSION)
            ->setServerInfo($serverInfo);

        return $connector;
    }

    /**
     * @return bool
     */
    public function finish(): bool
    {
        return true;
    }

    /**
     * @param null $params
     * @return bool
     */
    public function clear($params = null)
    {
        //TODO: set type in clear method
        return $this->application->getLinker()->clear();
    }
}