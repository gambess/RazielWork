<?php

namespace Pi2\Fractalia\SGSDWebMonitorBundle\DBAL;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * DateOfWeekFunction ::= "DAYOFWEEK" "(" ArithmeticPrimary ")"
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class DayOfWeek extends FunctionNode
{
    public $dateExpression;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->dateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'DAYOFWEEK(' .
            $this->dateExpression->dispatch($sqlWalker) .
            ')';
    }

}
