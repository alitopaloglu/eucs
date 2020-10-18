<?php

namespace App\Entity;

use App\Interfaces\ProvidersInterface;
use App\Repository\Provider2Repository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @ORM\Entity(repositoryClass=Provider2Repository::class)
 */
class Provider2 implements ProvidersInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="integer")
     */
    private $estimated_duration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var HttpClientInterface|null
     */
    private $client;

    /**
     * Provider2 constructor.
     * @param HttpClientInterface|null $client
     */
    public function __construct(HttpClientInterface $client = null)
    {
        $this->client = $client;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return $this
     */
    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getEstimatedDuration(): ?int
    {
        return $this->estimated_duration;
    }

    /**
     * @param int $estimated_duration
     * @return $this
     */
    public function setEstimatedDuration(int $estimated_duration): self
    {
        $this->estimated_duration = $estimated_duration;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getData(string $url)
    {
        try {
            $response = $this->client->request(
                'GET',
                $url
            );
            return json_decode($response->getContent(),true);
        } catch (TransportExceptionInterface $e) {
            echo $e->getMessage();
        } catch (ClientExceptionInterface $e) {
            echo $e->getMessage();
        } catch (RedirectionExceptionInterface $e) {
            echo $e->getMessage();
        } catch (ServerExceptionInterface $e) {
            echo $e->getMessage();
        }
        return false;
    }

    /**
     *@inheritDoc
     */
    public function getFields()
    {
        return array(
            'name'                  => 'setName',
            'level'                 => 'setLevel',
            'estimated_duration'    => 'setEstimatedDuration'
        );
    }

    /**
     * @inheritDoc
     */
    public function prepareJob(array $job)
    {
        if(!empty($job))
        {
            foreach ($job as $name => $j)
            {
                return array(
                    'name' => $name,
                    'level' => $j['level'],
                    'estimated_duration' => $j['estimated_duration']
                );
            }
        }
        return $job;
    }
}
