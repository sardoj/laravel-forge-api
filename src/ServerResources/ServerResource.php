<?php

namespace Laravel\Forge\ServerResources;

use ArrayAccess;
use Laravel\Forge\Server;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Laravel\Forge\Traits\ArrayAccessTrait;

abstract class ServerResource implements ArrayAccess
{
    use ArrayAccessTrait;

    /**
     * Associated server.
     *
     * @var \Laravel\Forge\Server
     */
    protected $server;

    /**
     * Create new resource instance.
     *
     * @param \Laravel\Forge\Server $server
     * @param array                 $data   = []
     */
    public function __construct(Server $server, array $data = [])
    {
        $this->server = $server;
        $this->data = $data;
    }

    /**
     * Resource type.
     *
     * @return string
     */
    abstract public static function resourceType();

    /**
     * Resource path (relative to Server URL).
     *
     * @return string
     */
    abstract public function resourcePath();

    /**
     * Create new resource instance from HTTP response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Laravel\Forge\Server               $server
     */
    public static function createFromResponse(ResponseInterface $response, Server $server)
    {
        $data = json_decode((string) $response->getBody(), true);
        $resource = static::resourceType();

        if (empty($data[$resource])) {
            throw new InvalidArgumentException('Given response is not a '.$resource.' response.');
        }

        return new static($server, $data[$resource]);
    }

    /**
     * Associated server.
     *
     * @return \Laravel\Forge\Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Resource API URL.
     *
     * @return string
     */
    public function apiUrl($path = '')
    {
        $path = ($path ? '/'.ltrim($path, '/') : '');

        return $this->server->apiUrl(
            $this->resourcePath().'/'.$this->id().$path
        );
    }

    /**
     * Resource ID.
     *
     * @return int
     */
    public function id(): int
    {
        return intval($this->data['id'] ?? 0);
    }

    /**
     * Delete current resource.
     *
     * @return bool
     */
    public function delete()
    {
        $this->server->getApi()->getClient()->request(
            'DELETE',
            $this->apiUrl()
        );

        return true;
    }
}