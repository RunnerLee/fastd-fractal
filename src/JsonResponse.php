<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-04
 */

namespace Runner\FastdFractal;

use FastD\Http\JsonResponse as Response;

class JsonResponse extends Response
{
    // Encode <, >, ', &, and " characters in the JSON, making it also safe to be embedded into HTML.
    // 15 === JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
    const JSON_OPTIONS = 15;

    /**
     * JsonResponse constructor.
     *
     * @param array $data
     * @param int   $status
     * @param array $headers
     */
    public function __construct(array $data, $status = 200, array $headers = [])
    {
        parent::__construct($this->formatData($data), $status, $headers);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function formatData(array $data)
    {
        array_walk(
            $data,
            function (&$value) {
                if (is_array($value)) {
                    $value = $this->formatData($value);
                } elseif (null === $value) {
                    $value = '';
                }
            }
        );

        return $data;
    }
}
