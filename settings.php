<?php

// Stop direct call
if (!empty($_SERVER['SCRIPT_FILENAME']) && basename(__file__) == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
	
class JustLikeItSettings {
	
	var $settingOptionPage;
	// Конструктор объекта
	function JustLikeItSettings()
	{
		$this->init();
		$this->actions();
	}
		
	function init()
	{
		$this->settingOptionPage = "just_like_options";
	}
	
	function actions() 
	{
		add_action('admin_menu', array(&$this,'create_admin_page_option'));
		add_action('admin_init', array(&$this,'setup_plugin_options'));
	}
	
	// Пункта в настройках будет достаточно.... создадим....
	function create_admin_page_option() 
	{
		add_options_page(__('Setting Just Like It Plugin', 'just-like-it'), 'Just Like It', 8, $this->settingOptionPage, array (&$this, 'printAdminPage'));
	}
	
	function setup_plugin_options() 
	{
		add_option('just_like_posts', 1);
		add_option('just_like_comments', 1);
		add_option('just_like_posts_like_accepted', '');
		if (FALSE == get_option('just_like_count_tags')){ add_option( 'just_like_count_tags', '$count');  }
		if (FALSE == get_option("just_like_no_auth")) { add_option( "just_like_no_auth", "<a href='$siteurl/wp-login.php?action=register'>Register</a> or <a href='$siteurl/wp-login.php'>log in</a> to assess the record"); }
		if (FALSE == get_option('just_like_like_label')){ add_option( 'just_like_like_label', '<img src=\'$img/heart.png\'/>');  }
		if (FALSE == get_option('just_like_unlike_label')){	add_option( 'just_like_unlike_label', '<img src=\'$img/heart_active.png\'/>');  }
		
		//  Сначала создаём секцию.
		add_settings_section(
			'just_like_options_plugin_section',	
			__('Genetal Settings', 'just-like-it'),
			array(&$this,'description_just_like_settings_section_callback'),
			$this->settingOptionPage
		); 

		add_settings_field(   
			'just_like_posts',	// ID used to identify the field throughout the theme  
			__('Allow to like posts', 'just-like-it'),	// The label to the left of the option interface element  
			array(&$this,'just_like_posts_callback'),	// The name of the function responsible for rendering the option interface  
			$this->settingOptionPage,	// The page on which this option will be displayed  
			'just_like_options_plugin_section',	// The name of the section to which this field belongs  
			array()	// Arg  
		); 
		
		add_settings_field(   
			'just_like_posts_like_accepted',	// ID used to identify the field throughout the theme  
			__('Types of posts, which allowed to like, separated by commas (blank = all)'),	// The label to the left of the option interface element  
			array(&$this,'just_like_posts_like_accepted_callback'),	// The name of the function responsible for rendering the option interface  
			$this->settingOptionPage,	// The page on which this option will be displayed  
			'just_like_options_plugin_section',	// The name of the section to which this field belongs  
			array()	// Arg  
		); 

		add_settings_field(   
			'just_like_comments',	// ID used to identify the field throughout the theme  
			__('Allow to like comments', 'just-like-it'),	// The label to the left of the option interface element  
			array(&$this,'just_like_comments_callback'),	// The name of the function responsible for rendering the option interface  
			$this->settingOptionPage,	// The page on which this option will be displayed  
			'just_like_options_plugin_section',	// The name of the section to which this field belongs  
			array()	// Arg  
		);
				
		add_settings_field(   
			'just_like_like_label',	// ID used to identify the field throughout the theme  
			__('I like it Label ($img is replaced by the address of the folder IMG of the plugin)', 'just-like-it'),	// The label to the left of the option interface element  
			array(&$this,'just_like_like_label_callback'),	// The name of the function responsible for rendering the option interface  
			$this->settingOptionPage,	// The page on which this option will be displayed  
			'just_like_options_plugin_section',	// The name of the section to which this field belongs  
			array()	// Arg  
		); 

		add_settings_field(   
			'just_like_unlike_label',	// ID used to identify the field throughout the theme  
			__('I dont like it Label ($img is replaced by the address of the folder IMG of the plugin)', 'just-like-it'),	// The label to the left of the option interface element  
			array(&$this,'just_like_unlike_label_callback'),	// The name of the function responsible for rendering the option interface  
			$this->settingOptionPage,	// The page on which this option will be displayed  
			'just_like_options_plugin_section',	// The name of the section to which this field belongs  
			array()	// Arg  
		);
		
		add_settings_field(   
			'just_like_count_tags',	// ID used to identify the field throughout the theme  
			__('View of like count ($count)', 'just-like-it'),	// The label to the left of the option interface element  
			array(&$this,'just_like_count_tags_callback'),	// The name of the function responsible for rendering the option interface  
			$this->settingOptionPage,	// The page on which this option will be displayed  
			'just_like_options_plugin_section',	// The name of the section to which this field belongs  
			array()	// Arg  
		); 
		
		add_settings_field(   
			'just_like_no_auth',	// ID used to identify the field throughout the theme  
			__('Text of box for unregistered users ($siteurl is replaced by the address of this site)', 'just-like-it'),	// The label to the left of the option interface element  
			array(&$this,'just_like_no_auth_callback'),	// The name of the function responsible for rendering the option interface  
			$this->settingOptionPage,	// The page on which this option will be displayed  
			'just_like_options_plugin_section',	// The name of the section to which this field belongs  
			array()	// Arg  
		); 
		
		register_setting('just_like_options_plugin_section', 'just_like_posts');
		register_setting('just_like_options_plugin_section', 'just_like_posts_like_accepted');
		register_setting('just_like_options_plugin_section', 'just_like_comments');
		register_setting('just_like_options_plugin_section', 'just_like_like_label');
		register_setting('just_like_options_plugin_section', 'just_like_unlike_label');
		register_setting('just_like_options_plugin_section', 'just_like_count_tags');
		register_setting('just_like_options_plugin_section', 'just_like_no_auth');
	}
	
	function description_just_like_settings_section_callback()
	{
		_e('You can enable or disable the buttons "Like" where needed', 'just-like-it');
	}
	
	function just_like_posts_callback()
	{
		echo "<input name='just_like_posts' type='checkbox' value='1' " . checked( 1, get_option('just_like_posts'), false ) . " />";
	}
	function just_like_posts_like_accepted_callback()
	{
		echo '<input name="just_like_posts_like_accepted" type="text" value="' . get_option('just_like_posts_like_accepted') . '" />';
	}
	function just_like_comments_callback()
	{
		echo "<input name='just_like_comments' type='checkbox' value='1' " . checked( 1, get_option('just_like_comments'), false ) . " />";
	}
	function just_like_like_label_callback()
	{
		echo '<input name="just_like_like_label" type="text"  value="' . get_option('just_like_like_label') . '" />';
	}
	function just_like_unlike_label_callback()
	{
		echo '<input name="just_like_unlike_label" type="text" value="' . get_option('just_like_unlike_label') . '" />';
	}
	function just_like_count_tags_callback()
	{
		echo '<input name="just_like_count_tags" type="text" value="' . get_option('just_like_count_tags') . '" />';
	}
	function just_like_no_auth_callback()
	{
		echo '<input name="just_like_no_auth" type="text" value="' . get_option('just_like_no_auth') . '" />';
	}
		
	function printAdminPage(){
		?>
		<div class=wrap>
			<h2><?php _e('Just like it Settings', 'just-like-it'); ?></h2>
			  
			<form method="post" action="options.php">  
				<?php settings_fields('just_like_options_plugin_section'); ?>  
				<?php do_settings_sections( $this->settingOptionPage ); ?>             
				<?php submit_button(); ?> 
			</form>
		</div>
		<?php
	}
}

?>