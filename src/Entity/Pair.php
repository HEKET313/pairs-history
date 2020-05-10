<?php

namespace App\Entity;

use App\Event\Entity\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Pair
 * @package App\Entity
 * @ORM\Entity(repositoryClass="Doctrine\ORM\EntityRepository")
 */
class Pair
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id = 0;
    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private string $name = "";
    /**
     * @var PairData[]|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\PairData", mappedBy="pair")
     */
    private ?Collection $data = null;

    public function __construct()
    {
        $this->data = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return PairData[]|Collection
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param PairData[]|Collection $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }
}
