<?php
echo form_open('home/create_login');
echo	form_label('Name: ', 'createtourname');
echo '<br>';
echo	form_input('createtourname');
echo '<br>';
echo	form_label('Size: ', 'createtoursize');
echo '<br>';
echo	form_input('createtoursize');
echo '<br>';
echo	form_label('Start (format: YY-MM-DD HH:MM:SS): ', 'createtourstart');
echo '<br>';
echo	form_input('createtourstart');
echo '<br>';
echo	form_label('End (format: YY-MM-DD HH:MM:SS): ', 'createtourend');
echo '<br>';
echo	form_input('createtourend');
echo '<br>';
echo	form_label('Type: ', 'createtourtype');
echo '<br>';
echo	form_input('createtourtype');
echo '<br>';
echo	form_submit('submitcreatetour', 'Create');
echo	form_close();
echo '</div>';
