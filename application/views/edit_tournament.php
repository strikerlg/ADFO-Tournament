<?php
echo form_open('tour_controller/create_login');
echo '<br>';
echo	form_label('Name: ', 'edittourname');
echo '<br>';
echo	form_input('edittourname');
echo '<br>';
echo	form_label('Size: ', 'edittoursize');
echo '<br>';
echo	form_input('edittoursize');
echo '<br>';
echo	form_label('Start (format: YY-MM-DD HH:MM:SS): ', 'edittourstart');
echo '<br>';
echo	form_input('edittourstart');
echo '<br>';
echo	form_label('End (format: YY-MM-DD HH:MM:SS): ', 'edittourend');
echo '<br>';
echo	form_input('edittourend');
echo '<br>';
echo	form_label('Type: ', 'edittourtype');
echo '<br>';
echo	form_input('edittourtype');
echo '<br>';
echo	form_submit('submitedittour', 'Edit');
echo	form_close();
echo '</div>';