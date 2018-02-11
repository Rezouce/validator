<?php

namespace Rezouce\Validator\Rule;

class DataParser
{
    public function parse(array $data, string $format): DataCollection
    {
        $dataCollection = new DataCollection();
        $dataCollection->add($data, '');

        $levels = explode('.', $format);

        foreach ($levels as $levelFormat) {
            $dataCollection = $this->getData($dataCollection, $levelFormat);
        }

        return $dataCollection;
    }

    private function getData(DataCollection $data, string $format): DataCollection
    {
        if ($format === '*') {
            return $this->extractAllIterableData($data);
        }

        return $this->extractAllMatchingData($data, $format);
    }

    private function extractAllIterableData(DataCollection $dataCollection): DataCollection
    {
        $parsedData = new DataCollection();

        foreach ($dataCollection as $data) {
            if (is_iterable($data->getData())) {
                foreach ($data->getData() as $key => $subData) {
                    $parsedData->add($subData, $this->generateKey($data->getKey(), $key));
                }
            } elseif ($data->isNull()) {
                $parsedData->add(null, $this->generateKey($data->getKey(), '0'));
            }
        }

        return $parsedData;
    }

    private function extractAllMatchingData(DataCollection $dataCollection, string $format): DataCollection
    {
        $parsedData = new DataCollection();

        foreach ($dataCollection as $key => $data) {
            $parsedData->add($data->getData()[$format] ?? null, $this->generateKey($data->getKey(), $format));
        }

        return $parsedData;
    }

    private function generateKey(string $currentKey, string $addedKeyValue): string
    {
        return empty($currentKey)
            ? $addedKeyValue
            : $currentKey . '.' . $addedKeyValue;
    }
}
