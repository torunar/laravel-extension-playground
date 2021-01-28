<?php

namespace App\GraphQL;

class TypeResolver
{
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

    public function resolve(string $type): string
    {
        if ($this->resolveByModelClass($type)) {
            return $this->resolveByModelClass($type);
        }

        if ($this->resolveByTypeClass($type)) {
            return $this->resolveByTypeClass($type);
        }

        return $type;
    }
}
