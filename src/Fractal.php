<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-04
 */

namespace Runner\FastdFractal;

use BadMethodCallException;
use FastD\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;

/**
 * Class Fractal.
 *
 * @method Fractal parseIncludes($includes)
 * @method Fractal parseExcludes($excludes)
 * @method Fractal parseFieldsets(array $fieldsets)
 */
class Fractal
{
    /**
     * @var TransformerAbstract[]
     */
    protected static $transformers = [];

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
            new Item($resource, self::transformer($transformer)),
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
            new Collection($resource, self::transformer($transformer)),
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
        $collection = new Collection($paginator->getCollection(), self::transformer($transformer));

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
    public static function transformer($transformer)
    {
        if (is_object($transformer)) {
            return $transformer;
        }
        if (!array_key_exists($transformer, self::$transformers)) {
            self::$transformers[$transformer] = new $transformer();
        }

        return self::$transformers[$transformer];
    }

    /**
     * @return Manager
     */
    public function manager()
    {
        return $this->manager;
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
     * @param $name
     * @param mixed $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, ['parseIncludes', 'parseExcludes', 'parseFieldsets'])) {
            call_user_func_array([$this->manager, $name], $arguments);

            return $this;
        }

        $class = get_class($this);

        throw new BadMethodCallException("Call to undefined method {$class}::{$name}()");
    }
}
