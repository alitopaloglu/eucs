<?php

namespace App\Interfaces;


/**
 * Manages providers self behaviors
 */
interface ProvidersInterface
{
    /**
     * Fills its own data to its own table
     *
     * @param string $url
     * @return array
     */
    public function getData(string $url);


    /**
     * Prepare custom data
     *
     * @param array $job
     * @return array
     */
    public function prepareJob(array $job);


    /**
     * Map custom fields
     *
     * @return array
     */
    public function getFields();
}
