<?php


namespace App\Contracts\Base;


interface CrudContract
{
    /**
     * @param $per_page
     * @return mixed
     */
    public function setPerPage($per_page);


    /**
     * @param array $relations
     * @return mixed
     */
    public function setRelations(array $relations = []);

    /**
     * @param array $counts
     * @return mixed
     */
    public function setCounts(array $counts = []);

    /**
     * @param array $columns
     * @return mixed
     */
    public function setColumns(array $columns = ['*']);

    /**
     * @param array $scopes
     * @return mixed
     */
    public function setScopes(array $scopes = []);

    /**
     * @param $id
     * @return mixed
     */
    public function findOneById($id);

    /**
     * @param array $params
     * @return mixed
     */
    public function findOneBy(array $params);

    /**
     * @param array $params
     * @return mixed
     */
    public function findBy(array $params);

    /**
     * @return mixed
     */
    public function findByFilter();

    /**
     * @param array $data
     * @return mixed
     */
    public function new(array $data);

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id);
}
