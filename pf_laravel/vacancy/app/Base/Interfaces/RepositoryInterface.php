<?php

namespace App\Base\Interfaces;

interface RepositoryInterface
{
    /**
     * Get one model by key
     */
    public function one(string|int|null $id);

    /**
     * Get one active model by key
     */
    public function oneActive(string|int|null $id);

    /**
     * Get all models
     */
    public function all();

    /**
     * Get all active models
     */
    public function allActive();

    /**
     * Get one model following the parameters
     *
     * @param array $parameters
     * @param bool  $active
     *
     */
    public function find(array $parameters = [], bool $active = false);

    /**
     * Get one active model following the parameters
     *
     * @param array $parameters
     *
     */
    public function findActive(array $parameters = []);

    /**
     * Get models following the parameters
     *
     * @param array    $parameters
     * @param null|int $limit
     * @param bool     $active
     */
    public function search(array $parameters = [], int $limit = null, bool $active = false);

    /**
     * Get active models following the parameters
     *
     * @param array    $parameters
     * @param null|int $limit
     */
    public function searchActive(array $parameters = [], int $limit = null);

    /**
     * Paginate models following the parameters
     *
     * @param array    $parameters
     * @param null|int $limit
     * @param bool     $active
     */
    public function paginate(array $parameters = [], int $limit = null, bool $active = false);

    /**
     * Paginate active models following the parameters
     *
     * @param array    $parameters
     * @param null|int $limit
     */
    public function paginateActive(array $parameters = [], int $limit = null);

    /**
     * Count models following the parameters
     *
     * @param array $parameters
     * @param bool  $active
     *
     * @return int
     */
    public function count(array $parameters = [], bool $active = false): int;

    /**
     * Count active models following the parameters
     *
     * @param array $parameters
     *
     * @return int
     */
    public function countActive(array $parameters = []): int;

    /**
     * Check existing of models following the parameters
     *
     * @param array $parameters
     * @param bool  $active
     *
     * @return bool
     */
    public function exists(array $parameters = [], bool $active = false): bool;

    /**
     * Check existing of active models following the parameters
     *
     * @param array $parameters
     *
     * @return bool
     */
    public function existsActive(array $parameters = []): bool;

    /**
     * Delete models following the parameters
     *
     * @param array $parameters
     * @param bool  $active
     *
     * @return int
     */
    public function delete(array $parameters = [], bool $active = false): int;

    /**
     * Delete active models following the parameters
     *
     * @param array $parameters
     *
     * @return int
     */
    public function deleteActive(array $parameters = []): int;

}
