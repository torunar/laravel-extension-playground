<?php

namespace Tests\Unit\Schemas\ValidationSchema;

use App\Schemas\ValidationSchema\ValidationSchema;
use App\Schemas\ValidationSchema\RuleTypes\ClosureRuleType;
use App\Schemas\ValidationSchema\RuleTypes\RuleObjectRuleType;
use App\Schemas\ValidationSchema\UniqueRuleTypeInterface;
use App\Schemas\ValidationSchema\ValidationRule;
use Illuminate\Contracts\Validation\Rule;
use Tests\TestCase;

class TestRuleObject implements Rule
{
    public function passes($attribute, $value): bool
    {
        return false;
    }

    public function message(): string
    {
        return '';
    }
}

class TestRuleType1 implements UniqueRuleTypeInterface
{
    public function getNativeRepresentation(): string
    {
        return 'test1';
    }

    public function getOperation(): string
    {
        return $this->getNativeRepresentation();
    }
}

class TestRuleType2 implements UniqueRuleTypeInterface
{
    public function getNativeRepresentation(): string
    {
        return 'test2';
    }

    public function getOperation(): string
    {
        return $this->getNativeRepresentation();
    }
}

class ParameterizedTestRuleType1 implements UniqueRuleTypeInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getOperation(): string
    {
        return 'param1';
    }

    public function getNativeRepresentation()
    {
        return "param1:{$this->value}";
    }
}

class ValidationSchemaTest extends TestCase
{
    public function testClosure()
    {
        $validatorCallback = static function () { };

        $schema = (new ValidationSchema())
            ->addRule(new ValidationRule('field', new ClosureRuleType($validatorCallback)));

        $this->assertEquals(
            [
                'field' => [$validatorCallback],
            ],
            $schema->getNativeRulesRepresentation(),
        );
    }

    public function testRuleObject()
    {
        $ruleObject = new TestRuleObject();
        $schema = (new ValidationSchema())
            ->addRule(
                new ValidationRule('field', new RuleObjectRuleType($ruleObject))
            );

        $this->assertEquals(
            [
                'field' => [$ruleObject],
            ],
            $schema->getNativeRulesRepresentation()
        );
    }

    public function testMultipleRules()
    {
        $schema = (new ValidationSchema())
            ->addRule(new ValidationRule('field', new TestRuleType1()))
            ->addRule(new ValidationRule('field', new TestRuleType2()));

        $this->assertEquals(
            [
                'field' => ['test1', 'test2'],
            ],
            $schema->getNativeRulesRepresentation()
        );
    }

    public function testUniqueRules()
    {
        $validatorCallback = static function () { };
        $ruleObject = new TestRuleObject();

        $schema = (new ValidationSchema())
            ->addRule(new ValidationRule('field', new TestRuleType1()))
            ->addRule(new ValidationRule('field', new TestRuleType1()))
            ->addRule(new ValidationRule('param', new ParameterizedTestRuleType1(1)))
            ->addRule(new ValidationRule('param', new ParameterizedTestRuleType1(2)))
            ->addRule(new ValidationRule('object', new RuleObjectRuleType($ruleObject)))
            ->addRule(new ValidationRule('object', new RuleObjectRuleType($ruleObject)))
            ->addRule(new ValidationRule('closure', new ClosureRuleType($validatorCallback)))
            ->addRule(new ValidationRule('closure', new ClosureRuleType($validatorCallback)));

        $this->assertEquals(
            [
                'field'   => ['test1'],
                'param'   => ['param1:2'],
                'object'  => [$ruleObject],
                'closure' => [$validatorCallback, $validatorCallback],
            ],
            $schema->getNativeRulesRepresentation()
        );
    }
}
