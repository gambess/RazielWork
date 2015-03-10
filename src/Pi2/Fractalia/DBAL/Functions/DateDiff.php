<?php

namespace Pi2\Fractalia\DBAL\Functions;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * DateDiffFunction ::= "DATEDIFF" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class DateDiff extends FunctionNode
{
    public $firstDateExpression;
    public $secondDateExpression;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->firstDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->secondDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'DATEDIFF(' .
            $this->firstDateExpression->dispatch($sqlWalker) . ', ' .
            $this->secondDateExpression->dispatch($sqlWalker) .
            ')';
    }

}
