<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel K
 * Date: 04/07/2015
 * Time: 02:14
 */

$title = !empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : ''; ?>

<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">Titre du widget :</label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>">
</p>