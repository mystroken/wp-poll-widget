<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel KWENE
 * Date: 07/06/2015
 * Time: 11:47
 */

// Caractéristiques
//==========================

require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/PollManager.php';
require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/AnswerManager.php';
require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/VoteManager.php';
require_once APPLEMANIAK_SONDAGE_PATH . 'object/Poll.php';
require_once APPLEMANIAK_SONDAGE_PATH . 'object/Answer.php';
require_once APPLEMANIAK_SONDAGE_PATH . 'object/Vote.php';


function get_polls()
{

    $poll_manager = new \ApplemaniakObject\manager\PollManager();
    echo json_encode($poll_manager->findAll());

}


function get_answers($poll_id)
{
    $answer_manager = new \ApplemaniakObject\manager\AnswerManager();
    echo json_encode($answer_manager->findAll($poll_id));
}


function save_vote($poll_id, $answer_id, $author)
{
    $poll_manager = new \ApplemaniakObject\manager\PollManager();
    $vote_manager = new \ApplemaniakObject\Manager\VoteManager();
    $vote = new \ApplemaniakObject\Vote();
    $poll = $poll_manager->find($poll_id);
    $in_array = false;

    for($i=0; $i < sizeof($poll->answers); $i++)
    {
        $answer = $poll->answers[$i];

        for($j=0; $j < sizeof($answer->votes); $j++)
        {
            $cur_vote = $answer->votes[$j];

            if( $cur_vote->author == $author ) $vote = $cur_vote; $in_array = true;
        }
    }

    $vote->author = $author;
    $vote->answer = $answer_id;

    if( true == $in_array )
    {
        // mise à jour du choix
        $vote_manager->update($vote);

    }
    else
    {
        // nouveau choix
        $vote_manager->create($vote);
    }

    echo json_encode($poll_manager->find($poll_id));
}