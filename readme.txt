README ME
tot_fields_db folder is hard coded so be careful changing the folder name. I have to add code to improve this.

plugin_activations.php creates and deletes the database. be careful deactivating the database as it deletes the tables and all data.
	note in production version i need to;
	remove this delete function in case plugin is accidentally deleted.
	create an automated routine to back up the database

tot_plugin_code.php is the main file that has the 'includes', function calls, loads style sheets, liads jquery functions.  
	
code nuggets;
'register_activation_hook' functions from main plugin page(tot_plugin_code.php) or they wont create/delete the databases
