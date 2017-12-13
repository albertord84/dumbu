<?php

namespace Clue\React\Socks;

use React\Promise;
use React\Promise\PromiseInterface;
use React\Promise\Deferred;
use React\Socket\ConnectionInterface;
use React\Socket\ConnectorInterface;
use \Exception;
use \InvalidArgumentException;
use RuntimeException;

class Client implements ConnectorInterface
{
    /**
     *
     * @var ConnectorInterface
     */
    private $connector;

    private $socksUri;

    private $protocolVersion = null;

    private $auth = null;

    public function __construct($socksUri, ConnectorInterface $connector)
    {
        // assume default scheme if none is given
        if (strpos($socksUri, '://') === false) {
            $socksUri = 'socks://' . $socksUri;
        }

        // parse URI into individual parts
        $parts = parse_url($socksUri);
        if (!$parts || !isset($parts['scheme'], $parts['host'])) {
            throw new \InvalidArgumentException('Invalid SOCKS server URI "' . $socksUri . '"');
        }

        // assume default port
        if (!isset($parts['port'])) {
            $parts['port'] = 1080;
        }

        // user or password in URI => SOCKS5 authentication
        if (isset($parts['user']) || isset($parts['pass'])) {
            if ($parts['scheme'] === 'socks') {
                // default to using SOCKS5 if not given explicitly
                $parts['scheme'] = 'socks5';
            } elseif ($parts['scheme'] !== 'socks5') {
                // fail if any other protocol version given explicitly
                throw new InvalidArgumentException('Authentication requires SOCKS5. Consider using protocol version 5 or waive authentication');
            }
            $parts += array('user' => '', 'pass' => '');
            $this->setAuth(rawurldecode($parts['user']), rawurldecode($parts['pass']));
        }

        // check for valid protocol version from URI scheme
        $this->setProtocolVersionFromScheme($parts['scheme']);

        $this->socksUri = $parts['host'] . ':' . $parts['port'];
        $this->connector = $connector;
    }

    private function setProtocolVersionFromScheme($scheme)
    {
        if ($scheme === 'socks' || $scheme === 'socks4a') {
            $this->protocolVersion = '4a';
        } elseif ($scheme === 'socks5') {
            $this->protocolVersion = '5';
        } elseif ($scheme === 'socks4') {
            $this->protocolVersion = '4';
        } else {
            throw new InvalidArgumentException('Invalid protocol version given');
        }
    }

    /**
     * set login data for username/password authentication method (RFC1929)
     *
     * @param string $username
     * @param string $password
     * @link http://tools.ietf.org/html/rfc1929
     */
    private function setAuth($username, $password)
    {
        if (strlen($username) > 255 || strlen($password) > 255) {
            throw new InvalidArgumentException('Both username and password MUST NOT exceed a length of 255 bytes each');
        }
        $this->auth = pack('C2', 0x01, strlen($username)) . $username . pack('C', strlen($password)) . $password;
    }

    /**
     * Establish a TCP/IP connection to the given target URI through the SOCKS server
     *
     * Many higher-level networking protocols build on top of TCP. It you're dealing
     * with one such client implementation,  it probably uses/accepts an instance
     * implementing React's `ConnectorInterface` (and usually its default `Connector`
     * instance). In this case you can also pass this `Connector` instance instead
     * to make this client implementation SOCKS-aware. That's it.
     *
     * @param string $uri
     * @return PromiseInterface Promise<ConnectionInterface,Exception>
     */
    public function connect($uri)
    {
        if (strpos($uri, '://') === false) {
            $uri = 'tcp://' . $uri;
        }

        $parts = parse_url($uri);
        if (!$parts || !isset($parts['scheme'], $parts['host'], $parts['port']) || $parts['scheme'] !== 'tcp') {
            return Promise\reject(new InvalidArgumentException('Invalid target URI specified'));
        }

        $host = trim($parts['host'], '[]');
        $port = $parts['port'];

        if ($this->protocolVersion === '4' && false === filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return Promise\reject(new InvalidArgumentException('Requires an IPv4 address for SOCKS4'));
        }

        if (strlen($host) > 255 || $port > 65535 || $port < 0 || (string)$port !== (string)(int)$port) {
            return Promise\reject(new InvalidArgumentException('Invalid target specified'));
        }

        // construct URI to SOCKS server to connect to
        $socksUri = $this->socksUri;

        // append path from URI if given
        if (isset($parts['path'])) {
            $socksUri .= $parts['path'];
        }

        // parse query args
        $args = array();
        if (isset($parts['query'])) {
            parse_str($parts['query'], $args);
        }

        // append hostname from URI to query string unless explicitly given
        if (!isset($args['hostname'])) {
            $args['hostname'] = $host;
        }

        // append query string
        $socksUri .= '?' . http_build_query($args, '', '&');

        // append fragment from URI if given
        if (isset($parts['fragment'])) {
            $socksUri .= '#' . $parts['fragment'];
        }

        $that = $this;

        // start TCP/IP connection to SOCKS server and then
        // handle SOCKS protocol once connection is ready
        // resolve plain connection once SOCKS protocol is completed
        return $this->connector->connect($socksUri)->then(
            function (ConnectionInterface $stream) use ($that, $host, $port) {
                return $that->handleConnectedSocks($stream, $host, $port);
            },
            function (Exception $e) {
                throw new RuntimeException('Unable to connect to proxy (ECONNREFUSED)', defined('SOCKET_ECONNREFUSED') ? SOCKET_ECONNREFUSED : 111, $e);
            }
        );
    }

