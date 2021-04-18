<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Filter\Strategy;

use PHPUnit\Framework\TestCase;
use Webfactory\VisibilityFilterBundle\Filter\Strategy\ValueInField;

class ValueInFieldTest extends TestCase
{
    /**
     * @test
     */
    public function gives_correct_SQL_clause_for_string(): void
    {
        $valueInField = new ValueInField('y');

        $sqlClause = $valueInField->getFilterSql('testAlias');

        static::assertEquals('testAlias = "y"', $sqlClause);
    }

    /**
     * @test
     */
    public function gives_correct_SQL_clause_for_int(): void
    {
        $valueInField = new ValueInField(2);

        $sqlClause = $valueInField->getFilterSql('testAlias');

        static::assertEquals('testAlias = 2', $sqlClause);
    }

    /**
     * @test
     */
    public function gives_correct_SQL_clause_for_false(): void
    {
        $valueInField = new ValueInField(false);

        $sqlClause = $valueInField->getFilterSql('testAlias');

        static::assertEquals('testAlias = 0', $sqlClause);
    }

    /**
     * @test
     */
    public function gives_correct_SQL_clause_for_true(): void
    {
        $valueInField = new ValueInField(true);

        $sqlClause = $valueInField->getFilterSql('testAlias');

        static::assertEquals('testAlias = 1', $sqlClause);
    }
}
