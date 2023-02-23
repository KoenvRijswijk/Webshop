<?php
/**
 * Crud interface
 *
 * @author Geert Weggemans - geert@man-kind.nl
 * @date jan 8 2020
 */
interface iCrud
{
    public function isConnected() : bool;
    public function getLastError() : string;
    public function beginTransaction();
    public function rollBack();
    public function commit();

    public function selectOne( string $sql, array $params=[], string $class='') : array|object|false;
    public function selectMore(string $sql, array $params=[], string $class='') : array|false;
    public function doInsert(  string $sql, array $params=[]) : int|false;
    public function doUpdate(  string $sql, array $params=[]) : int|false;
    public function doDelete(  string $sql, array $params=[]) : int|false;
}