<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 04/07/2015
 * Time: 03:31
 */

namespace ApplemaniakObject\manager;
use ApplemaniakObject\Answer;
use ApplemaniakObject\Poll;

require_once 'Manager.php';
require_once 'AnswerManager.php';

class PollManager
{




    /**
     * Add new poll to database
     *
     * @param Poll $poll
     * @return bool
     */
    public function create(Poll $poll)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_POLL;

        $register = array(
            'data'     => array(
                'poll_name'     => $poll->name,
                'poll_question' => $poll->question,
                'poll_author'   => ($poll->author)
            ), 'dataType' => array('%s', '%s', '%s')
        );

        if( Manager::create($table, $register) )
        {
            return $this->register_answers($poll->answers, $wpdb->insert_id);
        }
        return false;
    }




    /**
     * Edit a poll registered into database
     *
     * @param Poll $poll
     * @return bool
     */
    public function update(Poll $poll)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_POLL;

        $register = array(
            'data'     => array(
                'poll_name'     => $poll->name,
                'poll_question' => $poll->question,
                'poll_author'   => ($poll->author)
            ), 'dataType' => array('%s', '%s', '%s')
        );

        $where = array(
            'data' => array('poll_ID' => $poll->ID),
            'dataType' => array('%d')
        );


        if( Manager::update($table, $register, $where) )
        {
            return $this->register_answers($poll->answers, $poll->ID);
        }
        return false;
    }







    /**
     * Delete a poll form database
     *
     * @param Poll $poll
     * @return bool
     */
    public function delete(Poll $poll)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_POLL;
        $manager = new AnswerManager();

        for($i=0; $i < sizeof($poll->answers); $i++) $manager->delete($poll->answers[$i]);

        $sql = $wpdb->prepare("DELETE FROM $table WHERE poll_ID = %d", $poll->ID);
        return Manager::delete($sql);
    }





    public function convert($data)
    {
        try
        {
            $manager = new AnswerManager();
            $answers = $manager->findAll( intval($data->poll_ID) );
            $total_vote = 0;

            for($i=0; $i < sizeof($answers); $i++) $total_vote += sizeof($answers[$i]->votes);

            return new Poll(intval($data->poll_ID), remove_slashes($data->poll_name), remove_slashes($data->poll_question), $answers, ($data->poll_author), $total_vote);
        }
        catch(\Exception $e)
        {
            return null;
        }
    }




    public function find($id)
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_POLL;

        $result = $wpdb->get_row("SELECT * FROM $table WHERE poll_ID = $id");

        return $this->convert($result);

    }





    public function findAll()
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_POLL;
        $polls = array();

        $result = $wpdb->get_results("SELECT * FROM $table ORDER BY poll_ID DESC");


        for( $i=0; !empty($result) && $i < sizeof($result); $i++ ) $polls[] = $this->convert($result[$i]);

        return $polls;
    }






    /**
     * install the table into database
     */
    public static function install()
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_POLL;

        $wpdb->query("CREATE TABLE IF NOT EXISTS $table (
            poll_ID INT PRIMARY KEY AUTO_INCREMENT,
            poll_name VARCHAR(255) NOT NULL,
            poll_question LONGTEXT NOT NULL,
            poll_author LONGTEXT NOT NULL);
        ");
    }




    protected function register_answers($answers, $poll_id)
    {
        $return = true;

        if( is_array($answers) and !empty($answers) )
        {
            $manager = new AnswerManager();
            $all_poll_answers = $manager->findAll($poll_id);
            $to_delete = array_diff($all_poll_answers, $answers);
            $to_delete = array_values($to_delete);


            for($i = 0; $i < sizeof($answers); $i++)
            {
                $answer = new Answer($answers[$i]->ID, $answers[$i]->content, $answers[$i]->votes, $poll_id);
                if( $answer->ID == 0 ) $return = $manager->create($answer);
            }


            for($i = 0; $i < sizeof($to_delete); $i++)
            {
                $return = $manager->delete($to_delete[$i]);
            }


        }
        return $return;
    }





    /**
     * @param Poll $poll
     * @return bool
     */
    public function is_unique(Poll $poll, $polls)
    {
        $unique = true;

        for($i=0;$i<sizeof($polls); $i++)
        {
            $the_poll = $polls[$i];

            if( ($poll->ID == $the_poll->ID) || ($poll->name == $the_poll->name) || ($poll->question == $the_poll->question) ) $unique = false;
        }

        return $unique;
    }








    /**
     * uninstall table from database
     */
    public static function uninstall()
    {
        global $wpdb;
        $table = $wpdb->prefix . APPLEMANIAK_SONDAGE_TABLE_POLL;
        $wpdb->query("DROP TABLE IF EXISTS $table");
    }

}