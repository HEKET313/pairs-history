<?php

namespace App\Request;

use App\System\Request\IRequest;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GetDataRequest implements IRequest
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private string $pair = '';
    /**
     * @var DateTimeInterface|null
     * @Assert\Type("DateTimeInterface")
     */
    private ?DateTimeInterface $dateFrom = null;
    /**
     * @var DateTimeInterface|null
     * @Assert\Type("DateTimeInterface")
     */
    private ?DateTimeInterface $dateTo = null;

    /**
     * @return string
     */
    public function getPair(): string
    {
        return $this->pair;
    }

    /**
     * @param string $pair
     */
    public function setPair(string $pair): void
    {
        $this->pair = $pair;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateFrom(): ?DateTimeInterface
    {
        return $this->dateFrom;
    }

    /**
     * @param DateTimeInterface|null $dateFrom
     */
    public function setDateFrom(?DateTimeInterface $dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateTo(): ?DateTimeInterface
    {
        return $this->dateTo;
    }

    /**
     * @param DateTimeInterface|null $dateTo
     */
    public function setDateTo(?DateTimeInterface $dateTo): void
    {
        $this->dateTo = $dateTo;
    }
}
