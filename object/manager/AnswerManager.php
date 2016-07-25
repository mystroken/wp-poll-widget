<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 04/07/2015
 * Time: 03:31
 */

namespace ApplemaniakObject\manager;
use ApplemaniakObject\Answer;

require_once 'Manager.php';
require_once 'AnswerManager.php';
require_once 'VoteManager.php';


class AnswerManager
{




    /**
     * @param Answer $answer
     * @return bool
     */
    public function create(Answer $answer)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_ANSWER;

        $register = array(
            'data'     => array(
                'answer_content'     => $answer->content,
                'poll_ID'   => $answer->poll
            ), 'dataType' => array('%s', '%d')
        );

        return Manager::create($table, $register);
    }









    /**
     * @param Answer $answer
     * @return bool
     */
    public function update(Answer $answer)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_ANSWER;

        $register = array(
            'data'     => array(
                'answer_content'     => $answer->content,
                'poll_ID'   => $answer->poll
            ), 'dataType' => array('%s', '%d')
        );

        $where = array(
            'data' => array('answer_ID' => $answer->ID),
            'dataType' => array('%d')
        );


        return Manager::update($table, $register, $where);
    }




    public function convert($data)
    {
        try
        {
            $manager = new VoteManager();
            $votes = $manager->findAll($data->answer_ID);
            return new Answer($data->answer_ID, remove_slashes($data->answer_content), $votes, $data->poll_ID);
        }
        catch(\Exception $e)
        {
            return null;
        }
    }




    public function find($id)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_ANSWER;

        $result = $wpdb->get_row("SELECT * FROM $table WHERE answer_ID = $id");
        return $this->convert($result);

    }




    public function findAll($property = 0)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_ANSWER;
        $answers = array();

        if( $property > 0 )
        {
            $result = $wpdb->get_results("SELECT * FROM $table WHERE poll_ID = $property ORDER BY answer_ID ASC");

        }
        else
        {
            $result = $wpdb->get_results("SELECT * FROM $table ORDER BY answer_ID ASC");
        }


        for( $i=0; $i < sizeof($result); $i++ ) $answers[] = $this->convert($result[$i]);

        return $answers;
    }




    /**
     * @param Answer $answer
     * @return bool
     */
    public function delete(Answer $answer)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_ANSWER;
        $manager = new VoteManager();

        for($i=0; $i < sizeof($answer->votes); $i++) $manager->delete($answer->votes[$i]);

        $sql = $wpdb->prepare("DELETE FROM $table WHERE answer_ID = %d", $answer->ID);
        return Manager::delete($sql);
    }





    public function is_unique(Answer $answer, $answers)
    {
        foreach($answers as $el)
        {
            if( ($el->poll_ID == $answer->poll_ID) && ($el->answer_content == $answer->answer_content) ) return false;
        }
        return true;
    }






    /**
     * install the table into database
     */
    public static function install()
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_ANSWER;

        $wpdb->query("CREATE TABLE IF NOT EXISTS $table (
            answer_ID INT PRIMARY KEY AUTO_INCREMENT,
            poll_ID INT NOT NULL,
            answer_content LONGTEXT NOT NULL);
        ");
    }











    /**
     * uninstall table from database
     */
    public static function uninstall()
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_ANSWER;
        $wpdb->query("DROP TABLE IF EXISTS $table");
    }

}