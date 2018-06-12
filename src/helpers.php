<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-04
 */
use Runner\FastdFractal\Fractal;

if (!function_exists('fractal')) {
    /**
     * @return Fractal
     */
    function fractal()
    {
        return new Fractal();
    }
}
