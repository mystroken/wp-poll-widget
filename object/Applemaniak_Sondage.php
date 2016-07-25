<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 03/07/2015
 * Time: 21:04
 */



class Applemaniak_Sondage
{

    // icon to display in admin menu
    public static $icon_url = APPLEMANIAK_SONDAGE_ICON_URL;

    // position where display admin menu
    public static $position = '65.1993';

    // slug name of our menu
    public static $menu_slug = 'applemaniak-sondage';

    // admin menu page title
    public static $page_title = 'AppleManiak Sondage Widget';

    // admin menu title
    public static $menu_title = 'AppleManiak Sondage';

    // capability for user to see menu
    public static $capability = 'manage_options';






    /**
     * Constructor
     */
    public function __construct()
    {
        /*
         * plugin initialization
         *
         * registering menu, hooks, actions
         */
        self::initialize();

    }


    /**
     * Destructor
     *
     * destruct object
     */
    public function __destruct()
    {
        unset($this);
    }



    /**
     * Activate plugin
     */
    public function activate()
    {

        /**
         * We create our needed tables into database
         */
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/PollManager.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/VoteManager.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/AnswerManager.php';


        ApplemaniakObject\Manager\PollManager::install();
        ApplemaniakObject\Manager\AnswerManager::install();
        ApplemaniakObject\Manager\VoteManager::install();

    }



    /**
     * Desactivate plugin
     */
    public function desactivate()
    {
    }



    /**
     * uninstall plugin
     */
    public static function uninstall()
    {
        /**
         * Clean up database
         */
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/PollManager.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/VoteManager.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/AnswerManager.php';


        ApplemaniakObject\Manager\PollManager::uninstall();
        ApplemaniakObject\Manager\AnswerManager::uninstall();
        ApplemaniakObject\Manager\VoteManager::uninstall();
    }



    /**
     * Initialize
     * registering diferents actions and hooks
     */
    public function initialize()
    {

        // Add admin menu
        add_action('admin_menu', array(&$this, 'add_admin_menu'));


        add_action('admin_notices', array(&$this, 'admin_notices'));

        // Add ajax
        add_action('wp_ajax_applemaniak_sondage_ajax', array($this, 'applemaniak_sondage_ajax'));
        add_action('wp_ajax_nopriv_applemaniak_sondage_ajax', array($this, 'applemaniak_sondage_ajax'));

        // Add widget
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/Applemaniak_Sondage_Widget.php';
        add_action('widgets_init', function() { register_widget('Applemaniak_Sondage_Widget'); });


        // Load script & style (ADMIN PANEL)
        add_action( 'admin_enqueue_scripts', array( &$this, 'add_admin_scripts' ), 20 );


        // Load script & style (FRONT END)
        add_action( 'wp_enqueue_scripts', array( &$this, 'add_user_scripts' ), 20 );

    }



    /**
     * Adding a menu into admin panel
     */
    public function add_admin_menu()
    {

        add_menu_page(
            self::$page_title,
            self::$menu_title,
            self::$capability,
            self::$menu_slug,
            array(&$this, 'admin_menu_html'),
            self::$icon_url,
            self::$position
        );


    }


    /**
     * Html to render admin menu page
     */
    public function admin_menu_html()
    {
        $task = empty($_GET['task']) ? null : htmlspecialchars($_GET['task']);
        $poll_id = empty($_GET['poll_id']) ? 0 : intval($_GET['poll_id']);


        require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/PollManager.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/manager/AnswerManager.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/Poll.php';
        require_once APPLEMANIAK_SONDAGE_PATH . 'object/Answer.php';
        $poll_manager = new \ApplemaniakObject\manager\PollManager();
        //$answer_manager = new \ApplemaniakObject\manager\AnswerManager();

        $allPolls = $poll_manager->findAll();
        $poll = ($poll_id > 0) ? $poll_manager->find($poll_id) : new \ApplemaniakObject\Poll();
        $current_user = wp_get_current_user();


        /**
         * Placeholders
         */
        $page_title = ($poll->isEmpty()) ? 'Créer un nouveau sondage' : 'Editer un sondage';
        $btn_value = ($poll->isEmpty()) ? 'Enregistrer' : 'Editer';
        $sondage_name = (isset($_POST['sondage_title_input'])) ? strip_tags(trim($_POST['sondage_title_input'])) : $poll->name;
        $sondage_content = (isset($_POST['sondage_question_input'])) ? $_POST['sondage_question_input'] : $poll->question;


        /**
         * we create url to all our page
         */
        $route = array(
            'home' => 'admin.php?page='.self::$menu_slug,
            'add_edit' => 'admin.php?page='.self::$menu_slug.'&amp;task=add_edit_poll',
            'delete' => 'admin.php?page='.self::$menu_slug.'&amp;task=del_poll'
        );


        if( !empty($_POST) )
        {
            // Answers
            $answers = $_POST['answers'];

            for($i=0;$i<sizeof($answers);$i++) {

                $answer_id = (int) preg_replace('/^(.+)@(([0-9]){1,})$/isU', '$2', $answers[$i]);
                $answer_content = preg_replace('/^(.+)@(([0-9]){1,})$/isU', '$1', $answers[$i]);

                $poll->answers[] = new \ApplemaniakObject\Answer($answer_id, $answer_content);
            }

            // Poll
            $poll->ID = intval( $_POST['poll_id'] );
            $poll->name = strip_tags(trim($_POST['sondage_title_input']));
            $poll->question = $_POST['sondage_question_input'];
            $poll->author = $current_user->user_login;


            if( $poll->isEmpty() )
            {
                $this->admin_notices('Ce sondage parrait un peu vide, tu ne trouves pas!?', 'error form-invalid');
            }
            else
            {
                if( $poll_manager->is_unique($poll, $allPolls) )
                {
                    // AJOUT
                    if( $poll_manager->create($poll) )
                    {
                        $this->admin_notices('Le sondage a bien été crée', 'updated');
                    }
                    else
                    {
                        $this->admin_notices('Le sondage n\'a pas pu être crée. Veuillez réessayer un peu plus tard SVP', 'error form-invalid');
                    }

                }
                else
                {
                    // MISE A JOUR

                    if( $poll_manager->update($poll) )
                    {
                        $this->admin_notices('Le sondage a bien été mis à jour', 'updated');
                    }
                    else
                    {
                        $this->admin_notices('Le sondage n\'a pas pu être mis à jour. Veuillez réessayer un peu plus tard SVP', 'error form-invalid');
                    }
                }
            }

        }

        if( 'add_edit_poll' == $task)
        {

            include_once APPLEMANIAK_SONDAGE_VIEWS_FOLDER . 'add_edit_poll.php';

        }

        elseif( 'del_poll' == $task && $poll_id > 0 )
        {
            $poll = $poll_manager->find($poll_id);

            if( $poll_manager->delete($poll) )
            {
                $this->admin_notices('Sondage bien supprimé', 'updated');
            }
            else
            {
                $this->admin_notices('Impossible de supprimer ce sondage', 'error form-invalid');
            }

            $message = '';
            include_once APPLEMANIAK_SONDAGE_VIEWS_FOLDER . 'delete_poll.php';
        }
        else
        {

            include_once APPLEMANIAK_SONDAGE_VIEWS_FOLDER . 'homepage.php';
        }

        unset($allPolls);
    }


