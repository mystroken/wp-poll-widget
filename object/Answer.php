<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 04/07/2015
 * Time: 03:28
 */

namespace ApplemaniakObject;


class Answer
{

    /**
     * @var int
     */
    public $ID;



    /**
     * @var int
     */
    public $poll;



    /**
     * @var mixed|string
     */
    public $content;



    /**
     * @var Vote
     */
    public $votes;


    /**
     * @param int $id
     * @param string $content
     * @param array $votes
     * @param int $poll
     */
    public function __construct($id = 0, $content = '', $votes = array(), $poll = 0)
    {
        $this->ID = (int) $id;
        $this->votes = $votes;
        $this->content = $content;
        $this->poll = $poll;
    }




    public function __destruct()
    {
        unset($this);
    }


    public function __toString()
    {
        return $this->content;
    }

}