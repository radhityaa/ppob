<?php


namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class EnvFileService
{
    public function getAllEnv()
    {
        $envDetails = DotenvEditor::getKeys();
        $envDetails = new Collection($envDetails);

        return $envDetails->map(function ($item, $key) {
            return [
                'key' => $key,
                'data' => $item,
            ];
        })->groupBy(function ($item, $key) {
            $key = explode('_', $key);
            return $key[0];
        });
    }

    public function getEnv(array $env)
    {
        $envDetails = DotenvEditor::getKeys($env);
        $envDetails = new Collection($envDetails);
        return $envDetails->map(function ($item, $key) {
            return [
                'key' => $key,
                'data' => $item,
            ];
        })->groupBy(function ($item, $key) {
            $key = explode('_', $key);
            return $key[0];
        });
    }

    public function updateEnv(Request $request)
    {
        $keys = DotenvEditor::getKeys($request->keys());

        array_walk($keys, function ($data, $key) use ($request) {
            if ($request->input($key) != $data['value']) {
                DotenvEditor::setKey($key, $request->input($key));
            }
        });

        DotenvEditor::save();

        return $this->getEnv($request->keys());
    }
}
