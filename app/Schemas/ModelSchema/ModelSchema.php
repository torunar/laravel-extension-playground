<?php

namespace App\Schemas\ModelSchema;

class ModelSchema
{
    private string $tableName;

    private string $keyName;

    /** @var array<string, \App\Schemas\ModelSchema\Attribute> */
    private array $attributes = [];

    /** @var array<string, \App\Schemas\ModelSchema\Relation> */
    private array $relations = [];

    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function setKeyName(string $keyName): self
    {
        $this->keyName = $keyName;

        return $this;
    }

    public function addAttribute(Attribute $attribute): self
    {
        $this->attributes[$attribute->getName()] = $attribute;

        return $this;
    }

    public function addRelation(Relation $relation): self
    {
        $this->relations[$relation->getName()] = $relation;

        return $this;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getKeyName(): string
    {
        return $this->keyName;
    }

    /**
     * @return array<string, \App\Schemas\ModelSchema\Attribute>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return array<string, \App\Schemas\ModelSchema\Relation>
     */
    public function getRelations(): array
    {
        return $this->relations;
    }
}
