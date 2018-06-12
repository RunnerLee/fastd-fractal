<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-05
 */

namespace Runner\FastdFractal;

use League\Fractal\TransformerAbstract;

class AbstractTransformer extends TransformerAbstract
{
    /**
     * @param mixed $data
     * @param null  $transformer
     * @param null  $resourceKey
     *
     * @return \League\Fractal\Resource\Primitive
     */
    public function primitive($data, $transformer = null, $resourceKey = null)
    {
        return parent::primitive(
            $data,
            is_null($transformer) ? null : Fractal::transformer($transformer),
            $resourceKey
        );
    }

    /**
     * @param mixed                        $data
     * @param callable|TransformerAbstract $transformer
     * @param null                         $resourceKey
     *
     * @return \League\Fractal\Resource\Item
     */
    public function item($data, $transformer, $resourceKey = null)
    {
        return parent::item($data, Fractal::transformer($transformer), $resourceKey);
    }

    /**
     * @param mixed                        $data
     * @param callable|TransformerAbstract $transformer
     * @param null                         $resourceKey
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function collection($data, $transformer, $resourceKey = null)
    {
        return parent::collection($data, Fractal::transformer($transformer), $resourceKey);
    }
}
