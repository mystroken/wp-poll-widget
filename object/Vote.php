<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 04/07/2015
 * Time: 03:28
 */

namespace ApplemaniakObject;


class Vote
{

    /**
     * @var int
     */
    public $ID;



    /**
     * @var mixed|string
     */
    public $author;



    /**
     * @var int
     */
    public $answer;


    /**
     * @param int $id
     * @param string $author
     * @param int $answer
     */
    public function __construct($id = 0, $author = '', $answer = 0)
    {
        $this->ID = (int) $id;
        $this->author = $author;
        $this->answer = (int) $answer;
    }




    public function __destruct()
    {
        unset($this);
    }


    public function __toString()
    {
        return 'answer: '.$this->answer.', author: '.$this->author;
    }
}