    /**
     * Internal helper used to handle the communication with the SOCKS server
     *
     * @param ConnectionInterface $stream
     * @param string              $host
     * @param int                 $port
     * @return Promise Promise<ConnectionInterface, Exception>
     * @internal
     */
    public function handleConnectedSocks(ConnectionInterface $stream, $host, $port)
    {
        $deferred = new Deferred(function ($_, $reject) {
            $reject(new RuntimeException('Connection canceled while establishing SOCKS session (ECONNABORTED)', defined('SOCKET_ECONNABORTED') ? SOCKET_ECONNABORTED : 103));
        });

        $reader = new StreamReader();
        $stream->on('data', array($reader, 'write'));

        $stream->on('error', $onError = function (Exception $e) use ($deferred) {
            $deferred->reject(new RuntimeException('Stream error while waiting for response from proxy (EIO)', defined('SOCKET_EIO') ? SOCKET_EIO : 5, $e));
        });

        $stream->on('close', $onClose = function () use ($deferred) {
            $deferred->reject(new RuntimeException('Connection to proxy lost while waiting for response (ECONNRESET)', defined('SOCKET_ECONNRESET') ? SOCKET_ECONNRESET : 104));
        });

        if ($this->protocolVersion === '5') {
            $promise = $this->handleSocks5($stream, $host, $port, $reader);
        } else {
            $promise = $this->handleSocks4($stream, $host, $port, $reader);
        }
        $promise->then(function () use ($deferred, $stream) {
            $deferred->resolve($stream);
        }, function (Exception $error) use ($deferred) {
            // pass custom RuntimeException through as-is, otherwise wrap in protocol error
            if (!$error instanceof RuntimeException) {
                $error = new RuntimeException('Invalid response received from proxy (EBADMSG)', defined('SOCKET_EBADMSG') ? SOCKET_EBADMSG: 71, $error);
            }

            $deferred->reject($error);
        });

        return $deferred->promise()->then(
            function (ConnectionInterface $stream) use ($reader, $onError, $onClose) {
                $stream->removeListener('data', array($reader, 'write'));
                $stream->removeListener('error', $onError);
                $stream->removeListener('close', $onClose);

                return $stream;
            },
            function ($error) use ($stream, $onClose) {
                $stream->removeListener('close', $onClose);
                $stream->close();

                throw $error;
            }
        );
    }

    private function handleSocks4(ConnectionInterface $stream, $host, $port, StreamReader $reader)
    {
        // do not resolve hostname. only try to convert to IP
        $ip = ip2long($host);

        // send IP or (0.0.0.1) if invalid
        $data = pack('C2nNC', 0x04, 0x01, $port, $ip === false ? 1 : $ip, 0x00);

        if ($ip === false) {
            // host is not a valid IP => send along hostname (SOCKS4a)
            $data .= $host . pack('C', 0x00);
        }

        $stream->write($data);

        return $reader->readBinary(array(
            'null'   => 'C',
            'status' => 'C',
            'port'   => 'n',
            'ip'     => 'N'
        ))->then(function ($data) {
            if ($data['null'] !== 0x00) {
                throw new Exception('Invalid SOCKS response');
            }
            if ($data['status'] !== 0x5a) {
                throw new RuntimeException('Proxy refused connection with SOCKS error code ' . sprintf('0x%02X', $data['status']) . ' (ECONNREFUSED)', defined('SOCKET_ECONNREFUSED') ? SOCKET_ECONNREFUSED : 111);
            }
        });
    }

