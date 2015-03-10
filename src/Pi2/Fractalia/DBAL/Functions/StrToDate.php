<?php

namespace Pi2\Fractalia\DBAL\Functions;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * DateFunction ::= "STR_TO_DATE" "(" ArithmeticPrimary ", " ArithmeticPrimary ")"
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class Date extends FunctionNode
{
    /** @var \Doctrine\ORM\Query\AST\SimpleArithmeticExpression */
    public $dateString = null;
    /** @var \Doctrine\ORM\Query\AST\SimpleArithmeticExpression */
    public $dateFormat = null;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->dateString = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->dateFormat = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'STR_TO_DATE(' .
            $this->dateString->dispatch($sqlWalker) . ', ' .
            $this->dateFormat->dispatch($sqlWalker) .
            ')';
    }

}
