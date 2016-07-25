<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 04/07/2015
 * Time: 03:29
 */

namespace ApplemaniakObject\Manager;
use ApplemaniakObject\Vote;

require_once 'Manager.php';
require_once APPLEMANIAK_SONDAGE_PATH . 'object/Vote.php';

class VoteManager
{
    /**
     * Prototype for adding data to database
     *
     * @param $table
     * @param $register
     * @return bool
     */
    public function create(Vote $vote)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_VOTE;

        $register = array(
            'data'     => array(
                'vote_author'     => $vote->author,
                'answer_ID' => $vote->answer
            ), 'dataType' => array('%s', '%d')
        );

        return Manager::create($table, $register);
    }


    /**
     * @param Vote $vote
     * @return bool
     */
    public function update(Vote $vote)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_VOTE;

        $register = array(
            'data'     => array(
                'vote_author'     => $vote->author,
                'answer_ID' => $vote->answer
            ), 'dataType' => array('%s', '%d')
        );

        $where = array(
            'data' => array('vote_ID' => $vote->ID),
            'dataType' => array('%d')
        );


        return Manager::update($table, $register, $where);
    }




    /**
     * @param Vote $vote
     * @return bool
     */
    public function delete(Vote $vote)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_VOTE;

        $sql = $wpdb->prepare("DELETE FROM $table WHERE vote_ID = %d", $vote->ID);
        return Manager::delete($sql);
    }




    public function convert($data)
    {
        try
        {
            return new Vote($data->vote_ID, $data->vote_author, $data->answer_ID);
        }
        catch(\Exception $e)
        {
            return null;
        }
    }


    /**
     * @param $id
     * @return Vote|null
     */
    public function find($id)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_VOTE;

        $result = $wpdb->get_row("SELECT * FROM $table WHERE vote_ID = $id");
        return $this->convert($result);

    }


    /**
     * @param int $property
     * @return array
     */
    public function findAll($property = 0)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_VOTE;
        $votes = array();

        if( $property > 0 )
        {
             $result = $wpdb->get_results("SELECT * FROM $table WHERE answer_ID = $property ORDER BY vote_ID DESC");

        }
        else
        {
            $result = $wpdb->get_results("SELECT * FROM $table ORDER BY vote_ID DESC");
        }


        for( $i=0; $i < sizeof($result); $i++ ) $votes[] = $this->convert($result[$i]);

        return $votes;
    }



    /**
     * install the table into database
     */
    public static function install()
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_VOTE;

        $wpdb->query("CREATE TABLE IF NOT EXISTS $table (
            vote_ID INT PRIMARY KEY AUTO_INCREMENT,
            vote_author TEXT NOT NULL,
            answer_ID INT NOT NULL);
        ");
    }


    public function is_unique(Vote $vote, $votes)
    {
        foreach($votes as $el)
        {
            if( ($el->vote_author == $vote->vote_author) && ($el->answer_ID == $vote->answer_ID) ) return false;
        }
        return true;
    }



    /**
     * uninstall table from database
     */
    public static function uninstall()
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_VOTE;
        $wpdb->query("DROP TABLE IF EXISTS $table");
    }

}