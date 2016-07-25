<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 04/07/2015
 * Time: 03:35
 */

namespace ApplemaniakObject\manager;


final class Manager
{

    /**
     * Prototype for adding data to database
     *
     * @param $table
     * @param $register
     * @return bool
     */
    public static function create($table, $register = array())
    {
        $response = false;

        try
        {
            global $wpdb;
            $wpdb->insert($table, $register['data'], $register['dataType']);
            $response = true;
        }
        catch(\Exception $e)
        {
            die('Une erreur est survenue: ' . $e->getMessage());
        }

        return $response;
    }


    /**
     * Prototype for delete
     *
     * @param $sql
     * @return bool
     */
    public static function delete($sql)
    {
        global $wpdb;
        $response = false;

        try
        {
            $wpdb->query($sql);
            $response = true;
        }
        catch(\Exception $e)
        {
            die('Une erreur est survenue: ' . $e->getMessage());
        }

        return $response;
    }


    /**
     * Prototype for update data in database
     *
     * @param $table
     * @param $register
     * @param $where
     * @return bool
     */
    public static function update($table, $register, $where)
    {
        $response = false;

        try
        {
            global $wpdb;
            $wpdb->update($table, $register['data'], $where['data'], $register['dataType'], $where['dataType']);
            $response = true;
        }
        catch(\Exception $e)
        {
            die('Une erreur est survenue: ' . $e->getMessage());
        }

        return $response;
    }




    public function __destruct()
    {
        unset($this);
    }
}