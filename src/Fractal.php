<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-04
 */

namespace Runner\FastdFractal;

use FastD\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;

class Fractal
{
    /**
     * @var TransformerAbstract[]
     */
    protected $transformers = [];

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * Fractal constructor.
     */
    public function __construct()
    {
        $this->manager = (new Manager())->setSerializer(new DataArraySerializer());
    }

    /**
     * @param $resource
     * @param $transformer
     * @param int $status
     *
     * @return JsonResponse
     */
    public function item($resource, $transformer, $status = Response::HTTP_OK)
    {
        return $this->response(
            new Item($resource, $this->formatTransformer($transformer)),
            $status
        );
    }

    /**
     * @param $resource
     * @param $transformer
     * @param int $status
     *
     * @return JsonResponse
     */
    public function collection($resource, $transformer, $status = Response::HTTP_OK)
    {
        return $this->response(
            new Collection($resource, $this->formatTransformer($transformer)),
            $status
        );
    }

    /**
     * @param $paginator
     * @param $transformer
     * @param int $status
     *
     * @return JsonResponse
     */
    public function paginator($paginator, $transformer, $status = Response::HTTP_OK)
    {
        $collection = new Collection($paginator->getCollection(), $this->formatTransformer($transformer));

        return $this->response(
            $collection->setPaginator(new IlluminatePaginatorAdapter($paginator)),
            $status
        );
    }

    /**
     * @param $transformer
     *
     * @return TransformerAbstract
     */
    public function transformer($transformer)
    {
        return $this->formatTransformer($transformer);
    }

    /**
     * @param ResourceAbstract $resource
     * @param int              $status
     *
     * @return JsonResponse
     */
    protected function response(ResourceAbstract $resource, $status = Response::HTTP_OK)
    {
        return new JsonResponse(
            $this->manager->createData($resource)->toArray(),
            $status
        );
    }

    /**
     * @param $transformer
     *
     * @return TransformerAbstract
     */
    protected function formatTransformer($transformer)
    {
        if (is_object($transformer)) {
            return $transformer;
        }
        if (!array_key_exists($transformer, $this->transformers)) {
            $this->transformers[$transformer] = new $transformer();
        }

        return $this->transformers[$transformer];
    }
}
