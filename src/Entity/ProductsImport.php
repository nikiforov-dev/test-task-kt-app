<?php

namespace App\Entity;

use DateTime;

class ProductsImport
{
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
     * @var bool|null
     */
    private ?bool $processed = null;

    /**
     * @var DateTime|null
     */
    private ?DateTime $createdAt = null;

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
     * @return bool|null
     */
    public function getProcessed(): ?bool
    {
        return $this->processed;
    }

    /**
     * @param bool|null $processed
     *
     * @return $this
     */
    public function setProcessed(?bool $processed): self
    {
        $this->processed = $processed;

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
}