    private function handleSocks5(ConnectionInterface $stream, $host, $port, StreamReader $reader)
    {
        // protocol version 5
        $data = pack('C', 0x05);

        $auth = $this->auth;
        if ($auth === null) {
            // one method, no authentication
            $data .= pack('C2', 0x01, 0x00);
        } else {
            // two methods, username/password and no authentication
            $data .= pack('C3', 0x02, 0x02, 0x00);
        }
        $stream->write($data);

        $that = $this;

        return $reader->readBinary(array(
            'version' => 'C',
            'method'  => 'C'
        ))->then(function ($data) use ($auth, $stream, $reader) {
            if ($data['version'] !== 0x05) {
                throw new Exception('Version/Protocol mismatch');
            }

            if ($data['method'] === 0x02 && $auth !== null) {
                // username/password authentication requested and provided
                $stream->write($auth);

                return $reader->readBinary(array(
                    'version' => 'C',
                    'status'  => 'C'
                ))->then(function ($data) {
                    if ($data['version'] !== 0x01 || $data['status'] !== 0x00) {
                        throw new RuntimeException('Username/Password authentication failed (EACCES)', defined('SOCKET_EACCES') ? SOCKET_EACCES : 13);
                    }
                });
            } else if ($data['method'] !== 0x00) {
                // any other method than "no authentication"
                throw new RuntimeException('No acceptable authentication method found (EACCES)', defined('SOCKET_EACCES') ? SOCKET_EACCES : 13);
            }
        })->then(function () use ($stream, $reader, $host, $port) {
            // do not resolve hostname. only try to convert to (binary/packed) IP
            $ip = @inet_pton($host);

            $data = pack('C3', 0x05, 0x01, 0x00);
            if ($ip === false) {
                // not an IP, send as hostname
                $data .= pack('C2', 0x03, strlen($host)) . $host;
            } else {
                // send as IPv4 / IPv6
                $data .= pack('C', (strpos($host, ':') === false) ? 0x01 : 0x04) . $ip;
            }
            $data .= pack('n', $port);

            $stream->write($data);

            return $reader->readBinary(array(
                'version' => 'C',
                'status'  => 'C',
                'null'    => 'C',
                'type'    => 'C'
            ));
        })->then(function ($data) use ($reader) {
            if ($data['version'] !== 0x05 || $data['null'] !== 0x00) {
                throw new Exception('Invalid SOCKS response');
            }
            if ($data['status'] !== 0x00) {
                // map limited list of SOCKS error codes to common socket error conditions
                // @link https://tools.ietf.org/html/rfc1928#section-6
                if ($data['status'] === Server::ERROR_GENERAL) {
                    throw new RuntimeException('SOCKS server reported a general server failure (ECONNREFUSED)', defined('SOCKET_ECONNREFUSED') ? SOCKET_ECONNREFUSED : 111);
                } elseif ($data['status'] === Server::ERROR_NOT_ALLOWED_BY_RULESET) {
                    throw new RuntimeException('SOCKS server reported connection is not allowed by ruleset (EACCES)', defined('SOCKET_EACCES') ? SOCKET_EACCES : 13);
                } elseif ($data['status'] === Server::ERROR_NETWORK_UNREACHABLE) {
                    throw new RuntimeException('SOCKS server reported network unreachable (ENETUNREACH)', defined('SOCKET_ENETUNREACH') ? SOCKET_ENETUNREACH : 101);
                } elseif ($data['status'] === Server::ERROR_HOST_UNREACHABLE) {
                    throw new RuntimeException('SOCKS server reported host unreachable (EHOSTUNREACH)', defined('SOCKET_EHOSTUNREACH') ? SOCKET_EHOSTUNREACH : 113);
                } elseif ($data['status'] === Server::ERROR_CONNECTION_REFUSED) {
                    throw new RuntimeException('SOCKS server reported connection refused (ECONNREFUSED)', defined('SOCKET_ECONNREFUSED') ? SOCKET_ECONNREFUSED : 111);
                } elseif ($data['status'] === Server::ERROR_TTL) {
                    throw new RuntimeException('SOCKS server reported TTL/timeout expired (ETIMEDOUT)', defined('SOCKET_ETIMEDOUT') ? SOCKET_ETIMEDOUT : 110);
                } elseif ($data['status'] === Server::ERROR_COMMAND_UNSUPPORTED) {
                    throw new RuntimeException('SOCKS server does not support the CONNECT command (EPROTO)', defined('SOCKET_EPROTO') ? SOCKET_EPROTO : 71);
                } elseif ($data['status'] === Server::ERROR_ADDRESS_UNSUPPORTED) {
                    throw new RuntimeException('SOCKS server does not support this address type (EPROTO)', defined('SOCKET_EPROTO') ? SOCKET_EPROTO : 71);
                }

                throw new RuntimeException('SOCKS server reported an unassigned error code ' . sprintf('0x%02X', $data['status']) . ' (ECONNREFUSED)', defined('SOCKET_ECONNREFUSED') ? SOCKET_ECONNREFUSED : 111);
            }
            if ($data['type'] === 0x01) {
                // IPv4 address => skip IP and port
                return $reader->readLength(6);
            } elseif ($data['type'] === 0x03) {
                // domain name => read domain name length
                return $reader->readBinary(array(
                    'length' => 'C'
                ))->then(function ($data) use ($reader) {
                    // skip domain name and port
                    return $reader->readLength($data['length'] + 2);
                });
            } elseif ($data['type'] === 0x04) {
                // IPv6 address => skip IP and port
                return $reader->readLength(18);
            } else {
                throw new Exception('Invalid SOCKS reponse: Invalid address type');
            }
        });
    }
}
