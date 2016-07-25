<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 04/07/2015
 * Time: 02:14
 */

// arguments before widget
echo $args['before_widget'];
$poll_manager = new \ApplemaniakObject\manager\PollManager();
$polls = $poll_manager->findAll(); ?>





    <?php
    /**
     * Widget Title (if filled)
     */
    if( !empty($instance['title']) ): ?>

        <?php // arguments before title
            echo $args['before_title']; ?>


        <?php // display title
            echo apply_filters( 'widget_title', $instance['title'] ); ?>


        <?php // arguments after title
            echo $args['after_title']; ?>

    <?php endif; // End of title --> ?>



<div ng-app="SondageFrontEnd">

    <br>

    <form method="post" ng-controller="polls">

        <div ng-if="polls.length <= 0" class="applemaniak_sondage_question">
            Aucun sondage présent dans la base de données!
        </div>

        <div ng-if="polls.length > 0">

            <input type="hidden" ng-init="author_ip='<?php echo $_SERVER['REMOTE_ADDR']; ?>'">

            <div class="applemaniak_sondage_question">
                {{ polls[currentIndex].question }}
            </div>

            <div class="applemaniak_sondage_answers" ng-if="!viewResult">
                <p class="applemaniak_sondage_answer" ng-repeat="answer in polls[currentIndex].answers">
                    <input type="radio" name="applemaniak_sondage_answer" id="{{ answer.ID }}" value="{{ answer.ID }}" ng-click="selectAnswer(this);">
                    <label for="{{ answer.ID }}">{{ answer.content }}</label>
                </p>
            </div>

            <div class="applemaniak_sondage_answers" ng-if="viewResult">
                <p class="txt-right">
                    Total de vote: {{ polls[currentIndex].total_vote }}
                </p>
                <div class="applemaniak_sondage_result" ng-repeat="result in polls[currentIndex].answers">
                    <div class="applemaniak_sondage_result-bg" style="width: {{ (result.votes.length / polls[currentIndex].total_vote ) * 100 }}%"></div>
                    <div class="applemaniak_sondage_result-txt row">
                        <div class="col-s-6">{{ result.content }}</div>
                        <div class="col-s-6 txt-right" style="padding-right: 20px">{{ result.votes.length }}</div>
                    </div>
                </div>
                <p align="center">
                    <a rel="nofollow" href="javascript:void()" ng-click="hideResult();">Quitter les résultats</a>
                </p>
            </div>

            <div class="applemaniak_sondage_boutons" ng-if="!viewResult">

                <div class="row">
                    <div class="col-s-6">
                        <input type="button" value="Voter" ng-click="vote(currentIndex, author_ip);">
                    </div>
                    <div class="col-s-6 txt-right"><br>
                        <a rel="nofollow" href="javascript:void()" ng-click="showResult();">Les résultats &rightarrow;</a>
                    </div>
                </div>

            </div>

            <div class="applemaniak_sondage_navigation" ng-if="currentIndex >= 0 && currentIndex <= maxIndex">

                <div class="row">

                    <div class="col-s-6">
                        <span ng-if="currentIndex > 0">
                            <span ng-click="switchPoll(currentIndex-1);">&laquo; Suivant</span>
                        </span>
                    </div>

                    <div class="col-s-6 txt-right">
                        <span ng-if="currentIndex < maxIndex">
                            <span ng-click="switchPoll(currentIndex+1);">Précédent &raquo;</span>
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </form>

    <div class="applemaniak-sondage-loader" ng-if="loader">
        <img src="<?php echo APPLEMANIAK_SONDAGE_LOADER_URL; ?>" alt="applemaniak loader">
    </div>

</div>



<?php // arguments after widget
echo $args['after_widget']; ?>