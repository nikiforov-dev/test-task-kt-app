<?php

namespace App\Filters;

use Doctrine\ORM\QueryBuilder;
use LogicException;

abstract class AbstractFilter implements FilterInterface
{
    protected const FORMAT_EXPRESSION_LIKE   = "%s.%s LIKE :%s";
    protected const FORMAT_EXPRESSION_EQUALS = '%s.%s = :%s';

    /**
     * @var string
     */
    protected string $variable = '';

    /**
     * @var string
     */
    protected string $entityVariableName = '';

    /**
     * {@inheritdoc}
     */
    public function isValidType(array $filterData): bool
    {
        if (is_array($filterData[$this->variable])) {
            foreach ($filterData[$this->variable] as $value) {
                if (!is_numeric($value)) {
                    return false;
                }
            }
        } elseif (!is_numeric($filterData[$this->variable]) && !is_string($filterData[$this->variable])) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(QueryBuilder $queryBuilder, string $expression, mixed $value, string $parameterName): void
    {
        $queryBuilder
            ->andWhere($expression)
            ->setParameter($parameterName, $value)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldApply(mixed $filterData): bool
    {
        return is_array($filterData) && array_key_exists($this->variable, $filterData);
    }

    /**
     * {@inheritdoc}
     */
    public function apply(QueryBuilder $queryBuilder, mixed $filterData): void
    {
        $entityAlias = $queryBuilder->getRootAliases()[0];

        if (!$this->shouldApply($filterData)) {
            return;
        }

        $this->checkValid($filterData);

        $value         = $this->getValue($filterData);
        $parameterName = $this->variable . 'Parameter';

        if (is_string($value)) {
            $format = self::FORMAT_EXPRESSION_LIKE;
        } else {
            $format = self::FORMAT_EXPRESSION_EQUALS;
        }

        $this->filter(
            $queryBuilder,
            sprintf($format, $entityAlias, $this->entityVariableName, $parameterName),
            $value,
            $parameterName,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function checkValid(array $filterData): void
    {
        if (!$this->isValidType($filterData)) {
            throw new LogicException('Wrong data type');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(mixed $filterData): mixed
    {
        return $filterData[$this->variable];
    }
}