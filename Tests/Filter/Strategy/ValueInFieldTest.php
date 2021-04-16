<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Filter\Strategy;

use PHPUnit\Framework\TestCase;
use Webfactory\VisibilityFilterBundle\Filter\Strategy\ValueInField;

class ValueInFieldTest extends TestCase
{
    /**
     * @test
     */
    public function givesCorrectSQLClauseForString(): void
    {
        $valueInField = new ValueInField('y');

        $sqlClause = $valueInField->getFilterSql('testAlias');

        static::assertEquals('testAlias = "y"', $sqlClause);
    }

    /**
     * @test
     */
    public function givesCorrectSQLClauseForInt(): void
    {
        $valueInField = new ValueInField(2);

        $sqlClause = $valueInField->getFilterSql('testAlias');

        static::assertEquals('testAlias = 2', $sqlClause);
    }

    /**
     * @test
     */
    public function givesCorrectSQLClauseForFalse(): void
    {
        $valueInField = new ValueInField(false);

        $sqlClause = $valueInField->getFilterSql('testAlias');

        static::assertEquals('testAlias = 0', $sqlClause);
    }

    /**
     * @test
     */
    public function givesCorrectSQLClauseForTrue(): void
    {
        $valueInField = new ValueInField(true);

        $sqlClause = $valueInField->getFilterSql('testAlias');

        static::assertEquals('testAlias = 1', $sqlClause);
    }
}
