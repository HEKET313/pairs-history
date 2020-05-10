<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PairData
 * @package App\Entity
 * @ORM\Entity()
 */
class PairData
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id = 0;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $dateTime = null;
    /**
     * @var float|int
     * @ORM\Column(type="float")
     */
    private float $price = 0;
    /**
     * @var Pair
     * @ORM\ManyToOne(targetEntity="App\Entity\Pair", inversedBy="data")
     * @ORM\JoinColumn(name="pair_id", referencedColumnName="id")
     */
    private ?Pair $pair = null;

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
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setDateTime(\DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return float|int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float|int $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return Pair
     */
    public function getPair(): Pair
    {
        return $this->pair;
    }

    /**
     * @param Pair $pair
     */
    public function setPair(Pair $pair): void
    {
        $this->pair = $pair;
    }
}
