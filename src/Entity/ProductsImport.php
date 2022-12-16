<?php

namespace App\Entity;

use DateTime;

class ProductsImport
{
    public const UNPROCESSED = 'UNPROCESSED';
    public const PROCESSING  = 'PROCESSING';
    public const FINISHED    = 'FINISHED';
    public const ERROR       = 'ERROR';

    /**
     * @var int|null
     */
    private ?int $id = null;

    /**
     * @var string
     */
    private string $importXmlFile;

    /**
     * @var string|null
     */
    private ?string $reportCsvFile = null;

    /**
     * @var string
     */
    private string $status = self::UNPROCESSED;

    /**
     * @var DateTime|null
     */
    private ?DateTime $createdAt = null;

    /**
     * @var string|null
     */
    private ?string $error = null;

    /**
     * @var int|null
     */
    private ?int $count = null;

    /**
     * @var int|null
     */
    private ?int $alreadyLoaded = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return $this
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getImportXmlFile(): string
    {
        return $this->importXmlFile;
    }

    /**
     * @param string $importXmlFile
     *
     * @return $this
     */
    public function setImportXmlFile(string $importXmlFile): self
    {
        $this->importXmlFile = $importXmlFile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReportCsvFile(): ?string
    {
        return $this->reportCsvFile;
    }

    /**
     * @param string|null $reportCsvFile
     *
     * @return $this
     */
    public function setReportCsvFile(?string $reportCsvFile): self
    {
        $this->reportCsvFile = $reportCsvFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return $this
     */
    public function prePersist(): self
    {
        $this->createdAt = new DateTime();

        return $this;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     *
     * @return $this
     */
    public function setError(?string $error): self
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @param int|null $count
     *
     * @return $this
     */
    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAlreadyLoaded(): ?int
    {
        return $this->alreadyLoaded;
    }

    /**
     * @param int|null $alreadyLoaded
     *
     * @return $this
     */
    public function setAlreadyLoaded(?int $alreadyLoaded): self
    {
        $this->alreadyLoaded = $alreadyLoaded;

        return $this;
    }
}