<div class="wrap" ng-app="sondage" ng-controller="Polls">

    <div class="row">

        <div class="col-s-10 col-m-8">

            <h2>
                <span><img alt="" src="<?php echo APPLEMANIAK_SONDAGE_ICON_URL; ?>" style="vertical-align: middle"></span> Applemaniak Sondage Widget
                <a href="<?php echo $route['add_edit']; ?>" class="add-new-h2">Nouveau sondage</a>
            </h2>

        </div>

    </div>

    <br>

    <div class="row">
        <div class="col-s-10 col-m-8">

            <div class="row">
                <div class="col-s-12 col-m-7">
                    <input type="text" placeholder="Recherche">
                </div>
                <div class="col-s-12 col-m-4" style="text-align: right">
                    <em>{{ polls.length }} lignes</em>
                </div>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="col-s-10 col-m-8">


            <table class="wp-list-table widefat fixed striped posts" class="polls_table">
                <thead>
                <tr>
                    <th scope="col" class="manage-column column-title sorted asc" style="width: 60px">
                        <a href="#"><span>ID</span><span class="sorting-indicator"></span></a>
                    </th>
                    <th>
                        Nom
                    </th>
                    <th>
                        Question
                    </th>
                    <th style="width: 60px">
                        Votes
                    </th>
                    <th>
                        Auteur
                    </th>
                    <th style="width: 70px">
                        Supprimer
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="poll in polls">
                    <td>
                        <a href="<?php echo $route['add_edit'] . '&poll_id='; ?>{{ poll.ID }}">
                            {{$index + 1}}
                        </a>
                    </td>
                    <td>
                        <a href="<?php echo $route['add_edit'] . '&poll_id='; ?>{{ poll.ID }}">
                            {{ poll.name }}
                        </a>
                    </td>
                    <td>
                        <a href="<?php echo $route['add_edit'] . '&poll_id='; ?>{{ poll.ID }}">
                            {{ poll.question | limitCharacters: 255 }}
                        </a>
                    </td>
                    <td>
                        {{ poll.total_vote }}
                    </td>
                    <td>
                        {{ poll.author }}
                    </td>
                    <td>
                        <a href="<?php echo $route['delete'] . '&poll_id='; ?>{{ poll.ID }}">
                            Supprimer
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="applemaniak-sondage-loader" ng-if="loader">
                <img src="<?php echo APPLEMANIAK_SONDAGE_LOADER_URL; ?>" alt="applemaniak loader">
            </div>
        </div>
    </div>

</div>