    public function applemaniak_sondage_ajax()
    {

        //if( !current_user_can('publish_posts') ) return;

        include_once APPLEMANIAK_SONDAGE_LIBS_FOLDER.'ajax.php';
        $action = isset( $_REQUEST['applemaniak_sondage_action'] ) ? esc_html($_REQUEST['applemaniak_sondage_action']) : null;
        $poll_id = isset( $_REQUEST['poll_id'] ) ? intval( $_REQUEST['poll_id'] ) : 0;
        $answer_id = isset( $_REQUEST['answer_id'] ) ? intval( $_REQUEST['answer_id'] ) : 0;
        $author_id = isset( $_REQUEST['author_id'] ) ? strip_tags( $_REQUEST['author_id'] ) : null;

        switch($action)
        {
            case 'get_polls':
                get_polls();
                break;

            case 'get_answers':
                if($poll_id > 0) get_answers($poll_id);
                break;

            case 'save_vote':
                if($poll_id > 0 && $answer_id > 0 && !is_null($author_id)) save_vote($poll_id, $answer_id, $author_id);
                break;

            default:
                break;
        }
    }


    public function admin_notices($message, $class = '')
    {
        echo '<div class="'.$class.'"> <p>'.$message.'</p> </div>';

    }


    /**
     * Enqueue scripts for admin panel
     */
    public function add_admin_scripts()
    {
        wp_register_script('angularjs', APPLEMANIAK_SONDAGE_RESOURCES_URL . 'js/bower_components/angular/angular.js');

        wp_enqueue_script(
            'applemaniak-sondage-main-script',
            APPLEMANIAK_SONDAGE_RESOURCES_URL . 'js/admin/app.js',
            array('angularjs'),
            APPLEMANIAK_SONDAGE_VERSION,
            true
        );

        wp_enqueue_script(
            'applemaniak-sondage-controllers-script',
            APPLEMANIAK_SONDAGE_RESOURCES_URL . 'js/admin/controllers.js',
            array('angularjs'),
            APPLEMANIAK_SONDAGE_VERSION,
            true
        );


        wp_localize_script(
            'applemaniak-sondage-controllers-script',
            'sondageData',
            array(
                'ajaxurl' => APPLEMANIAK_SONDAGE_URL_WP_AJAX
            )
        );

        wp_enqueue_style(
            'applemaniak-sondage-admin-style',
            APPLEMANIAK_SONDAGE_RESOURCES_URL . 'css/applemaniak-sondage-widget.css',
            APPLEMANIAK_SONDAGE_VERSION,
            true
        );
    }


    /**
     * Enqueue scripts for front end
     */
    public function add_user_scripts()
    {
        wp_register_script('angularjs', APPLEMANIAK_SONDAGE_RESOURCES_URL . 'js/bower_components/angular/angular.js');

        wp_enqueue_script(
            'applemaniak-sondage-main-script',
            APPLEMANIAK_SONDAGE_RESOURCES_URL . 'js/user/app.js',
            array('angularjs'),
            APPLEMANIAK_SONDAGE_VERSION,
            true
        );


        wp_localize_script(
            'applemaniak-sondage-main-script',
            'sondageData',
            array(
                'ajaxurl' => APPLEMANIAK_SONDAGE_URL_WP_AJAX
            )
        );

        wp_enqueue_style(
            'applemaniak-sondage-style',
            APPLEMANIAK_SONDAGE_RESOURCES_URL . 'css/frontend.css',
            APPLEMANIAK_SONDAGE_VERSION,
            true
        );
    }
}

// End of class -->