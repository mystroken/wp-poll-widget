<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 04/07/2015
 * Time: 01:01
 */


class Applemaniak_Sondage_Widget extends WP_Widget
{

    /**
     * Name of widget displayed
     * @var mixed|string
     */
    public $name = 'AppleManiak Sondage Widget';




    /**
     * widget slug name (must be unique)
     * @var mixed|string
     */
    public $slug_name = 'applemaniak_sondage_widget';




    /**
     * Description of widget
     * @var string
     */
    public $description = 'Proposez un joli petit sondage à vos utilisateurs!';






    /**
     * Constructor
     *
     * init the widget
     */
    public function __construct()
    {
        parent::__construct(
            $this->slug_name,
            $this->name,
            array('description' => $this->description)
        );
    }







    /**
     * front end view of widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/VoteManager.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/AnswerManager.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/PollManager.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/Vote.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/Answer.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/Poll.php';

        require_once APPLEMANIAK_SONDAGE_VIEWS_FOLDER . 'widget_front_end.php';
    }








    /**
     * Back end view of widget
     *
     * @param array $instance
     * @return void
     */
    public function form($instance)
    {
        require_once APPLEMANIAK_SONDAGE_VIEWS_FOLDER . 'widget_back_end.php';
    }







    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags( stripslashes($new_instance['title']) );

        return $instance;

    }

}