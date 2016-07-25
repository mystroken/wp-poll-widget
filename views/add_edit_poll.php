<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 04/07/2015
 * Time: 23:40
 */
?>



<div class="wrap" ng-app="sondage">

    <div class="row">

        <form action="<?php echo $route['home']; ?>" method="post" class="col-s-10 col-m-8">

            <div class="row">

                <div class="col-s-12 col-m-7">

                    <h2>
                        <img alt="" src="<?php echo APPLEMANIAK_SONDAGE_ICON_URL; ?>" style="vertical-align: middle"> <?php echo $page_title; ?>
                    </h2>

                </div>

                <div class="col-s-12 col-m-4" style="margin-top: 10px; text-align: right">

                    <input type="submit" value="<?php echo $btn_value; ?>" class="button-primary" id="sondage_apply_btn">
                    <input type="button" value="Annuler" onclick="window.location='<?php echo $route['home']; ?>'" class="button-secondary" id="sondage_cancel_btn">

                </div>

            </div>


            <br><br>


            <div class="row">

                <div class="col-s-11" style="width: 95%">

                    <p>
                        <input type="hidden" name="poll_id" value="<?php echo $poll->ID; ?>">
                        <input type="text" value="<?php echo $sondage_name; ?>" class="large-text sondage-title" placeholder="Entrez un nom à ce sondage" name="sondage_title_input" id="sondage_title_input">
                    </p>

                </div>

            </div>



            <div class="row">

                <div class="col-s-12 col-m-7">

                    <h3>
                        Question
                    </h3>

                    <?php

                    $content = $sondage_content;
                    $editor_id = 'sondage_question_input';

                    wp_editor( $content, $editor_id );

                    ?>

                </div>

                <div class="col-s-12 col-m-4" style="text-align: right" ng-controller="Answers">

                    <h3>
                        Réponses à choisir
                    </h3>

                    <p>
                        <input type="hidden" id="poll_id" value="<?php echo $poll_id; ?>">
                        <input type="text" ng-model="answer_input" size="18" autocomplete="off">
                        <input type="button" class="button" id="sondage_answer_add_btn" value="Ajouter" ng-click="addAnswer()">
                    </p>

                    <p>
                        <small>Réponses déjà enregistrées</small>
                    </p>

                    <ul id="answers_list_ul" class="answers_list_ul" ng-init="answers=[]">
                        <li ng-repeat="answer in answers" data-answer-id="{{ answer.ID }}" ng-model="answer_item">
                            {{ answer.content }}
                            <span class="sondage_answer_del_btn" ng-click="delAnswer(this)">X</span>
                            <input type="hidden" name="answers[]" value="{{ answer.content }}@{{ answer.ID }}">
                        </li>
                    </ul>

                    <?php if( $poll->ID > 0 ): ?>
                    <div class="poll_result">

                        <h3>
                            Résultats
                        </h3>

                        <div class="applemaniak_sondage_answers">
                            <p class="txt-right">
                                Total de vote: <?php echo $poll->total_vote; ?>
                            </p>
                            <?php foreach( $poll->answers as $result): ?>
                            <div class="applemaniak_sondage_result">
                                <div class="applemaniak_sondage_result-bg" style="width: <?php echo (sizeof($result->votes) / $poll->total_vote ) * 100 ?>%"></div>
                                <div class="applemaniak_sondage_result-txt row">
                                    <div class="col-s-7" style="text-align: left"><?php echo $result->content ?></div>
                                    <div class="col-s-2 txt-right" style="padding-right: 20px"><?php echo sizeof($result->votes) ?></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                    <?php endif; ?>

                </div>

            </div>

        </form>

    </div>

</div>