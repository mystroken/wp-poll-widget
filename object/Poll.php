<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 04/07/2015
 * Time: 03:27
 */

namespace ApplemaniakObject;

class Poll
{

    /**
     * @var int
     */
    public $ID;



    /**
     * @var string
     */
    public $name;



    /**
     * @var mixed|string
     */
    public $question;



    /**
     * @var \WP_User
     */
    public $author;



    /**
     * @var array
     */
    public $answers;


    /**
     * @var int
     */
    public $total_vote;


    /**
     * @param int $id
     * @param string $name
     * @param string $question
     * @param array $answers
     * @param \WP_User|null $author
     */
    public function __construct($id = 0, $name = '', $question = '', $answers = array(), $author = null, $total_vote = 0)
    {
        $this->ID = (int) $id;
        $this->name = $name;
        $this->author = ($author instanceof \WP_User) ? $author->user_login : $author;
        $this->answers = $answers;
        $this->question = $question;
        $this->total_vote = $total_vote;
    }



    public function __destruct()
    {
        unset($this);
    }



    public function __toString()
    {
        return 'ID: '.$this->ID.', name: '.$this->name;
    }


    /**
     * @return bool
     */
    public function isEmpty()
    {
        return ( empty($this->name) || empty($this->question) );
    }

}