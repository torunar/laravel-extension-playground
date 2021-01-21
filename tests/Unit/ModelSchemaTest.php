<?php

namespace Tests\Unit;

use App\Schemas\ModelSchema\Attribute;
use App\Schemas\ModelSchema\AttributeTypes\CastedAttributeType;
use App\Schemas\ModelSchema\AttributeTypes\PrimitiveAttributeType;
use App\Schemas\ModelSchema\DescribedByModelSchema;
use App\Schemas\ModelSchema\Events\CreateModelSchemaEvent;
use App\Schemas\ModelSchema\ModelSchema;
use App\Schemas\ModelSchema\Relation;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EmojiCaster implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === ':thinking:') {
            return '🤔';
        }

        return $value;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === '🤔') {
            return ':thinking:';
        }

        return $value;
    }
}

class FakeModel extends Model
{
    use DescribedByModelSchema;
}

class ModelSchemaTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $modelSchema = new ModelSchema();
        $modelSchema
            ->setTableName('fakes')
            ->setKeyName('id')
            ->addAttribute(new Attribute('id', new PrimitiveAttributeType('int')))
            ->addAttribute(new Attribute('name', new PrimitiveAttributeType('string')))
            ->addAttribute(new Attribute('json', new PrimitiveAttributeType('json')))
            ->addAttribute(new Attribute('emoji', new CastedAttributeType(EmojiCaster::class)))
            ->addAttribute(new Attribute('hidden', new PrimitiveAttributeType('string'), true, true))
            ->addAttribute(new Attribute('secret', new PrimitiveAttributeType('string'), false))
            ->addRelation(
                new Relation(
                    'dependencies',
                    static function (FakeModel $model) {
                        return $model->hasMany(FakeModel::class);
                    }
                )
            );

        FakeModel::setModelSchema($modelSchema);
    }

    public function testSetModelSchema()
    {
        Event::fake();

        FakeModel::setModelSchema(new ModelSchema());

        Event::assertDispatched(
            static function (CreateModelSchemaEvent $event) {
                return $event->getModelClass() === FakeModel::class;
            }
        );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddAttribute()
    {
        $fake = new FakeModel();

        $fake->setRawAttributes(
            [
                'id'   => '42',
                'name' => 42,
                'json' => '[4,2]',
            ]
        );

        $this->assertSame(42, $fake->id);
        $this->assertSame('42', $fake->name);
        $this->assertSame([4, 2], $fake->json);
    }

    public function testAttributeCaster()
    {
        $fake = new FakeModel();
        $fake->setRawAttributes(
            [
                'emoji' => ':thinking:',
            ]
        );
        $this->assertSame('🤔', $fake->emoji);

        $fake->emoji = 'foo';
        $this->assertSame('foo', $fake->emoji);

        $fake->emoji = '🤔';
        $this->assertSame(['emoji' => ':thinking:'], $fake->getAttributes());
    }

    public function testMissingRelation()
    {
        $fake = new FakeModel();

        $this->expectException(\BadMethodCallException::class);
        $fake->missingRelation();
    }

    public function testAddRelation()
    {
        $fake = new FakeModel();

        $this->assertInstanceOf(HasMany::class, $fake->dependencies());
    }

    public function testHidden()
    {
        $fake = new FakeModel();
        $fake->setRawAttributes(
            [
                'id'     => 42,
                'hidden' => 420,
            ]
        );

        $this->assertEquals(['id' => 42], $fake->toArray());
    }

    public function testFillable()
    {
        $fake = new FakeModel(
            [
                'id'     => 42,
                'name'   => 'fake',
                'secret' => 'secret',
            ]
        );

        $this->assertEquals(['id' => 42, 'name' => 'fake'], $fake->toArray());
    }

    public function testSetTable()
    {
        $fake = new FakeModel();
        $this->assertEquals('fakes', $fake->getTable());
        $this->assertEquals('id', $fake->getKeyName());
    }
}
