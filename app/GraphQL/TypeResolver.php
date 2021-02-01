<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\Type;

class TypeResolver
{
    private array $defaultTypeAliases = [
        'int'     => Type::INT,
        'integer' => Type::INT,
        'string'  => Type::STRING,
        'bool'    => Type::BOOLEAN,
        'boolean' => Type::BOOLEAN,
        'float'   => Type::FLOAT,
        'id'      => Type::ID,
    ];

    private array $typeClassToTypeNameMap = [];

    private array $modelClassToTypeNameMap = [];

    public function addMappingByModelClass(string $modelClass, string $type)
    {
        $this->modelClassToTypeNameMap[$modelClass] = $type;
    }

    public function addMappingByTypeClass(string $typeClass, string $type)
    {
        $this->typeClassToTypeNameMap[$typeClass] = $type;
    }

    private function resolveByModelClass(string $modelClass): ?string
    {
        if (isset($this->modelClassToTypeNameMap[$modelClass])) {
            return $this->modelClassToTypeNameMap[$modelClass];
        }

        return null;
    }

    private function resolveByTypeClass(string $typeClass): ?string
    {
        if (isset($this->typeClassToTypeNameMap[$typeClass])) {
            return $this->typeClassToTypeNameMap[$typeClass];
        }

        return null;
    }

    private function getDefaultTypeByAlias(string $type): ?string
    {
        if (isset($this->defaultTypeAliases[$type])) {
            return $this->defaultTypeAliases[$type];
        }

        return null;
    }

    public function resolve(string $type): string
    {
        if ($this->getDefaultTypeByAlias($type)) {
            return $this->getDefaultTypeByAlias($type);
        }

        if ($this->resolveByModelClass($type)) {
            return $this->resolveByModelClass($type);
        }

        if ($this->resolveByTypeClass($type)) {
            return $this->resolveByTypeClass($type);
        }

        return $type;
    }
}
