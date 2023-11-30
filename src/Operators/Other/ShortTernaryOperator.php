<?php

/**
 * This file is part of the Zephir.
 *
 * (c) Phalcon Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Zephir\Operators\Other;

use Zephir\Builder\Operators\UnaryOperatorBuilder;
use Zephir\Builder\Statements\IfStatementBuilder;
use Zephir\Builder\Statements\LetStatementBuilder;
use Zephir\Builder\StatementsBlockBuilder;
use Zephir\CompilationContext;
use Zephir\CompiledExpression;
use Zephir\Operators\AbstractOperator;
use Zephir\Statements\IfStatement;

/**
 * a ?: b
 *
 * Compiles short ternary expressions
 */
class ShortTernaryOperator extends AbstractOperator
{
    /**
     * Compile ternary operator.
     *
     * @param                    $expression
     * @param CompilationContext $compilationContext
     *
     * @return CompiledExpression
     */
    public function compile($expression, CompilationContext $compilationContext): CompiledExpression
    {
        $returnVariable = $this->processTenary($compilationContext, $expression);

        $ifBuilder = new IfStatementBuilder(
            new UnaryOperatorBuilder(
                'not',
                $expression['left']
            ),
            new StatementsBlockBuilder([
                /**
                 * Create an implicit 'let' operation to update the evaluated right operator
                 */
                new LetStatementBuilder([
                    'assign-type' => 'variable',
                    'variable'    => $returnVariable->getName(),
                    'operator'    => 'assign',
                    'expr'        => $expression['extra'],
                    'file'        => $expression['file'],
                    'line'        => $expression['line'],
                    'char'        => $expression['char'],
                ], $expression['extra']),
            ]),
            new StatementsBlockBuilder([
                /**
                 * Create an implicit 'let' operation to update the evaluated right operator
                 */
                new LetStatementBuilder([
                    'assign-type' => 'variable',
                    'variable'    => $returnVariable->getName(),
                    'operator'    => 'assign',
                    'expr'        => $expression['left'],
                    'file'        => $expression['file'],
                    'line'        => $expression['line'],
                    'char'        => $expression['char'],
                ], $expression['extra']),
            ])
        );

        $ifStatement = new IfStatement($ifBuilder->get());
        $ifStatement->compile($compilationContext);

        return new CompiledExpression('variable', $returnVariable->getName(), $expression);
    }
}
