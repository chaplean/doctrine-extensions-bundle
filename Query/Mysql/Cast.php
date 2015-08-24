<?php

namespace Chaplean\Bundle\DoctrineExtensionsBundle\Query\Mysql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Literal;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Cast.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class Cast extends FunctionNode
{
    const PARAMETER_KEY = 'expression';
    const TYPE_KEY = 'type';

    /**
     * @var array
     */
    protected $supportedTypes = array(
        'char',
        'string',
        'text',
        'date',
        'datetime',
        'time',
        'int',
        'integer',
        'decimal'
    );

    /**
     * @var array
     */
    private $expr = array();

    /**
     * @param \Doctrine\ORM\Query\Parser $parser
     *
     * @return void
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->expr[self::PARAMETER_KEY] = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_AS);
        $parser->match(Lexer::T_IDENTIFIER);

        $lexer = $parser->getLexer();
        $type = $lexer->token['value'];

        if ($lexer->isNextToken(Lexer::T_OPEN_PARENTHESIS)) {
            $parser->match(Lexer::T_OPEN_PARENTHESIS);

            /** @var Literal $parameter */
            $parameter = $parser->Literal();
            $parameters = array(
                $parameter->value
            );

            if ($lexer->isNextToken(Lexer::T_COMMA)) {
                while ($lexer->isNextToken(Lexer::T_COMMA)) {
                    $parser->match(Lexer::T_COMMA);
                    $parameter = $parser->Literal();
                    $parameters[] = $parameter->value;
                }
            }

            $parser->match(Lexer::T_CLOSE_PARENTHESIS);
            $type .= '(' . implode(', ', $parameters) . ')';
        }

        if (!$this->checkType($type)) {
            $parser->syntaxError(
                sprintf(
                    'Type unsupported. Supported types are: "%s"',
                    implode(', ', $this->supportedTypes)
                ),
                $lexer->token
            );
        }

        $this->expr[self::TYPE_KEY] = $type;
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * Check that given type is supported.
     *
     * @param string $type
     * @return bool
     */
    protected function checkType($type)
    {
        $type = strtolower(trim($type));

        foreach ($this->supportedTypes as $supportedType) {
            if (strpos($type, $supportedType) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     *
     * @return string
     * @throws \Doctrine\ORM\Query\AST\ASTException
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        /** @var Node $value */
        $value = $this->expr[self::PARAMETER_KEY];
        $type  = $this->expr[self::TYPE_KEY];
        $type = strtolower($type);

        if ($type == 'char') {
            $type = 'char(1)';
        } elseif ($type == 'string' || $type == 'text') {
            $type = 'char';
        } elseif ($type == 'int' || $type == 'integer') {
            $type = 'signed';
        }

        return sprintf('CAST(%s AS %s)', $value->dispatch($sqlWalker), $type);
    }
}
